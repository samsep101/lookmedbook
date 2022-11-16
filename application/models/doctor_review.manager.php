<?php
	class DoctorReviewManager extends ModelManager
	{
		protected $table_name = 'visit_rating';
		protected $model_name = 'DoctorReviewModel';

        protected $selected_fields = 'id,
                                      dt,
                                      doctor_review_text as text,
                                      doctor_review_text,
                                      is_confirmed,
                                      doctor_id,
                                      account_id,
                                      author_name,
                                      visit_id,
                                      is_doctor_advice,
                                      value_for_money,
                                      diagnosis_is_clear,
                                      service_at_the_reception,
                                      waiting_time,
                                      relationship
                                        ';

        protected $selected_field_joins = ' vr.id,
                                            vr.dt,
                                            vr.is_confirmed,
                                            vr.doctor_review_text as text,
                                            vr.doctor_review_text,
                                            vr.doctor_id,
                                            vr.account_id,
                                            vr.author_name,
                                            vr.visit_id,
                                            vr.is_doctor_advice,
                                            vr.value_for_money,
                                            vr.diagnosis_is_clear,
                                            vr.service_at_the_reception,
                                            vr.waiting_time,
                                            vr.relationship
                                            ';

        public function beforeSave(DynamicModel $model)
        {
            /**
             * @var DoctorReviewModel $model
             */
            if (!$model->dt)
                $model->dt = date('Y-m-d H:i:s');
            if (!$model->is_doctor_advice)
                $model->is_doctor_advice = 1;
        }

		public function afterSave(DynamicModel $model)
		{
			/**
			 * @var DoctorReviewModel $model
			 */
			SiteTaskManager::calculateDoctorRate($model->doctor);
		}

		public function getConfirmedListByDoctorId($doctor_id)
		{
			$db = Register::get('db');

			$sql = 'SELECT  '.$this->selected_field_joins.'
                    FROM '.$this->table_name.' vr
                    LEFT OUTER JOIN visit v ON v.id = vr.visit_id
                    WHERE vr.doctor_id = ' . (int)$doctor_id . '
                        AND vr.is_confirmed = 1
                        AND vr.doctor_review_text IS NOT NULL';

			$data = $db->query($sql);

			return ($data) ? $this->initList($data) : array();
		}

		public function getConfirmedListByDoctorIdWithPagging($doctor_id, $page, $by_page)
		{
			$sql = 'SELECT  '.$this->selected_field_joins.'
                    FROM visit_rating vr
                    LEFT OUTER JOIN visit v ON v.id = vr.visit_id
                    WHERE vr.doctor_id = ' . (int)$doctor_id . '
                        AND vr.is_confirmed = 1
                        AND vr.doctor_review_text IS NOT NULL
                    GROUP BY vr.doctor_review_text
                    LIMIT ' . $page . ', ' . $by_page . ';';

			$data = $this->db->query($sql);
            $data = array_reverse($data);

            $data = array_reverse($data);

			return ($data) ? $this->initList($data) : array();
		}

		public function getCountConfirmedListByDoctorId($doctor_id)
		{
			$sql = 'SELECT COUNT(*) as result
                    FROM visit_rating vr
                    LEFT OUTER JOIN visit v ON vr.visit_id = v.id
                    WHERE vr.doctor_id = ' . (int)$doctor_id . '
                        AND vr.is_confirmed = 1
                        AND vr.doctor_review_text IS NOT NULL';

			$data = $this->db->query($sql);

			return $data[0]['result'];
		}

        /**
		 * @return DoctorReviewModel
		 */
		public function getOneLastConfirmedByDoctorId($doctor_id)
		{
			$db = Register::get('db');

			$sql = 'SELECT  '.$this->selected_field_joins.'
                    FROM visit_rating vr
                    LEFT OUTER JOIN visit v ON vr.visit_id = v.id
                    WHERE vr.doctor_id = ' . (int)$doctor_id . '
                        AND v.status_id in (' . VisitModel::FEDDBACK . ',' . VisitModel::VISITED . ')
                        AND vr.is_confirmed = 1
                        AND vr.dt is not null
                        AND vr.doctor_review_text IS NOT NULL
                    ORDER BY vr.dt DESC
                    LIMIT 1';

			$data = $db->query($sql);

			return ($data) ? $this->initOne($data[0]) : null;
		}


        /**
         * @param $account_id
         * @param $offset
         * @param $limit
         * @return DoctorReviewModel[]
         */
        public function getListByAccountIdWithPadding($account_id, $offset, $limit)
		{
			$sql = 'SELECT  '.$this->selected_field_joins.'
                    FROM visit_rating vr
                    LEFT OUTER JOIN visit v ON vr.visit_id = v.id
                    WHERE v.account_id = ' . (int)$account_id . '
                        AND v.status_id in (' . VisitModel::FEDDBACK . ',' . VisitModel::VISITED . ')
                        AND v.visit_start_time < NOW()
                        AND vr.doctor_review_text IS NOT NULL
                    ORDER BY v.visit_start_time DESC
                    LIMIT ' . $offset . ', ' . $limit . ';';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		public function getConfirmedOneByVisitIdAndAccountId($visit_id, $account_id)
		{
			$sql = 'SELECT  '.$this->selected_field_joins.'
                    FROM ' . $this->table_name . ' vr
                    WHERE vr.visit_id = ' . (int)$visit_id . '
                    AND vr.account_id = ' . (int)$account_id . '
                    AND vr.is_confirmed = 1';

			$data = $this->db->query($sql);
			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

        /**
		 * @return DoctorReviewModel
		 */
		public function getOneByVisitIdAndAccountId($visit_id, $account_id)
		{
			$sql = 'SELECT '.$this->selected_field_joins.'
                    FROM ' . $this->table_name . ' vr
                    WHERE vr.visit_id = ' . (int)$visit_id . '
                        AND vr.account_id = ' . (int)$account_id;

			$data = $this->db->query($sql);
			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

        /**
         * @param $account_id
         * @param $doctor_id
         *
         * @return DoctorReviewModel
         */
        public function getOneByAccountIdAndDoctorId($account_id, $doctor_id)
        {
            $data = $this->orm_model->select()->where('account_id = ? AND doctor_id = ?', $account_id, $doctor_id)->fetchOne();
            return $this->initOne($data);
        }

        /**
		 * @return DoctorReviewModel[]
		 */
		public function getListByAccountId($account_id)
		{
			$sql = 'SELECT '.$this->selected_field_joins.'
                    FROM ' . $this->table_name . '  vr
                    WHERE vr.account_id = ' . (int)$account_id;

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

        public function getListByDoctorId($doctor_id)
        {
            $sql = 'SELECT'.$this->selected_field_joins.'
                    FROM '.$this->table_name.' vr
                    WHERE vr.doctor_id = '.(int)$doctor_id;

            $data = $this->db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }
	}
