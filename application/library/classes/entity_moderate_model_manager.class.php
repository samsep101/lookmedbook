<?php
    abstract class EntityModerateModelManager extends ModerateModelManager
    {
        public function createNewRevision($entity_id)
        {
            $entity = $this->getModeratedEntityById($entity_id);

            $model = ModelFactory::getByTableName($this->table_name);

            if ($entity && $this->fields)
                foreach($this->fields as $field_name)
                {
                    $model->{$field_name} = $entity->{$field_name};
                }

            $model->{$this->moderated_entity_name.'_id'} = $entity_id;
            $model->moderate_status_id = ModerateStatusModel::EDIT;
            $model->revision_number = $entity ? $this->getLastRevisionNumberByEntityId($entity_id) + 1 : 0;
            $model->save();

            return $model;
        }

        public function getLastRevisionNumberByEntityId($entity_id)
        {
            $sql = 'SELECT revision_number
                    FROM '.$this->table_name.'
                    WHERE '.$this->moderated_entity_name.'_id = '.(int)$entity_id.'
                    ORDER BY revision_number DESC
                    LIMIT 1';
            $data = $this->db->query($sql);

            if ($data && $data[0]['revision_number']){
                return $data[0]['revision_number'];
            } else {
                return 0;
            }
        }

        public function publishRevision($entity_id, $model = null)
        {
            $moderated_entity = $this->getModeratedEntityById($entity_id);

	        if (!$model)
				$model = $this->getCurrentRevision($entity_id);

            foreach($this->fields as $field_name)
            {
                $moderated_entity->{$field_name} = $model->{$field_name};
            }

            $this->beforePublish($moderated_entity);

            if (!$moderated_entity->save())
            {
                return FALSE;
            }

            $model->moderate_status_id = ModerateStatusModel::PUBLISHED;


            if ($model->save())
			{
				$this->afterPublish();
				return true;
			} else {
				return false;
			}
        }

        public function beforePublish($model)
        {

        }

		public function afterPublish()
		{

		}

        public function getCurrentRevision($entry_id)
        {
            $revision = $this->orm_model->select()->where($this->moderated_entity_name.'_id = '.(int)$entry_id.' AND moderate_status_id != '.ModerateStatusModel::PUBLISHED)->fetchOne();
            if (!$revision)
                return $this->createNewRevision($entry_id);
            else
                return $this->initOne($revision);
        }


        public function createNewEntity(ModerateModel $model)
        {
            $entity_manager = ModelManagerFactory::getByName($this->moderated_entity_name);

            $new_model = $entity_manager->createModel();

            $new_model->is_confirmed = 0;

            if ($this->fields)
            {
                foreach($this->fields as $field_name) {
                    $new_model->{$field_name} = $model->{$field_name};
                }
            }

            if ($new_model->save())
            {
                if ($this->moderated_entity_name == 'doctor') {

                    if (Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY)){
                        $clinic_id = ClinicUserHelper::getClinicIdByUserId(Acl::userId());
                    }

                    if (
						Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)
						|| Acl::isAuthed(RoleModel::ACCOUNT_MANAGER)
						|| Acl::isAuthed(RoleModel::FREELANCE_MANAGER)
					){
                        $clinic_id = $_POST['clinic_id'];
                    }

                    $doctor_to_clinic_manager = new DoctorToClinicManager();
                    $doctor_to_clinic = $doctor_to_clinic_manager->getOneByClinicIdAndDoctorId($clinic_id, $new_model->getId());

                    if (!$doctor_to_clinic){
                        $new_doctor_to_clinic_model = new DoctorToClinicModel();
                        $new_doctor_to_clinic_model->doctor_id = $new_model->getId();
                        $new_doctor_to_clinic_model->clinic_id = $clinic_id;
                        $new_doctor_to_clinic_model->save();
                    }
                }

                return $new_model->getId();
            } else {
                return false;
            }
        }
    }