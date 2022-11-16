<?php
	class DoctorSchedulePageParamsHelper {

		public static function getRegistryDoctorSchedulePageParams()
		{
			$request = new Request();
			$clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId(false);
			$doctor_id = $request->getParam('id');
			$specialty_id = $request->getParam('specialty_id');

			$doctor_manager = new DoctorManager();
            /**
             * @var DoctorModel $doctor
             * @var ClinicModel $clinic
             * @var SpecialtyModel $specialty
             */
            $doctor = $doctor_manager->getOneById($doctor_id);

			if (!$clinic_id)
				$clinic_id = RegistryAccessHelper::determineClinicIdByDoctorId($doctor_id);

			$clinic_manager = new ClinicManager();
			$clinic = $clinic_manager->getOneById($clinic_id);

			if (!$clinic && $doctor->clinics)
				$clinic = $doctor->clinics[0];

			if (!$clinic)
				ErrorPageViewHelper::page404();

			if (!$doctor->isWorkInClinic($clinic->getId()))
				ErrorPageViewHelper::page404();

			$specialty_manager = new SpecialtyManager();
			$specialty = $specialty_manager->getOneById($specialty_id);

            if (!$specialty)
            {
                $specialties = $doctor->getSpecialtiesByClinicId($clinic_id);
				if ($specialties)
                	$specialty = $specialties[0];
            }

			$params = new RegistryDoctorSchedulePageParams();
			$params->clinic = $clinic;
			$params->doctor = $doctor;
			$params->specialty = $specialty;

			return $params;
		}
	}