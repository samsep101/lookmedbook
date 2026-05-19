<?php
	class DoctorScheduleManager extends ModelManager
	{
		protected $table_name = 'doctor_schedule';
		protected $model_name = 'DoctorScheduleModel';

	public function beforeSave(DynamicModel $model)
		{
			if(!$model->getId())
			{
				$last_schedule = $this->getLastInfiniteScheduleByDoctorIdAndClinicIdAndSpecialtyId($model->doctor_id, $model->clinic_id, $model->specialty_id);

				if($last_schedule && !$last_schedule->date_to)
				{
					$date = $model->date_from;
					$date = strtotime($date);
					$date = date('Y-m-d', $date - 86400);

					$last_schedule->date_to = $date;
					$last_schedule->save();
				}
			}
		}

		public function getLastInfiniteScheduleByDoctorIdAndClinicIdAndSpecialtyId($doctor_id, $clinic_id, $specialty_id)
		{
			$sql = 'SELECT *
				FROM doctor_schedule
				WHERE doctor_id = ' . (int)$doctor_id . '
					AND clinic_id = ' . (int)$clinic_id . '
					AND specialty_id = ' . (int)$specialty_id . '
					AND date_to IS NULL
				LIMIT 1';

			$data = $this->db->query($sql);

			return ($data) ? $this->initOne($data[0]) : null;
		}

        /**
         * @param int $doctor_id
         * @param int $clinic_id
         * @param int|null $specialty_id
         * @return DoctorScheduleModel|null
         * @throws \Exception
         */
		public function getOneCurrentByDoctorIdAndClinicIdAndSpecialtyId($doctor_id, $clinic_id, $specialty_id)
		{

			$memcache = Register::get('memcache');
			$cache_key = join('_', ['schedule',$doctor_id, $clinic_id, $specialty_id]);
			$cache = $memcache->get(
				$cache_key
			);

			if ($cache !== false) 
				return $cache;

			$sql = 'SELECT *
				FROM doctor_schedule
				WHERE doctor_id = ' . (int)$doctor_id . '
					AND clinic_id = ' . (int)$clinic_id;
			if($specialty_id)
			{
				$sql .= '
					AND specialty_id = ' . (int)$specialty_id;
			}
			$sql .= '
					AND (date_to IS NULL OR date_to > NOW())
					AND date_from <= (NOW() + INTERVAL 1 DAY)';

			$data = $this->db->query($sql);


			$result = ($data) ? $this->initOne($data[0]) : null;


			$memcache->set($cache_key, $result, 1080);
			return $result;
		}

	/**
		 * @return DoctorScheduleModel
		 */
		public function getOneCurrentByRegistryDoctorSchedulePageParams(RegistryDoctorSchedulePageParams $params)
		{
			return $this->getOneCurrentByDoctorIdAndClinicIdAndSpecialtyId($params->doctor->getId(), $params->clinic->getId(), $params->specialty->getId());
		}

		public function getFreeDaysRangeAndScheduleId($schedule_id)
		{
			$doctor_schedule = $this->getOneById($schedule_id);

			$sql = 'SELECT (MAX(date_to) + INTERVAL 1 DAY) as min_date
				FROM doctor_schedule
				WHERE date_to > NOW()
					AND ((date_to IS NULL ) OR (date_to < "' . $this->db->escape($doctor_schedule->date_from) . '"))
					AND doctor_id = ' . (int)$doctor_schedule->doctor_id . '
					AND clinic_id = ' . (int)$doctor_schedule->clinic_id . '
					AND specialty_id = ' . (int)$doctor_schedule->specialty_id . '
					AND id != ' . (int)$schedule_id;

			$data = $this->db->query($sql);


			$result = array();
			$result['min_date'] = ($data[0]['min_date']) ? date('d-m-Y', strtotime($data[0]['min_date'])) : date('d-m-Y', strtotime($doctor_schedule->date_from));

			$sql = 'SELECT (MIN(date_from) - INTERVAL 1 DAY) as max_date
				FROM doctor_schedule
				WHERE date_from > NOW()
					AND date_from > "' . $this->db->escape($doctor_schedule->date_from) . '"
					AND doctor_id = ' . (int)$doctor_schedule->doctor_id . '
					AND clinic_id = ' . (int)$doctor_schedule->clinic_id . '
					AND specialty_id = ' . (int)$doctor_schedule->specialty_id . '
					AND id != ' . (int)$schedule_id;

			$data = $this->db->query($sql);
			$result['max_date'] = ($data[0]['max_date']) ? date('d-m-Y', strtotime($data[0]['max_date'])) : null;

			return $result;
		}

		public function getFreeDaysRangeByDoctorIdAndClinicIdAndSpecialtyId($doctor_id, $clinic_id, $specialty_id)
		{
			$sql = 'SELECT (MAX(date_to) + INTERVAL 1 DAY) as min_date,
					(MAX(date_from) + INTERVAL 2 DAY) as max_date
				FROM doctor_schedule
				WHERE (date_to > NOW() OR date_to IS NULL)
					AND doctor_id = ' . $doctor_id . '
					AND clinic_id = ' . $clinic_id . '
					AND specialty_id = ' . $specialty_id;

			$data = $this->db->query($sql);


			$result = array();
			$result['min_date'] = ($data[0]['min_date']) ? date('d-m-Y', strtotime(max($data[0]['min_date'], $data[0]['max_date']))) : date('d-m-Y', time() + 86400);
			$result['max_date'] = null;

			return $result;
		}

		public function getPastListByDoctorIdAndClinicIdAndSpecialtyId($doctor_id, $clinic_id, $specialty_id)
		{
			$sql = 'SELECT *
				FROM doctor_schedule
				WHERE date_from < NOW()
					AND doctor_id = ' . (int)$doctor_id . '
					AND clinic_id = ' . (int)$clinic_id . '
					AND specialty_id = ' . (int)$specialty_id . '
				ORDER BY date_from';

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

		public function getFutureListByDoctorIdAndClinicIdAndSpecialtyId($doctor_id, $clinic_id, $specialty_id)
		{
			$sql = 'SELECT *
				FROM doctor_schedule
				WHERE (date_to > NOW() OR date_to IS NULL)
					AND doctor_id = ' . (int)$doctor_id . '
					AND clinic_id = ' . (int)$clinic_id . '
					AND specialty_id = ' . (int)$specialty_id . '
				ORDER BY date_from';

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

    /**
		 * @return DoctorScheduleModel[]
		 */
		public function getListByDoctorIdAndClinicIdSpecialtyIdAndDateInterval($doctor_id, $clinic_id, $specialty_id, $date_start, $date_end)
		{
			$sql = 'SELECT *
                FROM doctor_schedule
                WHERE clinic_id = ' . $clinic_id . '
                    AND doctor_id = ' . $doctor_id . '
                    AND specialty_id = ' . $specialty_id . '
                    AND date_from >= "' . date('Y-m-d 00:00:00', strtotime($date_start)) . '"
                    AND date_to <= "' . date('Y-m-d 23:59:59', strtotime($date_end)) . '"
                ORDER BY date_from ASC';
			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

    /**
		 * @return DoctorScheduleModel[]
		 */
		public function getListByDateInterval($date_start, $date_end)
		{
			$sql = 'SELECT *
                FROM doctor_schedule
                WHERE date_from >= "' . date('Y-m-d 00:00:00', strtotime($date_start)) . '"
                    AND (date_to <= "' . date('Y-m-d 23:59:59', strtotime($date_end)) . '" OR  date_to IS NULL)
                ORDER BY date_from ASC';
			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

		public function getCurrentList()
		{
			$sql = 'SELECT *
				FROM doctor_schedule
				WHERE (date_to IS NULL OR date_to > NOW())
					AND date_from <= NOW()';

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}
	}
