<?php
	class VidalInfopageManager extends VidalModelManager
	{
		protected $table_name = "infopage";
		protected $model_name = "VidalInfopageModel";
		protected $id_field_name = 'InfoPageID';



		/**
		 * @var int $CountryCode
		 * @return VidalInfopageModel[]
		 */
		public function getListByCountryCode($CountryCode)
		{
			$data = $this->orm_model->select()->where('CountryCode = ?', $CountryCode)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @param $document_id
		 *
		 * @return VidalInfopageModel
		 */
		public function getOneByDocumentId($document_id)
		{
			$sql = 'SELECT i.*
					FROM infopage i
					INNER JOIN document_infopage d2i ON i.InfoPageID = d2i.InfopageID
					WHERE d2i.DocumentID='.(int)$document_id;

			$data = $this->db->query($sql);

			if($data)
			{
				return $this->initOne($data[0]);
			} else {
				return null;
			}
		}
	}