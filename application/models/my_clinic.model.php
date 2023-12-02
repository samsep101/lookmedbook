<?php

	/**
	 * @property int $id
	 * @property int $account_id
	 * @property AccountModel $account
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 * @property datetime $dt
	 *
	 * @property ClinicModel $clinic_field
	 */
    class MyClinicModel extends DynamicModel {
		protected function _field_clinic_field()
		{
			$this->clinic_field = $this->clinic;
			return $this->clinic_field;
		}

	}