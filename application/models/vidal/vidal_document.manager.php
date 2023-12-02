<?php
	class VidalDocumentManager extends VidalModelManager
	{
		protected $table_name = "document";
		protected $model_name = "VidalDocumentModel";
		protected $id_field_name = 'DocumentID';

		/**
		 * @param $rus_name
		 * @return VidalDocumentModel[]
		 */
		public function getListByRusName($rus_name)
		{
			$data = $this->orm_model->select()->where('RusName = ?', $rus_name)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @param $rus_name
		 * @return VidalDocumentModel[]
		 */
		public function getListByRusNameClean($rus_name)
		{
			$data = $this->orm_model->select()->where('rus_name_clean = ?', $rus_name)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @param $rus_name
		 * @return VidalDocumentModel[]
		 */
		public function getListByRusNameCleanPart($rus_name)
		{
			$sql = 'SELECT *
					FROM '.$this->table_name.'
					WHERE rus_name_clean LIKE "'.$rus_name.'%"';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		/**
		 * @param $rus_name
		 * @param $dosage_form_list
		 *
		 * @return VidalDocumentModel[]
		 */
		public function getListByRusNameCleanAndDosageFormList($rus_name, array $dosage_form_list)
		{
			$str = '';

			foreach($dosage_form_list as $v)
			{
				$str .= '"'.$this->db->escape($v).'",';
			}
			$str = trim($str,',');

			$sql = 'SELECT *
					FROM '.$this->table_name.'
					WHERE rus_name_clean = "'.$this->db->escape($rus_name).'"
						AND dosage_form IN ('.$str.')';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		public function getOneByRusNameAndDosageForm($ru_name, $dosage_form)
		{
			$data = $this->orm_model->select()->where('RusName = ? AND dosage_form = ?', $ru_name, $dosage_form)->fetchOne();
			return $this->initOne($data);
		}

		/**
		 * @param $product_id
		 * @return VidalDocumentModel
		 */
		public function getOneByProductId($product_id)
		{
			$sql = 'SELECT d.*
					FROM document d
					INNER JOIN product_document d2p ON d.DocumentID = d2p.DocumentID
					WHERE d2p.ProductID = '.(int)$product_id.'
					ORDER BY FIELD(ArticleID, 2, 5, 4)';


			$articles = array(2,5,4);

			$data = $this->db->query($sql);

			$result = null;

			if($data){
				$result = $this->initOne($data[0]);
			} else {
				/**
				 * @var VidalProductMoleculenameManager $product_to_molecule_manager
				 */
				$product_to_molecule_manager = ModelManagerFactory::getByName('vidal_product_moleculename');

				if($product_to_molecule_manager->checkIsMonoComponentProductByProductId($product_id))
				{
					$sql = 'SELECT d.*
							FROM document d
							INNER JOIN product_moleculename p2m ON p2m.ProductID = '.(int)$product_id.'
							INNER JOIN moleculename mn ON mn.MoleculeNameID = p2m.MoleculeNameId
							INNER JOIN molecule m ON m.MoleculeID = mn.MoleculeID
							INNER JOIN molecule_document m2d ON m2d.MoleculeID = m.MoleculeID AND m2d.DocumentID = d.DocumentID
							WHERE p2m.ProductID = '.(int)$product_id.'
								AND d.ArticleID = 1';

					$data = $this->db->query($sql);

					if($data)
					{
						$result = $this->initOne($data[0]);
					}

				}
			}

			return $result;
		}
	}