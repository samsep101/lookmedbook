<?php
	class VisitValidator extends ModelValidator
	{
		public function validate(VisitModel $visit)
		{

            if ($visit->status_id == VisitModel::CONFIRMED) {
                if ($visit->clinic_id && $visit->doctor_id && $visit->specialty_id) {
                    /**
                     * @var DoctorSpecialtyToClinicManager $doctor_specialty_to_clinic_manager
                     */
                    $doctor_specialty_to_clinic_manager = ModelManagerFactory::getByName('doctor_specialty_to_clinic');
                    $doctor_specialty_to_clinic = $doctor_specialty_to_clinic_manager->getOneByDoctorIdAndClinicIdAndSpecialtyId($visit->doctor_id, $visit->clinic_id, $visit->specialty_id);

                    /*if (!$doctor_specialty_to_clinic) {
						$this->error_messages[] = 'Выбранная специальность не соответствует специальности врача в данной клинике';
						return false;
					}*/
				}

				// Если поставили статус "Подтвержден", то должно быть указано время
                if (($visit->status_id = VisitModel::CONFIRMED) && (!$visit->visit_start_time)) {
					$this->error_messages[] = 'Не указано время визита';
					return false;
				}
			}

			return true;
		}

	}