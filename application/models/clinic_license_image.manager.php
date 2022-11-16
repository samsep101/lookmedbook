<?php
	class ClinicLicenseImageManager extends ModelManager
	{
		protected $table_name = 'clinic_license_image';
		protected $model_name = 'ClinicLicenseImageModel';


        /**
		 * @return ClinicLicenseImageModel[]
		 */
		public function getListByClinicId($clinic_id){

			$sql = 'SELECT  *
                    FROM ' . $this->table_name . ' ms
                    WHERE clinic_id = ' . (int)$clinic_id;

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		public function deleteByClinicId($clinic_id)
		{
			$sql = 'DELETE
					FROM ' . $this->table_name . '
					WHERE clinic_id = ' . (int)$clinic_id;
			$data = $this->db->query($sql);
		}
	}
