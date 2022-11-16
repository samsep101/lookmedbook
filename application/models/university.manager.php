<?php
	class UniversityManager extends ModelManager
	{
		protected $table_name = 'university';
		protected $model_name = 'UniversityModel';

        /**
		 * @return UniversityModel[]
		 */
		public function getListByDoctorId($doctor_id)
		{
			$db = Register::get('db');

            $sql = 'SELECT u.*
                    FROM university u
                    INNER JOIN doctor_education de ON de.university_id = u.id
                    WHERE doctor_id = ' . (int)$doctor_id;

			$data = $db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

        /**
		 * @return UniversityModel[]
		 */
		public function getListByType($type)
		{
			$db = Register::get('db');

			$sql = 'SELECT *
                    FROM university
                    WHERE type = ' . (int)$type . '
                    ORDER BY name ASC';

			$data = $db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}
	}
