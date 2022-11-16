<?php
    class ModerateDataPublishHelper {

        public static function publishClinic($clinic_id, $publish = false)
        {
            $revision_condition = array(
                'clinic_id' => $clinic_id,
            );

            $moderate_clinic_information_manager = new ModerateClinicInformationManager();

            /**
             * @var ModerateClinicInformationModel $information
             * @var ClinicModel $clinic
             */
            $information = $moderate_clinic_information_manager->getCurrentRevision($clinic_id);
            $moderate_clinic_information_manager->publishRevision($clinic_id);

            $moderate_clinic_card_image_manager = new ModerateClinicCardImageManager();
            $moderate_clinic_card_image_manager->publishRevision($clinic_id);

            $moderate_clinic_email_manager = new ModerateClinicEmailManager();
            $moderate_clinic_email_manager->publishRevision($revision_condition);

            $moderate_clinic_license_manager = new ModerateClinicLicenseManager();
            $moderate_clinic_license_manager->publishRevision($clinic_id);

            $moderate_clinic_license_image_manager = new ModerateClinicLicenseImageManager();
            $moderate_clinic_license_image_manager->publishRevision($revision_condition);

            $moderate_clinic_phone_manager = new ModerateClinicPhoneManager();
            $moderate_clinic_phone_manager->publishRevision($revision_condition);

            $moderate_clinic_requisites_manager = new ModerateClinicRequisitesManager();
            $moderate_clinic_requisites_manager->publishRevision($clinic_id);

            $moderate_image_to_clinic_manager = new ModerateImageToClinicManager();
            $moderate_image_to_clinic_manager->publishRevision($revision_condition);

            $moderate_feature_to_clinic_manager = new ModerateFeatureToClinicManager();
            $moderate_feature_to_clinic_manager->publishRevision($revision_condition);

            $moderate_specialization_to_clinic_manager = new ModerateSpecializationToClinicManager();
            $moderate_specialization_to_clinic_manager->publishRevision($revision_condition);

            $moderate_specialty_to_clinic_manager = new ModerateSpecialtyToClinicManager();
            $moderate_specialty_to_clinic_manager->publishRevision($revision_condition);

            $moderate_purpose_of_visit_to_clinic_manager = new ModeratePurposeOfVisitToClinicManager();
            $moderate_purpose_of_visit_to_clinic_manager->publishRevision($revision_condition);

            $moderate_clinic_description_manager = new ModerateClinicDescriptionManager();
            $moderate_clinic_description_manager->publishRevision($clinic_id);

            $moderate_metro_station_to_clinic_manager = new ModerateMetroStationToClinicManager();
            $moderate_metro_station_to_clinic_manager->publishRevision($revision_condition);

            $clinic_manager = new ClinicManager();
            $clinic = $clinic_manager->getOneById($clinic_id);
			if (Acl::userRole() == RoleModel::FREELANCE_MANAGER)
			{
				$clinic->is_active = 1;
			} else {
				$clinic->is_active = $information->is_active;
			}

            $clinic->save();
            return true;
        }

        public static function publishDoctor($doctor_id, $clinic_id)
        {
            $doctor_manager = new DoctorManager();
            /**
             * @var DoctorModel $doctor
             */
            $doctor = $doctor_manager->getOneById($doctor_id);

            $revision_condition = array(
                'doctor_id' => $doctor_id,
            );

            $clinic_revision_conditions = array();

            if ($clinic_id)
            {
                $clinic_revision_conditions = array(
                    array(
                        'doctor_id' => $doctor_id,
                        'clinic_id' => $clinic_id
                    )
                );
            } else {
                foreach($doctor->clinics as $clinic)
                {
                    $clinic_revision_conditions[] = array(
                        'doctor_id' => $doctor->getId(),
                        'clinic_id' => $clinic->getId()
                    );
                }
            }

            $moderate_doctor_information_manager = new ModerateDoctorInformationManager();
            $moderate_doctor_information_manager->publishRevision($doctor_id);

            $moderate_doctor_card_image_manager = new ModerateDoctorCardImageManager();
            $moderate_doctor_card_image_manager->publishRevision($doctor_id);

            $moderate_doctor_certificate_manager = new ModerateDoctorCertificateManager();
            $moderate_doctor_certificate_manager->publishRevision($revision_condition);

            $moderate_doctor_education_manager = new ModerateDoctorEducationManager();
            $moderate_doctor_education_manager->publishRevision($revision_condition);



            $moderate_doctor_university_manager = new ModerateDoctorUniversityManager();
            $moderate_doctor_university_manager->publishRevision($doctor_id);

            $moderate_image_to_doctor_manager = new ModerateImageToDoctorManager();
            $moderate_image_to_doctor_manager->publishRevision($revision_condition);


            foreach($clinic_revision_conditions as $clinic_revision_condition){
                $moderate_specialty_to_doctor_manager = new ModerateSpecialtyToDoctorManager();
                $moderate_specialty_to_doctor_manager->publishRevision($clinic_revision_condition);

                $moderate_purpose_of_visit_to_doctor_manager = new ModeratePurposeOfVisitToDoctorManager();
                $moderate_purpose_of_visit_to_doctor_manager->publishRevision($clinic_revision_condition);
            }
        }
    }