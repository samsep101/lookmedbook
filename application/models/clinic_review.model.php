<?php
	/**
	 * @property int $id
	 * @property int $visit_id
	 * @property VisitModel $visit
	 * @property int $account_id
	 * @property AccountModel $account
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 * @property string $text
	 * @property string $dt
	 * @property int $is_confirmed
	 * @property string $author_name
	 *
	 * @property DoctorModel $doctor
	 * @property float $total_rating
	 */
	class ClinicReviewModel extends DynamicModel
	{
		protected function _field_doctor()
		{
            if ($this->visit && $this->visit->doctor_id) {
                $this->doctor = $this->visit->doctor;
            }
            else {
                $this->doctor = false;
            }
            return $this->doctor;
		}

        protected function _field_specialty()
        {
            if ($this->visit && $this->visit->specialty_id) {
                $this->specialty = $this->visit->specialty;
            }
            else {
                $this->specialty = false;
            }
            return $this->specialty;
        }

		public function getClinicReviewRatingByVisitId($visit_id)
		{
			$visit_rating_manager = new VisitRatingManager();
			$visit_rating = $visit_rating_manager->getOneByVisitId($visit_id);
			return $visit_rating;
		}

		public function _field_total_rating()
		{
			if($this->visit && $this->visit->rating)
			{
				return $this->visit->rating->total_clinic_rating;
			}
			else
			{
				return null;
			}
		}

        public function getAccountNameOrFallback($property = 'display_name', $fallback = 'Анонимный пациент')
        {
            return $this->account && isset($this->account->$property)
                ? $this->account->$property
                : ($this->author_name ?: $fallback);
        }
    }
