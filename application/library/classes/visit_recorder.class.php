<?php
    class VisitRecorder
    {

        private $visit_model;

        public function getVisit()
        {
            return $this->visit_model;
        }

        public function record(VisitInformation $visit_information, $visit_id = null)
        {
            $schedule = ModelManagerFactory::getByName('schedule')->getOneById($visit_information->schedule_id);

            if ($visit_information->clinic_id && $visit_information->doctor_id && $visit_information->specialty_id) {
                $doctor_specialty_to_clinic = ModelManagerFactory::getByName('doctor_specialty_to_clinic')->getOneByDoctorIdAndClinicIdAndSpecialtyId($visit_information->doctor_id, $visit_information->clinic_id, $visit_information->specialty_id);
                if (!$doctor_specialty_to_clinic) {
                    JsonResponse::error(ValidationErrorCodes::NOT_DOCTOR_SPECIALTY_TO_CLINIC);
                }
            }

            $account_phone_manager = new AccountPhoneManager();

            $phone = $account_phone_manager->getOneByAccountIdAndPhone($visit_information->account_id, $visit_information->phone);
            if (!$phone) {
                /* По требованию заказчика убрана проверка на повторяющиеся телефоны
                if (($phone = $account_phone_manager->getOneByPhone($visit_information->phone)) || $phone->is_confirmed) {
                    JsonResponse::error(ValidationErrorCodes::ALREADY_REGISTERED);
                }

                $phone = new AccountPhoneModel();
                $phone->account_id = $visit_information->account_id;
                $phone->phone = $visit_information->phone;
                $phone->dt = DateHelper::now();
                $phone->is_confirmed = 0;
                $phone->save();
                */

                /* Добавляем все телефоны которых нет в базе */
                if (!($phone = $account_phone_manager->getOneByPhone($visit_information->phone))) {
                    $phone = new AccountPhoneModel();
                    $phone->account_id = $visit_information->account_id;
                    $phone->phone = $visit_information->phone;
                    $phone->dt = DateHelper::now();
                    $phone->is_confirmed = 0;
                    $phone->save();
                }
            }


//            if (!$phone->is_confirmed) {
//                $error_data = array(
//                    'phone_id' => $phone->getId()
//                );
//                JsonResponse::error(ValidationErrorCodes::NOT_CONFIRMED_PHONE, $error_data);
//            }


            $visit_manager = new VisitManager();

            $is_first_visit = $visit_manager->checkFirstVisitByDoctorIdAndAccountId($visit_information->doctor_id, $visit_information->account_id) ? 1 : 0;

            $price = null;

            if ($schedule) {
                /**
                 * @var DoctorToClinicManager $doctor_to_clinic_manager
                 */
                $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');
                if ($is_first_visit) {
                    $price = $doctor_to_clinic_manager->getFirstVisitPriceByDoctorIdAndClinicId($visit_information->doctor_id, $schedule->clinic_id);
                } else {
                    $price = $doctor_to_clinic_manager->getSecondVisitPriceByDoctorIdAndClinicId($schedule->doctor_id, $schedule->clinic_id);
                }
            }

            if ($visit_id) {
                $visit = ModelManagerFactory::getByName('visit')->getOneById($visit_id);
            } else {
                $visit = new VisitModel();
            }

            $visit->schedule_id = $visit_information->schedule_id;
            $visit->clinic_id = $visit_information->clinic_id;
            $visit->doctor_id = $visit_information->doctor_id;
            $visit->account_id = $visit_information->account_id;
            $visit->full_name = $visit_information->full_name;
            $visit->phone = $visit_information->phone;
            $visit->purpose_of_visit_id = $visit_information->purpose_of_visit_id;
            $visit->is_first_visit = $is_first_visit;
            $visit->price = $price;
            $visit->specialty_id = $visit_information->specialty_id;
            $visit->comment = $visit_information->comment;
            $visit->status_id = $visit_information->visit_status_id;
            $visit->appeal_id = $visit_information->appeal_id;
            $visit->after_work = $visit_information->after_work;
            $visit->disease_id = $visit_information->disease_id;
            $visit->processed_user = $visit_information->processed_user;
            $visit->visit_start_time = $visit_information->visit_start_time;

            if($visit->appeal_id)
            {
                $visit->visit_channel_id = VisitChannelModel::APPEAL;
            }

            if ($visit_id) {
                ModelManagerFactory::getByName('visit')->setOneById($visit_id, $visit);
            }
            else {
                if (!$visit->save()) {
                    $error_codes = $visit->getValidator()->getErrorCodes();
                    return $error_codes[0];
                }
            }

            if ($schedule)
                $schedule->save();

            $this->visit_model = $visit;

            return 0;
        }
    }