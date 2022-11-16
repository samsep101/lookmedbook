<?php

	/**
	 * @property int $id
	 * @property int $doctor_id
	 * @property DoctorModel $doctor
	 * @property int $high_education_university_id
	 * @property UniversityModel $high_education_university
	 * @property int $high_education_specialty_id
	 * @property SpecialtyModel $high_education_specialty
	 * @property int $high_education_end_year
	 * @property int $secondary_education_university_id
	 * @property UniversityModel $secondary_education_university
	 * @property int $secondary_education_specialty_id
	 * @property SecondaryEducationSpecialtyModel $secondary_education_specialty
	 * @property int $secondary_education_end_year
	 * @property int $moderate_status_id
	 * @property ModerateStatusModel $moderate_status
	 * @property int $revision_number
	 * @property string $dt
	 *
	 */
    class ModerateDoctorUniversityModel extends ModerateModel {
		protected $fields = array(
			'high_education_end_year',
			'high_education_specialty_id',
			'high_education_university_id',
			'secondary_education_end_year',
			'secondary_education_specialty_id',
			'secondary_education_university_id',
		);
	}