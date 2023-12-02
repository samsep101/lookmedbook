<?php
  class DoctorToClinicManager extends ModelManager
  {
    protected $table_name = 'doctor_to_clinic';
    protected $model_name = 'DoctorToClinicModel';

    public function afterSave(DoctorToClinicModel $model)
    {
      /*
      if ($model->doctor_id) {
        $cache = Register::get('cache');
        $cache_id = 'doctor_card_'.$model->doctor_id;
        $cache->remove($cache_id, 'doctor_card_block');
        if ($model->specialty_id) {
          $cache_id.='_specialty_'.$model->specialty_id;
          $cache->remove($cache_id, 'doctor_card_block');
          foreach ($model->doctor->purposes_of_visit as $purpose) {
            $cache_id = 'doctor_card_'.$model->doctor_id.'_specialty_'.$model->specialty_id.'_purpose_'.$purpose->getId();
            $cache->remove($cache_id, 'doctor_card_block');
          }
        }
        if ($model->clinic_id) {
          if ($model->specialty_id) {
            foreach ($model->doctor->purposes_of_visit as $purpose) {
              $cache_id = 'doctor_card_'.$model->doctor_id.'_specialty_'.$model->specialty_id.'_clinic_'.$model->clinic_id.'_purpose_'.$purpose->getId();
              $cache->remove($cache_id, 'doctor_card_block');
            }
          }
          else {
            $cache_id.='_clinic_'.$model->clinic_id;
            $cache->remove($cache_id, 'doctor_card_block');
          }
        }
      }
      */
    }

    public function afterDelete(DynamicModel $model)
    {
      /**
       * @var DoctorToClinicModel $model
       * @var DoctorSpecialtyToClinicModel[] $doctor_specialties_to_clinic
       * @var PurposeOfVisitToDoctorModel[] $purposes_of_visit_to_doctor
       * @var DoctorSpecialtyToClinicManager $doctor_specialty_to_clinic_manager
       * @var PurposeOfVisitToDoctorManager $purpose_of_visit_to_doctor_manager
       */
      /*
      $doctor_specialty_to_clinic_manager = new DoctorSpecialtyToClinicManager();
      $doctor_specialty_to_clinic_manager->deleteByDoctorIdAndClinicId($model->doctor_id, $model->clinic_id);

      $purpose_of_visit_to_doctor_manager = new PurposeOfVisitToDoctorManager();
      $purpose_of_visit_to_doctor_manager->deleteByDoctorIdAndClinicId($model->doctor_id, $model->clinic_id);
*/
      $doctor_specialty_to_clinic_manager = ModelManagerFactory::getByName('doctor_specialty_to_clinic');
      $doctor_specialties_to_clinic = $doctor_specialty_to_clinic_manager->getListByClinicIdAndDoctorId($model->clinic_id, $model->doctor_id);
      if ($doctor_specialties_to_clinic) {
        foreach($doctor_specialties_to_clinic as $doctor_specialty_to_clinic) {
          $doctor_specialty_to_clinic->is_to_delete = 1;
          $doctor_specialty_to_clinic->delete_date = date('Y-m-d H:i:s');
          $doctor_specialty_to_clinic->disableValidation();

          $doctor_specialty_to_clinic->save();
        }
      }

      $purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('purpose_of_visit_to_doctor');
      $purposes_of_visit_to_doctor = $purpose_of_visit_to_doctor_manager->getListByDoctorIdAndClinicId($model->doctor_id, $model->clinic_id);
      if ($purposes_of_visit_to_doctor) {
        foreach($purposes_of_visit_to_doctor as $purpose_of_visit_to_doctor) {
          $purpose_of_visit_to_doctor->is_to_delete = 1;
          $purpose_of_visit_to_doctor->delete_date = date('Y-m-d H:i:s');

          $purpose_of_visit_to_doctor->save();
        }
      }
    }

    /**
		 * @return DoctorToClinicModel[]
		 */
    public function getListByDoctorId($doctor_id)
    {
      $data = $this->orm_model->select()->where('doctor_id = ?', $doctor_id)->fetchAll();
      return $this->initList($data);
    }

    /**
		 * @return DoctorToClinicModel[]
		 */
    public function getListByClinicId($clinic_id)
    {
      $data = $this->orm_model->select()->where('clinic_id = ?', $clinic_id)->fetchAll();
      return $this->initList($data);
    }

    public function getFirstVisitPriceByDoctorIdAndClinicId($doctor_id, $clinic_id)
    {
      $data = $this->orm_model->select()->where('doctor_id = ? AND clinic_id = ?', $doctor_id, $clinic_id)->fetchOne();
      return ($data) ? $data['first_visit_price'] : FALSE;
    }

    public function getFirstVisitPriceByDoctorIdAndClinicIdAndSpecialtyId($doctor_id, $clinic_id, $specialty_id)
    {
      $data = $this->orm_model->select()->where('doctor_id = ? AND clinic_id = ? AND specialty_id = ?', $doctor_id, $clinic_id, $specialty_id)->fetchOne();
      return ($data) ? $data['first_visit_price'] : FALSE;
    }

    public function getSecondVisitPriceByDoctorIdAndClinicId($doctor_id, $clinic_id)
    {
      $data = $this->orm_model->select()->where('doctor_id = ? AND clinic_id = ?', $doctor_id, $clinic_id)->fetchOne();
      return ($data) ? $data['second_visit_price'] : FALSE;
    }

    public function getSecondVisitPriceByDoctorIdAndClinicIdAndSpecialtyId($doctor_id, $clinic_id, $specialty_id)
    {
      $data = $this->orm_model->select()->where('doctor_id = ? AND clinic_id = ? AND specialty_id = ?', $doctor_id, $clinic_id, $specialty_id)->fetchOne();
      return ($data) ? $data['second_visit_price'] : FALSE;
    }

    public function getSpecialtyIdByDoctorIdAndClinicId($doctor_id, $clinic_id)
    {
      $data = $this->orm_model->select()->where('doctor_id = ? AND clinic_id = ?', $doctor_id, $clinic_id)->fetchOne();
      return ($data) ? $data['specialty_id'] : FALSE;
    }

    /**
		 * @return DoctorToClinicModel
		 */
    public function getOneByClinicIdAndDoctorId($clinic_id, $doctor_id)
    {
      $data = $this->orm_model->select()->where('clinic_id = ? AND doctor_id = ?', (int)$clinic_id, (int)$doctor_id)->fetchOne();
      return (count($data)) ? $this->initOne($data) : null;
    }

    /**
		 * @return DoctorToClinicModel
		 */
		/**
		 * @return DoctorToClinicModel
		 */
    public function getOneByClinicIdAndSpecialtyId($clinic_id, $specialty_id)
    {
      $data = $this->orm_model->select()->where('clinic_id = ? AND specialty_id = ?', (int)$clinic_id, (int)$specialty_id)->fetchOne();
      return (count($data)) ? $this->initOne($data) : null;
    }

    /**
		 * @return DoctorToClinicModel
		 */
    public function getOneByClinicId($clinic_id)
    {
      $data = $this->orm_model->select()->where('clinic_id = ?', (int)$clinic_id)->fetchOne();
      return (count($data)) ? $this->initOne($data) : null;
    }

    /**
		 * @return DoctorToClinicModel
		 */
    public function getOneByDoctorId($doctor_id)
    {
      $data = $this->orm_model->select()->where('doctor_id = ?', (int)$doctor_id)->fetchOne();
      return (count($data)) ? $this->initOne($data) : null;
    }

    /**
		 * @return DoctorToClinicModel[]
		 */
		/**
		 * @return DoctorToClinicModel[]
		 */
    public function getListByClinicIdAndDoctorId($clinic_id, $doctor_id)
    {
      $data = $this->orm_model->select()->where('clinic_id = ? AND doctor_id = ?', (int)$clinic_id, (int)$doctor_id)->fetchAll();
      return (count($data)) ? $this->initList($data) : array();
    }

    public function getSpecialtiesListToUpdate()
    {
      $sql = 'SELECT DISTINCT d2c.*
          FROM doctor_to_clinic d2c
          INNER JOIN schedule s ON d2c.doctor_id = s.doctor_id AND d2c.clinic_id = s.clinic_id
          WHERE d2c.specialty_id IS NOT NULL';

      $data = $this->db->query($sql);
      return $this->initList($data);
    }

    public function getClinicListToUpdate()
    {
      $sql = 'SELECT DISTINCT d2c.*
          FROM doctor_to_clinic d2c
          INNER JOIN purpose_of_visit_to_doctor pv2d ON pv2d.doctor_id = d2c.doctor_id AND pv2d.specialty_id = d2c.specialty_id
          WHERE d2c.clinic_id IS NOT NULL';

      $data = $this->db->query($sql);
      return $this->initList($data);
    }

    public function deleteByDoctorId($doctor_id)
    {
      $sql = 'DELETE
          FROM doctor_to_clinic
          WHERE doctor_id = '.(int)$doctor_id;

      $this->db->query($sql);
    }

    public function deleteByDoctorIdAndClinicId($doctor_id, $clinic_id)
    {
      $sql = 'DELETE
          FROM doctor_to_clinic
          WHERE doctor_id = '.(int)$doctor_id.'
            AND clinic_id = '.(int)$clinic_id;

      $this->db->query($sql);
    }
  }
