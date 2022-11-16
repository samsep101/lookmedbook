<?php

    /**
     * @property int                                                $id
     * @property int                                                $region_id
     * @property RegionModel                                        $region
     * @property string                                             $name
     * @property string                                             $seo_name
     * @property string                                             $original_alias
     * @property int                                                $primary_clinic_id
     * @property string                                             $alias
     * @property string                                             $full_name
     * @property int                                                $clinic_type_id
     * @property ClinicTypeModel                                    $clinic_type
     * @property string                                             $about
     * @property string                                             $address
     * @property int                                                $street_id
     * @property StreetModel                                        $street
     * @property string                                             $house
     * @property string                                             $structure
     * @property string                                             $case
     * @property int                                                $room
     * @property string                                             $latitude
     * @property string                                             $longitude
     * @property string                                             $rate
     * @property string                                             $advice_rate
     * @property int                                                $city_id
     * @property CityModel                                          $city
     * @property string                                             $availability
     * @property int                                                $is_children
     * @property int                                                $is_adult
     * @property int                                                $is_handicapped
     * @property int                                                $is_pregnant
     * @property int                                                $only_children
     * @property int                                                $only_adult
     * @property int                                                $is_day_and_night
     * @property string                                             $start_time_monday
     * @property string                                             $end_time_monday
     * @property string                                             $start_time_tuesday
     * @property string                                             $end_time_tuesday
     * @property string                                             $start_time_wednesday
     * @property string                                             $end_time_wednesday
     * @property string                                             $start_time_thursday
     * @property string                                             $end_time_thursday
     * @property string                                             $start_time_friday
     * @property string                                             $end_time_friday
     * @property string                                             $start_time_saturday
     * @property string                                             $end_time_saturday
     * @property string                                             $start_time_sunday
     * @property string                                             $end_time_sunday
     * @property string                                             $name_of_bank
     * @property string                                             $bank_bik
     * @property string                                             $bank_inn
     * @property string                                             $bank_kpp
     * @property string                                             $correspondent_account
     * @property string                                             $current_account
     * @property string                                             $ogrn
     * @property string                                             $legal_address
     * @property string                                             $fact_address
     * @property int                                                $is_card_pay
     * @property int                                                $is_cache_pay
     * @property string                                             $director_fio
     * @property string                                             $license_number
     * @property string                                             $license_issue_date
     * @property string                                             $license_validity_date
     * @property int                                                $is_active
     * @property int                                                $not_work
     * @property int                                                $metro_station_id
     * @property MetroStationModel                                  $metro_station
     * @property MetroStationModel[]                                $metro_stations
     * @property int                                                $card_image_id
     * @property ImageModel                                         $card_image
     * @property int                                                $balls
     * @property int                                                $image_id
     * @property ImageModel                                         $image
     * @property string                                             $fio
     * @property string                                             $phone
     * @property string                                             $direct_phone
     * @property string                                             $top_phone
     * @property string                                             $top_phone_text
     * @property string                                             $email
     * @property string                                             $site
     * @property int                                                $postcode
     * @property string                                             $monday_break_start_time
     * @property string                                             $monday_break_end_time
     * @property string                                             $tuesday_break_start_time
     * @property string                                             $tuesday_break_end_time
     * @property string                                             $wednesday_break_start_time
     * @property string                                             $wednesday_break_end_time
     * @property string                                             $thursday_break_start_time
     * @property string                                             $thursday_break_end_time
     * @property string                                             $friday_break_start_time
     * @property string                                             $friday_break_end_time
     * @property string                                             $saturday_break_start_time
     * @property string                                             $saturday_break_end_time
     * @property string                                             $sunday_break_start_time
     * @property string                                             $sunday_break_end_time
     * @property int                                                $is_contract
     * @property int                                                $is_prescribe_sick_leave
     * @property int                                                $clinic_status_id
     * @property ClinicStatusModel                                  $clinic_status
     * @property datetime                                           $dt_publish
     * @property date                                               $date_publish
     *
     * @property DoctorModel[]                                      $doctors
     * @property SpecialtyModel[]                                   $specialties
     * @property SpecialtyModel[]                                   $just_its_specialties
     * @property SpecializationModel[]                              $specializations
     * @property FeatureModel[]                                     $features
     * @property ClinicReviewModel[]                                $reviews
     * @property bool                                               $my_clinic
     * @property string                                             $metro_station_name
     * @property ImageModel[]                                       $images
     * @property ClinicPhoneModel[]                                 $phones
     * @property string                                             $phones_string
     * @property array                                              $structured
     * @property int                                                $data_source_id
     * @property DataSourceModel                                    $data_source
     * @property string                                             $yandex_url
     * @property bool                                               $is_region
     * @property UserModel                                          $freelancer
     * @property SpecialtyModel[]                                   $doctor_specialties
     * @property string                                             $contract_number
     * @property date                                               $date_contract
     * @property string                                             $legal_entity
     * @property int                                                $representative_user_id
     * @property integer                                            $is_active_updated_at
     *
     * @property ModerateClinicInformationModel                     $moderate_information
     * @property ModerateClinicCardImageModel                       $moderate_card_image
     * @property string                                             $moderate_about
     * @property SpecializationModel[]                              $moderate_specializations
     * @property DoctorModel[]                                      $not_virtual_doctors
     * @property int                                                $is_state
     * @property int                                                $is_yandex_send
     * @property datetime                                           $dt_price_actual
     * @property bool                                               $is_import_enabled
     * @property bool                                               $is_import_of_metro_stations_enabled
     *
     * @property UserModel[]                                        $users
     * @property DistrictModel                                      $district
     * @property int                                                $district_id
     * @property int                                                $docdoc_id
     * @property int                                                $visit_disallow
     * @property int                                                $type_flags
     * @property ServiceCategoryModel[]                             $service_categories
     */
    class ClinicModel extends DynamicModel
    {
        const REGION_PUBLISHED = 1;
        const REGION_RAW       = 3;
        const REGION_PROBLEM   = 2;

        const TYPE_FLAG_DOCTOR = 0b1;
        const TYPE_FLAG_DIAGNOSTIC_CENTER = 0b10;
        const TYPE_FLAG_ONLINE_RECORD = 0b100;

        const INACTIVE_MAX_TIME = 7776000;

        /**
         * @return bool|ClinicModel
         */
        public function getPrimaryClinic()
        {
            if (!$this->primary_clinic_id)
                return false;

            return (new ClinicManager())->getOneById($this->primary_clinic_id);
        }

        public function __construct()
        {
            $this->setDefaultValue('is_adult', 1);
            $this->setDefaultValue('is_yandex_send', 1);
            $this->setDefaultValue('type_flags', 0);
            $this->setDefaultValue('is_import_of_metro_stations_enabled', 1);
        }

        protected function _field_doctors()
        {
            if(!isset($this->doctors))
            {
                $doctor_manager = new DoctorManager();
                $this->doctors  = $doctor_manager->getActiveListByClinicId($this->getId());
            }

            return $this->doctors;
        }

        protected function _field_specialties()
        {
            if (!isset($this->specialties)) {
                /**
                 * @var SpecialtyManager $specialty_manager
                 * @var SpecialtyModel[] $specialties
                 */

                $specialty_manager = ModelManagerFactory::getByName('specialty');
                $this->specialties = $specialty_manager->getSpecialtyListForClinic($this->getId());
            }
            return $this->specialties;
        }

        protected function _field_just_its_specialties()
        {
            /**
             * @var SpecialtyManager $specialty_manager
             * @var SpecialtyModel[] $specialties
             */
            $specialty_manager = ModelManagerFactory::getByName('specialty');
            $specialties       = $specialty_manager->getListByClinicId($this->getId());

            return $this->just_its_specialties = $specialties;
        }

        protected function _field_doctor_specialties()
        {
            if(!isset($this->doctor_specialties))
            {
                /**
                 * @var SpecialtyManager $specialty_manager
                 */
                $specialty_manager        = ModelManagerFactory::getByName('specialty');
                $this->doctor_specialties = $specialty_manager->getDoctorSpecialtyListByClinicId($this->getId());
            }

            return $this->doctor_specialties;
        }

        protected function _field_specializations()
        {
            $specializations = ModelManagerFactory::getByName('specialization')->getListByClinicId($this->getId());

            return $this->specializations = $specializations;
        }

        protected function _field_features()
        {
            $features = ModelManagerFactory::getByName('feature')->getActiveListByClinicId($this->getId());

            return $this->features = $features;
        }

        protected function _field_reviews()
        {
            $clinic_review_manager = new ClinicReviewManager();
            $reviews               = $clinic_review_manager->getConfirmedListByClinicId($this->getId());

            return $this->reviews = $reviews;
        }

        protected function _field_my_clinic()
        {
            $this->my_clinic = ModelManagerFactory::getByName('my_clinic')->checkExistsByClinicIdAndAccountId($this->id, Acc::accountId());

            return $this->my_clinic;
        }

        protected function _field_metro_station()
        {
            if(isset($this->metro_station) && $this->metro_station)
            {
                return $this->metro_station;
            }

            $this->metro_station = null;

            if (isset($this->metro_station_id)) {
                /** @var MetroStationManager $metroStationManager */
                $metroStationManager = ModelManagerFactory::getByName('metro_station');
                $this->metro_station = $metroStationManager->getOneById($this->metro_station_id);
            }

            if (empty($this->metro_station)) {
                /** @var MetroStationToClinicManager $metro_station_to_clinic_manager */
                $metro_station_to_clinic_manager = ModelManagerFactory::getByName('metro_station_to_clinic');
                /** @var MetroStationToClinicModel $metro_station_to_clinic */
                $metro_station_to_clinic = $metro_station_to_clinic_manager->getOneByClinicId($this->getId());

                if ($metro_station_to_clinic) {
                    $this->metro_station_id = $metro_station_to_clinic->metro_station_id;
                    $this->metro_station = $metro_station_to_clinic->metro_station;
                }
            }

            return $this->metro_station;
        }

        protected function _field_metro_stations()
        {
            if(isset($this->metro_stations) && $this->metro_stations)
            {
                return $this->metro_stations;
            }

            /**
             * @var MetroStationManager $metro_station_manager
             * @var MetroStationModel   $metro_station
             */
            $metro_station_manager = ModelManagerFactory::getByName('metro_station');

            /**
             * @var MetroStationToClinicManager $metro_station_to_clinic_manager
             * @var MetroStationToClinicModel   $metro_station_to_clinic
             */
            $metro_station_to_clinic_manager = ModelManagerFactory::getByName('metro_station_to_clinic');
            $metro_station_to_clinic         = $metro_station_to_clinic_manager->getListByClinicId($this->getId());
            $metro_stations                  = array();
            $metro_stations_ids              = array();
            if($metro_station_to_clinic)
            {
                foreach($metro_station_to_clinic as $item)
                {
                    if(!in_array($item->metro_station_id, $metro_stations_ids))
                    {
                        $metro_stations_ids[] = $item->metro_station_id;
                        $metro_stations[] = $metro_station_manager->getOneById($item->metro_station_id);
                    }
                }
                $this->metro_stations = $metro_stations;
            }
            else
            {
                $this->metro_stations = NULL;
            }

            return $this->metro_stations;
        }

        protected function _field_metro_station_name()
        {
            if(isset($this->metro_station_name) && $this->metro_station_name)
            {
                return $this->metro_station_name;
            }

            if(isset($this->metro_station) && $this->metro_station)
            {
                $this->metro_station_name = $this->metro_station->name;
            }
            else
            {
                /**
                 * @var MetroStationToClinicManager $metro_station_to_clinic_manager
                 * @var MetroStationToClinicModel   $metro_station_to_clinic
                 * @var MetroStationManager         $metro_station_manager
                 * @var MetroStationModel           $metro_station
                 */
                $metro_station_to_clinic_manager = ModelManagerFactory::getByName('metro_station_to_clinic');
                $metro_station_to_clinic         = $metro_station_to_clinic_manager->getOneByClinicId($this->getId());

                if($metro_station_to_clinic)
                {
                    $this->metro_station_id = $metro_station_to_clinic->metro_station_id;
                    $metro_station_manager  = ModelManagerFactory::getByName('metro_station');
                    $metro_station          = $metro_station_manager->getOneById($this->metro_station_id);

                    if($metro_station)
                    {
                        $this->metro_station      = $metro_station;
                        $this->metro_station_name = $metro_station->name;
                    }
                    else
                    {
                        $this->metro_station_name = NULL;
                    }
                }
                else
                {
                    $this->metro_station_name = NULL;
                }
            }

            return $this->metro_station_name;
        }

        protected function _field_images()
        {
            $image_manager = new ImageManager();
            $this->images  = $image_manager->getListByClinicId($this->getId());

            return $this->images;
        }

        protected function _field_phones()
        {
            if(!isset($this->phones))
            {
                $clinic_phone_manager = new ClinicPhoneManager();
                $this->phones         = $clinic_phone_manager->getListByClinicId($this->getId());
            }

            return $this->phones;
        }

        protected function _field_phones_string()
        {
            $str = '';

            if($this->phones)
            {
                foreach($this->phones as $phone)
                {
                    $str .= $phone->phone_number . ', ';
                }
            }

            $str = trim($str, ', ');

            return $str;
        }

        protected function _field_card_image()
        {
            $image_manager    = new ImageManager();
            $this->card_image = $image_manager->getOneById($this->card_image_id);

            return $this->card_image;
        }

        public function _field_structured()
        {
            $result = array();

            $result['id']            = $this->clinic_id;
            $result['schedule_info'] = array('week' => array(),);

            $days = array(
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday'
            );

            foreach($days as $day)
            {
                if($this->{'start_time_' . $day})
                {
                    $result['schedule_info']['week'][$day] = array(
                        'start_time' => $this->{'start_time_' . $day},
                        'end_time'   => $this->{'end_time_' . $day},
                        'break'      => array(
                            'start_time' => $this->{$day . '_break_start_time'},
                            'end_time'   => $this->{$day . '_break_end_time'},
                        ),
                    );
                }
            }

            return $result;
        }

        /**
         * @param string $date
         * @return null|string
         */
        public function getClinicWorkTimeByDate($date)
        {
            $current_time = strtotime($date);
            $time_from    = NULL;
            $time_to      = NULL;

            $week_day = DateHelper::getDayOfWeekNameByDate($current_time);

            $time_from = $this->{'start_time_' . $week_day};
            $time_to   = $this->{'end_time_' . $week_day};

            $time_from = preg_replace('/[^0-9:]/ims', '', $time_from);
            $time_to   = preg_replace('/[^0-9:]/ims', '', $time_to);

            if(!$time_from || !$time_to)
            {
                return FALSE;
            }

            if(((int)$time_from == 0) || ((int)$time_to == 0))
            {
                return '24<br />часа';
            }

            if((int)$time_to == 0)
            {
                $time_to = '24:00';
            }

            return ($time_from && $time_to) ? 'c ' . (int)$time_from . '<br>до ' . (int)$time_to : FALSE;
        }

        public function isPrimaryClinic()
        {
            return (boolean) (new ClinicManager())->getChildsClinic($this->id);
        }

        public function isPublishNow()
        {
            return (($this->clinic_status_id == ClinicStatusModel::PUBLISHED) && ($this->params['clinic_status_id'] != ClinicStatusModel::PUBLISHED));
        }

        public function isCheckProblemNow()
        {
            return (($this->clinic_status_id == ClinicStatusModel::PROBLEM) && ($this->params['clinic_status_id'] != ClinicStatusModel::PROBLEM));
        }

        public function isChangeStatus()
        {
            return $this->isPublishNow() || $this->isCheckProblemNow();
        }

        public function _field_freelancer()
        {
            if(!isset($this->freelancer))
            {
                $user_manager = new UserManager();
                $freelancers  = $user_manager->getListByRoleIdAndClinicId(RoleModel::FREELANCE_MANAGER, $this->getId());

                $this->freelancer = NULL;
                if($freelancers) $this->freelancer = $freelancers[0];
            }

            return $this->freelancer;
        }

        public function _field_users()
        {
            if(!isset($this->users))
            {
                /**
                 * @var UserManager $user_manager
                 */
                $user_manager = ModelManagerFactory::getByName('user');
                $this->users  = $user_manager->getListByClinicId($this->getId());
            }

            return $this->users;
        }


        public function getRealClinicDoctors()
        {
            $doctor_manager = new DoctorManager();
            if($this->specialties) return $doctor_manager->getListByClinicIdAndClinicSpecialtiesIds($this->getId(), $this->specialties);
            else
                return array();
        }

        public function _field_moderate_card_image()
        {
            $moderate_clinic_card_image_manager = new ModerateClinicCardImageManager();

            /**
             * @var ModerateClinicCardImageModel $moderate_card_image
             */
            $moderate_card_image = $moderate_clinic_card_image_manager->getCurrentRevision($this->getId());

            $image = NULL;

            if($moderate_card_image && $moderate_card_image->card_image) $image = $moderate_card_image->card_image;

            return $image;
        }

        public function _field_moderate_information()
        {
            if(!isset($this->moderate_information))
            {
                $moderate_clinic_information_manager = new ModerateClinicInformationManager();
                $this->moderate_information          = $moderate_clinic_information_manager->getCurrentRevision($this->getId());
            }

            return $this->moderate_information;
        }

        protected function _field_moderate_about()
        {
            if(!isset($this->moderate_about))
            {
                $moderate_description_manager = new ModerateClinicDescriptionManager();
                $description                  = $moderate_description_manager->getCurrentRevision($this->getId());

                $this->moderate_decription = '';

                if($description) $this->moderate_about = $description->about;
            }

            return $this->moderate_about;
        }

        protected function _field_moderate_specializations()
        {
            if(!isset($this->moderate_specializations))
            {
                $result = array();

                $moderate_specialization_to_clinic_manager = new ModerateSpecializationToClinicManager();

                $revision = $moderate_specialization_to_clinic_manager->getCurrentRevision(array(
                                                                                               'clinic_id' => $this->getId()
                                                                                           ));

                if($revision->elements)
                {
                    foreach($revision->elements as $specialization_to_clinic)
                    {
                        $result[] = $specialization_to_clinic->specialization;
                    }
                }

                $this->moderate_specializations = $result;
            }

            return $this->moderate_specializations;
        }

        protected function _field_name_with_address()
        {
            return $this->name . ' (' . $this->city->name . ', ' . $this->address . ')';
        }

        public function getRecordButton($type = 1){
            if ($this->docdoc_id){
                $idval = 'docdocrecordToClinic' . $this->id . '_' . rand(0, 1000);
                $clinicId = $this->docdoc_id;
                $return = "<div id=\"$idval\"></div>
                <script type=\"text/javascript\">
                DdWidgetAsync({
                        widget: 'Button',
                        template: 'Button_common',
                        pid: '9387',
                        id: '$idval',
                        clinicId: '$clinicId',
                        container: '$idval',
                        action: 'LoadWidget',
                        city: 'msk'
                    });
                </script>
                ";

                return $return;
            }
            else{
                if ($type == 1)
                    return "<a class=\"btn-appoint\"  onclick=\"recordController.showForm(0,". $this->id .",0)\">Записаться на прием</a>";

                if ($type == 2)
                    return "<a href=\"#divider-shadow\" onclick=\"recordController.showForm(0,". $this->id.",0)\" class=\"clinic-side-box-button btn-find-doctor-2\"><span class=\"txt appoint\">Записаться на прием</span></a>";

                if ($type == 3)
                    return "<a href=\"#divider-shadow\" onclick=\"recordController.showForm(0,". $this->id.",0)\" class=\"btn-appoint clinic-side-box-button\"><span>Записаться</span></a>";
            }

        }

        public function getFirstVisitPriceByClinicId($clinic_id)
        {
        }

        protected function _field_not_virtual_doctors()
        {
            if(!isset($this->not_virtual_doctors))
            {
                $doctor_manager = new DoctorManager();
                $this->doctors  = $doctor_manager->getNotVirtualListByClinicId($this->getId());
            }

            return $this->doctors;
        }

        public function getMinFirstVisitPriceBySpecialtyIdAndPurposeOfVisitId($specialty_id, $purpose_of_visit_id)
        {
            /**
             * @var PurposeOfVisitManager $purpose_of_visit_manager
             */
            $purpose_of_visit_manager = ModelManagerFactory::getByName('purpose_of_visit');
            $min_price                = $purpose_of_visit_manager->getMinOneByClinicIdAndSpecialtyIdAndPurposeOfVisitId($this->getId(), $specialty_id, $purpose_of_visit_id);

            return $min_price ? $min_price->visit_price : NULL;
        }

        protected function _field_district()
        {
            /**
             * @var DistrictManager $district_manager
             */

            $district_manager = ModelManagerFactory::getByName('district');

            return $district_manager->getOneByClinicId($this->getId());
        }

        protected function _field_district_id()
        {
            if(isset($this->district_id) && $this->district_id)
            {
                return $this->district_id;
            }

            if($this->district)
            {
                return $this->district->getId();
            }

            /**
             * @var DistrictManager $district_manager
             * @var DistrictModel   $district
             */
            $district_manager = ModelManagerFactory::getByName('district');
            $district         = $district_manager->getOneByClinicId($this->getId());
            $this->district   = $district;

            if($district)
            {
                $this->district_id = $district->getId();
            }
            else
            {
                $this->district_id = NULL;
            }

            return $this->district_id;
        }

        public function _field_types()
        {
            $clinic_id = intval($this->id);

            $clinic_to_types_manager = ModelManagerFactory::getByName('clinic_to_types');

            return $clinic_to_types_manager->getListTypesForClinic($clinic_id);
        }

        public function _field_services()
        {
            $clinic_id = intval($this->id);

            $clinic_to_services_manager = ModelManagerFactory::getByName('clinic_to_services');

            return $clinic_to_services_manager->getListServicesForClinic($clinic_id);
        }

        public function _field_types_for_clinic()
        {
            if (!isset($this->types_for_clinic)) {
                $clinic_id = intval($this->id);

                $clinic_to_types_manager = ModelManagerFactory::getByName('clinic_to_types');
                $types = $clinic_to_types_manager->getTypesToIdentifyTypesTheCurrentClinic($clinic_id);

                $this->types_for_clinic = count($types) > 0 ? $types : [];
            }
            return $this->types_for_clinic;
        }

        public function _field_services_for_clinic()
        {
            $clinic_id = intval($this->id);

            $clinic_to_services_manager = ModelManagerFactory::getByName('clinic_to_services');
            $types                      = $clinic_to_services_manager->getServicesToIdentifyServicesTheCurrentClinic($clinic_id);

            return count($types) > 0 ? $types : array();
        }

        public function _field_service_categories()
        {
            $clinic_id = (int)$this->id;
            $serviceCategoryManager = new ServiceCategoryManager();
            $service_categories = $serviceCategoryManager->getListByClinicId($clinic_id);

            return $service_categories;
        }

        public function getSeoName()
        {
            return isset($this->seo_name) ? $this->seo_name : $this->name;
        }

        public function getRealRate()
        {
            return $this->rate ? min($this->rate, 5) : $this->rate;
        }

        public function isOpenOnWeekends()
        {
            return ($this->start_time_saturday && $this->end_time_saturday)
                || ($this->start_time_sunday && $this->end_time_sunday);
        }

        public function isOpenInMorning()
        {
            return $this->isOpenBetween(6, 10);
        }

        public function isOpenInEvening()
        {
            return $this->isOpenBetween(16, 24);
        }

        private function intvalOrNull($value)
        {
            return $value === null ? null : (int) $value;
        }

        private function getOpeningHours()
        {
            return array_map([$this, 'intvalOrNull'], [
                $this->start_time_monday,
                $this->start_time_tuesday,
                $this->start_time_wednesday,
                $this->start_time_thursday,
                $this->start_time_friday,
                $this->start_time_saturday,
                $this->start_time_sunday,
            ]);
        }

        private function getClosingHours()
        {
            return array_map([$this, 'intvalOrNull'], [
                $this->end_time_monday,
                $this->end_time_tuesday,
                $this->end_time_wednesday,
                $this->end_time_thursday,
                $this->end_time_friday,
                $this->end_time_saturday,
                $this->end_time_sunday,
            ]);
        }

        private function isOpenBetween($start, $end)
        {
            $openingHours = $this->getOpeningHours();
            $closingHours = $this->getClosingHours();
            foreach ($openingHours as $i => $o) {
                $c = $closingHours[$i];
                if (($o >= $start && $o < $end) || ($c > $start && $c <= $end)) {
                    return true;
                }
            }
            return false;
        }

        public function isDoctorWorkingTimeFlagsUpdateRequired()
        {
            if ($this->isNew()) {
                return true;
            }
            $attrs = [
                'is_active',
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
            foreach ($attrs as $attr) {
                /** @noinspection TypeUnsafeComparisonInspection */
                if ($this->$attr != $this->getOldAttribute($attr)) {
                    return true;
                }
            }
            return false;
        }
    }