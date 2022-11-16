<?php
	/**
	 * @property int $id
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 * @property int $doctor_id
	 * @property DoctorModel $doctor
	 * @property datetime $dt_start
	 * @property datetime $dt_end
	 * @property int $is_busy
	 * @property int $visit_id
	 * @property VisitModel $visit
	 * @property int $reserved_account_id
	 * @property AccountModel $reserved_account
	 * @property datetime $reserve_dt
	 * @property int $specialty_id
	 * @property SpecialtyModel $specialty
	 *
	 */
	class ScheduleModel extends DynamicModel
	{
		public function setNotBusyStatus()
		{
			$schedule_manager = new ScheduleManager();
			$schedule_manager->setNotBusyStatusByScheduleId($this->getId());
		}

		public function setReservedStatusByAccountId($account_id)
		{
			$schedule_manager = new ScheduleManager();
			$schedule_manager->setReservedStatusByScheduleIdAndAccountId($this->getId(), $account_id);

			$this->is_busy = ScheduleManager::RESERVED_STATUS;
		}
	}