<?php
	class DoctorArticleManager extends ModelManager
	{
		protected $table_name = 'doctor_article';
		protected $model_name = 'DoctorArticleModel';


        /**
		 * @return DoctorArticleModel[]
		 */
		public function getListByDoctorId($doctor_id){

			$data = $this->orm_model->select()->where('doctor_id = ?', $doctor_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return DoctorArticleModel[]
		 */
		public function getListByDoctorArticleTypeId($doctor_article_type_id){
			$data = $this->orm_model->select()->where('doctor_article_type_id = ?', $doctor_article_type_id)->fetchAll();
			return $this->initList($data);
		}

	}
