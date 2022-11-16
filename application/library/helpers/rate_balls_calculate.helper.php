<?php
class RateBallsCalculateHelper {

    public static function calculateDoctorBalls(DoctorModel $doctor)
    {
        $balls = 0;
        $is_purpose = false;

        if ($doctor->card_image && file_exists(ABS_ROOT .$doctor->card_image->path)) {
            $balls +=RatingBallsCounterModel::PHOTO;
        }

        if ($doctor->about || $doctor->about_internal) {
            $balls+=RatingBallsCounterModel::ABOUT;
        }

		/**
		 * @var PurposeOfVisitToDoctorManager $purpose_of_visit_to_doctor_manager
		 */
		$purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('purpose_of_visit_to_doctor');
		$doctor_purposes = $purpose_of_visit_to_doctor_manager->getListByDoctorId($doctor->getId());
        if ($doctor_purposes) {
            foreach ($doctor_purposes as $purpose) {
                if ($purpose->first_visit_price && $purpose->second_visit_price) {
                    $balls+=RatingBallsCounterModel::PURPOSE_PRICES;
                    $is_purpose = true;
                    break;
                }
            }
        }

        if (!$is_purpose)
        {
			/**
			 * @var DoctorToClinicManager $doctor_to_clinic_manager
			 */
			$doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');
			$doctor_to_clinics = $doctor_to_clinic_manager->getListByDoctorId($doctor->getId());
            if ($doctor_to_clinics) {
                foreach ($doctor_to_clinics as $record) {
                    if ($record->first_visit_price && $record->second_visit_price) {
                        $balls+=RatingBallsCounterModel::PURPOSE_PRICES;
                        break;
                    }
                }
            }
        }

        $clinic = $doctor->clinic;
        if ($clinic
            && (($clinic->start_time_monday && $clinic->end_time_monday)
                || ($clinic->start_time_tuesday && $clinic->end_time_tuesday)
                || ($clinic->start_time_wednesday && $clinic->end_time_wednesday)
                || ($clinic->start_time_thursday && $clinic->end_time_thursday)
                || ($clinic->start_time_friday && $clinic->end_time_friday)
                || ($clinic->start_time_saturday && $clinic->end_time_saturday)
                || ($clinic->start_time_sunday && $clinic->end_time_sunday))
        ) {
            $balls+=RatingBallsCounterModel::SCHEDULE;
        }

        $doctor_manager = new DoctorManager();
        $doctor_manager->setBallsById($doctor->getId(), $balls);
    }

    public static function calculateClinicBalls(ClinicModel $clinic)
    {
        $balls = 0;
        $purposed = false;

        if ($clinic->card_image && file_exists(ABS_ROOT . $clinic->card_image->path)) {
            $balls+=RatingBallsCounterModel::PHOTO;
        }

        if ($clinic->about) {
            $balls+=RatingBallsCounterModel::ABOUT;
        }

		/**
		 * @var PurposeOfVisitToDoctorManager $purpose_of_visit_to_doctor_manager
		 */
		$purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('purpose_of_visit_to_doctor');
		if ($clinic->doctors) {
            foreach ($clinic->doctors as $doctor) {
                $doctor_purposes = $purpose_of_visit_to_doctor_manager->getListByDoctorId($doctor->getId());
                if ($doctor_purposes) {
                    foreach ($doctor_purposes as $purpose) {
                        if ($purpose->first_visit_price && $purpose->second_visit_price) {
                            $balls+=RatingBallsCounterModel::PURPOSE_PRICES;
                            $purposed = true;
                            break;
                        }
                    }
                }
                if ($purposed)
                    break;
                else {
					/**
					 * @var DoctorToClinicManager $doctor_to_clinic_manager
					 */
					$doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');
					$doctor_to_clinics = $doctor_to_clinic_manager->getListByClinicIdAndDoctorId($clinic->getId(), $doctor->getId());
                    if ($doctor_to_clinics) {
                        foreach ($doctor_to_clinics as $record) {
                            if ($record->first_visit_price && $record->second_visit_price) {
                                $balls+=RatingBallsCounterModel::PURPOSE_PRICES;
                                $purposed = true;
                                break;
                            }
                        }
                    }
                }
                if ($purposed) break;
            }
        }

        if (($clinic->start_time_monday && $clinic->end_time_monday) || ($clinic->start_time_tuesday && $clinic->end_time_tuesday) || ($clinic->start_time_wednesday && $clinic->end_time_wednesday) || ($clinic->start_time_thursday && $clinic->end_time_thursday) || ($clinic->start_time_friday && $clinic->end_time_friday) || ($clinic->start_time_saturday && $clinic->end_time_saturday) || ($clinic->start_time_sunday && $clinic->end_time_sunday)) {
            $balls+=RatingBallsCounterModel::SCHEDULE;
        }

        $clinic_manager = new ClinicManager();
        $clinic_manager->setBallsById($clinic->getId(), $balls);
    }
}
