<?php
	class MoleculeToProductManager extends ModelManager
	{
		protected $table_name = "molecule_to_product";
		protected $model_name = "MoleculeToProductModel";


		/**
		 * @var int $molecule_id
		 * @return MoleculeToProductModel[]
		 */
		public function getListByMoleculeId($molecule_id)
		{
			$data = $this->orm_model->select()->where('molecule_id = ?', $molecule_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $product_id
		 * @return MoleculeToProductModel[]
		 */
		public function getListByProductId($product_id)
		{
			$data = $this->orm_model->select()->where('product_id = ?', $product_id)->fetchAll();
			return $this->initList($data);
		}

	}