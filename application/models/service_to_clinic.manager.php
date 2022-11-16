<?php
	class ServiceToClinicManager extends ModelManager
	{
		protected $table_name = 'service_to_clinic';
		protected $model_name = 'ServiceToClinicModel';
        protected $id_field_name = 'id';

        /**
         * @param      $clinicId
         * @param null $type
         * @return ServiceToClinicModel[]
         */
        public function getListByClinicId($clinicId, $type = null)
        {
            $sql = 'SELECT stc.*, sc.docdoc_id
                    FROM service_category sc
                    INNER JOIN service_to_clinic stc ON sc.id=stc.service_id
                    WHERE stc.clinic_id = ?';

            $params = [$clinicId];
            if ($type) {
                $sql .= ' AND sc.service_type=?';
                $params[] = $type;
            }

            $data = $this->db->query($sql, $params);
            $result = [];
            if (!empty($data)) {
                foreach ($data as $entry) {
                    /** @var ServiceToClinicModel $res */
                    $res = $this->initOne($entry);
                    $result[$res->service_id] = $res;
                }
            }

            return $result;
        }

        public function getGroupedPriceByClinicId($serviceId, $clinicId)
        {
            if (!is_array($clinicId)) {
                $clinicId = [(int)$clinicId];
            } else {
                foreach ($clinicId as $idx=>$v) {
                    $clinicId[$idx] = (int)$v;
                }
            }

            $sql = 'SELECT clinic_id, price  FROM service_to_clinic WHERE service_id=? AND clinic_id IN ('. implode(',', $clinicId) .')';
            $data = $this->db->query($sql, [$serviceId]);
            $result = [];
            foreach ($data as $value) {
                $result[$value['clinic_id']] = $value['price'];
            }
            return $result;
        }

        public function deleteByServiceCategoryId($serviceCategoryId)
        {
            $this->orm_model->delete('service_id = ' . (int)$serviceCategoryId);
        }
	}