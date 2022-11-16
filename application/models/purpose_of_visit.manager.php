<?php
	class PurposeOfVisitManager extends ModelManager
	{
		protected $table_name = 'purpose_of_visit';
		protected $model_name = 'PurposeOfVisitModel';


        /**
		 * @return PurposeOfVisitModel[]
		 */
		public function getListBySpecialtyId($specialty_id)
		{
			$sql = 'SELECT p.*,
            			p2s.is_main
                    FROM specialty s
                    INNER JOIN purpose_of_visit_to_specialty p2s ON p2s.specialty_id = s.id
                    INNER JOIN purpose_of_visit p ON p.id = p2s.purpose_of_visit_id
                    WHERE s.id = ' . (int)$specialty_id . '
                        OR s.id = s.parent_id
                    ORDER BY is_main DESC, sort ASC, name ASC';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

        /**
		 * @return PurposeOfVisitModel[]
		 */
		/**
		 * @return PurposeOfVisitModel[]
		 */
		public function getListBySpecialtyIdAndClinicId($specialty_id, $clinic_id)
		{
			$sql = 'SELECT DISTINCT p.*
                    FROM purpose_of_visit p
                    INNER JOIN purpose_of_visit_to_doctor p2d ON p2d.purpose_of_visit_id = p.id
                    INNER JOIN doctor_specialty_to_clinic d2c ON d2c.doctor_id = p2d.doctor_id
                    WHERE d2c.clinic_id = ' . (int)$clinic_id . '
                        AND d2c.specialty_id = ' . (int)$specialty_id . ';';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

        /**
		 * @return PurposeOfVisitModel[]
		 */
		public function getListByDoctorId($doctor_id)
		{
			$sql = 'SELECT *
                    FROM purpose_of_visit
                    WHERE id IN (
                        SELECT purpose_of_visit_id
                        FROM purpose_of_visit_to_doctor
                        WHERE doctor_id = ' . (int)$doctor_id . '
                    )
                    ORDER BY name';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

        /**
		 * @return PurposeOfVisitModel[]
		 */
		/**
		 * @return PurposeOfVisitModel[]
		 */
		public function getListByDoctorIdAndSpecialtyId($doctor_id, $specialty_id)
		{
			$sql = 'SELECT *, p2v.id as id
                    FROM purpose_of_visit p2v
                    INNER JOIN purpose_of_visit_to_doctor p2d ON p2d.purpose_of_visit_id = p2v.id
                    WHERE p2d.doctor_id = ' . (int)$doctor_id . '
                        AND p2d.specialty_id = ' . (int)$specialty_id . ';';

			$data = Register::get('db')->query($sql);

			return $this->initList($data);
		}

        /**
		 * @return PurposeOfVisitModel
		 */
		public function getOneByName($name)
		{
			$data = $this->orm_model->select()->where('name = ?', $name)->fetchOne();

			return ($data) ? $this->initOne($data) : null;
		}

        /**
		 * @return PurposeOfVisitModel[]
		 */
		public function getListByAccountIdAndPastVisit($account_id)
		{
			$sql = 'SELECT * FROM (
                                    SELECT DISTINCT pv.*
                                    FROM clinic c
                                    INNER JOIN schedule s ON s.clinic_id = c.id
                                    INNER JOIN visit v ON s.visit_id = v.id
                                    INNER JOIN purpose_of_visit pv ON v.purpose_of_visit_id = pv.id
                                    WHERE v.account_id = ' . (int)$account_id . '
                                    UNION
                                    SELECT DISTINCT pv.*
                                    FROM clinic c
                                    INNER JOIN my_clinic mc ON mc.clinic_id = c.id
                                    INNER JOIN doctor_to_clinic d2c ON d2c.clinic_id = mc.clinic_id
                                    INNER JOIN purpose_of_visit_to_doctor pv2d ON pv2d.doctor_id = d2c.doctor_id
                                    INNER JOIN purpose_of_visit pv ON pv2d.purpose_of_visit_id = pv.id
                                    WHERE mc.account_id = ' . (int)$account_id . '
                            ) a ORDER BY name';
			$data = Register::get('db')->query($sql);
			return (count($data)) ? $this->initList($data) : array();
		}

		public function getMainListBySpecialtyId($specialty_id)
		{
			$sql = 'SELECT pv.*
					FROM purpose_of_visit pv
					WHERE (
							SELECT COUNT(*)
							FROM purpose_of_visit_to_specialty pv2s
							WHERE pv2s.specialty_id = ' . (int)$specialty_id . '
								AND pv2s.purpose_of_visit_id = pv.id
								AND pv2s.is_main = 1
						) > 0';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

        /**
		 * @return PurposeOfVisitModel[]
		 */
		/**
		 * @return PurposeOfVisitModel[]
		 */
		public function getListByDoctorIdAndClinicIdAndSpecialtyId($doctor_id, $clinic_id, $specialty_id)
		{
			$sql = 'SELECT *, p2v.id as id
                    FROM purpose_of_visit p2v
                    INNER JOIN purpose_of_visit_to_doctor p2d ON p2d.purpose_of_visit_id = p2v.id
                    WHERE p2d.doctor_id = ' . (int)$doctor_id . '
                        AND p2d.specialty_id = ' . (int)$specialty_id . '
                        AND p2d.clinic_id = ' . (int)$clinic_id;

			$data = Register::get('db')->query($sql);

			return $this->initList($data);
		}

        public function getMinOneByClinicIdAndSpecialtyIdAndPurposeOfVisitId($clinic_id, $specialty_id, $purpose_of_visit_id)
        {
            $sql = 'SELECT * FROM (
                        SELECT p2d.id, p2d.visit_price
                            FROM purpose_of_visit p2v
                            INNER JOIN purpose_of_visit_to_doctor p2d ON p2d.purpose_of_visit_id = p2v.id
                            WHERE p2d.specialty_id = '.(int)$specialty_id.'
                                AND p2d.clinic_id = '.(int)$clinic_id.'
                                AND p2v.id = '.(int)$purpose_of_visit_id.'
                            HAVING MIN(p2d.visit_price)
                        UNION
                        SELECT p2c.id, p2c.visit_price
                            FROM purpose_of_visit p2v
                            INNER JOIN purpose_of_visit_to_clinic p2c ON p2c.purpose_of_visit_id = p2v.id
                            WHERE p2c.specialty_id = '.(int)$specialty_id.'
                                AND p2c.clinic_id = '.(int)$clinic_id.'
                                AND p2v.id = '.(int)$purpose_of_visit_id.'
                            HAVING MIN(p2c.visit_price)
                    ) a
                    ORDER BY a.visit_price';
            $data = Register::get('db')->query($sql);
            return (isset($data[0])) ? $this->initOne($data[0]) : null;
        }
	}
