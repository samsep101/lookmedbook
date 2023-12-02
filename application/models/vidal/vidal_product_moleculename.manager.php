<?php
	class VidalProductMoleculenameManager extends VidalModelManager
	{
		protected $table_name = "product_moleculename";
		protected $model_name = "VidalProductMoleculenameModel";


		/**
		 * @param $product_id
		 *
		 * @return bool
		 */
		public function checkIsMonoComponentProductByProductId($product_id)
		{
			$sql = 'SELECT COUNT(*) as cnt
					FROM '.$this->table_name.'
					WHERE ProductID = '.(int)$product_id;

			$data = $this->db->query($sql);

			return $data[0]['cnt'] == 1;
		}

	}