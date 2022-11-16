<?php
    class DoctorRegistryController extends BaseController
    {
        public function __construct()
        {
            $this->layout = 'registry';
        }

		/**
		 * Метод для страницы управления списком врачей
		 */
		public function index()
        {
            $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();
            $this->view->clinic_id = $clinic_id;
            $this->view->entry_id = $clinic_id;

            if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER))
                $specialties = ModelManagerFactory::getByName('specialty')->getListByClinicId($clinic_id);
            else
                $specialties = ModelManagerFactory::getByName('specialty')->getRootListToSearchDoctors();
            $this->view->specialties = $specialties;

            $search_params = new SearchParams();

            if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)) {
                $search_params->addJoin('doctor_to_clinic');
                $search_params->addParam('doctor_to_clinic.clinic_id', $clinic_id, 'clinic');
            }

            $search_params->setOffsetAndLimit(0, 11);
            $search_params->addSortParam('last_name', 'ASC');
            $doctors = ModelManagerFactory::getByName('doctor')->getListBySearchParams($search_params);
            $this->view->doctors = $doctors;
            $this->view->page = 1;
        }

		/**
		 * Метод для управления общей инфомрацией о враче
		 */
		public function information()
        {
            $doctor_id = $this->request('id', 0);

            RegistryAccessHelper::checkAccessToDoctor($doctor_id, true);
	        $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId(false);

            $moderate_doctor_information_manager = new ModerateDoctorInformationManager();
            $doctor_description = $moderate_doctor_information_manager->getCurrentRevision($doctor_id);

			$view_processor = new FormViewProcessor('moderate_doctor_information', $doctor_description);
			$this->view->view_processor = $view_processor;

			$this->view->clinic_id = $clinic_id;
			$this->view->entry_id = $doctor_id;
			$this->view->model_name = 'moderate_doctor_information';
			$this->view->model = $doctor_description;
			$this->view->menu_type = 'doctor';
			$this->view->menu_active = 'information';

			$doctor_manager = new DoctorManager();
            $this->view->doctor = $doctor_manager->getOneById($doctor_id);

            $specialty_manager = new SpecialtyManager();
            $doctor_specialties = $specialty_manager->getListByDoctorIdAndClinicId($doctor_id, $clinic_id);

            $only_adult = false;
            $only_children = false;

            // Проверка, есть ли у врача толкьо взрослые или только детские специализации
            foreach($doctor_specialties as $specialty)
            {
                if($specialty->for_whom == '2')
                {
                    $only_adult = true;
                }

                if($specialty->for_whom == '3')
                {
                    $only_children = true;
                }
            }

            $this->view->only_adult = $only_adult;
            $this->view->only_children = $only_children;
        }

		/**
		 * Метод для страницы добавления врача
		 */
		public function add()
        {
            $doctor_id = $this->request('doctor_id', 0);

            if (RegistryAccessHelper::checkAuth()) {

                $clinic_id = $this->request('clinic_id');

                $clinic_manager = new ClinicManager();
                if (!$clinic = $clinic_manager->getOneById($clinic_id))
                    RedirectManager::redirect(MANAGE_FOLDER);

                if (!$clinic_id)
                    RedirectManager::redirect(MANAGE_FOLDER);

                $this->view->clinic_id = $clinic_id;
                $this->view->add_flag = TRUE;
            }

            $moderate_doctor_information = new ModerateDoctorInformationModel();

            $view_processor = new FormViewProcessor('moderate_doctor_information', $moderate_doctor_information);
            $this->view->view_processor = $view_processor;
            $this->view->entry_id = $doctor_id;
            $this->view->model_name = 'moderate_doctor_information';
            $this->view->model = $moderate_doctor_information;

	        $this->view->menu_active = 'add_doctor';
            $this->view->add_flag = TRUE;
        }

		/**
		 * Метод для страницы управления списком специальностей врачей
		 */
		public function specialties()
        {
			// получение $doctor_id и $clinic_id
            $doctor_id = $this->request('id', 0);

	        RegistryAccessHelper::checkAccessToDoctor($doctor_id, true);
            $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId(false);

			$doctor_manager = new DoctorManager();
			$doctor = $doctor_manager->getOneById($doctor_id);
			$this->view->doctor = $doctor;

			// если клиника не указана, то выбираем первую из списка
			if (!$clinic_id)
			{
				$clinic_id = RegistryAccessHelper::determineClinicIdByDoctorId($doctor_id);
			}

			if ((!$clinic_id) || !$doctor->isWorkInClinic($clinic_id))
				ErrorPageViewHelper::page404();

			$clinic_manager = new ClinicManager();
			$this->view->clinic = $clinic_manager->getOneById($clinic_id);

			// Получаем специальности, которые есть в данной клинике
            $specialty_to_clinic_manager = new ModerateSpecialtyToClinicManager();

            // Фильтрация специализаций в зависимости от того, детский или взрослый врач
            if($doctor->is_adult == '1' && $doctor->is_children == '1')
            {
                $this->view->specialties = $specialty_to_clinic_manager->getSpecialtyListByClinicId($clinic_id);
            }
            else
            {
                if($doctor->is_adult == '1')
                {
                    $this->view->specialties = $specialty_to_clinic_manager->getSpecialtyListByClinicIdAndDoctorType($clinic_id, 3);
                }
                else
                {
                    $this->view->specialties = $specialty_to_clinic_manager->getSpecialtyListByClinicIdAndDoctorType($clinic_id, 2);
                }
            }

			// Получаем специальности в данной клинике у данного врача
            $specialty_to_doctor_manager = new ModerateSpecialtyToDoctorManager();
			$revision_condition = array(
				'doctor_id' => $doctor_id,
				'clinic_id' => $clinic_id
			);
            $revision = $specialty_to_doctor_manager->getCurrentRevision($revision_condition);

            $this->view->model = $revision->revision_info;

            $specialty_id = null;
			$this->view->selected_specialties = $revision->elements;

            $this->view->clinic_id = $clinic_id;
            $this->view->entry_id = $doctor_id;
            $this->view->menu_type = 'doctor';
            $this->view->menu_active = 'specialties';

			$this->view->model_name = 'moderate_specialty_to_doctor';
        }

		/**
		 * Метод для управления списком изображений врача
		 */
		public function photos()
        {
            $doctor_id = $this->request('id', 0);

            RegistryAccessHelper::checkAccessToDoctor($doctor_id, true);
			$clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

	        $doctor_manager = new DoctorManager();
            $doctor = $doctor_manager->getOneById($doctor_id);

            $this->view->doctor = $doctor;
            $this->view->clinic_id = $clinic_id;
            $this->view->entry_id = $doctor_id;
            $this->view->menu_type = 'doctor';
            $this->view->menu_active = 'photos';

            if ($clinic_id) {
                $clinic = ModelManagerFactory::getByName('clinic')->getOneById($clinic_id);
                if ($clinic)
                    $this->view->clinic = $clinic;
            }

            $moderate_doctor_card_image_manager = new ModerateDoctorCardImageManager();

            $revision_condition = array(
                'doctor_id' => $doctor_id
            );

            $card_image = $moderate_doctor_card_image_manager->getCurrentRevision($doctor_id);

            $doctor->card_image_id = $card_image->card_image_id;

            $this->view->entry_id = $doctor_id;
            $this->view->model_name = 'moderate_doctor_card_image';
            $this->view->model = $card_image;

            $moderate_image_to_doctor_manager = new ModerateImageToDoctorManager();
            $image_revision = $moderate_image_to_doctor_manager->getCurrentRevision($revision_condition);
            $this->view->doctor_images = $image_revision->elements;
        }

		/**
		 * Метод для страницы управления образованием врача
		 */
		public function education()
        {
            $doctor_id = $this->request('id', 0);

            RegistryAccessHelper::checkAccessToDoctor($doctor_id, true);
            $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

            $moderate_doctor_university_manager = new ModerateDoctorUniversityManager();
            $doctor_university = $moderate_doctor_university_manager->getCurrentRevision($doctor_id);

            $view_processor = new FormViewProcessor('moderate_doctor_university', $doctor_university);
            $this->view->view_processor = $view_processor;

            $university_manager = new UniversityManager();
            $universities = $university_manager->getListByType(1);
            $secondary_universities = $university_manager->getListByType(2);

            $specialty_manager = new SpecialtyManager();
            $specialties = $specialty_manager->getSortedList('name');

            $this->view->universities = $universities;
            $this->view->secondary_universities = $secondary_universities;

            $this->view->specialties = $specialties;

            $this->view->clinic_id = $clinic_id;
            $this->view->entry_id = $doctor_id;
            $this->view->model_name = 'moderate_doctor_university';
            $this->view->model = $doctor_university;

            $this->view->menu_type = 'doctor';
            $this->view->menu_active = 'education';

            $moderate_doctor_education_manager = new ModerateDoctorEducationManager();
            $doctor_education_revision = $moderate_doctor_education_manager->getCurrentRevision($doctor_id);
            $this->view->doctor_educations = $doctor_education_revision->elements;

            $moderate_doctor_certificate_manager = new ModerateDoctorCertificateManager();
            $doctor_certificate_revision = $moderate_doctor_certificate_manager->getCurrentRevision($doctor_id);
            $this->view->doctor_certificates = $doctor_certificate_revision->elements;

			$doctor_manager = new DoctorManager();
			$this->view->doctor = $doctor_manager->getOneById($doctor_id);
        }

		/**
		 * Метод для страницы управления расписанием врача
		 */
		public function schedule()
        {
            $doctor_id = $this->request('id', 0);

            RegistryAccessHelper::checkAccessToDoctor($doctor_id);
            $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

			if (!$clinic_id)
				$clinic_id = RegistryAccessHelper::determineClinicIdByDoctorId($doctor_id);

            $doctor_manager = new DoctorManager();
            $doctor = $doctor_manager->getOneById($doctor_id);

            $specialties = ModelManagerFactory::getByName('specialty')->getList();
            $this->view->specialties = $specialties;

            $this->view->doctor = $doctor;
            $this->view->clinic_id = $clinic_id;
            $this->view->menu_type = 'doctor';
            $this->view->menu_active = 'schedule';

            $this->view->entry_id = $doctor_id;
        }

		/**
		 * Метод для страницы упралвения прошедшими графиками врача
		 */
		public function schedule_past()
		{
			if (!RegistryAccessHelper::checkAuth())
				JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

			$params = DoctorSchedulePageParamsHelper::getRegistryDoctorSchedulePageParams();

			$this->view->menu_type = 'doctor';
			$this->view->menu_active = 'schedule';
			$this->view->schedule_top_menu_active = 'past';

			$this->view->date_from = date('d-m-Y');

			$this->doctor = $params->doctor;
			$this->clinic = $params->clinic;
			$this->specialty = $params->specialty;

			if ($this->specialty){
				$doctor_schedule_manager = new DoctorScheduleManager();
				$this->view->schedules_list = $doctor_schedule_manager->getPastListByDoctorIdAndClinicIdAndSpecialtyId($this->doctor->getId(), $this->clinic->getId(), $this->specialty->getId());
			}

			$this->setSchedulesPageParams();
		}

		/**
		 * Метод для страницы управления текущим графиком работы
		 */
		public function schedule_view()
        {
			if (!RegistryAccessHelper::checkAuth())
				JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

			$schedule_id = $this->request('schedule_id');

			$doctor_schedule_manager = new DoctorScheduleManager();
			if ($schedule_id)
			{
				$schedule = $doctor_schedule_manager->getOneById($schedule_id);
				$clinic = $schedule->clinic;
				$doctor = $schedule->doctor;
				$specialty = $schedule->specialty;
			} else {
				$params = DoctorSchedulePageParamsHelper::getRegistryDoctorSchedulePageParams();

				$clinic = $params->clinic;
				$doctor = $params->doctor;
				$specialty = $params->specialty;

				$schedule_manager = new DoctorScheduleManager();
				$schedule = NULL;
				if ($params->specialty)
					$schedule = $schedule_manager->getOneCurrentByRegistryDoctorSchedulePageParams($params);

				if (!$schedule)
				{
					RedirectManager::redirect(RegistryScheduleLinkViewHelper::getScheduleCreateLink($params->doctor, $params->clinic, $params->specialty));
				}
			}

			$this->view->menu_type = 'doctor';
			$this->view->menu_active = 'schedule';
			$this->view->schedule = $schedule;

            $even_numbers = false;
            $odd_numbers = false;

            if ($schedule){
                if ($schedule->even_numbers_start_time || $schedule->even_numbers_end_time || $schedule->even_numbers_monday || $schedule->even_numbers_tuesday || $schedule->even_numbers_wednesday || $schedule->even_numbers_thursday || $schedule->even_numbers_friday || $schedule->even_numbers_saturday || $schedule->even_numbers_sunday)
                    $even_numbers = true;

                $this->view->even_numbers = $even_numbers;

                if ($schedule->odd_numbers_start_time || $schedule->odd_numbers_end_time || $schedule->odd_numbers_monday || $schedule->odd_numbers_tuesday || $schedule->odd_numbers_wednesday || $schedule->odd_numbers_thursday || $schedule->odd_numbers_friday || $schedule->odd_numbers_saturday || $schedule->odd_numbers_sunday)
                    $odd_numbers = true;

                $this->view->odd_numbers = $odd_numbers;
            }

			if ($specialty)
			{
				if($schedule->date_to && (strtotime($schedule->date_to) < time()))
				{
					$this->view->show_buttons = false;
					$this->view->schedules_list = $doctor_schedule_manager->getPastListByDoctorIdAndClinicIdAndSpecialtyId($doctor->getId(), $clinic->getId(), $specialty->getId());
					$this->view->schedule_top_menu_active = 'past';
				} else {
					$this->view->show_buttons = true;
					$this->view->schedules_list = $doctor_schedule_manager->getFutureListByDoctorIdAndClinicIdAndSpecialtyId($doctor->getId(), $clinic->getId(), $specialty->getId());
					$this->view->schedule_top_menu_active = 'current';
				}

				$days_range = $doctor_schedule_manager->getFreeDaysRangeAndScheduleId($schedule->getId());

				$this->view->days_range = $days_range;
			}

			if ($schedule && $schedule->is_active && Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY))
				$this->view->show_buttons = false;

			$this->clinic = $clinic;
			$this->doctor = $doctor;
			$this->specialty = $specialty;

			$this->setSchedulesPageParams();

			$this->select_clinic_url = '/registry/doctor/schedule_view?id='.$doctor->getId().'&clinic_id=';
        }


		private function setSchedulesPageParams()
		{
			$moderate_specialty_to_doctor_manager = new ModerateSpecialtyToDoctorManager();
			$doctor_specialties =
				$moderate_specialty_to_doctor_manager->getSpecialtiesListByDoctorIdAndClinicId($this->doctor->getId(), $this->clinic->getId());

			$this->view->doctor_specialties = $doctor_specialties;
			$this->view->clinic = $this->clinic;
			$this->view->specialty = $this->specialty;
			$this->view->entry_id = $this->doctor->getId();

			$this->view->doctor = $this->doctor;
			$this->view->clinic_id = $this->clinic->getId();
		}

		/**
		 * Метод для управления настройками графика
		 */
		public function schedule_options()
        {
            $doctor_id = $this->request('id', 0);

            RegistryAccessHelper::checkAccessToDoctor($doctor_id);
            $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

            $doctor_manager = new DoctorManager();
            $doctor = $doctor_manager->getOneById($doctor_id);

            $this->view->doctor = $doctor;
            $this->view->clinic_id = $clinic_id;
            $this->view->menu_type = 'doctor';
            $this->view->menu_active = 'schedule';

            $this->view->entry_id = $doctor_id;
        }

		/**
		 * Метод для страницы создания графика
		 */
		public function schedule_create()
        {
			$params = DoctorSchedulePageParamsHelper::getRegistryDoctorSchedulePageParams();

			$this->view->menu_type = 'doctor';
			$this->view->menu_active = 'schedule';
			$this->view->schedule_top_menu_active = 'create';

			$this->view->date_from = date('d-m-Y');

			$this->doctor = $params->doctor;
			$this->clinic = $params->clinic;
			$this->specialty = $params->specialty;

			$this->setSchedulesPageParams();

			if (!$this->specialty)
			{
				$this->not_have_specialty = true;
			} else {
				$doctor_schedule_manager =  new DoctorScheduleManager();
				$this->view->days_range = $doctor_schedule_manager->getFreeDaysRangeByDoctorIdAndClinicIdAndSpecialtyId($params->doctor->getId(), $params->clinic->getId(), $params->specialty->getId());
			}
        }

		/**
		 * Метод для страницы редактирования графика
		 */
		public function schedule_edit()
        {
            $doctor_id = $this->request('id', 0);

            RegistryAccessHelper::checkAccessToDoctor($doctor_id);
            $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

            $doctor_manager = new DoctorManager();
            $doctor = $doctor_manager->getOneById($doctor_id);

            $this->view->doctor = $doctor;
            $this->view->clinic_id = $clinic_id;
            $this->view->menu_type = 'doctor';
            $this->view->menu_active = 'schedule';

            $this->view->entry_id = $doctor_id;
        }

		/**
		 * Метод для сохранения расписания
		 */
		public function ajaxSaveSchedule()
		{
			if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER) && !Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY))
				JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

			$doctor_id = $this->request->post('doctor_id');

			if (Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY))
				$clinic_id = ClinicUserHelper::getClinicIdByUserId(Acl::userId());
			else {
				$clinic_id = $this->request->post('clinic_id');
			}

			$specialty_id = $this->request->post('specialty_id');
			$schedule_id = $this->request->post('schedule_id');
			$is_active = $this->request->post('is_active', 0);

			$schedule_info = $this->request('schedule_info');

			$doctor_schedule_manager = new DoctorScheduleManager();

			if ($schedule_id)
			{
				$doctor_schedule_model = $doctor_schedule_manager->getOneById($schedule_id);
			} else {
			 	$doctor_schedule_model = new DoctorScheduleModel();
			}
			$doctor_schedule_model->clearParams();

			$doctor_schedule_model->doctor_id = $doctor_id;
			$doctor_schedule_model->clinic_id = $clinic_id;
			$doctor_schedule_model->specialty_id = $specialty_id;
			$doctor_schedule_model->date_from = date('Y-m-d', strtotime($schedule_info['date_from']));
			$doctor_schedule_model->date_to = $schedule_info['date_to'] ? date('Y-m-d', strtotime($schedule_info['date_to'])) : NULL;
			$doctor_schedule_model->visit_slot_time = $schedule_info['visit_slot_time'];
			$doctor_schedule_model->schedule_type_id = $schedule_info['schedule_type_id'];
			$doctor_schedule_model->is_active = $is_active;

			$days_of_week = DateHelper::getWeekDaysNames();

			if ($doctor_schedule_model->schedule_type_id == 4)
			{
				if ($schedule_info['even_numbers']['is_active'])
				{
					$doctor_schedule_model->even_numbers_start_time = $schedule_info['even_numbers']['start_time'];
					$doctor_schedule_model->even_numbers_end_time = $schedule_info['even_numbers']['end_time'];
					$doctor_schedule_model->even_numbers_break_start_time = $schedule_info['even_numbers']['break']['start_time'];
					$doctor_schedule_model->even_numbers_break_end_time = $schedule_info['even_numbers']['break']['end_time'];
					$doctor_schedule_model->even_numbers_visit_type_id = $schedule_info['even_numbers']['visit_type_id'];

					foreach($days_of_week as $day_of_week)
					{
						$doctor_schedule_model->{'even_numbers_'.$day_of_week} = (int)$schedule_info['even_numbers']['days'][$day_of_week];
					}
				}

				if ($schedule_info['odd_numbers']['is_active'])
				{
					$doctor_schedule_model->odd_numbers_start_time = $schedule_info['odd_numbers']['start_time'];
					$doctor_schedule_model->odd_numbers_end_time = $schedule_info['odd_numbers']['end_time'];
					$doctor_schedule_model->odd_numbers_break_start_time = $schedule_info['odd_numbers']['break']['start_time'];
					$doctor_schedule_model->odd_numbers_break_end_time = $schedule_info['odd_numbers']['break']['end_time'];
					$doctor_schedule_model->odd_numbers_visit_type_id = $schedule_info['odd_numbers']['visit_type_id'];

					foreach($days_of_week as $day_of_week)
					{
						$doctor_schedule_model->{'odd_numbers_'.$day_of_week} = (int)$schedule_info['odd_numbers']['days'][$day_of_week];
					}
				}
			} else {
				foreach($days_of_week as $day_of_week)
				{
					if (isset($schedule_info['first_week'][$day_of_week]) && $schedule_info['first_week'][$day_of_week])
					{
						$doctor_schedule_model->{'first_week_'.$day_of_week.'_start_time'} = $schedule_info['first_week'][$day_of_week]['start_time'];
						$doctor_schedule_model->{'first_week_'.$day_of_week.'_end_time'} = $schedule_info['first_week'][$day_of_week]['end_time'];
						$doctor_schedule_model->{'first_week_'.$day_of_week.'_break_start_time'} = $schedule_info['first_week'][$day_of_week]['break']['start_time'];
						$doctor_schedule_model->{'first_week_'.$day_of_week.'_break_end_time'} = $schedule_info['first_week'][$day_of_week]['break']['end_time'];
						$doctor_schedule_model->{'first_week_'.$day_of_week.'_visit_type_id'} = $schedule_info['first_week'][$day_of_week]['visit_type_id'];
					}

					if (isset($schedule_info['second_week'][$day_of_week]) && $schedule_info['second_week'][$day_of_week])
					{
						if ($doctor_schedule_model->schedule_type_id > 1)
						{
							$doctor_schedule_model->{'second_week_'.$day_of_week.'_start_time'} = $schedule_info['second_week'][$day_of_week]['start_time'];
							$doctor_schedule_model->{'second_week_'.$day_of_week.'_end_time'} = $schedule_info['second_week'][$day_of_week]['end_time'];
							$doctor_schedule_model->{'second_week_'.$day_of_week.'_break_start_time'} = $schedule_info['second_week'][$day_of_week]['break']['start_time'];
							$doctor_schedule_model->{'second_week_'.$day_of_week.'_break_end_time'} = $schedule_info['second_week'][$day_of_week]['break']['end_time'];
							$doctor_schedule_model->{'second_week_'.$day_of_week.'_visit_type_id'} = $schedule_info['second_week'][$day_of_week]['visit_type_id'];
						}
					}
				}
			}

			if ($doctor_schedule_model->save())
			{
				$data = array(
					'doctor_schedule_id' => $doctor_schedule_model->getId()
				);
				JsonResponse::result($data);
			} else {
				JsonResponse::result(ValidationErrorCodes::WRONG_DATA);
			}

		}

		/**
		 * Метод для удаления специальности
		 */
        public function ajaxDeleteSpecialty()
        {
            if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER) && !Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY))
                JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

            $clinic_id = $this->request('clinic_id');
            $doctor_id = $this->request('doctor_id');
            $specialty_id = $this->request('specialty_id');

            $specialty_to_doctor_manager = new SpecialtyToDoctorManager();
            $specialty_to_doctor = $specialty_to_doctor_manager->getListByDoctorIdAndClinicId($doctor_id, $clinic_id);

            if ($specialty_to_doctor){
                $specialty_to_doctor_manager->deleteByClinicIdAndDoctorIdAndSpecialtyId($clinic_id, $doctor_id, $specialty_id);
                ModelManagerFactory::getByName('moderate_specialty_to_doctor')->deleteByClinicIdAndDoctorIdAndSpecialtyId($clinic_id, $doctor_id, $specialty_id);

                $doctor_specialty_to_clinic = ModelManagerFactory::getByName('doctor_specialty_to_clinic')->getOneByDoctorIdAndClinicIdAndSpecialtyId($doctor_id, $clinic_id, $specialty_id);
                ModelManagerFactory::getByName('doctor_specialty_to_clinic')->deleteById($doctor_specialty_to_clinic->id);

                JsonResponse::result(array('deleted' => TRUE));
            } else {
                JsonResponse::result(2);
            }
        }

        public function clinics()
        {
			ini_set('memory_limit', '128M');
            $doctor_id = $this->request('id', 0);

            $access_roles = array(RoleModel::ACCOUNT_SUPER_MANAGER, RoleModel::ACCOUNT_MANAGER, RoleModel::FREELANCE_MANAGER);
            if (!RegistryAccessHelper::checkAccessToDoctor($doctor_id) || !in_array(Acl::userRole(), $access_roles))
                ErrorPageViewHelper::page404();

            $doctor_manager = new DoctorManager();
            $doctor = $doctor_manager->getOneById($doctor_id);
            $this->view->doctor = $doctor;

            $clinic_manager = new ClinicManager();
            $clinics_info = $clinic_manager->getIdAndNameByUserId(Acl::userId());

            $this->view->clinics = $clinics_info;
            $this->view->entry_id = $doctor_id;
            $this->view->menu_type = 'doctor';
            $this->view->menu_active = 'clinics';
        }

        public function ajaxSaveClinics()
        {
            $access_roles = array(RoleModel::ACCOUNT_SUPER_MANAGER, RoleModel::ACCOUNT_MANAGER);
            if(!in_array(Acl::userRole(), $access_roles))
            {
                JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
            }

            $doctor_id = $this->request->post('doctor_id');
            $clinics = $this->request->post('clinics');

            if (!$clinics) $clinics = array();

            $clinic_manager = new ClinicManager();
            $doctor_clinics = $clinic_manager->getListByDoctorIdAndUserId($doctor_id, Acl::userId());


            $doctor_to_clinic_manager = new DoctorToClinicManager();

            /**
             * @var ClinicModel[] $clinics_to_delete
             * @vat ClinicModel[] $doctor_clinics
             */
            $clinics_to_delete = array();
            foreach($doctor_clinics as $clinic)
            {
                if(!in_array($clinic->getid(), $clinics))
                {
                    $clinics_to_delete[] = $clinic;
                }
            }

            if ($clinics_to_delete)
                foreach($clinics_to_delete as $clinic_to_delete)
                {
                    /**
                     * @var DoctorToClinicModel $doctor_to_clinic
                     */
                    $doctor_to_clinic = $doctor_to_clinic_manager->getOneByClinicIdAndDoctorId($clinic_to_delete->getId(), $doctor_id);
                    if ($doctor_to_clinic)
                        $doctor_to_clinic_manager->delete($doctor_to_clinic);
                }

            if ($clinics)
                foreach($clinics as $clinic_id)
                {
                    $model = $doctor_to_clinic_manager->getOneByClinicIdAndDoctorId($clinic_id, $doctor_id);

                    if (!$model)
                    {
                        $doctor_to_clinic = new DoctorToClinicModel();
                        $doctor_to_clinic->doctor_id = $doctor_id;
                        $doctor_to_clinic->clinic_id = $clinic_id;
                        $doctor_to_clinic->save();
                    }
                }

            JsonResponse::result(true);
        }

	}

