<?php
	class ClinicPricelistManager extends ModelManager
	{
		protected $table_name = 'clinic_pricelist';
		protected $model_name = 'ClinicPricelistModel';


        /**
		 * @return ClinicPricelistModel[]
		 */
		public function getListByClinicId($clinic_id){

			$sql = 'SELECT  *
                    FROM ' . $this->table_name . '
                    WHERE clinic_id = ' . (int)$clinic_id;

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		public function deleteByClinicId($clinic_id)
		{
			$sql = 'DELETE
					FROM ' . $this->table_name . '
					WHERE clinic_id = ' . (int)$clinic_id;
			$this->db->query($sql);
		}
	}
