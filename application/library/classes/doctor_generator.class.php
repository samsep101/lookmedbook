<?php
    class DoctorGenerator
    {
        protected $test_account_id = 1;
        protected $name_generator;
        protected $clinics;
        protected $doctor_types;

        protected $article_types;

        protected $specialties;

        protected $qualifying_categories;

        protected $universities;

        public function __construct()
        {
            $this->name_generator = new NameGenerator();
            $this->doctor_types = ModelManagerFactory::getByName('doctor_type')->getList();
            $this->clinics = ModelManagerFactory::getByName('clinic')->getList();
            $this->article_types = ModelManagerFactory::getByName('doctor_article_type')->getList();
            $this->specialties = ModelManagerFactory::getByName('specialty')->getList();
            $this->qualifying_categories = ModelManagerFactory::getByName('qualifying_category')->getList();
            $this->universities = ModelManagerFactory::getByName('university')->getList();
        }

        public function generate($number = 10)
        {
            for ($i = 0; $i < $number; $i++) {
                $doctor = new DoctorModel();

                $sex_id = rand(1, 2);

                if ($sex_id == 1) {
                    $this->name_generator->generateMaleName();
                } else {
                    $this->name_generator->generateFemaleName();
                }

                $doctor->first_name = $this->name_generator->getFirstName();
                $doctor->second_name = $this->name_generator->getSecondName();
                $doctor->last_name = $this->name_generator->getLastName();
                $doctor->full_lower_name = mb_strtolower($this->name_generator->getFullName(), 'utf-8');
                $doctor->sex_id = $sex_id;

                $doctor->doctor_type_id = $this->getRandomDoctorTypeId();
                $doctor->is_active = 1;
                $doctor->is_leave_the_house = rand(1, 100) > 90 ? 1 : 0;
                $doctor->rate = rand(10, 50) / 10;

                ModelManagerFactory::getByName('doctor')->save($doctor);

                $this->attachSpecialties($doctor);
                $this->attachClinics($doctor);
                $this->attachAcademicDegrees($doctor);
                $this->attachAcademicTitles($doctor);

                $this->attachPurposes($doctor);
                $this->attachSchedule($doctor);
                $this->attachUniversities($doctor);
                $this->attachCourses($doctor);
            }
        }

        private function attachClinics(DoctorModel $doctor)
        {
            // От 1 до 3 клиник
            $clinic_num = rand(1, 3);
            for ($t = 1; $t <= $clinic_num; $t++) {
                $clinic_id = $this->getRandomClinicId();

                $doctor_to_clinic_model = new DoctorToClinicModel();
                $doctor_to_clinic_model->doctor_id = $doctor->getId();
                $doctor_to_clinic_model->clinic_id = $clinic_id;

                $doctor_to_clinic_model->specialty_id = $doctor->specialties[rand(0, count($doctor->specialties) - 1)]->getId();

                if (rand(1, 10) == 1) {
                    $doctor_to_clinic_model->first_visit_price = 0;
                    $doctor_to_clinic_model->second_visit_price = 0;
                } else {
                    $doctor_to_clinic_model->first_visit_price = rand(4, 40) * 100;
                    $doctor_to_clinic_model->second_visit_price = $doctor_to_clinic_model->first_visit_price - rand(1, 4) * 100;
                }

                $doctor_to_clinic_model->specialty_id = $this->getRandomSpecialtyId();

                ModelManagerFactory::getByName('doctor_to_clinic')->save($doctor_to_clinic_model);
            }
        }

        private function attachSchedule(DoctorModel $doctor)
        {
            $today_time = strtotime(date('Y-m-d 00:00:00'));

            for ($day = 1; $day <= 30; $day++) {
                $start_h = rand(0, 23);
                $clinic = $doctor->clinics[rand(0, count($doctor->clinics) - 1)];
                $hours_count = rand(1, 3);

                for ($h = $start_h; $h <= $start_h + $hours_count; $h++) {
                    if ($h > 23)
                        break;
                    for ($m = 0; $m < 60; $m += 15) {
                        $schedule = new ScheduleModel();
                        $schedule->clinic_id = $clinic->getId();
                        $schedule->doctor_id = $doctor->getId();
                        $schedule->dt_start = date('Y-m-d H:i:s', $today_time + ($day - 1) * 24 * 60 * 60 + $h * 60 * 60 + $m * 60);
                        $schedule->dt_end = date('Y-m-d H:i:s', $today_time + ($day - 1) * 24 * 60 * 60 + $h * 60 * 60 + $m * 60 + 14 * 60 + 59);
                        $schedule->is_busy = (rand(1, 3) == 3) ? 1 : 0;

                        ModelManagerFactory::getByName('schedule')->save($schedule);

                        unset($schedule);
                    }
                }
                $start_h += $hours_count;
            }
        }

        private function attachAcademicDegrees(DoctorModel $doctor)
        {
            // От 0 до 5 научных званий
            $academic_degree_num = rand(0, 5);
            for ($t = 1; $t <= $academic_degree_num; $t++) {
                $academic_degree_model = new DoctorAcademicDegreeModel();
                $academic_degree_model->name = 'Научная степень №' . $t;
                $academic_degree_model->full_name = 'Полное название научной степени №' . $t;
                $academic_degree_model->description = 'Описание научной степени №' . $t;
                $academic_degree_model->doctor_id = $doctor->getId();

                ModelManagerFactory::getByName('doctor_academic_degree')->save($academic_degree_model);
            }
        }

        private function attachAcademicTitles(DoctorModel $doctor)
        {
            // От 0 до 5 научных званий
            $academic_title_num = rand(0, 5);
            for ($t = 1; $t <= $academic_title_num; $t++) {
                $academic_title_model = new DoctorAcademicTitleModel();
                $academic_title_model->name = 'Научное звание №' . $t;
                $academic_title_model->full_name = 'Полное название научного звания №' . $t;
                $academic_title_model->description = 'Описание научного звания №' . $t;
                $academic_title_model->doctor_id = $doctor->getId();

                ModelManagerFactory::getByName('doctor_academic_title')->save($academic_title_model);
            }
        }

        private function attachSpecialties(DoctorModel $doctor)
        {
            $specialties_num = rand(1, 2);

            for ($t = 1; $t <= $specialties_num; $t++) {
                $specialty_to_doctor_model = new SpecialtyToDoctorModel();
                $specialty_to_doctor_model->specialty_id = $this->getRandomSpecialtyId();
                $specialty_to_doctor_model->doctor_id = $doctor->getId();
                $specialty_to_doctor_model->qualifying_category_id = $this->getRandomQualifyingCategoryId();
                ModelManagerFactory::getByName('specialty_to_doctor')->save($specialty_to_doctor_model);
            }
        }

        private function attachPurposes(DoctorModel $doctor)
        {
            $purpose_manager = new PurposeOfVisitManager();

            $specialty_manager = new SpecialtyManager();
            $specialties = $specialty_manager->getListByDoctorId($doctor->getId());

            foreach ($specialties as $specialty) {
                $purposes = $purpose_manager->getListBySpecialtyId($specialty->getId());

                if (rand(1, 2) == 1) {
                    unset($purposes[0]);
                }

                if ($purposes)
                    foreach ($purposes as $purpose) {
                        $relation_model = new PurposeOfVisitToDoctorModel();
                        $relation_model->doctor_id = $doctor->getId();
                        $relation_model->purpose_of_visit_id = $purpose->getId();
                        $relation_model->specialty_id = $specialty->getId();
                        ModelManagerFactory::getByName('purpose_of_visit_to_doctor')->save($relation_model);
                    }

            }
        }

        private function attachUniversities(DoctorModel $doctor)
        {
            $university_count = rand(1, 2);

            $universities = ModelManagerFactory::getByName('university')->getRandomListWithLimit($university_count);

            foreach ($universities as $university) {
                $doctor_education = new DoctorEducationModel();
                $doctor_education->level = 'ВУЗ';
                $doctor_education->end_year = rand(1990, 2010);
                $doctor_education->university_id = $university->getId();
                $doctor_education->specialty_id = $this->getRandomSpecialtyId();
                $doctor_education->doctor_id = $doctor->getId();

                ModelManagerFactory::getByName('doctor_education')->save($doctor_education);
            }
        }

        private function attachCourses(DoctorModel $doctor)
        {
            $courses_count = rand(0, 4);

            for ($i = 1; $i <= $courses_count; $i++) {
                $doctor_course = new DoctorCourseModel();
                $doctor_course->doctor_id = $doctor->getId();
                $doctor_course->name = 'Курсы №' . $i;
                $doctor_course->description = 'Описание курсов №' . $i;

                ModelManagerFactory::getByName('doctor_course')->save($doctor_course);
            }
        }

        private function getRandomQualifyingCategoryId()
        {
            return $this->qualifying_categories[rand(0, count($this->qualifying_categories) - 1)]->getId();
        }

        private function getRandomClinicId()
        {
            return $this->clinics[rand(0, count($this->clinics) - 1)]->getId();
        }

        private function getRandomDoctorTypeId()
        {
            return $this->doctor_types[rand(0, count($this->doctor_types) - 1)]->getId();
        }

        private function getRandomSpecialtyId()
        {
            return $this->specialties[rand(0, count($this->specialties) - 1)]->getId();
        }
    }