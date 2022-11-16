<?php
	/**
	 * @property int $id
	 * @property int $account_id
	 * @property AccountModel $account
	 * @property string $full_name
	 * @property int $schedule_id
	 * @property ScheduleModel $schedule
	 * @property string $phone
	 * @property int $status_id
	 * @property string $confirm_code
	 * @property datetime $confirm_dt
	 * @property datetime $notification_dt
	 * @property string $price
	 * @property int $is_first_visit
	 * @property int $purpose_of_visit_id
	 * @property PurposeOfVisitModel $purpose_of_visit
	 * @property datetime $visit_start_time
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 * @property int $doctor_id
	 * @property DoctorModel $doctor
	 * @property string $comment
	 * @property string $admin_comment
	 * @property int $specialty_id
	 * @property SpecialtyModel $specialty
	 * @property string $yandex_id
	 * @property string $city_id
     * @property datetime $create_time
	 *
	 * @property string $doctor_name
	 * @property string $clinic_name
	 * @property string $clinic_address
	 * @property string $account_name
	 * @property DoctorTypeModel $doctor_type
	 * @property VisitRatingModel $rating
	 * @property string $clinic_phone
	 * @property string $formatted_phone
	 * @property SpecialtyModel $doctor_specialty
	 * @property SpecialtyModel $visit_specialty
	 * @property SpecialtyModel $dative_visit_specialty
     * @property SpecialtyModel $genitive_visit_specialty
     * @property SpecialtyModel $plural_visit_specialty
	 * @property datetime $dt
	 * @property string $visit_number
	 * @property string $visit_range
     * @property int $notify_minutes
	 * @property string $moscow_visit_start_time
     * @property int $is_new_visit
     * @property int $appeal_id
     * @property int $from_mobile
     * @property string $whence_canceled
     * @property string $email
     * @property int $visit_channel_id
     * @property int $first_visit_price_to_doctor
     * @property int $second_visit_price_to_doctor
     * @property int $first_visit_price_to_clinic
     * @property int $second_visit_price_to_clinic
     * @property TargetCallModel $target_call
     * @property int $target_call_id
     * @property int $operator_account_id
     * @property boolean $create_mail_sended
     * @property AppealModel $appeal
     * @property CityModel $city
     * @property AccountModel $operator
	 */
	class VisitModel extends DynamicModel
	{
		const CHECKING = 1;
		const CANCELLED = 2;
		const CONFIRMED = 3;
		const REJECTED = 4;
		const CALL_TO_CLINIC = 5;
		const FEDDBACK = 6;
		const VISITED = 7;
		const NOT_VISITED = 8;

		const RESERVED_TIME_SLOT = 100000;

        private $whence_canceled;

        public function __construct()
        {
            $this->setDefaultValue('visit_channel_id', VisitChannelModel::SITE);
        }

		protected function _field_doctor_name()
		{
			return trim($this->doctor->last_name) . ' ' . trim($this->doctor->first_name) . ' ' . trim($this->doctor->second_name);
		}

		protected function _field_clinic_name()
		{
            if($this->clinic)
            {
                return $this->clinic->name;
            } else {
                return '';
            }
		}

		protected function _field_clinic_address()
		{
            if($this->clinic)
            {
                return $this->clinic->address;
            } else {
                return '';
            }
		}

		protected function _field_account_name()
		{
			return $this->account->last_name . ' ' . $this->account->first_name . ' ' . $this->account->middle_name;
		}

		protected function _field_doctor_type()
		{
			$this->doctor_type = $this->doctor->doctor_type;
			return $this->doctor_type;
		}

		protected function _field_rating()
		{
			if(!isset($this->rating))
			{
				$visit_rating_manager = new VisitRatingManager();
				$visit_rating = $visit_rating_manager->getOneByVisitId($this->getId());

				$this->rating = $visit_rating;
			}

			return $this->rating;
		}

		protected function _field_clinic_phone()
		{
			if($this->clinic)
			{
                /**
                 * @var ClinicPhoneManager $clinic_phone_manager
                 */
                $clinic_phone_manager =  ModelManagerFactory::getByName('clinic_phone');
                if($this->clinic_id)
                {
                    $clinic_phone = $clinic_phone_manager->getOneByClinicId($this->clinic->getId());

                    if($clinic_phone)
                    {
                        return $clinic_phone->phone_number;
                    }
                    else
                    {
                        return false;
                    }
                } else {
                    return false;
                }
			}

			return false;
		}

		protected function _field_formatted_phone()
		{
			return preg_replace('/[^0-9]/', '', $this->phone);
		}

		protected function _field_doctor_specialty()
		{
			$doctor_to_clinic = ModelManagerFactory::getByName('doctor_to_clinic')->getOneByClinicIdAndDoctorId($this->clinic_id, $this->doctor_id);
			return ($doctor_to_clinic->specialty && $doctor_to_clinic->specialty->name) ? $doctor_to_clinic->specialty->name : '';
		}

		protected function _field_visit_specialty()
		{
			return ($this->specialty_id) ? $this->specialty->name : '';
		}

        protected function _field_dative_visit_specialty()
        {
            return ($this->specialty_id) ? $this->specialty->dative_name : '';
        }

        protected function _field_genitive_visit_specialty()
        {
            return ($this->specialty_id) ? $this->specialty->genitive_name : '';
        }

        protected function _field_plural_visit_specialty()
        {
            return ($this->specialty_id) ? $this->specialty->plural_name : '';
        }

		protected function _field_dt()
		{
			if($this->visit_start_time)
			{
				$this->dt = $this->visit_start_time;
				return $this->dt;
			}
			else
			{
				$this->dt = isset($this->schedule->dt_start) ? $this->schedule->dt_start : null;
				return $this->dt;
			}
		}

		protected function _field_visit_number()
		{
			return $this->getId();
		}

		public function checkVisitReviewByAccountId($account_id)
		{
			$visit_rating_manager = new VisitRatingManager();
			return (bool)$visit_rating_manager->getOneByVisitIdAndAccountId($this->getId(), $account_id);
		}

		public function checkConfirmedReviews()
		{
			$clinic_review_confirm = true;
			$doctor_review_confirm = true;
			$clinic_review_manager = new ClinicReviewManager();
			$doctor_review_manager = new DoctorReviewManager();
			$clinic_review = $clinic_review_manager->getOneByVisitIdAndAccountId($this->getId(), Acc::accountId());
			if($clinic_review && $clinic_review->is_confirmed != 1)
			{
				$clinic_review_confirm = false;
			}
			$doctor_review = $doctor_review_manager->getOneByVisitIdAndAccountId($this->getId(), Acc::accountId());
			if($doctor_review && $doctor_review->is_confirmed != 1)
			{
				$doctor_review_confirm = false;
			}

			return (bool)($clinic_review_confirm && $doctor_review_confirm);
		}

		public function isChangeStatus()
		{
			return ($this->getOldAttribute('status_id') != $this->status_id);
		}

		public function isChangeApprovedStatus()
		{
		    $oldStatusId = $this->getOldAttribute('status_id');
			if($oldStatusId == VisitModel::CONFIRMED && $this->status_id == VisitModel::FEDDBACK)
			{
				return false;
			}

			if($oldStatusId == VisitModel::FEDDBACK && $this->status_id == VisitModel::CONFIRMED)
			{
				return false;
			}

			return ($oldStatusId != $this->status_id);
		}

		protected function _field_visit_range()
		{
			if($this->schedule && $this->schedule->dt_start && $this->schedule->dt_end)
			{
				$visit_range = DateViewHelper::date($this->schedule->dt_start, 'date_and_time') . ' - ' . DateViewHelper::date($this->schedule->dt_end, 'date_and_time');
			}
			else
			{
				$visit_range = '';
			}
			return $visit_range;
		}

        protected function _field_time_create()
        {
            $create_time = '';

            if($this->create_time)
            {
                $create_time = DateViewHelper::date($this->create_time, 'date_and_time');
            }

            return $create_time;
        }

        public static function getStatusNameByStatusId($status_id)
        {
            switch ($status_id){
				case VisitModel::CHECKING:
					return 'Новая заявка';
					break;
				case VisitModel::CANCELLED:
					return 'Отменено';
					break;
				case VisitModel::CONFIRMED:
					return 'Пациент записан';
					break;
				case VisitModel::CALL_TO_CLINIC:
					return 'Звонок в клинику';
					break;
				case VisitModel::FEDDBACK:
					return 'Обратная связь';
					break;
				case VisitModel::VISITED:
					return 'Был у врача';
					break;
				case VisitModel::NOT_VISITED:
					return 'Не был у врача';
					break;
				default:
					return '';
					break;
			}
		}

		public static function getBookStatusNameForYandexByStatusId($status_id)
		{
			switch($status_id)
			{
				case VisitModel::CHECKING:
					return 'ACCEPTED';
					break;
				case VisitModel::CANCELLED:
					return 'CANCELLED_BY_USER';
					break;
				case VisitModel::CONFIRMED:
					return 'APPROVED';
					break;
				case VisitModel::CALL_TO_CLINIC:
					return 'USER_NOTIFIED';
					break;
				case VisitModel::FEDDBACK:
					return 'APPROVED';
					break;
				case VisitModel::VISITED:
					return 'COME';
					break;
				case VisitModel::NOT_VISITED:
					return 'DID_NOT_COME';
					break;
				default:
					return '';
					break;
			}
		}

        public function getWhenceCanceled()
        {
            return ($this->whence_canceled) ? $this->whence_canceled : '';
        }

        public function setWhenceCanceled($value)
        {
            $this->whence_canceled = $value;
        }

        protected function _field_first_visit_price_to_doctor()
        {
            if(isset($this->first_visit_price_to_doctor) && $this->first_visit_price_to_doctor) {
                return $this->first_visit_price_to_doctor;
            } else {
                /**
                 * @var PurposeOfVisitManager $purpose_of_visit_manager
                 * @var PurposeOfVisitModel $purpose_of_visit
                 * @var PurposeOfVisitToDoctorManager $purpose_of_visit_to_doctor_manager
                 * @var PurposeOfVisitToDoctorModel $purpose_of_visit_to_doctor
                 */
                $purpose_of_visit_manager = ModelManagerFactory::getByName('purpose_of_visit');
                $purpose_of_visit = $purpose_of_visit_manager->getOneByName('Первичный прием');

                if($purpose_of_visit) {
                    $purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('purpose_of_visit_to_doctor');
                    $purpose_of_visit_to_doctor = $purpose_of_visit_to_doctor_manager->getOneByClinicIdAndDoctorIdAndSpecialtyIdAndPurposeOfVisitId(
                        $this->clinic_id,
                        $this->doctor_id,
                        $this->specialty_id,
                        $purpose_of_visit->getId()
                    );


                    $this->first_visit_price_to_doctor = ($purpose_of_visit_to_doctor) ? $purpose_of_visit_to_doctor->visit_price : null;

                    return $this->first_visit_price_to_doctor;
                } else {
                    return null;
                }
            }
        }

        protected function _field_second_visit_price_to_doctor()
        {
            if(isset($this->second_visit_price_to_doctor) && $this->second_visit_price_to_doctor) {
                return $this->second_visit_price_to_doctor;
            } else {
                /**
                 * @var PurposeOfVisitManager $purpose_of_visit_manager
                 * @var PurposeOfVisitModel $purpose_of_visit
                 * @var PurposeOfVisitToDoctorManager $purpose_of_visit_to_doctor_manager
                 * @var PurposeOfVisitToDoctorModel $purpose_of_visit_to_doctor
                 */
                $purpose_of_visit_manager = ModelManagerFactory::getByName('purpose_of_visit');
                $purpose_of_visit = $purpose_of_visit_manager->getOneByName('Повторный прием');

                if($purpose_of_visit) {
                    $purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('purpose_of_visit_to_doctor');
                    $purpose_of_visit_to_doctor = $purpose_of_visit_to_doctor_manager->getOneByClinicIdAndDoctorIdAndSpecialtyIdAndPurposeOfVisitId(
                        $this->clinic_id,
                        $this->doctor_id,
                        $this->specialty_id,
                        $purpose_of_visit->getId()
                    );
                    $this->second_visit_price_to_doctor = ($purpose_of_visit_to_doctor) ? $purpose_of_visit_to_doctor->visit_price : null;

                    return $this->second_visit_price_to_doctor;
                } else {
                    return null;
                }
            }
        }

        protected function _field_first_visit_price_to_clinic()
        {
            if(isset($this->first_visit_price_to_clinic) && $this->first_visit_price_to_clinic) {
                return $this->first_visit_price_to_clinic;
            } else {
                /**
                 * @var PurposeOfVisitManager $purpose_of_visit_manager
                 * @var PurposeOfVisitModel $purpose_of_visit
                 * @var PurposeOfVisitToClinicManager $purpose_of_visit_to_clinic_manager
                 * @var PurposeOfVisitToClinicModel $purpose_of_visit_to_clinic
                 */
                $purpose_of_visit_manager = ModelManagerFactory::getByName('purpose_of_visit');
                $purpose_of_visit = $purpose_of_visit_manager->getOneByName('Первичный прием');

                if($purpose_of_visit) {
                    $purpose_of_visit_to_clinic_manager = ModelManagerFactory::getByName('purpose_of_visit_to_clinic');
                    $purpose_of_visit_to_clinic = $purpose_of_visit_to_clinic_manager->getOneByClinicIdAndSpecialtyIdAndPurposeOfVisitId(
                        $this->clinic_id,
                        $this->specialty_id,
                        $purpose_of_visit->getId()
                    );
                    $this->first_visit_price_to_clinic = ($purpose_of_visit_to_clinic) ? $purpose_of_visit_to_clinic->visit_price : null;

                    return $this->first_visit_price_to_clinic;
                } else {
                    return null;
                }
            }
        }

        protected function _field_second_visit_price_to_clinic()
        {
            if(isset($this->second_visit_price_to_clinic) && $this->second_visit_price_to_clinic) {
                return $this->second_visit_price_to_clinic;
            } else {
                /**
                 * @var PurposeOfVisitManager $purpose_of_visit_manager
                 * @var PurposeOfVisitModel $purpose_of_visit
                 * @var PurposeOfVisitToClinicManager $purpose_of_visit_to_clinic_manager
                 * @var PurposeOfVisitToClinicModel $purpose_of_visit_to_clinic
                 */
                $purpose_of_visit_manager = ModelManagerFactory::getByName('purpose_of_visit');
                $purpose_of_visit = $purpose_of_visit_manager->getOneByName('Повторный прием');

                if($purpose_of_visit) {
                    $purpose_of_visit_to_clinic_manager = ModelManagerFactory::getByName('purpose_of_visit_to_clinic');
                    $purpose_of_visit_to_clinic = $purpose_of_visit_to_clinic_manager->getOneByClinicIdAndSpecialtyIdAndPurposeOfVisitId(
                        $this->clinic_id,
                        $this->specialty_id,
                        $purpose_of_visit->getId()
                    );
                    $this->second_visit_price_to_clinic = ($purpose_of_visit_to_clinic) ? $purpose_of_visit_to_clinic->visit_price : null;

                    return $this->second_visit_price_to_clinic;
                } else {
                    return null;
                }
            }
        }

        protected function _field_target_call()
        {
            if(isset($this->target_call) && $this->target_call) {
                return $this->target_call;
            }

            if($this->appeal_id) {
                /**
                 * @var TargetCallManager $target_call_manager
                 */
                $target_call_manager = ModelManagerFactory::getByName('target_call');
                $this->target_call = $target_call_manager->getOneByVisitId($this->getId());
            } else {
                $this->target_call = null;
            }

            return $this->target_call;
        }

        protected function _field_target_call_id()
        {
            if(isset($this->target_call_id) && $this->target_call_id) {
                return $this->target_call_id;
            } else if(isset($this->target_call) && $this->target_call) {
                $this->target_call_id = $this->target_call->getId();
                return $this->target_call_id;
            }

            if($this->appeal_id) {
                /**
                 * @var TargetCallManager $target_call_manager
                 * @var TargetCallModel $target_call
                 */
                $target_call_manager = ModelManagerFactory::getByName('target_call');
                $target_call = $target_call_manager->getOneByVisitId($this->getId());
                $this->target_call = $target_call;

                if($this->target_call) {
                    $this->target_call_id = $target_call->getId();
                } else {
                    $this->target_call_id = null;
                }
            } else {
                $this->target_call_id = null;
            }

            return $this->target_call_id;
        }

        protected function _field_appeal()
        {
            if(isset($this->appeal) && $this->appeal) {
                return $this->appeal;
            }

            /**
             * @var AppealManager $appeal_manager
             */
            $appeal_manager = ModelManagerFactory::getByName('appeal');
            $this->appeal = $appeal_manager->getOneById($this->appeal_id);

            return $this->appeal;
        }

        protected function _field_city()
        {
            if(isset($this->city) && $this->city) {
                return $this->city;
            }

            /**
             * @var AppealManager $appeal_manager
             */
            return $this->appeal = (new CityManager())->getOneById($this->city_id);
        }

        protected function _field_operator()
        {
            if(isset($this->operator) && $this->operator) {
                return $this->operator;
            }

            /**
             * @var AppealManager $appeal_manager
             */
            return $this->operator = (new AccountManager())->getOneById($this->operator_account_id);
        }
	}
