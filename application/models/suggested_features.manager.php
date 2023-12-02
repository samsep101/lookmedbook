<?php
	class SuggestedFeaturesManager extends ModelManager
	{
		protected $table_name = 'suggested_features';
		protected $model_name = 'SuggestedFeaturesModel';


        public function beforeSave(DynamicModel $model)
		{
			if($model->status_id == SuggestStatusModel::ACCEPT)
			{
				$feature_manager = new FeatureManager();
				$feature = $feature_manager->getOneByName($model->feature_name);

				if(!$feature)
				{
					$feature = $feature_manager->createModel();
					$feature->name = $model->feature_name;
					$feature->save();

					$feature_to_clinic_model = new FeatureToClinicModel();
					$feature_to_clinic_model->feature_id = $feature->getId();
					$feature_to_clinic_model->clinic_id = $model->clinic_id;
					$feature_to_clinic_model->save();

				}
			}
		}


        /**
		 * @return SuggestedFeaturesModel[]
		 */
		public function getListByStatusId($status_id)
		{
			$sql = 'SELECT 	*
                    FROM ' . $this->table_name . '
                    WHERE status_id = ' . (int)$status_id . '
                    ORDER BY feature_name';
			$data = $this->db->query($sql);

			$this->clearRegister();
			return $this->initList($data);
		}

        /**
		 * @return SuggestedFeaturesModel[]
		 */
		/**
		 * @return SuggestedFeaturesModel[]
		 */
		public function getListByStatusIdAndClinicId($status_id, $clinic_id)
		{

			$sql = 'SELECT 	*
                    FROM ' . $this->table_name . '
                    WHERE status_id = ' . (int)$status_id . '
                    AND clinic_id = ' . (int)$clinic_id . '
                    ORDER BY feature_name';
			$data = $this->db->query($sql);

			$this->clearRegister();
			return $this->initList($data);
		}

		public function deleteOneByName($feature_name)
		{
			$sql = 'DELETE
                    FROM ' . $this->table_name . '
                    WHERE feature_name = "' . $this->db->escape($feature_name) . '";';
			$data = $this->db->query($sql);
		}

		public function checkExistsByFeatureName($feature_name)
		{
			$sql = 'SELECT 	count(*) as result
                    FROM ' . $this->table_name . '
                    WHERE feature_name = "' . $this->db->escape($feature_name) . '";';
			$data = $this->db->query($sql);

			$this->clearRegister();
			return (isset($data['result']) && $data['result']) ? true : false;
		}
	}
