<?php
	/**
	 * @property int $id
	 * @property int $visit_id
	 * @property VisitModel $visit
	 * @property int $cabinet
	 * @property int $waiting_time
	 * @property int $relationship
	 * @property int $value_for_money
	 * @property int $diagnosis_is_clear
	 * @property int $service_at_the_reception
	 * @property int $is_doctor_advice
	 * @property int $is_clinic_advice
	 * @property int $account_id
     * @property int $doctor_id
     * @property int $clinic_id
     * @property datetime $dt
     * @property int $is_confirmed
	 * @property float  $total_clinic_rating
	 * @property float $total_doctor_rating
     * @property string $private_review_text
     * @property string $doctor_review_text
     * @property string $clinic_review_text
     * @property string $author_name
	 */
	class VisitRatingModel extends DynamicModel
	{
		public function _field_total_clinic_rating()
		{
			if($this->service_at_the_reception)
			{
				return round(($this->cabinet + $this->service_at_the_reception + $this->waiting_time) / 3, 1);
			}
			else
			{
				return null;
			}
		}

		public function _field_total_doctor_rating()
		{
			if($this->diagnosis_is_clear)
			{
				return round(($this->cabinet + $this->waiting_time + $this->relationship + $this->value_for_money + $this->diagnosis_is_clear) / 5, 1);
			}
			else
			{
				return null;
			}
		}
	}
