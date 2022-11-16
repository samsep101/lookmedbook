<?php
	class ScheduleManager extends ModelManager
	{
		protected $table_name = 'schedule';
		protected $model_name = 'ScheduleModel';

		const RESERVED_STATUS = 2;
		const BUSY_STATUS = 1;
		const NOT_BUSY_STATUS = 0;

		public function getWeekListByDoctorId($doctor_id)
		{
			$first_day_of_week = date('Y-m-d', strtotime('Last Monday', time()));
			$last_day_of_week = date('Y-m-d', strtotime('Next Monday', time()));

			$sql = 'SELECT s.*
                    FROM ' . $this->table_name . ' s
                    WHERE s.doctor_id = ' . (int)$doctor_id . '
                        AND dt_start >= "' . $first_day_of_week . '" and dt_start <= "' . $last_day_of_week . '"';
			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();

		}

		public function getNotBusyListByDoctorIdAndDateRange($doctor_id, $date_from, $date_to)
		{
			$date_from = date('Y-m-d 00:00:00', strtotime($date_from));
			$date_to = date('Y-m-d 00:00:00', strtotime($date_to) + 86400);

			$data = $this->orm_model->select()->where('doctor_id = ? AND dt_end >= ? AND dt_start < ? AND is_busy = 0 AND dt_start > NOW()', $doctor_id, $date_from, $date_to)->fetchAll();

			return (count($data)) ? $this->initList($data) : array();
		}

		public function getNotBusyListByDoctorIdAndDateRangeAndStartWithTodayDate($doctor_id, $date_from, $date_to)
		{
			$date_from = date('Y-m-d 00:00:00', strtotime($date_from) - 86400);
			$date_to = date('Y-m-d 00:00:00', strtotime($date_to) + 86400);

			$sql = 'SELECT *
                    FROM schedule
                    WHERE doctor_id = ' . (int)$doctor_id . '
                    AND dt_end >= "' . $date_from . '"
                    AND dt_start < "' . $date_to . '"
                    AND is_busy = 0
                    ORDER BY dt_start';

			//Test::dump($sql);

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

		public function getDistinctTimeByDoctorIdAndDateRange($doctor_id, $date_from, $date_to)
		{
			$date_from = date('Y-m-d 00:00:00', strtotime($date_from));
			$date_to = date('Y-m-d 00:00:00', strtotime($date_to) + 86400);

			$sql = 'SELECT DISTINCT(DATE_FORMAT(dt_start, "%H:%i")) as `time`
                    FROM schedule s
                    WHERE doctor_id = ' . (int)$doctor_id . '
                        AND dt_end >= "' . $date_from . '"
                        AND dt_end < "' . $date_to . '"
                        AND is_busy = 0
                        AND dt_start > NOW()
                    ORDER BY time';

			$data = Register::get('db')->query($sql);

			$result = array();
			if($data)
			{
				foreach($data as $v)
				{
					$result[] = $v['time'];
				}
			}


			return $result;
		}

        /**
		 * @return ScheduleModel
		 */
		public function getOneByAccountIdAndVisitId($account_id, $visit_id)
		{
			$sql = 'SELECT vs.*
                    FROM visit vs
                    INNER JOIN schedule sch ON sch.visit_id = vs.id
                    WHERE vs.account_id = ' . (int)$account_id . '
                        AND vs.id = ' . (int)$visit_id;

			$data = $this->db->query($sql);

			return (isset($data)) ? $this->initOne($data[0]) : null;
		}

		public function setNotBusyStatusByVisitId($visit_id)
		{
			$this->orm_model->update(array('is_busy' => 0, 'visit_id' => null), 'visit_id = ' . (int)$visit_id);
		}

        /**
		 * @return ScheduleModel[]
		 */
		public function getListByDoctorIdAndWeekCounterAndIsBusy($doctor_id, $week_counter)
		{
			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE doctor_id = ' . (int)$doctor_id . '
                        AND is_busy != 1
                        AND date_format(dt_start, "%v") = date_format(date_add(now(), interval ' . $week_counter . ' week), "%v")
                    ORDER BY dt_start ASC';
			$data = $this->db->query($sql);

			return (isset($data)) ? $this->initList($data) : array();
		}

        /**
		 * @return ScheduleModel[]
		 */
		public function getListByDoctorIdAndDayCounterAndIsBusy($doctor_id, $day_counter)
		{
			$date = date('Y-m-d H:i:s');
			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE doctor_id = ' . (int)$doctor_id . '
                        AND is_busy != 1
                        AND date_format(dt_start, "%d") = date_format(date_add(now(), interval ' . $day_counter . ' day),  "%d")
                    ORDER BY dt_start ASC';
			$data = $this->db->query($sql);

			return (isset($data)) ? $this->initList($data) : array();
		}

		public static function setIsBusyAndVisitIdById($schedule_id, $is_busy, $visit_id)
		{
			$sql = 'UPDATE schedule
                    SET is_busy = ' . $is_busy . ',
                        visit_id = ' . $visit_id . '
                    WHERE id = ' . $schedule_id;

			Register::get('db')->query($sql);
		}

        /**
		 * @return ScheduleModel[]
		 */
		public function getListByDoctorIdAndDtStartAndIsBusy($doctor_id, $dt_start)
		{
			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE doctor_id = ' . (int)$doctor_id . '
                    AND is_busy = 0
                    AND dt_start LIKE "%' . $dt_start . '%"
                    ORDER BY dt_start ASC';
			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

        /**
		 * @return ScheduleModel
		 */
		public function getOneFirstByDoctorIdAndDate($doctor_id, $date)
		{
			$sql = 'SELECT *
                    FROM `' . $this->table_name . '`
                    WHERE doctor_id = ' . (int)$doctor_id . '
                        AND dt_start >= "' . date('Y-m-d 00:00:00', strtotime($date)) . '"
                        AND dt_start <= "' . date('Y-m-d 00:00:00', strtotime($date) + 86400) . '"
                    ORDER BY dt_start ASC
                    LIMIT 1';

			$data = $this->db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

        /**
		 * @return ScheduleModel
		 */
		/**
		 * @return ScheduleModel
		 */
		public function getOneFirstByDoctorIdAndDateAndClinicId($doctor_id, $date, $clinic_id)
		{
			$sql = 'SELECT *
                    FROM `' . $this->table_name . '`
                    WHERE doctor_id = ' . (int)$doctor_id . '
                        AND dt_start >= "' . date('Y-m-d 00:00:00', strtotime($date)) . '"
                        AND dt_start <= "' . date('Y-m-d 00:00:00', strtotime($date) + 86400) . '"
                        AND clinic_id = ' . (int)$clinic_id . '
                    ORDER BY dt_start ASC
                    LIMIT 1';

			$data = $this->db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

        /**
		 * @return ScheduleModel
		 */
		public function getOneLastByDoctorIdAndDate($doctor_id, $date)
		{
			$sql = 'SELECT *
                    FROM `' . $this->table_name . '`
                    WHERE doctor_id = ' . (int)$doctor_id . '
                        AND dt_start >= "' . date('Y-m-d 00:00:00', strtotime($date)) . '"
                        AND dt_start <= "' . date('Y-m-d 00:00:00', strtotime($date) + 86400) . '"
                    ORDER BY dt_start DESC
                    LIMIT 1';

			$data = $this->db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

        /**
		 * @return ScheduleModel
		 */
		/**
		 * @return ScheduleModel
		 */
		public function getOneLastByDoctorIdAndDateAndClinicId($doctor_id, $date, $clinic_id)
		{
			$sql = 'SELECT *
                    FROM `' . $this->table_name . '`
                    WHERE doctor_id = ' . (int)$doctor_id . '
                        AND dt_start >= "' . date('Y-m-d 00:00:00', strtotime($date)) . '"
                        AND dt_start <= "' . date('Y-m-d 00:00:00', strtotime($date) + 86400) . '"
                        AND clinic_id = ' . (int)$clinic_id . '
                    ORDER BY dt_start DESC
                    LIMIT 1';

			$data = $this->db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

		public function getAvailabilityByDoctorId($doctor_id)
		{
			$sql = 'SELECT COUNT(*) as result
                    FROM schedule
                    WHERE doctor_id = ' . (int)$doctor_id . '
                        AND dt_start >= NOW()
                        AND dt_start <= "' . date('Y-m-d 00:00:00', time() + 7 * 86400) . '"';

			$data = $this->db->query($sql);
			$total = $data[0]['result'];

			$sql = 'SELECT COUNT(*) as result
                    FROM schedule
                    WHERE doctor_id = ' . (int)$doctor_id . '
                        AND dt_start >= NOW()
                        AND dt_start <= "' . date('Y-m-d 00:00:00', time() + 7 * 86400) . '"
                        AND is_busy = 0';
			$data = $this->db->query($sql);
			$free = $data[0]['result'];

			return $free / $total;
		}

		/**
		 * Проверка, есть ли в ближайшую неделю у доктора визиты в указанное время
		 *
		 * @param $doctor_id
		 * @param $time_from
		 * @param $time_to
		 *
		 * @return ScheduleModel[]
		 */
		public function checkWeekVisitsByDoctorIdAndTimeRange($doctor_id, $time_from, $time_to)
		{
			$sql = 'SELECT COUNT(*) as result
                    FROM schedule
                    WHERE doctor_id = ' . (int)$doctor_id . '
                        AND dt_start >= NOW()
                        AND dt_start <= "' . date('Y-m-d 00:00:00', time() + 7 * 86400) . '"
                        AND HOUR(dt_start) >= ' . (int)$time_from . '
                        AND HOUR(dt_start) <' . (int)$time_to;

			$data = $this->db->query($sql);

			return (bool)$data[0]['result'];
		}

		public function checkWeekendVisitsByDoctorId($doctor_id)
		{
			$sql = 'SELECT COUNT(*) as result
                    FROM schedule
                    WHERE doctor_id = ' . (int)$doctor_id . '
                        AND (DAYOFWEEK(dt_start) = 1
                            OR DAYOFWEEK(dt_start) = 7)
                        AND dt_start >= NOW()
                        AND dt_start <= "' . date('Y-m-d 00:00:00', time() + 7 * 86400) . '"';

			$data = $this->db->query($sql);

			return (bool)$data[0]['result'];
		}

		public function setReservedStatusByScheduleIdAndAccountId($schedule_id, $account_id)
		{
			$sql = 'UPDATE schedule
                    SET is_busy = ' . self::RESERVED_STATUS . ',
                        reserved_account_id = ' . (int)$account_id . ',
                        reserve_dt = NOW()
                    WHERE id = ' . (int)$schedule_id;

			Register::get('db')->query($sql);
		}

		public function setNotBusyStatusByScheduleId($schedule_id)
		{
			$sql = 'UPDATE schedule
                    SET is_busy=' . self::NOT_BUSY_STATUS . '
                    WHERE id = ' . (int)$schedule_id;

			Register::get('db')->query($sql);
		}

		public function resetReservedStatusByMinutes($minutes)
		{
			$sql = 'UPDATE schedule
                    SET is_busy = ' . self::NOT_BUSY_STATUS . ',
                        reserve_dt = NULL,
                        reserved_account_id = NULL
                    WHERE is_busy = ' . self::RESERVED_STATUS . '
                        AND reserve_dt <= (NOW() - INTERVAL ' . (int)$minutes . ' MINUTE)';

			Register::get('db')->query($sql);
		}

		public function setUnreservedById($schedule_id)
		{
			$sql = 'UPDATE schedule
                    SET is_busy = 0,
                    visit_id = null
                    WHERE id = ' . (int)$schedule_id;

			$this->db->query($sql);
		}

		public function setReservedById($schedule_id)
		{
			$sql = 'UPDATE schedule
                    SET is_busy = ' . self::BUSY_STATUS . '
                    WHERE id = ' . (int)$schedule_id;

			Register::get('db')->query($sql);
		}

	    /**
		 * @return ScheduleModel[]
		 */
		public function getListByDoctorIdAndDate($doctor_id, $date)
		{
			$datetime = strtotime($date);

			$sql = 'SELECT *
		            FROM schedule
		            WHERE doctor_id = ' . (int)$doctor_id . '
		                AND dt_start >= "' . date('Y-m-d 00:00:00', $datetime) . '"
		                AND dt_start <= "' . date('Y-m-d 23:59:59', $datetime) . '"';


			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

		public function deleteByDoctorIdAndDate($doctor_id, $date)
		{
			$datetime = strtotime($date);

			$sql = 'DELETE
		            FROM schedule
		            WHERE doctor_id = ' . (int)$doctor_id . '
		                AND dt_start >= "' . date('Y-m-d 00:00:00', $datetime) . '"
		                AND dt_start <= "' . date('Y-m-d 23:59:59', $datetime) . '"';

			$data = $this->db->query($sql);
			return (count($data)) ? $this->initList($data) : array();
		}

        /**
		 * @return ScheduleModel[]
		 */
		public function getListByDoctorIdAndClinicId($doctor_id, $clinic_id)
		{
			$data = $this->orm_model->select()->where('doctor_id = ? AND clinic_id = ?', $doctor_id, $clinic_id)->fetchAll();

			return (count($data)) ? $this->initList($data) : array();
		}

        public function getListByDoctorIdAndClinicIdAndTodayDate($doctor_id, $clinic_id)
        {
            $sql = 'SELECT *
		            FROM schedule
		            WHERE doctor_id = ' . (int)$doctor_id . '
		                AND clinic_id = '. (int)$clinic_id .'
		                AND dt_start >= "' . date('Y-m-d 00:00:00', strtotime('today')) . '"';

            $data = $this->db->query($sql);
            return (count($data)) ? $this->initList($data) : array();
        }

		public function updateSpecialtyId()
		{
			$doctor_to_clinic_manager = new DoctorToClinicManager();
			$doctors_to_clinic = $doctor_to_clinic_manager->getSpecialtiesListToUpdate();
			$schedule_manager = new ScheduleManager();

			if($doctors_to_clinic)
			{
				foreach($doctors_to_clinic as $doctor_to_clinic)
				{
					if(count($schedules = $schedule_manager->getListByDoctorIdAndClinicId($doctor_to_clinic->doctor_id, $doctor_to_clinic->clinic_id)))
					{
						foreach($schedules as $schedule)
						{
							$schedule->specialty_id = $doctor_to_clinic->specialty_id;
							$schedule_manager->save($schedule);
						}
						;
					}
					;
				}
			}
		}

        /**
		 * @return ScheduleModel[]
		 */
		public function getListByClinicIdAndDoctorIdAndSpecialtyIdAndDate($clinic_id, $doctor_id, $specialty_id, $dt_start, $dt_end)
		{
			$datetime_start = strtotime($dt_start);
			$datetime_end = strtotime($dt_end);

			$sql = 'SELECT *
                    FROM schedule
                    WHERE clinic_id = ' . $clinic_id . '
                        AND doctor_id = ' . $doctor_id . '
                        AND specialty_id = ' . $specialty_id . '
                        AND dt_start >= "' . date('Y-m-d 00:00:00', $datetime_start) . '"
                        AND dt_end <= "' . date('Y-m-d 23:59:59', $datetime_end) . '"
                    ORDER BY dt_start ASC';

			$data = $this->db->query($sql);
			return (count($data)) ? $this->initList($data) : array();
		}

        /**
		 * @return ScheduleModel[]
		 */
		/**
		 * @return ScheduleModel[]
		 */
		public function getListByDoctorIdAndClinicIdAndSpecialtyIdAndDate($doctor_id, $clinic_id, $specialty_id, $datetime)
		{
			//$datetime = strtotime($date);
			$previous_day = $datetime - 86400;

			$sql = 'SELECT *
		            FROM schedule
		            WHERE clinic_id = ' . $clinic_id . '
                        AND doctor_id = ' . $doctor_id . '
                        AND specialty_id = ' . $specialty_id . '
		                AND dt_start >= "' . date('Y-m-d 23:59:59', $previous_day) . '"
		                AND dt_start <= "' . date('Y-m-d 23:59:59', $datetime) . '"';

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

        /**
		 * @return ScheduleModel[]
		 */
		/**
		 * @return ScheduleModel[]
		 */
		/**
		 * @return ScheduleModel[]
		 */
		public function getListByDoctorIdAndClinicIdAndSpecialtyIdAndDateRangeAndStartWithTodayDate($doctor_id, $clinic_id, $specialty_id, $date_from, $date_to)
		{
			$date_from = date('Y-m-d 00:00:00', strtotime($date_from) - 86400);
			$date_to = date('Y-m-d 00:00:00', strtotime($date_to) + 86400);



			$sql = 'SELECT *
                    FROM schedule
                    WHERE doctor_id = ' . (int)$doctor_id . '
                    AND clinic_id = ' . (int)$clinic_id . '
                    AND specialty_id = ' . (int)$specialty_id . '
                    AND dt_end >= "' . $date_from . '"
                    AND dt_start < "' . $date_to . '"
                    ORDER BY dt_start';

			//Test::dump($sql);

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

        public function getSpecialtyIdByDoctorIdAndClinicId($doctor_id, $clinic_id)
        {
            $sql = 'SELECT specialty_id
                    FROM schedule
                    WHERE doctor_id = '.(int)$doctor_id.'
                    AND clinic_id = '.(int)$clinic_id.'
                    GROUP BY specialty_id';

            $data = $this->db->query($sql);
            return (count($data)) ? $data : array();
	    }

        public function getListByClinicIdAndDoctorIdAndSpecialtyId($clinic_id, $doctor_id, $specialty_id)
        {
            $sql = 'SELECT *
                    FROM schedule
                    WHERE doctor_id = '.(int)$doctor_id.'
                        AND clinic_id = '.(int)$clinic_id.'
                        AND specialty_id = '.(int)$specialty_id;

            $data = $this->db->query($sql);
            return (count($data)) ? $data : array();
        }

        public function deleteByDoctorIdAndClinicId($doctor_id, $clinic_id)
        {
            $sql = 'DELETE
                    FROM schedule
                    WHERE doctor_id = ' .(int)$doctor_id .'
                    AND clinic_id = ' .(int)$clinic_id;

            $this->db->query($sql);
        }


		/**
		 * Метод проверяет налчиие слотов для записи для указанного доктора
		 *
		 * @param int$doctor_id
		 * @return bool
		 */
		public function isHasVisitSlotsByDoctorId($doctor_id)
		{
			$sql = 'SELECT id
					FROM `schedule`
					WHERE doctor_id = '.(int)$doctor_id.'
					LIMIT 1';
			$data = $this->db->query($sql);

			return (bool)$data;
		}
    }
