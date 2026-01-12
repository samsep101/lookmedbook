<?php

    /**
     * @property int                   $id
     * @property string                $first_name
     * @property string                $second_name
     * @property string                $last_name
     * @property string                $full_lower_name
     * @property int                   $docdoc_id
     * @property string                $alias
     * @property string                $old_alias
     * @property int                   $sex_id
     * @property SexModel              $sex
     * @property int                   $image_id
     * @property ImageModel            $image
     * @property int                   $card_image_id
     * @property ImageModel            $card_image
     * @property int                   $doctor_type_id
     * @property DoctorTypeModel       $doctor_type
     * @property string                $rate
     * @property int                   $work_experience
     * @property string                $advice_rate
     * @property string                $about
     * @property string                $about_internal
     * @property int                   $is_active
     * @property string                $availability
     * @property int                   $is_has_morning_time
     * @property int                   $is_has_evening_time
     * @property int                   $is_has_weekend_time
     * @property int                   $is_leave_the_house
     * @property string                $cabinet_rate
     * @property string                $waiting_time_rate
     * @property string                $relationship_rate
     * @property string                $value_for_money_rate
     * @property string                $diagnosis_is_clear_rate
     * @property int                   $is_adult
     * @property int                   $is_children
     * @property int                   $is_pregnant
     * @property int                   $is_handicapped
     * @property string                $start_time_monday [deprecated]
     * @property string                $end_time_monday [deprecated]
     * @property string                $start_time_tuesday [deprecated]
     * @property string                $end_time_tuesday [deprecated]
     * @property string                $start_time_wednesday [deprecated]
     * @property string                $end_time_wednesday [deprecated]
     * @property string                $start_time_thursday [deprecated]
     * @property string                $end_time_thursday [deprecated]
     * @property string                $start_time_friday [deprecated]
     * @property string                $end_time_friday [deprecated]
     * @property string                $start_time_saturday [deprecated]
     * @property string                $end_time_saturday [deprecated]
     * @property string                $start_time_sunday [deprecated]
     * @property string                $end_time_sunday [deprecated]
     * @property int                   $is_confirmed
     * @property int                   $balls
     * @property ActionModel[]         $actions
     * @property ClinicModel[]         $clinics
     * @property ClinicModel           $clinic
     * @property int                   $reviews_count
     * @property SpecialtyModel[]      $specialties
     * @property string                $specialties_names
     * @property string                $specialties_names_links
     * @property PurposeOfVisitModel[] $purposes_of_visit
     * @property bool                  $my_doctor
     * @property int                   $was_first_visit
     * @property string                $full_name
     * @property string                $short_fio
     * @property string                $full_fio
     * @property ImageModel[]          $images
     * @property DoctorReviewModel     $last_review
     * @property DoctorReviewModel[]   $reviews
     * @property string                $moderate_full_name
     * @property ClinicModel[]         $clinics_for_all
     * @property bool                  $is_virtual Для записи на специальность
     * @property CityModel             $city
     * @property int                   $to_validate
     * @property bool                  $is_has_visit_slots
     * @property bool                  $is_need_to_index_update
     * @property DistrictModel         $district
     * @property integer               $is_active_updated_at
     *
     * @property string                $education
     * @property string                $course
     * @property string                $certificate
     * @property string                $academic_title
     * @property DoctorInfoModel       $doctor_info
     */
    class DoctorModel extends DynamicModel
    {
        const ADULT    = 6;
        const CHILDREN = 7;
        const PREGNANT = 8;

        const RESERVED_DOCTOR_SLOT = 100000;

        const INACTIVE_MAX_TIME = 7776000;

        private static $deprecatedParams = [
            'start_time_monday',
            'end_time_monday',
            'start_time_tuesday',
            'end_time_tuesday',
            'start_time_wednesday',
            'end_time_wednesday',
            'start_time_thursday',
            'end_time_thursday',
            'start_time_friday',
            'end_time_friday',
            'start_time_saturday',
            'end_time_saturday',
            'start_time_sunday',
            'end_time_sunday',
        ];

        public function __construct()
        {
            $this->setDefaultValue('is_adult', 1);
            $this->setDefaultValue('rate', 4);
        }

        public function __get($param_name)
        {
            if (in_array($param_name, static::$deprecatedParams, true)) {
                error_log("Param $param_name is deprecated");
                return $this->clinic ? $this->clinic->$param_name : null;
            }
            return parent::__get($param_name);
        }

        protected function _field_clinics()
        {
            /**
             * @var ClinicManager $clinic_manager
             */
            $clinic_manager = ModelManagerFactory::getByName('clinic');
            $this->clinics  = $clinic_manager->getActiveListByDoctorId($this->getId());

            return $this->clinics;
        }


        protected function _field_actions()
        {
            /**
             * @var ActionManager $action_manager
             */
            $clinic_manager = ModelManagerFactory::getByName('action');
            $this->actions  = $clinic_manager->getActionsByDoctorId($this->getId());
            return $this->actions;

        }


        protected function _field_clinic()
        {
            if($this->clinics)
            {
                return $this->clinics[0];
            }

            return NULL;
        }

        protected function _field_reviews_count()
        {
            /**
             * @var DoctorReviewManager $doctor_review_manager
             */
            $doctor_review_manager = ModelManagerFactory::getByName('doctor_review');
            $this->reviews_count   = $doctor_review_manager->getCountConfirmedListByDoctorId($this->getId());

            return $this->reviews_count;
        }

        protected function _field_specialties()
        {
            /**
             * @var SpecialtyManager $specialty_manager
             */
            $specialty_manager = ModelManagerFactory::getByName('specialty');
            $this->specialties = $specialty_manager->getListByDoctorId($this->id);

            return $this->specialties;
        }

        /**
         * @return CityModel
         */
        protected function _field_city()
        {
            return ($this->clinic) ? $this->clinic->city : NULL;
        }

        protected function _field_specialties_names()
        {
            $str = '';

            $i     = 0;
            $equal = FALSE;

            foreach($this->specialties as $specialty)
            {
                // Добавлено, чтобы не выводилась несколько раз специализация, если врач работает в
                // разных клиниках по одной и той же специализации и везде совпадает параметр
                // детский, взрослый или для всех
                if(count($this->specialties) > 0 && $i != 0)
                {
                    for($j = 0; $j < $i; $j++)
                    {
                        if($this->specialties[$i]->getId() == $this->specialties[$j]->getId() &&
                            $this->specialties[$i]->for_whom == $this->specialties[$j]->for_whom
                        )
                        {
                            $equal = TRUE;
                        }
                    }

                    if($equal)
                    {
                        $equal = FALSE;

                        continue;
                    }
                }

                // Добавлен вывод специализации врача в зависимости от того
                // детский или взрослый врач и детская или взрослая специализация
                switch($specialty->for_whom)
                {
                    // 1 - для всех, 2 - для взрослых, 3 - для детей
                    case '1':
                        if($this->is_children == '1')
                        {
                            if($this->is_adult == '1')
                            {
                                if($i == 0)
                                {
                                    $str .= StringHelper::startProposalWord($specialty->name) . ', '
                                        . 'детский ' . mb_strtolower($specialty->name, 'utf-8') . ', ';
                                }
                                else
                                {
                                    $str .= mb_strtolower($specialty->name, 'utf-8') . ', '
                                        . 'детский ' . mb_strtolower($specialty->name, 'utf-8') . ', ';
                                }
                            }
                            else
                            {
                                if($i == 0)
                                {
                                    $str .= 'Детский ' . mb_strtolower($specialty->name, 'utf-8') . ', ';
                                }
                                else
                                {
                                    $str .= 'детский ' . mb_strtolower($specialty->name, 'utf-8') . ', ';
                                }
                            }
                        }
                        else
                        {
                            if($i == 0)
                            {
                                $str .= StringHelper::startProposalWord($specialty->name) . ', ';
                            }
                            else
                            {
                                $str .= mb_strtolower($specialty->name, 'utf-8') . ', ';
                            }
                        }
                        break;

                    case '2':
                        if($this->is_adult == '1')
                        {
                            if($i == 0)
                            {
                                $str .= StringHelper::startProposalWord($specialty->name) . ', ';
                            }
                            else
                            {
                                $str .= mb_strtolower($specialty->name, 'utf-8') . ', ';
                            }
                        }
                        break;

                    case '3':
                        if($this->is_children == '1')
                        {
                            if($i == 0)
                            {
                                $str .= StringHelper::startProposalWord($specialty->name) . ', ';
                            }
                            else
                            {
                                $str .= mb_strtolower($specialty->name, 'utf-8') . ', ';
                            }
                        }
                        break;
                }

                $i++;
            }

            return trim($str, ', ');
        }

        protected function _field_specialties_names_links()
        {
            $str = '';

            $i     = 0;
            $equal = FALSE;
            $active = "active";
            foreach($this->specialties as $specialty)
            {
                // Добавлено, чтобы не выводилась несколько раз специализация, если врач работает в
                // разных клиниках по одной и той же специализации и везде совпадает параметр
                // детский, взрослый или для всех

                $href = LinkHelper::getSiteUrlByCity($this->city) . '/doctor/' . $specialty->alias;
                $spec_data  = "data-spec=\"{$specialty->id}\" class=\"spec-tabs ".$active."\"";
                $active = "";

                if(count($this->specialties) > 0 && $i != 0)
                {
                    for($j = 0; $j < $i; $j++)
                    {
                        if($this->specialties[$i]->getId() == $this->specialties[$j]->getId() &&
                            $this->specialties[$i]->for_whom == $this->specialties[$j]->for_whom
                        )
                        {
                            $equal = TRUE;
                        }
                    }

                    if($equal)
                    {
                        $equal = FALSE;

                        continue;
                    }
                }

                // Добавлен вывод специализации врача в зависимости от того
                // детский или взрослый врач и детская или взрослая специализация
                switch($specialty->for_whom)
                {
                    // 1 - для всех, 2 - для взрослых, 3 - для детей
                    case '1':
                        if($this->is_children == '1')
                        {
                            if($this->is_adult == '1')
                            {
                                if($i == 0)
                                {
                                    $str .= '<a '.$spec_data.' href="' . $href . '">' . StringHelper::startProposalWord($specialty->name)
                                        . ' (детский ' .  StringHelper::startProposalWord($specialty->name, 'utf-8'). ')</a>';
                                }
                                else
                                {
                                    $str .= '<a '.$spec_data.' href="' . $href . '">' .  StringHelper::startProposalWord($specialty->name, 'utf-8')
                                        . ' (детский ' .  StringHelper::startProposalWord($specialty->name, 'utf-8') . ')</a>';
                                }
                            }
                            else
                            {
                                if($i == 0)
                                {
                                    $str .= '<a '.$spec_data.'>Детский ' .  StringHelper::startProposalWord($specialty->name, 'utf-8') . '</a>';
                                }
                                else
                                {
                                    $str .= '<a '.$spec_data.'>детский ' .  StringHelper::startProposalWord($specialty->name, 'utf-8') . '</a>';
                                }
                            }
                        }
                        else
                        {
                            if($i == 0)
                            {
                                $str .= '<a '.$spec_data.' href="' . $href . '">' . StringHelper::startProposalWord($specialty->name) . '</a>';
                            }
                            else
                            {
                                $str .= '<a '.$spec_data.' href="' . $href . '">' .  StringHelper::startProposalWord($specialty->name, 'utf-8') . '</a>';
                            }
                        }
                        break;

                    case '2':
                        if($this->is_adult == '1')
                        {
                            if($i == 0)
                            {
                                $str .= '<a '.$spec_data.' href="' . $href . '">' . StringHelper::startProposalWord($specialty->name) . '</a>';
                            }
                            else
                            {
                                $str .= '<a '.$spec_data.' href="' . $href . '">' .  StringHelper::startProposalWord($specialty->name, 'utf-8') . '</a> ';
                            }
                        }
                        break;

                    case '3':
                        if($this->is_children == '1')
                        {
                            $str .= '<a '.$spec_data.' href="' . $href . '">' .  StringHelper::startProposalWord($specialty->name) . '</a> ';
                        }
                        break;
                }

                $i++;
            }

            return trim($str, ', ');
        }

        protected function _field_purposes_of_visit()
        {
            /**
             * @var PurposeOfVisitManager $purpose_of_visit_manager
             */
            $purpose_of_visit_manager = ModelManagerFactory::getByName('purpose_of_visit');
            $this->purposes_of_visit  = $purpose_of_visit_manager->getListByDoctorId($this->id);

            return $this->purposes_of_visit;
        }

        protected function _field_my_doctor()
        {
            $this->my_doctor = ModelManagerFactory::getByName('my_doctor')->checkExistsByDoctorIdAndAccountId($this->id, Acc::accountId());

            return $this->my_doctor;
        }

        protected function _field_was_first_visit()
        {
            $this->was_first_visit = ModelManagerFactory::getByName('visit')->checkFirstVisitByDoctorIdAndAccountId($this->id, Acc::accountId());

            return $this->was_first_visit;
        }

        protected function _field_full_name()
        {
            $this->full_name = $this->last_name . ' ' . $this->first_name . ' ' . $this->second_name;

            return $this->full_name;
        }

        protected function _field_short_fio()
        {
            $this->short_fio = $this->last_name . '' . mb_substr($this->first_name, 0, 1, 'utf-8') . mb_substr($this->second_name, 0, 1, 'utf-8');

            return $this->short_fio;
        }

        protected function _field_full_fio()
        {
            $nameParts = array_filter(array_map(
                function ($e) {
                    return StringTransliterationHelper::translit(trim($e));
                },
                [$this->last_name, $this->first_name, $this->second_name]
            ));
            return implode('-', $nameParts);
        }

        protected function _field_images()
        {
            /**
             * @var ImageManager $image_manager
             */
            $image_manager = ModelManagerFactory::getByName('image');
            $this->images  = $image_manager->getListByDoctorId($this->getId());

            return $this->images;
        }

        public function getPurposeOfVisitListBySpecialtyId($specialty_id)
        {
            /**
             * @var PurposeOfVisitManager $purpose_manager
             */
            $purpose_manager = ModelManagerFactory::getByName('purpose_of_visit');
            $data            = $purpose_manager->getListByDoctorIdAndSpecialtyId($this->getId(), $specialty_id);

            return $data;
        }

        public function getWorkTimeAndClinicIdByDate($date)
        {
            $schedule_manager = new ScheduleManager();

            $first_entry = $schedule_manager->getOneFirstByDoctorIdAndDate($this->getId(), $date);

            if(!$first_entry)
            {
                return FALSE;
            }

            $last_entry = $schedule_manager->getOneLastByDoctorIdAndDate($this->getId(), $date);

            $start_h = date('H', strtotime($first_entry->dt_start));

            if(date('i', strtotime($last_entry->dt_end)) != '00')
            {
                $end_h = date('H', strtotime($last_entry->dt_end)) + 1;
            }
            else
            {
                $end_h = date('H', strtotime($last_entry->dt_end));
            }

            if(strlen($end_h) == 1)
            {
                $end_h = '0' . $end_h;
            }


            return array('time' => 'c ' . $start_h . ' до ' . $end_h, 'clinic_id' => $first_entry->clinic_id, 'schedule_id' => $first_entry->id);
        }

        public function getWorkTimeByDateAndClinicId($date, $clinic_id)
        {
            $schedule_manager = new ScheduleManager();

            $first_entry = $schedule_manager->getOneFirstByDoctorIdAndDateAndClinicId($this->getId(), $date, $clinic_id);

            if(!$first_entry)
            {
                return FALSE;
            }

            $last_entry = $schedule_manager->getOneLastByDoctorIdAndDateAndClinicId($this->getId(), $date, $clinic_id);

            $start_h = date('H', strtotime($first_entry->dt_start));

            if(date('i', strtotime($last_entry->dt_end)) != '00')
            {
                $end_h = date('H', strtotime($last_entry->dt_end)) + 1;
            }
            else
            {
                $end_h = date('H', strtotime($last_entry->dt_end));
            }

            if(strlen($end_h) == 1)
            {
                $end_h = '0' . $end_h;
            }


            return array('time' => 'c ' . $start_h . ' до ' . $end_h, 'schedule_id' => $first_entry->id);
        }

        public function getFirstVisitPrice($clinic_id, $specialty_id = FALSE, $purpose_of_visit_id = 283)
        {
            $first_visit_price = null;
            if (!$this->docdoc_id) {
                $purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('purpose_of_visit_to_doctor');
                $first_visit_price                  = $purpose_of_visit_to_doctor_manager->getFirstVisitPriceByDoctorIdAndClinicIdAndSpecialtyIdAndPurposeOfVisitId($this->id, $clinic_id, $specialty_id, $purpose_of_visit_id);
            }

            if($specialty_id && !$first_visit_price)
            {
                $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');
                $first_visit_price        = $doctor_to_clinic_manager->getFirstVisitPriceByDoctorIdAndClinicIdAndSpecialtyId($this->id, $clinic_id, $specialty_id);
            }

            if(!$first_visit_price)
            {
                $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');

                $first_visit_price = $doctor_to_clinic_manager->getFirstVisitPriceByDoctorIdAndClinicId($this->id, $clinic_id);

            }
            return $first_visit_price;
        }

        public function getFirstVisitPriceByClinicId($clinic_id, $specialty_id = FALSE, $purpose_of_visit_id = FALSE)
        {
            /**
             * @var PurposeOfVisitToDoctorManager $purpose_of_visit_to_doctor_manager
             * @var DoctorToClinicManager         $doctor_to_clinic_manager
             */
            if(!$this->docdoc_id && $specialty_id && $purpose_of_visit_id)
            {
                $purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('purpose_of_visit_to_doctor');
                $first_visit_price                  = $purpose_of_visit_to_doctor_manager->getFirstVisitPriceByDoctorIdAndClinicIdAndSpecialtyIdAndPurposeOfVisitId($this->id, $clinic_id, $specialty_id, $purpose_of_visit_id);
            }
            else
            {
                $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');

                if($specialty_id)
                {
                    $first_visit_price = $doctor_to_clinic_manager->getFirstVisitPriceByDoctorIdAndClinicIdAndSpecialtyId($this->id, $clinic_id, $specialty_id);
                }
                else
                {
                    $first_visit_price = $doctor_to_clinic_manager->getFirstVisitPriceByDoctorIdAndClinicId($this->id, $clinic_id);
                }
            }

            return $first_visit_price;
        }

        public function getSecondVisitPrice($clinic_id, $specialty_id = FALSE, $purpose_of_visit_id = 284)
        {
            if ($this->docdoc_id) {
                return null;
            }
            $purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('purpose_of_visit_to_doctor');
            $second_visit_price                 = $purpose_of_visit_to_doctor_manager->getFirstVisitPriceByDoctorIdAndClinicIdAndSpecialtyIdAndPurposeOfVisitId($this->id, $clinic_id, $specialty_id, $purpose_of_visit_id);

            if(0 && $specialty_id && !$second_visit_price)
            {
                $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');
                $second_visit_price       = $doctor_to_clinic_manager->getFirstVisitPriceByDoctorIdAndClinicIdAndSpecialtyId($this->id, $clinic_id, $specialty_id);
            }

            if(!$second_visit_price)
            {
                $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');

                $second_visit_price = $doctor_to_clinic_manager->getSecondVisitPriceByDoctorIdAndClinicId($this->id, $clinic_id);

            }

            return $second_visit_price;
        }

        public function getSecondVisitPriceByClinicId($clinic_id, $specialty_id = FALSE, $purpose_of_visit_id = FALSE)
        {
            /**
             * @var PurposeOfVisitToDoctorManager $purpose_of_visit_to_doctor_manager
             * @var DoctorToClinicManager         $doctor_to_clinic_manager
             */
            if ($this->docdoc_id) {
                return null;
            }
            if($specialty_id && $purpose_of_visit_id)
            {
                $purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('purpose_of_visit_to_doctor');
                $second_visit_price                 = $purpose_of_visit_to_doctor_manager->getFirstVisitPriceByDoctorIdAndClinicIdAndSpecialtyIdAndPurposeOfVisitId($this->id, $clinic_id, $specialty_id, $purpose_of_visit_id);
            }
            else
            {
                $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');

                if($specialty_id)
                {
                    $second_visit_price = $doctor_to_clinic_manager->getSecondVisitPriceByDoctorIdAndClinicIdAndSpecialtyId($this->id, $clinic_id, $specialty_id);
                }
                else
                {
                    $second_visit_price = $doctor_to_clinic_manager->getSecondVisitPriceByDoctorIdAndClinicId($this->id, $clinic_id);
                }
            }

            return $second_visit_price;
        }

        public function getWorkTimeIdByDate($date)
        {
            /**
             * @var ScheduleManager $schedule_manager
             */
            $schedule_manager = ModelManagerFactory::getByName('schedule');

            $first_entry = $schedule_manager->getOneFirstByDoctorIdAndDate($this->getId(), $date);

            if(!$first_entry)
            {
                return FALSE;
            }

            return $first_entry->id;
        }

        public function getSpecialtyByClinicId($clinic_id)
        {
            /**
             * @var DoctorToClinicManager $doctor_to_clinic_manager
             */
            $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');
            $specialty_id             = $doctor_to_clinic_manager->getSpecialtyIdByDoctorIdAndClinicId($this->id, $clinic_id);

            return ModelManagerFactory::getByName('specialty')->getOneById($specialty_id);
        }

        public function _field_last_review()
        {
            /**
             * @var DoctorReviewManager $doctor_review_manager
             */
            $doctor_review_manager = ModelManagerFactory::getByName('doctor_review');
            $this->last_review     = $doctor_review_manager->getOneLastConfirmedByDoctorId($this->getId());

            return $this->last_review;
        }

        /**
         * Проверяет, есть ли у пользователя визит, на который он не оставил отзыв
         *
         * @param $account_id
         */
        public function getOneLastUncommentedVisitByAccountId($account_id)
        {
            /**
             * @var VisitManager $visit_manager
             */
            $visit_manager = ModelManagerFactory::getByName('visit');

            return $visit_manager->getOneLastUncommentedByAccountIdAndDoctorId($account_id, $this->getId());
        }

        public function getOneClinicbyId($clinic_id)
        {
            $clinic_manager = new ClinicManager();

            return $clinic_manager->getOneById($clinic_id);
        }

        protected function _field_card_image()
        {
            /**
             * @var ImageManager $image_manager
             */
            $image_manager    = ModelManagerFactory::getByName('image');
            $this->card_image = $image_manager->getOneById($this->card_image_id);

            return $this->card_image;
        }

        public function getSpecialtiesListByClinicId($clinic_id)
        {
            /**
             * @var SpecialtyManager $specialty_manager
             */
            $specialty_manager = ModelManagerFactory::getByName('specialty');

            return $specialty_manager->getDoctorSpecialtyListByClinicId($this->getId(), $clinic_id);
        }

        public function isWorkInClinic($clinic_id)
        {
            /**
             * @var DoctorToClinicManager $doctor_to_clinic_manager
             */
            $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');

            return (bool)$doctor_to_clinic_manager->getOneByClinicIdAndDoctorId($clinic_id, $this->getId());
        }

        public function _field_is_has_visit_slots()
        {
            /**
             * @var ScheduleManager $schedule_manager
             */
            $schedule_manager = ModelManagerFactory::getByName('schedule');

            return $schedule_manager->isHasVisitSlotsByDoctorId($this->getId());

        }


        public function hasRate()
        {
            return ((($this->advice_rate) || ($this->cabinet_rate) || ($this->waiting_time_rate) || ($this->relationship_rate) || ($this->value_for_money_rate) || ($this->diagnosis_is_clear_rate)));
        }

        protected function _field_reviews()
        {
            /**
             * @var DoctorReviewManager $doctor_review_manager
             */
            $doctor_review_manager = ModelManagerFactory::getByName('doctor_review');
            $reviews               = $doctor_review_manager->getConfirmedListByDoctorId($this->getId());

            return $this->reviews = $reviews;
        }

        public function getClinicsBySpecialtyId($specialty_id)
        {
            $specialties_ids[] = $specialty_id;

            /**
             * @var DoctorManager $doctorManager
             */
            $doctorManager    = ModelManagerFactory::getByName('doctor');
            $suitable_specialties = $doctorManager->getRelatedSpecialties($specialty_id);
            if($suitable_specialties)
            {
                foreach($suitable_specialties as $suitable_specialty)
                {
                    $specialties_ids[] = $suitable_specialty->getId();
                }
            }

            /**
             * @var ClinicManager $clinic_manager
             */
            $clinic_manager = ModelManagerFactory::getByName('clinic');

            return $clinic_manager->getListByDoctorIdAndSpecialtyIdList($this->getId(), $specialties_ids);
        }

        /**
         * @param int $clinic_id
         *
         * @return SpecialtyModel[]
         */
        public function getSpecialtiesByClinicId($clinic_id)
        {
            $specialty_manager = new SpecialtyManager();

            return $specialty_manager->getListByDoctorIdAndClinicId($this->getId(), $clinic_id);
        }

        public function getSuggestedSpecialtiesListBySpecialtyIdAndClinicId($specialty_id, $clinic_id)
        {
            $specialties_ids[]    = $specialty_id;
            /**
             * @var DoctorManager $doctorManager
             */
            $doctorManager    = ModelManagerFactory::getByName('doctor');
            $suitable_specialties = $doctorManager->getRelatedSpecialties($specialty_id);
            if($suitable_specialties)
            {
                foreach($suitable_specialties as $suitable_specialty)
                {
                    $specialties_ids[] = $suitable_specialty->getId();
                }
            }

            $specialty_manager = new SpecialtyManager();

            return $specialty_manager->getSuitableListByDoctorIdAndClinicIdAndSpecialtyIdList($this->getId(), $clinic_id, $specialties_ids);
        }

        public function _field_moderate_full_name()
        {
            $moderate_doctor_information_manager = new ModerateDoctorInformationManager();
            /**
             * @var ModerateDoctorInformationModel $moderate_information
             */
            $moderate_information = $moderate_doctor_information_manager->getCurrentRevision($this->getId());

            return $moderate_information->last_name . ' ' . $moderate_information->first_name . ' ' . $moderate_information->second_name;
        }

        protected function _field_clinics_for_all()
        {
            $clinic_manager        = ModelManagerFactory::getByName('clinic');
            $this->clinics_for_all = $clinic_manager->getAllByDoctorId($this->getId());

            return $this->clinics_for_all;
        }

        public function checkExistingDoctorScheduleByClinicIdAndClinicSpecialties($clinic_id, $doctor_clinic_specialties)
        {
            $doctor_schedule_manager = new DoctorScheduleManager();
            foreach($doctor_clinic_specialties as $clinic_specialty)
            {
                $doctor_schedule = $doctor_schedule_manager->getOneCurrentByDoctorIdAndClinicIdAndSpecialtyId($this->getId(), $clinic_id, $clinic_specialty->getId());
                if($doctor_schedule) return TRUE;
            }

            return FALSE;
        }

        public function getSpecialtiesNamesStringByClinicSpecialties($clinic_specialties)
        {
            $str = '';

            $i = 1;
            foreach($clinic_specialties as $specialty)
            {
                if($i == 1)
                    $str .= StringHelper::startProposalWord($specialty->name) . ', ';
                else
                    $str .= mb_strtolower($specialty->name, 'utf-8') . ', ';
                $i++;
            }

            return trim($str, ', ');
        }

        public function getSpecialtiesNamesStringByClinicId($clinic_id)
        {
            $specialties = $this->getSpecialtiesByClinicId($clinic_id);

            return $this->getSpecialtiesNamesStringByClinicSpecialties($specialties);
        }

        protected function _field_district()
        {
            /**
             * @var DistrictManager $district_manager
             */

            $district_manager = ModelManagerFactory::getByName('district');

            return $district_manager->getOneByDoctorId($this->getId());
        }

        protected function _field_education()
        {
            /**
             * @var DoctorInfoManager $doctor_info_manager
             * @var DoctorInfoModel   $doctor_info
             */

            if(isset($this->education) && $this->education)
            {
                return $this->education;
            }
            else if(isset($this->doctor_info) && $this->doctor_info)
            {
                return $this->doctor_info->education;
            }

            $doctor_info_manager = ModelManagerFactory::getByName('doctor_info');
            $doctor_info         = $doctor_info_manager->getOneByDoctorId($this->id);
            $this->doctor_info   = $doctor_info;
            $this->education     = ($doctor_info) ? $doctor_info->education : NULL;

            return $this->education;
        }

        protected function _field_course()
        {
            /**
             * @var DoctorInfoManager $doctor_info_manager
             * @var DoctorInfoModel   $doctor_info
             */

            if(isset($this->course) && $this->course)
            {
                return $this->course;
            }
            else if(isset($this->doctor_info) && $this->doctor_info)
            {
                return $this->doctor_info->course;
            }

            $doctor_info_manager = ModelManagerFactory::getByName('doctor_info');
            $doctor_info         = $doctor_info_manager->getOneByDoctorId($this->id);
            $this->doctor_info   = $doctor_info;
            $this->course        = ($doctor_info) ? $doctor_info->course : NULL;

            return $this->certificate;
        }

        protected function _field_certificate()
        {
            /**
             * @var DoctorInfoManager $doctor_info_manager
             * @var DoctorInfoModel   $doctor_info
             */

            if(isset($this->certificate) && $this->certificate)
            {
                return $this->certificate;
            }
            else if(isset($this->doctor_info) && $this->doctor_info)
            {
                return $this->doctor_info->certificate;
            }

            $doctor_info_manager = ModelManagerFactory::getByName('doctor_info');
            $doctor_info         = $doctor_info_manager->getOneByDoctorId($this->id);
            $this->doctor_info   = $doctor_info;
            $this->certificate   = ($doctor_info) ? $doctor_info->certificate : NULL;

            return $this->certificate;
        }

        protected function _field_academic_title()
        {
            /**
             * @var DoctorInfoManager $doctor_info_manager
             * @var DoctorInfoModel   $doctor_info
             */

            if(isset($this->academic_title) && $this->academic_title)
            {
                return $this->academic_title;
            }
            else if(isset($this->doctor_info) && $this->doctor_info)
            {
                return $this->doctor_info->academic_title;
            }

            $doctor_info_manager  = ModelManagerFactory::getByName('doctor_info');
            $doctor_info          = $doctor_info_manager->getOneByDoctorId($this->id);
            $this->doctor_info    = $doctor_info;
            $this->academic_title = ($doctor_info) ? $doctor_info->academic_title : NULL;

            return $this->academic_title;
        }

        protected function _field_doctor_info()
        {
            /**
             * @var DoctorInfoManager $doctor_info_manager
             */

            if(isset($this->doctor_info) && $this->doctor_info)
            {
                return $this->doctor_info;
            }

            $doctor_info_manager = ModelManagerFactory::getByName('doctor_info');
            $this->doctor_info   = $doctor_info_manager->getOneByDoctorId($this->id);

            return $this->doctor_info;
        }

        public function getRecordButton($type = 1)
        {
            if (SITE_PHONE_CODE != '495'  && $this->clinic && $this->clinic->docdoc_id) {
                $idval = 'docdocrecordToDoctor' . $this->id . '_' . rand(0, 1000);
                $clinicId = $this->clinic->docdoc_id;
                $doctorId = $this->docdoc_id ? $this->docdoc_id : 'null';
                $return = "<div id=\"$idval\"></div>
                <script type=\"text/javascript\">
                DdWidgetAsync({
                        widget: 'Button',
                        template: 'Button_common',
                        pid: '9387',
                        id: '$idval',
                        container: '$idval',
                        action: 'LoadWidget',
                        clinicId: $clinicId,
                        doctorId: $doctorId,
                        city: 'msk'
                    });
                </script>
                ";

                return $return;
            }
            else{
                global $is_small_card;
                if ($type == 1)
                    return "<a onclick=\"recordController.showForm(".$this->getId().",0,0)\" href=\"#record-to-the-doctor-popup-".$this->getId()."\" class=\"btn-appoint refactor-btn-appoint-styles ".($is_small_card ? 'btn-appoint-sm' : '' )."\">
            <span class=\"button-name\">
                Записаться на прием сейчас
            </span>
        </a>";

                if ($type == 2)
                    return "<a class=\"btn-appoint\" href=\"#record-to-the-doctor-popup-".$this->getId()."\" onclick=\"recordController.showForm(".$this->getId().",0,0)\">Записаться</a>";
            }

        }

        /**
         * @return bool Whether the flags' values have changed.
         */
        public function updateWorkingTimeFlags()
        {
            $w = $m = $e = 0;
            foreach ($this->clinics as $clinic) {
                $w |= $clinic->isOpenOnWeekends();
                $m |= $clinic->isOpenInMorning();
                $e |= $clinic->isOpenInEvening();
            }
            $ow = (int) $this->getOldAttribute('is_has_weekend_time');
            $om = (int) $this->getOldAttribute('is_has_morning_time');
            $oe = (int) $this->getOldAttribute('is_has_evening_time');
            $this->is_has_weekend_time = (string) $w;
            $this->is_has_morning_time = (string) $m;
            $this->is_has_evening_time = (string) $e;

            return $ow !== $w || $om !== $m || $oe !== $e;
        }
    }
