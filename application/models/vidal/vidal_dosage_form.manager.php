<?php
	class VidalDosageFormManager extends VidalModelManager
	{
		protected $table_name = 'dosage_form';
		protected $model_name = 'VidalDosageFormModel';

		/**
		 * @param $name_part
		 *
		 * @return VidalDosageFormModel[]
		 */
		public function getListByNamePart($name_part)
		{
			$sql = 'SELECT *
					FROM '.$this->table_name.'
					WHERE name LIKE "'.$this->db->escape($name_part).'%"';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}
	}