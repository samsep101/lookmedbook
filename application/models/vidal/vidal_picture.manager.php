<?php
	class VidalPictureManager extends VidalModelManager
	{
		protected $table_name = "picture";
		protected $model_name = "VidalPictureModel";
		protected $id_field_name = 'PictureID';


		/**
		 * @param $product_id
		 *
		 * @return VidalPictureModel|null
		 */
		public function getOneByProductId($product_id)
		{
			$sql = 'SELECT p.*
					FROM picture p
					INNER JOIN product_picture p2p ON p.PictureID = p2p.PictureID
					WHERE p2p.ProductID = '.(int)$product_id;

			$data = $this->db->query($sql);

			return ($data) ? $this->initOne($data[0]) : null;
		}

	}