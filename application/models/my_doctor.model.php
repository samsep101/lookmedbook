<?php

	/**
	 * @property int $id
	 * @property int $account_id
	 * @property AccountModel $account
	 * @property int $doctor_id
	 * @property DoctorModel $doctor
	 * @property datetime $dt
	 *
	 * @property DoctorModel $doctor_field
	 */
    class MyDoctorModel extends DynamicModel {

		protected function _field_doctor_field()
		{
			$this->doctor_field = $this->doctor;
			return $this->doctor_field;
		}

	}