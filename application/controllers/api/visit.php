<?php
    class VisitApiController extends ApiController
    {
        public $layout = 'ajax';

        public function getCachedMethods()
        {
            return array(
                'getOneById' => array(
                    'tags' => array(
                        'visit',
                        'visit:%visit_id%',
                        'visit:list',
                        'doctor:%doctor_id%',
                        'clinic:%clinic_id%',
                        'specialty:%specialty_id%',
                    ),
                ),
            );
        }

        // запись на прием к врачу
        public function add()
        {
            /**
             * @var AccountPhoneManager $account_phone_manager
             * @var AccountPhoneModel $account_phone
             * @var AccountPhoneModel $old_phone
             * @var ScheduleManager $schedule_manager
             * @var VisitManager $visit_manager
             * @var DoctorToClinicManager $doctor_to_clinic_manager
             */

            $phone = $_GET['phone'];
            $full_name = $_GET['full_name'];
            $clinic_id =  isset($_GET['clinic_id']) ? $_GET['clinic_id'] : null;
            $doctor_id = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : null;
            $specialty_id = isset($_GET['specialty_id']) ? $_GET['specialty_id'] : null;

            if (!$phone || !$full_name)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $account_id = $_GET['account_id'];
            $purpose_of_visit_id = $_GET['purpose_of_visit_id'];
            $comment = $_GET['comment'];
            $schedule_id = $_GET['slot_id'];
            $code = $_GET['code'];
            $phone_id = $_GET['checkId'];

            $new_account = null;
            $user_name = explode(' ', $full_name);

            $account_phone_manager = ModelManagerFactory::getByName('account_phone');
            $account_phone = $account_phone_manager->getOneByPhone($phone);

            if (!$account_id) {
                if (!$account_phone) {

                    $password = StringGeneratorHelper::generate(6);
                    Register::add('new_password', $password);

                    $account = new AccountModel();
                    $account->last_name = $user_name[0];
                    $account->first_name = $user_name[1];
                    $account->full_name = $full_name;
                    $account->email = $phone . '@mobile';
                    $account->password_hash = PasswordHashGenerator::generate($password);
                    $account->dt = date('Y-m-d H:i:s');
                    $account->setValidator(new WithoutValidator());

                    if (!$account->save()) {
                        $error_codes = $account->getValidator()->getErrorCodes();
                        ApiHeader::error($error_codes[0]);
                    }

                    $account_id = $account->getId();

                    $account->email .= ($account_id . '.reg');
                    $account->save();

                    $session = StringGeneratorHelper::generate(20);
                    ApiController::setAccountSession($account->getId(), $session);

                    $this->setPhone($phone, $account_id);

                    $new_account = array(
                        'id' => (int)$account_id,
                        'email' => $account->email,
                        'password' => $password,
                        'session' => $session,
                    );
                } else {
                    $result = array(
                        'email' => ModelManagerFactory::getByName('account')->getOneById($account_phone->account_id)->email,
                    );

                    ApiHeader::response($result, $this->e_tag);
                }
            }

            $old_phone = $account_phone_manager->getOneByPhone($phone);

            if (!$old_phone) {
                $this->setPhone($phone, $account_id);
            } elseif ($old_phone->account_id != $account_id) {
                $result = array(
                    'email' => ModelManagerFactory::getByName('account')->getOneById($old_phone->account_id)->email,
                );

                ApiHeader::response($result, $this->e_tag);
            }

            $schedule_manager = ModelManagerFactory::getByName('schedule');
            if (!$slots = $schedule_manager->getListByClinicIdAndDoctorIdAndSpecialtyId($clinic_id, $doctor_id, $specialty_id))
                ApiHeader::error(ApiRequestErrors::SLOTS_NOT_EXIST);

            $visit_manager = ModelManagerFactory::getByName('visit');

            $is_first_visit = $visit_manager->checkFirstVisitByDoctorIdAndAccountId($doctor_id, $account_id) ? 1 : null;

            $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');
            if ($is_first_visit) {
                $price = $doctor_to_clinic_manager->getFirstVisitPriceByDoctorIdAndClinicId($doctor_id, $clinic_id);
            } else {
                $price = $doctor_to_clinic_manager->getSecondVisitPriceByDoctorIdAndClinicId($doctor_id, $clinic_id);
            }

            $visit = new VisitModel();
            $visit->schedule_id = ($schedule_id) ? $schedule_id : 100000;
            $visit->clinic_id = $clinic_id;
            $visit->doctor_id = $doctor_id;
            $visit->account_id = $account_id;
            $visit->specialty_id = $specialty_id;
            $visit->full_name = $full_name;
            $visit->phone = $phone;
            $visit->purpose_of_visit_id = $purpose_of_visit_id;
            $visit->is_first_visit = $is_first_visit;
            $visit->price = $price;
            $visit->comment = $comment;
            $visit->from_mobile = 1;
            $visit->visit_channel_id = VisitChannelModel::MOBILE;

            $account_phone = $account_phone_manager->getOneById($phone_id);
            if ($code == $account_phone->code || $account_phone->no_code){
                if (!$visit->save()) {
                    $error_codes = $visit->getValidator()->getErrorCodes();
                    ApiHeader::error($error_codes[0]);
                }
            } else {
                ApiHeader::error(ApiRequestErrors::WRONG_CODE);
            }

            if ($new_account) {
                $result = array(
                    'new_user' => $new_account,
                    'visit_id' => (int)$visit->getId(),
                );
            } else {
                $result = array(
                    'visit_id' => (int)$visit->getId(),
                    'number_of_confirmed' => (int)$old_phone->is_confirmed,
                );
            }

            ApiHeader::response($result, $this->e_tag);
        }

        private function setPhone($phone, $account_id)
        {
            $account_phone = new AccountPhoneModel();
            $account_phone->phone = $phone;
            $account_phone->account_id = $account_id;
            $account_phone->code = StringGeneratorHelper::generateNumbers(3);
            $account_phone->dt = date('Y-m-d H:i:s');
            $account_phone->is_confirmed = 0;

            $account_phone->save();

            if ($account_phone) {
                $sms_sender = new SmsSender();
                $sms_sender->send('+' . $account_phone->phone, $account_phone->code);
            }
        }

        // получение визита
        public function getOneById()
        {
            /**
            * @var VisitManager $visit_manager
            * @var VisitModel $visit
            * @var ScheduleManager $schedule_manager
            * @var ScheduleModel $schedule
            */

            $visit_id = $_GET['visit_id'];

            if (!$visit_id)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $visit_manager = ModelManagerFactory::getByName('visit');

            if (!$visit = $visit_manager->getOneById($visit_id))
                ApiHeader::error(ApiRequestErrors::VISIT_NOT_EXIST);

            $schedule_manager = ModelManagerFactory::getByName('schedule');
            $schedule = $schedule_manager->getOneById($visit->schedule_id);

            $result = array(
                'visit_id' => $visit->getId(),
                'schedule_id' => array(
                    'id' => ($schedule) ? $schedule->id : 0,
                    'start_time' => ($schedule) ? @strtotime($schedule->dt_start) : '',
                    'end_time' => ($schedule) ? @strtotime($schedule->dt_end) : '',
                ),
                'status' => array(
                    'id' => $visit->status_id,
                    'title' => VisitModel::getStatusNameByStatusId($visit->status_id),
                ),
                'confirm_code' => $visit->confirm_code,
                'confirm_dt' => $visit->confirm_dt,
                'notification_dt' => $visit->notification_dt,
                'purpose_of_visit_id' => $visit->purpose_of_visit_id,
                'visit_start_time' => $visit->visit_start_time,
                'clinic' => array(
                    'clinic_id' => $visit->clinic_id,
                    'name' => ($visit->clinic_id) ? $visit->clinic->name : '',
                    'address' => ($visit->clinic_id) ? $visit->clinic->address : '',
                    'latitude' => $visit->clinic->latitude,
                    'longitude' => $visit->clinic->longitude,
                ),
                'doctor' => array(
                    'doctor_id' => $visit->doctor_id,
                    'first_name' => ($visit->doctor_id && $visit->doctor->first_name) ? $visit->doctor->first_name : '',
                    'last_name' => ($visit->doctor_id && $visit->doctor->last_name) ? $visit->doctor->last_name : '',
                ),
                'specialty' => array(
                    'specialty_id' => $visit->specialty_id,
                    'name' => ($visit->specialty_id) ? $visit->specialty->name : ''
                ),
                'comment' => ($visit->comment) ? $visit->comment : '',
            );

            ApiHeader::response($result, $this->e_tag);
        }

        // добавление рейтинга визиту
        public function addRating()
        {
            $visit_id = $_GET['visit_id'];
            if (!$visit_id)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $cabinet = $_GET['cabinet'];
            $waiting_time = $_GET['waiting_time'];
            $relationship = $_GET['relationship'];
            $value_for_money = $_GET['value_for_money'];
            $diagnosis_is_clear = $_GET['diagnosis_is_clear'];
            $service_at_the_reception = $_GET['service_at_the_reception'];
            $is_doctor_advice = $_GET['is_doctor_advice'];
            $is_clinic_advice = $_GET['is_clinic_advice'];

            $params[] = $cabinet;
            $params[] = $waiting_time;
            $params[] = $relationship;
            $params[] = $value_for_money;
            $params[] = $diagnosis_is_clear;
            $params[] = $service_at_the_reception;
            $params[] = $is_doctor_advice;
            $params[] = $is_clinic_advice;

            $count = count($params);
            for ($i = 0; $i < $count - 2; $i++) {
                if ($params[$i] > 5 || $params[$i] < 1)
                    ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS_VALUE);
            }

            if (!$visit = ModelManagerFactory::getByName('visit')->getOneById($visit_id))
                ApiHeader::error(ApiRequestErrors::VISIT_NOT_EXIST);

            /**
             * @var VisitRatingManager $visit_rating_manager
             */
            $visit_rating_manager = ModelManagerFactory::getByName('visit_rating_manager');

            $visit_rating = $visit_rating_manager->getOneByVisitId($visit_id);
            $visit_rating->visit_id = $visit_id;
            $visit_rating->account_id = $this->account_id;
            $visit_rating->cabinet = $cabinet;
            $visit_rating->waiting_time = $waiting_time;
            $visit_rating->relationship = $relationship;
            $visit_rating->value_for_money = $value_for_money;
            $visit_rating->diagnosis_is_clear = $diagnosis_is_clear;
            $visit_rating->service_at_the_reception = $service_at_the_reception;
            $visit_rating->is_doctor_advice = ($is_doctor_advice) ? 1 : null;
            $visit_rating->is_clinic_advice = ($is_clinic_advice) ? 1 : null;

            if (!$visit_rating->save()) {
                $error_code = $visit_rating->getValidator()->getErrorCodes();
                ApiHeader::error($error_code[0]);
            }

            ApiHeader::response(1, $this->e_tag);
        }

        // получение списка визитов пользователя
        public function getList()
        {
            $account_id = $_GET['account_id'];

            if (!$account_id)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $visit_manager = new VisitManager();
            if (!$visits = $visit_manager->getListByAccountId($account_id))
                ApiHeader::error(ApiRequestErrors::VISITS_BY_ACCOUNT_NOT_EXIST);

            $result = array();

            foreach ($visits as $visit) {
                $city_manager = ModelManagerFactory::getByName('city');
                $city = $city_manager->getOneById($visit->clinic->city_id);
                $metro_station_manager = ModelManagerFactory::getByName('metro_station');
                $metro_station = $metro_station_manager->getOneById($visit->clinic->metro_station_id);

                $metro = new ArrayObject();
                if ($metro_station){
                    $metro['id'] = (int)$metro_station->getId();
                    $metro['name'] = $metro_station->name;
                }

                $result[] = array(
                    'visit_id' => $visit->getId(),
                    'slot' => array(
                        'id' => $visit->schedule_id,
                        'start_time' => ($visit->schedule_id) ? @strtotime($visit->schedule->dt_start) : 0,
                        'end_time' => ($visit->schedule_id) ? @strtotime($visit->schedule->dt_end) : 0,
                    ),
                    'status' => array(
                        'id' => $visit->status_id,
                        'title' => VisitModel::getStatusNameByStatusId($visit->status_id)
                    ),
                    'confirm_code' => $visit->confirm_code,
                    'confirm_dt' => $visit->confirm_dt,
                    'notification_dt' => $visit->notification_dt,
                    'purpose_of_visit_id' => $visit->purpose_of_visit_id,
                    'visit_start_time' => $visit->visit_start_time,
                    'clinic' => array(
                        'clinic_id' => $visit->clinic_id,
                        'name' => ($visit->clinic_id) ? $visit->clinic->name : '',
                        'address' => array(
                            'city' => array(
                                'id' => ($city) ? (int)$city->getId() : 0,
                                'name' => ($city) ? $city->name : '',
                            ),
                            'address' => ($visit->clinic->address) ? $visit->clinic->address : '',
                            'metro' => $metro,
                            'longitude' => ($visit->clinic->longitude) ? (float)$visit->clinic->longitude : 0.0,
                            'latitude' => ($visit->clinic->latitude) ? (float)$visit->clinic->latitude : 0.0,
                        ),
                    ),
                    'doctor' => array(
                        'doctor_id' => $visit->doctor_id,
                        'first_name' => ($visit->doctor_id && $visit->doctor->first_name) ? $visit->doctor->first_name : '',
                        'last_name' => ($visit->doctor_id && $visit->doctor->last_name) ? $visit->doctor->last_name : '',
                    ),
                    'specialty' => array(
                        'specialty_id' => $visit->specialty_id,
                        'name' => ($visit->specialty_id) ? $visit->specialty->name : ''
                    )
                );
            }

            ApiHeader::response($result, $this->e_tag);
        }

        public function request()
        {
            $full_name = (isset($_GET['full_name'])) ? $_GET['full_name'] : null;
            $phone = (isset($_GET['phone'])) ? $_GET['phone'] : null;
            $comment = (isset($_GET['comment'])) ? $_GET['comment'] : null;
            $city_id = isset($_GET['city_id']) ? $_GET['city_id'] : null;
            $specialty_id = isset($_GET['specialty_id']) ? $_GET['specialty_id'] : null;

            if (!$full_name || !$phone)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $visit = new VisitModel();
            $visit->full_name = $full_name;
            $visit->phone = $phone;
            $visit->comment = $comment;
            $visit->city_id = $city_id;
            $visit->specialty_id = $specialty_id;


            if (!$visit->save()) {
                $error_code = $visit->getValidator()->getErrorCodes();
                ApiHeader::error($error_code[0]);
            };



            ApiHeader::response(1, $this->e_tag);
        }

        public function cancel()
        {
            /**
             * @var VisitModel $visit
             */

            $visit_id = $_GET['visit_id'];

            if (!$visit_id)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $visit_manager = new VisitManager();
            $visit = $visit_manager->getOneById($visit_id);

            if (!$visit)
                ApiHeader::error(ApiRequestErrors::VISIT_NOT_EXIST);

            $visit->status_id = 2;
            $visit->setWhenceCanceled('из мобильного приложения');

            if (!$visit->save()) {
                $error_code = $visit->getValidator()->getErrorCodes();
                ApiHeader::error($error_code[0]);
            }

            ApiHeader::response(1, $this->e_tag);
        }

        // запись на прием к врачу - 2
        public function record()
        {
            /**
             * @var AccountPhoneManager $account_phone_manager
             * @var AccountPhoneModel $account_phone
             * @var VisitManager $visit_manager
             * @var DoctorToClinicManager $doctor_to_clinic_manager
             */
            $account_phone_manager = ModelManagerFactory::getByName('account_phone');

            $phone = isset($_GET['phone']) ? $_GET['phone'] : null;
            $full_name = isset($_GET['full_name']) ? $_GET['full_name'] : null;
            $clinic_id = isset($_GET['clinic_id']) ? $_GET['clinic_id'] : null;
            $doctor_id = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : null;
            $specialty_id = isset($_GET['specialty_id']) ? $_GET['specialty_id'] : null;
            $purpose_of_visit_id = isset($_GET['purpose_of_visit_id']) ? $_GET['purpose_of_visit_id'] : null;
            $comment = isset($_GET['comment']) ? $_GET['comment'] : null;
            $schedule_id = isset($_GET['slot_id']) ? $_GET['slot_id'] : null;
            $city_id = isset($_GET['city_id']) ? $_GET['city_id'] : null;

            if (!$phone || !$full_name)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $user_name = explode(' ', $full_name);
            $last_name = isset($user_name[0]) ? $user_name[0] : '';
            $first_name = isset($user_name[1]) ? $user_name[1] : '';

            $account_phone = $account_phone_manager->getOneByPhone($phone);

            $account_id = $this->authorized_account->getId();

            if(!$account_id && !$account_phone)
            {
                    $password = StringGeneratorHelper::generate(6);
                    Register::add('new_password', $password);

                    $account = new AccountModel();
                $account->last_name = $last_name;
                $account->first_name = $first_name;
                    $account->full_name = $full_name;
                    $account->email = $phone . '@mobile';
                    $account->password_hash = PasswordHashGenerator::generate($password);
                    $account->dt = date('Y-m-d H:i:s');
                $account->disableValidation();

                    if (!$account->save()) {
                        $error_codes = $account->getValidator()->getErrorCodes();
                        ApiHeader::error($error_codes[0]);
                    }

                    $account_id = $account->getId();

                    $account->email .= ($account_id . '.reg');
                    $account->save();

                $session_code = StringGeneratorHelper::generate(20);
                ApiController::setAccountSession($account->getId(), $session_code);

                    $this->setPhone($phone, $account_id);

                    $new_account = array(
                        'id' => (int)$account_id,
                        'email' => $account->email,
                        'password' => $password,
                    'session' => $session_code
                    );
                    $result = array(
                        'phone_not_confirmed' => $phone,
                        'new_user' => $new_account,
                    );
                    ApiHeader::response($result, $this->e_tag);
            }

            if(!$account_id && $account_phone)
            {
                $account_phone->code = PhoneConfirmCodeGeneratorHelper::generate();
                    $account_phone->save();

                    $sms_sender = new SmsSender();
                    $sms_sender->send('+' . $account_phone->phone, $account_phone->code);

                    $result = array(
                        'phone_not_confirmed' => $account_phone->phone
                    );
                    ApiHeader::response($result, $this->e_tag);
                }

            if($account_id)
            {
                if ($account_phone && $account_phone->is_confirmed == 1) {
                    if ($account_id == $account_phone->account_id) {
                        $visit_manager = ModelManagerFactory::getByName('visit');

                        $is_first_visit = $visit_manager->checkFirstVisitByDoctorIdAndAccountId($doctor_id, $account_id) ? 1 : null;

                        $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');
                        if ($is_first_visit) {
                            $price = $doctor_to_clinic_manager->getFirstVisitPriceByDoctorIdAndClinicId($doctor_id, $clinic_id);
                        } else {
                            $price = $doctor_to_clinic_manager->getSecondVisitPriceByDoctorIdAndClinicId($doctor_id, $clinic_id);
                        }

                        $visit = new VisitModel();
                        $visit->schedule_id = ($schedule_id) ? $schedule_id : 100000;
                        $visit->clinic_id = $clinic_id;
                        $visit->doctor_id = $doctor_id;
                        $visit->account_id = $account_id;
                        $visit->city_id = $city_id;
                        $visit->specialty_id = $specialty_id;
                        $visit->full_name = $full_name;
                        $visit->phone = $phone;
                        $visit->purpose_of_visit_id = $purpose_of_visit_id;
                        $visit->is_first_visit = $is_first_visit;
                        $visit->price = $price;
                        $visit->comment = $comment;
                        $visit->from_mobile = 1;

                        if (!$visit->save()) {
                            $error_codes = $visit->getValidator()->getErrorCodes();
                            ApiHeader::error($error_codes[0]);
                        }

                        $result = array(
                            'visit_id' => (int)$visit->getId(),
                        );

                        ApiHeader::response($result, $this->e_tag);
                    } else {
                        ApiHeader::error(ApiRequestErrors::NOT_THAT_ACCOUNT_PHONE);
                    }
                } elseif ($account_phone && $account_phone->is_confirmed == 0) {
                    if ($account_id == $account_phone->account_id) {
                        $account_phone->code = StringGeneratorHelper::generateNumbers(3);
                        $account_phone->save();

                        $sms_sender = new SmsSender();
                        $sms_sender->send('+' . $account_phone->phone, $account_phone->code);

                        $result = array(
                            'phone_not_confirmed' => $account_phone->phone
                        );
                        ApiHeader::response($result, $this->e_tag);
                    } else {
                        ApiHeader::error(ApiRequestErrors::NOT_THAT_ACCOUNT_PHONE);
                    }
                }
            }
        }
    }