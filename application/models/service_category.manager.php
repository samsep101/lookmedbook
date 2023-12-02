<?php
	class ServiceCategoryManager extends ModelManager
	{
		protected $table_name = 'service_category';
		protected $model_name = 'ServiceCategoryModel';

        public function getListByDocDocIds($docDocId, $type)
        {
            if (!$docDocId) {
                return [];
            }

            if (!is_array($docDocId)) {
                $docDocId = [$docDocId];
            }

            foreach ($docDocId as &$item) {
                $item = (int)$item;
            }
            unset($item);

            $sql = 'SELECT *
                    FROM service_category
                    WHERE `docdoc_id` IN (' . implode(',', $docDocId) . ') '
                    . ' AND `service_type`= ?';

            $data = $this->db->query($sql, [$type]);
            return (isset($data)) ? $this->initList($data) : [];
        }

        /**
         * @return ControllerModel
         */
        public function getOneByDocDocId($docDocId, $type)
        {
            $sql = 'SELECT *
                    FROM service_category
                    WHERE `docdoc_id` = ? AND `service_type`= ?';

            $data = $this->db->query($sql, [$docDocId, $type]);
            return (isset($data[0])) ? $this->initOne($data[0]) : null;
        }

        /**
		 * @return ControllerModel
		 */
		public function getOneByAlias($alias)
		{
			$sql = 'SELECT *
                    FROM service_category
                    WHERE `alias` = ?';

			$query = $this->db->prepare($sql);
            $query->bind_param('s', $alias);
			$data = $query->execute();
			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

        /***
         * @param $clinicId
         * @return ServiceCategoryModel[]
         */
		public function getListByClinicId($clinicId)
        {
            $sql = 'SELECT sc.* FROM service_category sc '
                . 'INNER JOIN service_to_clinic stc '
                . 'ON sc.id=stc.service_id '
                . 'WHERE stc.clinic_id = ?';
            $data = $this->db->query($sql, [$clinicId]);

            return $data ? $this->initList($data) : [];
        }

        /**
         * @param string $query
         * @param int    $byPage
         *
         * @return ServiceCategoryModel[]
         */
        public function getActiveListByTitleOrAltName($query, $byPage)
        {
            $sql = 'SELECT sc.* FROM service_category sc '
                .'WHERE sc.is_active = 1 AND sc.name LIKE ?'
                ."LIMIT $byPage";
            $data = $this->db->query($sql, ["%$query%"]);

            return $data ? $this->initList($data) : [];
        }

        public function deleteByDocDocId($docdocId)
        {
            $this->orm_model->delete(['docdoc_id' => $docdocId]);
        }

        /**
         * @param ServiceCategoryModel $model
         */
		protected function beforeSave(DynamicModel $model)
        {
            parent::beforeSave($model);
            if (!$model->getId()) {
                $model->created_at = time();
            }
            $model->updated_at = time();
        }
    }
