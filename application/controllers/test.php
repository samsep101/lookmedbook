<?php

    class TestController extends Controller
    {
        public function mosopen()
        {
            $mosopen_parser = new MosopenParser();
            $mosopen_parser->parse();
        }

        public function amp()
        {

        }

        public function cl()
        {
            /**
             * @var ClinicModel $clinic
             */
            $clinic_formatter = new ClinicElasticSearchFormatter();
            $clinic_manager   = new ClinicManager();
            $clinic           = $clinic_manager->getOneById(329);

            Test::dump($clinic->freelancer);


        }


        public function fillClinicsRegion()
        {
            $clinic_manager = new ClinicManager();
            $clinics        = $clinic_manager->getList();

            $geocoder = new YandexGeocoder();

            $region_manager = new RegionManager();

            foreach($clinics as $clinic)
            {
                if(($clinic->city_id != 2) || $clinic->region_id)
                {
                    continue;
                }

                if($clinic->latitude && $clinic->longitude)
                {
                    $region_name = $geocoder->getDistrictInfoByGeoPoint(new GeoPoint($clinic->latitude, $clinic->longitude));

                    $region = $region_manager->getOneByName($region_name);

                    if($region)
                    {
                        $clinic->region_id = $region->getId();
                        $clinic->save();
                    }
                }
            }
        }

        public function google()
        {
            $file = file_get_contents('https://webcache.googleusercontent.com/search?q=cache:UgLt0PhT0OoJ:energyfc.ru/index.php/component/joomsport/view_match/120+&cd=3&hl=ru&ct=clnk');
            Test::dump($file);

        }

        public function fillClinicsAddress()
        {
            $clinic_manager = new ClinicManager();

            $clinics = $clinic_manager->getList();

            $street_manager = new StreetManager();

            $i = 0;
            foreach($clinics as $clinic)
            {
                $i++;
                if($clinic->city_id != 2)
                {
                    continue;
                }

                if($clinic->address)
                {
                    $address = $clinic->address;

                    $address_type = '';

                    $address = str_replace('Москва,', '', $address);

                    if((mb_strpos($address, 'ул.', NULL, 'utf-8') !== FALSE) || (mb_strpos($address, 'Ул.', NULL, 'utf-8') !== FALSE) || (mb_strpos($address, 'ул', NULL, 'utf-8') !== FALSE) || (mb_strpos($address, 'улица', NULL, 'utf-8') !== FALSE) || (mb_strpos($address, 'Улица', NULL, 'utf-8') !== FALSE)
                    )
                    {
                        $address_type = 'улица';
                    }

                    $address = str_replace('ул.', '', $address);
                    $address = str_replace('Ул.', '', $address);
                    $address = str_replace('улица', '', $address);
                    $address = str_replace('Улица', '', $address);
                    $address = str_replace('ул ', '', $address);


                    if((mb_strpos($address, 'бульвар', NULL, 'utf-8') !== FALSE))
                    {
                        $address_type = 'бульвар';
                    }
                    $address = str_replace('бульвар', '', $address);

                    if((mb_strpos($address, 'аллея', NULL, 'utf-8') !== FALSE))
                    {
                        $address_type = 'аллея';
                    }
                    $address = str_replace('аллея', '', $address);

                    if((mb_strpos($address, 'проспект', NULL, 'utf-8') !== FALSE))
                    {
                        $address_type = 'проспект';
                    }
                    $address = str_replace('проспект', '', $address);

                    if((mb_strpos($address, 'Проспект', NULL, 'utf-8') !== FALSE))
                    {
                        $address_type = 'проспект';
                    }
                    $address = str_replace('Проспект', '', $address);

                    if((mb_strpos($address, 'шоссе', NULL, 'utf-8') !== FALSE))
                    {
                        $address_type = 'шоссе';
                    }
                    $address = str_replace('шоссе', '', $address);

                    if((mb_strpos($address, 'набережная', NULL, 'utf-8') !== FALSE))
                    {
                        $address_type = 'набережная';
                    }
                    $address = str_replace('набережная', '', $address);

                    if((mb_strpos($address, 'переулок', NULL, 'utf-8') !== FALSE))
                    {
                        $address_type = 'переулок';
                    }
                    $address = str_replace('переулок', '', $address);

                    if((mb_strpos($address, 'пер.', NULL, 'utf-8') !== FALSE))
                    {
                        $address_type = 'переулок';
                    }
                    $address = str_replace('пер.', '', $address);

                    if((mb_strpos($address, 'проезд', NULL, 'utf-8') !== FALSE))
                    {
                        $address_type = 'проезд';
                    }
                    $address = str_replace('проезд', '', $address);

                    $address = trim($address);


                    if(!$address_type)
                    {
                        echo '2 - ' . $address;
                        exit();
                    }

                    if(preg_match('/^(.+), ?(?:(?:д\.)?([0-9\/а-г]+))(?:(?:с([0-9]+))|(?:к([0-9]+)))?$/u', $address, $matches))
                    {
                        Test::dump($matches, FALSE);

                        $street_name = $matches[1];
                        if(preg_match('/^([0-9]\-[йя]) (.*)$/u', $street_name))
                        {
                            $street_name = trim(preg_replace('/^([0-9]\-[йя]) (.*)$/u', '$2 $1', $street_name));
                            echo '6 - ' . $address_type . ' ' . $street_name . '<br />';
                        }
                        $street_name = preg_replace('/ {2,}/', ' ', $street_name);

                        if($street = $street_manager->getOneByPrefixAndName($address_type, $street_name))
                        {
                            $clinic->street_id = $street->getId();
                            $clinic->house     = $matches[2];
                            $clinic->structure = (isset($matches[3])) ? $matches[3] : NULL;
                            $clinic->case      = (isset($matches[4])) ? $matches[4] : NULL;
                            $clinic->save();
                        }
                        else
                        {
                            echo '3 - ' . $address . ' - ' . $i . '<br />';
                        }
                    }
                    else
                    {
                        echo '1 - ' . $address;
                    }
                }
            }
        }

        public function createCompiledJSLibrary(){
            //getting list of js files from /media/js/library/ catalog
            $js_library_folder = ABS_ROOT . '/media/js/library';
            $all_js = glob($js_library_folder.'/*');
            $exclude_js = ['jquery.radio.min.js', 'basket_info.controller.js', 'elements.controller.js', 'file_uploader.controller.js', 'sex_select_form.controller.js', 'site_statistic.controller.js'];
            $js_file_list = [];
            $pat = '/([a-zA-Z0-9\.]+)\.js/is';
            foreach ($all_js as $e)
            {
                $file_name = str_replace($js_library_folder.'/', '', $e);

                if (array_search($file_name, $exclude_js) === false){
                    if (preg_match($pat, $file_name))
                        $js_file_list[] = $file_name;
                }
            }

            $compiler = isset($_SERVER['argv'][2]) && $_SERVER['argv'][2] === '--dev'
                ? new ConcatenatingLocalCompilerHelper()
                : new ExtendedRemoteCompilerHelper();
            foreach ($js_file_list as $fname){
                $compiler->addLocalFile($js_library_folder.'/'.$fname);
                echo "add file $fname<br>".PHP_EOL;
            }

            $compiled = $compiler->compile();
            $compiled_code = $compiled->getCompiledCode();

            $compiled_js_fname = $js_library_folder.'/../js_library.js';

            if (!file_put_contents($compiled_js_fname, $compiled_code)) {
                die("error writing file $compiled_js_fname");
            }

            echo "file ".realpath($compiled_js_fname)." writed successfuly!\n";
            exit(0);
        }

        public function testYandex()
        {
            $url  = 'https://geocode-maps.yandex.ru/1.x/?geocode=37.611006,55.757962&format=json&kind=district';
            $data = CurlRequestSender::get($url);

            Test::dump(json_decode($data));
        }

        /*
             public function setAllGrants()
             {
                 $controllers = ModelManagerFactory::getByName('controller')->getList();

                 foreach ($controllers as $controller) {
                     $grant = new GrantModel();
                     $grant->role_id = 1;
                     $grant->controller_id = $controller->getId();
                     $grant->setAllRights();

                     ModelManagerFactory::getByName('grant')->save($grant);
                 }

                 exit();
             }
             */

        public function generateModelFiles()
        {
            $file_generator = new ModelFilesGenerator();
            $file_generator->generate();
        }

        public function generateAliases()
        {
            $doctor_manager = new DoctorManager();

            foreach($doctor_manager->getIterator() as $doctor)
            {
                $doctor->save();
            }

            /*
               $clinic_manager = new ClinicManager();
               $clinics = $clinic_manager->getList();

               foreach ($clinics as $clinic)
               {
                   $clinic->save();
               }
               */

            $disease_manager = new DiseaseManager();
            $diseases        = $disease_manager->getList();

            foreach($diseases as $disease)
            {
                $disease->save();
            }

            $city_manager = new CityManager();
            $cities       = $city_manager->getList();
            foreach($cities as $city)
            {
                $city->save();
            }

            $specialty_manager = new SpecialtyManager();
            $specialties       = $specialty_manager->getList();
            foreach($specialties as $specialty)
            {
                $specialty->save();
            }

            $metro_station_manager = new MetroStationManager();
            $metro_stations        = $metro_station_manager->getList();
            foreach($metro_stations as $metro_station)
            {
                $metro_station->save();
            }

        }


        public function addSpecialtiesToDoctorsInSchedule()
        {
            $schedule_manager = new ScheduleManager();
            $schedule_manager->updateSpecialtyId();
            exit();
        }

        public function addClinicInPurposeOfVisitToDoctor()
        {
            $purpose_of_visit_to_doctor_manager = new PurposeOfVisitToDoctorManager();

            $list = $purpose_of_visit_to_doctor_manager->getList();

            foreach($list as $purpose_of_visit)
            {
                if($purpose_of_visit->doctor->clinics)
                {
                    $purpose_of_visit->clinic_id = $purpose_of_visit->doctor->clinics[0]->getId();
                    $purpose_of_visit->save();
                }
            }
        }

        public function insertInDoctorSpecialtyToClinic()
        {
            $doctor_specialty_to_clinic_manager = new DoctorSpecialtyToClinicManager();
            $doctor_specialty_to_clinic_manager->truncateTable();

            $doctor_to_clinic_manager = new DoctorToClinicManager();
            if($doctors_to_clinic = $doctor_to_clinic_manager->getList())
            {
                foreach($doctors_to_clinic as $doctor_to_clinic)
                {
                    $doctor_specialties_to_clinic               = new DoctorSpecialtyToClinicModel();
                    $doctor_specialties_to_clinic->doctor_id    = $doctor_to_clinic->doctor_id;
                    $doctor_specialties_to_clinic->clinic_id    = $doctor_to_clinic->clinic_id;
                    $doctor_specialties_to_clinic->specialty_id = $doctor_to_clinic->specialty_id;
                    $doctor_specialties_to_clinic->save();
                }
            }
            exit();
        }

        function setPregnantStatus()
        {
            $doctor_manager = new DoctorManager();
            $doctors        = $doctor_manager->getList();

            foreach($doctors as $doctor)
            {
                if($doctor->specialties && $doctor->specialties[0]->getId() != 34)
                {
                    $doctor->is_pregnant = 1;
                    $doctor->save();
                }
                else
                {
                    $doctor->is_pregnant = 0;
                    $doctor->save();
                }
            }
        }

        function setPurposeOfVisit()
        {
            $doctor_specialty_to_clinic_manager = new DoctorSpecialtyToClinicManager();

            $relations = $doctor_specialty_to_clinic_manager->getList();

            $pv2d_manager = new PurposeOfVisitToDoctorManager();
            foreach($relations as $relation)
            {
                $pv2d = $pv2d_manager->getOneByClinicIdAndDoctorIdAndSpecialtyIdAndPurposeOfVisitId($relation->clinic_id, $relation->doctor_id, $relation->specialty_id, 283);

                if(!$pv2d)
                {
                    $new_pv2d                      = new PurposeOfVisitToDoctorModel();
                    $new_pv2d->purpose_of_visit_id = 283;
                    $new_pv2d->clinic_id           = $relation->clinic_id;
                    $new_pv2d->doctor_id           = $relation->doctor_id;
                    $new_pv2d->specialty_id        = $relation->specialty_id;
                    $new_pv2d->save();
                }

                $pv2d = $pv2d_manager->getOneByClinicIdAndDoctorIdAndSpecialtyIdAndPurposeOfVisitId($relation->clinic_id, $relation->doctor_id, $relation->specialty_id, 284);

                if(!$pv2d)
                {
                    $new_pv2d                      = new PurposeOfVisitToDoctorModel();
                    $new_pv2d->purpose_of_visit_id = 284;
                    $new_pv2d->clinic_id           = $relation->clinic_id;
                    $new_pv2d->doctor_id           = $relation->doctor_id;
                    $new_pv2d->specialty_id        = $relation->specialty_id;
                    $new_pv2d->save();
                }
            }
        }

        public function generateDoctorsCvsFile()
        {
            SiteTaskManager::generateDoctorsExcelFile();
            exit();
        }


        public function generateActiveDiseaseExcelFile()
        {
            SiteTaskManager::generateActiveDiseaseExcelFile();
            exit();
        }

        public function generateDiseaseCvsFile()
        {
            SiteTaskManager::generateDiseaseExcelFile();
            exit();
        }


        public function generateDoctorCardImages()
        {
            $doctor_manager = new DoctorManager();
            $doctors        = $doctor_manager->getList();

            foreach($doctors as $doctor)
            {
                if(!$doctor->card_image_id && $doctor->images)
                {
                    $doctor->card_image_id = $doctor->images[0]->getId();
                    $doctor->save();
                }
            }
        }

        public function updateDoctorWorkingTimeFlags()
        {
            $clinicManager = new ClinicManager();
            $clinics = $clinicManager->getActiveList();
            $clinicManager->updateDoctorWorkingTimeFlags($clinics);
            exit();
        }

        public function insertInDoctorSchedule()
        {
            $doctor_schedule_manager = new DoctorScheduleManager();
            //$doctor_schedule_manager->truncateTable();

            $doctor_manager = new DoctorManager();
            $doctors        = $doctor_manager->getList();

            $specialty_to_doctor_manager = new SpecialtyToDoctorManager();

            if($doctors)
            {
                foreach($doctors as $doctor)
                {
                    $specialties_to_doctor = $specialty_to_doctor_manager->getListByDoctorId($doctor->getId());

                    foreach($specialties_to_doctor as $specialty_to_doctor)
                    {
                        if($specialty_to_doctor->specialty_id && $specialty_to_doctor->clinic_id)
                        {
                            $existing_schedule = $doctor_schedule_manager->getOneCurrentByDoctorIdAndClinicIdAndSpecialtyId($doctor->getId(), $specialty_to_doctor->clinic_id, $specialty_to_doctor->specialty_id);
                            if($existing_schedule)
                            {
                                $doctor_schedule_model = new DoctorScheduleModel();

                                $doctor_schedule_model->clinic_id    = $specialty_to_doctor->clinic_id;
                                $doctor_schedule_model->specialty_id = $specialty_to_doctor->specialty_id;

                                $doctor_schedule_model->is_active = 1;

                                $doctor_schedule_model->doctor_id = $doctor->getId();

                                $doctor_schedule_model->schedule_type_id = 1;
                                $doctor_schedule_model->date_from        = '2013-08-31';
                                $doctor_schedule_model->visit_slot_time  = 30;

                                $doctor_schedule_model->save();
                            }
                        }
                    }
                }

            }

            exit();
        }

        public function insertInSpecializationToClinic()
        {
            $specialization_to_clinic_manager = new SpecializationToClinicManager();
            $specialization_to_clinic_manager->truncateTable();

            $clinic_manager = new ClinicManager();
            $clinics        = $clinic_manager->getList();

            $specialty_to_clinic_manager = new SpecialtyToClinicManager();

            $specialty_to_specialization_manager = new SpecialtyToSpecializationManager();

            foreach($clinics as $clinic)
            {
                $specialties_to_clinic = $specialty_to_clinic_manager->getListByClinicId($clinic->getId());

                foreach($specialties_to_clinic as $specialty_to_clinic)
                {
                    $specializations = $specialty_to_specialization_manager->getListBySpecialtyId($specialty_to_clinic->specialty_id);

                    if($specializations)
                    {

                        $specialization_to_clinic = $specialization_to_clinic_manager->getOneBySpecializationIdAndClinicId($specializations[0]->specialization_id, $clinic->getId());

                        if(!$specialization_to_clinic)
                        {
                            $specialization_to_clinic_model                    = new SpecializationToClinicModel();
                            $specialization_to_clinic_model->specialization_id = $specializations[0]->specialization_id;
                            $specialization_to_clinic_model->clinic_id         = $clinic->getId();
                            $specialization_to_clinic_model->save();
                        }
                    }
                }
            }

            exit();
        }

        public function generateYandexCompaniesFeed()
        {
            ini_set("memory_limit", "128M");
            set_time_limit(0);
            $generator = new YandexServicesCompaniesFeedGenerator();
            $generator->generate(1);

            exit();
        }

        public function generateYandexFeedAllClinic()
        {
            ini_set("memory_limit", "512M");
            set_time_limit(0);
            $generator = new YandexServicesCompaniesFeedGenerator();
            $generator->generate(3);

            exit();
        }

        public function generateTopClinics()
        {
            ini_set("memory_limit", "512M");
            set_time_limit(0);
            $generator = new TopClinicsGenerator();
            $generator->generate();

            exit();
        }

        public function checkExistYandexFeedAllClinic()
        {
            $file = 'booking/yandex-all-clinic.xml';
            if(file_exists($file))
            {
                echo json_encode(array('status' => 'exist'));
            }
            else
            {
                echo json_encode(array('status' => 'notExist'));
            }
            exit();
        }

        public function saveXMLFileAllClinic()
        {
            $file = 'booking/yandex-all-clinic.xml';
            if(file_exists($file))
            {
                if(ob_get_level())
                {
                    // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
                    ob_end_clean();
                }
                // заставляем браузер показать окно сохранения файла
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                // читаем файл и отправляем его пользователю
                readfile($file);
            }
            exit();
        }

        public function insertDoctorSpecialtyToClinicFromSpecialtyToDoctor()
        {
            $specialty_to_doctor_manager = new SpecialtyToDoctorManager();
            if($specialties_to_doctor = $specialty_to_doctor_manager->getList())
            {
                foreach($specialties_to_doctor as $specialty_to_doctor)
                {

                    $doctor_specialty_to_clinic_manager = new DoctorSpecialtyToClinicManager();
                    $existing_record                    = $doctor_specialty_to_clinic_manager->getOneByDoctorIdAndClinicIdAndSpecialtyId($specialty_to_doctor->doctor_id, $specialty_to_doctor->clinic_id, $specialty_to_doctor->specialty_id);
                    if(!$existing_record)
                    {
                        $doctor_to_clinic               = new DoctorSpecialtyToClinicModel();
                        $doctor_to_clinic->doctor_id    = $specialty_to_doctor->doctor_id;
                        $doctor_to_clinic->clinic_id    = $specialty_to_doctor->clinic_id;
                        $doctor_to_clinic->specialty_id = $specialty_to_doctor->specialty_id;
                        $doctor_to_clinic->save();
                    }
                }
            }
            exit();
        }

        public function formVisitsSlots()
        {
            ini_set("memory_limit", "1024M");
            set_time_limit(0);
            $doctor_visits_builder = new DoctorVisitsSlotsBuilder();
            $doctor_visits_builder->cleanDoctorSchedules();
            $doctor_visits_builder->getDoctorSchedules();
            exit();
        }

        public function cleanDoctorSchedules()
        {
            $doctor_visits_builder = new DoctorVisitsSlotsBuilder();
            $doctor_visits_builder->cleanDoctorSchedules();
            exit();
        }

        public function setPurposesOfVisitToSpecialty()
        {
            $specialty_names = array(
                'врач ультразвуковой диагностики',
                'врач функциональной диагностики',
                'рентгенолог',
                'физиотерапевт',
                'врач лфк',
                'реабилитолог'
            );

            $specialty_manager = new SpecialtyManager();

            $specialties = $specialty_manager->getList();

            $pv2s_manager = new PurposeOfVisitToSpecialtyManager();

            foreach($specialties as $specialty)
            {
                if(in_array(mb_strtolower($specialty->name, 'utf-8'), $specialty_names))
                {
                    continue;
                }

                $pv2d = $pv2s_manager->getOneBySpecialtyIdAndPurposeOfVisitId($specialty->getId(), 283);

                if(!$pv2d)
                {
                    $new_pv2d                      = new PurposeOfVisitToSpecialtyModel();
                    $new_pv2d->purpose_of_visit_id = 283;
                    $new_pv2d->specialty_id        = $specialty->getId();
                    $new_pv2d->is_main             = 1;
                    $new_pv2d->save();
                }
                else
                {
                    $pv2d->is_main = 1;
                    $pv2d->save();
                }

                $pv2d = $pv2s_manager->getOneBySpecialtyIdAndPurposeOfVisitId($specialty->getId(), 284);

                if(!$pv2d)
                {
                    $new_pv2d                      = new PurposeOfVisitToSpecialtyModel();
                    $new_pv2d->purpose_of_visit_id = 284;
                    $new_pv2d->specialty_id        = $specialty->getId();
                    $new_pv2d->is_main             = 1;
                    $new_pv2d->save();
                }
                else
                {
                    $pv2d->is_main = 1;
                    $pv2d->save();
                }
            }
        }

        public function clearDoctorsCache()
        {
            /*
               $memcache_facade = new MemcacheFacade(SITE_URL);
               $memcache_facade->deleteGroup('doctor_card_block');
               */
            $this->file_cache->deleteGroup('doctor_card_block');
            /*
               $cache = Register::get('cache');
               $cache->clean('doctor_card_block', 'ingroup');
               */
            exit();
        }

        public function clearMemcache()
        {
            MemcacheAdapter::clear();
        }

        public function clearCache()
        {
            MemcacheAdapter::clear();
            exit();
        }

        public function setDiseaseGenitiveAndPrepositionalNames()
        {
            $word_decline = WordDeclination::getInstance();

            $disease_manager = new DiseaseManager();
            $diseases        = $disease_manager->getList();

            foreach($diseases as $disease)
            {
                if(!$disease->genitive_name && !$disease->prepositional_name)
                {
                    $words = explode(' ', $disease->title);

                    foreach($words as $word)
                    {
                        if($word_decline->getAllForms($word))
                        {
                            $disease->genitive_name .= $word_decline->toGenitive($word) . ' ';
                            $disease->prepositional_name .= $word_decline->toPrepositional($word) . ' ';
                        }
                        else
                        {
                            $disease->genitive_name .= $word . ' ';
                            $disease->prepositional_name .= $word . ' ';
                        }
                    }
                }

                $disease->save();
            }
        }

        public function setAdultFlagToDoctorAndClinic()
        {
            $doctor_manager = new DoctorManager();
            $doctor_manager->setAdultFlagToDoctor();

            $clinic_manager = new ClinicManager();
            $clinic_manager->setAdultFlagToClinic();

            exit;
        }

        public function updateToLowerSpecializationNameFirstLetter()
        {
            $specialization_manager = new SpecializationManager();
            $specializations        = $specialization_manager->getList();
            foreach($specializations as $specialization)
            {
                if($specialization->name == 'ЛФК')
                {
                    continue;
                }
                $letter               = mb_substr($specialization->name, 0, 1, 'utf-8');
                $specialization->name = str_replace($letter, mb_strtolower($letter, 'utf-8'), $specialization->name);
                $specialization->save();
            }
            exit;
        }

        public function updateToLowerSpecialtyNameFirstLetter()
        {
            $specialty_manager = new SpecialtyManager();
            $specialties       = $specialty_manager->getList();
            foreach($specialties as $specialty)
            {
                if($specialty->name == 'ЛФК')
                {
                    continue;
                }
                $name_letter     = mb_substr($specialty->name, 0, 1, 'utf-8');
                $specialty->name = str_replace($name_letter, mb_strtolower($name_letter, 'utf-8'), $specialty->name);
                $specialty->save();
            }
            exit;
        }

        public function changeAndDeleteClinicSpecialtyAndSpecialization()
        {
            $specialty_to_clinic_manager      = new SpecialtyToClinicManager();
            $specialization_to_clinic_manager = new SpecializationToClinicManager();
            $specialization_manager           = new SpecializationManager();
            $specialty_manager                = new SpecialtyManager();

            $specialty_vrach_medicinu = $specialty_manager->getOneByName('врач восстановительной медицины');
            $specialty_reabilitolog   = $specialty_manager->getOneByName('реабилитолог');

            $specialization_reabilitologiya = $specialization_manager->getOneByName('реабилитология');
            $specialization_vosst_medicina  = $specialization_manager->getOneByName('Восстановительная медицина');

            if($specialization_reabilitologiya && $specialization_vosst_medicina)
            {

                $specialty_to_clinics = $specialty_to_clinic_manager->getListBySpecialtyId($specialty_reabilitolog->id);
                if(count($specialty_to_clinics))
                {
                    foreach($specialty_to_clinics as $specialty_to_clinic)
                    {
                        $specialty_to_clinic->specialty_id = $specialty_vrach_medicinu->id;
                        $specialty_to_clinic->save();
                    }
                }

                $specialization_to_clinics = $specialization_to_clinic_manager->getListBySpecializationId($specialization_reabilitologiya->id);
                if(count($specialization_to_clinics))
                {
                    foreach($specialization_to_clinics as $specialization_to_clinic)
                    {
                        $specialization_to_clinic->specialization_id = $specialization_vosst_medicina->id;
                        $specialization_to_clinic->save();
                    }
                }

                $specialization_manager->deleteById($specialization_reabilitologiya->id);
            }
            exit;
        }

        public function changeAndDeleteDoctorSpecialtyAndSpecialization()
        {
            $specialty_to_doctor_manager        = new SpecialtyToDoctorManager();
            $doctor_specialty_to_clinic_manager = new DoctorSpecialtyToClinicManager();

            $specialty_manager = new SpecialtyManager();

            $specialty_vrach_medicinu = $specialty_manager->getOneByName('врач восстановительной медицины');
            $specialty_reabilitolog   = $specialty_manager->getOneByName('реабилитолог');


            if($specialty_reabilitolog && $specialty_vrach_medicinu)
            {

                $specialty_to_doctors = $specialty_to_doctor_manager->getListBySpecialtyId($specialty_reabilitolog->id);
                if(count($specialty_to_doctors))
                {
                    foreach($specialty_to_doctors as $specialty_doctor)
                    {
                        $specialty_doctor->specialty_id = $specialty_vrach_medicinu->id;
                        $specialty_doctor->save();
                    }
                }

                $doctor_specialties_to_clinic = $doctor_specialty_to_clinic_manager->getListBySpecialtyId($specialty_reabilitolog->id);
                if(count($doctor_specialties_to_clinic))
                {
                    foreach($doctor_specialties_to_clinic as $doctor_specialty_to_clinic)
                    {
                        $doctor_specialty_to_clinic->specialty_id = $specialty_vrach_medicinu->id;
                        $doctor_specialty_to_clinic->save();
                    }
                }

                $specialty_manager->deleteById($specialty_reabilitolog->id);
            }
            exit;
        }


        public function updateDoctorSpecialty()
        {
            $doctor_specialty_to_clinic_manager = new DoctorSpecialtyToClinicManager();
            $specialty_to_doctor_manager        = new SpecialtyToDoctorManager();
            $specialty_manager                  = new SpecialtyManager();

            $specialty_to_doctors = $specialty_to_doctor_manager->getListBySpecialtyName('детский%');

            if(count($specialty_to_doctors))
            {

                $specialties_to_delete = array();

                foreach($specialty_to_doctors as $specialty_to_doctor)
                {

                    $names = explode(' ', $specialty_to_doctor->specialty_name);
                    if(count($names) > 2)
                    {
                        $len  = strlen('детский');
                        $name = substr($specialty_to_doctor->specialty_name, $len + 1);
                    }
                    else
                    {
                        $name = $names[1];
                    }

                    $specialty = $specialty_manager->getOneByName($name);
                    if($specialty)
                    {

                        $children_specialties_to_delete[] = $specialty_to_doctor->specialty_id;

                        $specialty_to_doctor->specialty_id = $specialty->id;

                        $doctors_specialty_to_clinic = $doctor_specialty_to_clinic_manager->getListByDoctorId($specialty_to_doctor->doctor_id);
                        if(count($doctors_specialty_to_clinic))
                        {
                            foreach($doctors_specialty_to_clinic as $doctor_specialty_to_clinic)
                            {
                                if($doctor_specialty_to_clinic->specialty_id == $specialty_to_doctor->specialty_id)
                                {
                                    $doctor_specialty_to_clinic->specialty_id = $specialty->id;
                                    $doctor_specialty_to_clinic->save();
                                }
                            }
                        }

                        $doctor_manager      = new DoctorManager();
                        $doctor              = $doctor_manager->getOneById($specialty_to_doctor->doctor_id);
                        $doctor->is_children = 1;

                        $doctor->save();
                        $specialty_to_doctor->save();
                    }
                }

                if(count($children_specialties_to_delete))
                {
                    foreach($children_specialties_to_delete as $specialty_id)
                    {
                        //$specialty_manager->deleteById($specialty_id);
                    }
                }
            }

            exit('ok');
        }

        public function updateClinicSpecialties()
        {
            $specialty_manager                  = new SpecialtyManager();
            $doctor_specialty_to_clinic_manager = new DoctorSpecialtyToClinicManager();

            $specialties = $specialty_manager->getListByName('детский%');

            if(count($specialties))
            {
                foreach($specialties as $specialty)
                {

                    $names = explode(' ', $specialty->name);
                    if(count($names) > 2)
                    {
                        $len  = strlen('детский');
                        $name = substr($specialty->name, $len + 1);
                    }
                    else
                    {
                        $name = $names[1];
                    }

                    $need_specialty = $specialty_manager->getOneByName($name);
                    if($need_specialty)
                    {
                        $doctors_specialty_to_clinic = $doctor_specialty_to_clinic_manager->getListBySpecialtyId($specialty->getId());

                        if(count($doctors_specialty_to_clinic))
                        {
                            foreach($doctors_specialty_to_clinic as $doctor_specialty_to_clinic)
                            {
                                if($doctor_specialty_to_clinic->specialty_id == $specialty->id)
                                {
                                    $doctor_specialty_to_clinic->specialty_id = $need_specialty->id;
                                    $doctor_specialty_to_clinic->save();
                                }
                            }
                        }
                    }
                }
            }

            exit;
        }


        public function generateSeoLinksFile()
        {
            $header = array(array('URL'), array('------------------'));
            $links  = SeoLinksHelper::getLinks();

            $links_formatted = array();

            foreach($links as $link)
            {
                $links_formatted[] = array($link);
            }

            $result = array_merge($header, $links_formatted);

            //Test::dump($result);

            PhpHeaderHelper::csv('seo_links.csv');
            $generator = new CsvGenerator();
            echo $generator->generateFromArray($result);

        }


        public function addModelComments()
        {
            $tables = DbHelper::getTablesList();

            foreach($tables as $table_name)
            {
                $file_name = ABS_ROOT . '/application/models/' . $table_name . '.model.php';
                if(file_exists($file_name))
                {
                    $file_content = file_get_contents($file_name);

                    $ext_fields = array();
                    if(preg_match_all('/_field_(.+?)[ \(]/ims', $file_content, $matches))
                    {
                        foreach($matches[1] as $field_name)
                        {
                            $ext_fields[] = array('Field' => $field_name, 'Type' => 'ext');
                        }
                    }

                    $table_fields = DbHelper::getTableFields($table_name);

                    $fields = array_merge($table_fields, $ext_fields);

                    $comments = ModelCommentsGenerator::generate($fields);

                    if(preg_match('/^(<\?php.+)(class.+)$/ims', $file_content, $matches))
                    {
                        $new_file_content = "<?php\r\n" . $comments . "\r\n    " . $matches[2];
                        file_put_contents($file_name, $new_file_content);
                    }
                }
            }


            exit();
        }

        public function addManagerComments()
        {
            $tables = DbHelper::getTablesList();

            foreach($tables as $table_name)
            {
                $file_name = ABS_ROOT . '/application/models/' . $table_name . '.manager.php';

                if(!file_exists($file_name))
                {
                    continue;
                }

                $model_name = StringHelper::toCamelCase($table_name) . 'Model';

                $file_content = file_get_contents($file_name);

                if(preg_match_all('/(public +function +get((?:One)|(?:List))[A-Za-z0-9]*)\(/ims', $file_content, $matches))
                {
                    if($matches[1])
                    {
                        foreach($matches[1] as $key => $method_name)
                        {
                            if($matches[2]{$key} == 'One')
                            {
                                $comment = $model_name;
                            }
                            else
                            {
                                $comment = $model_name . '[]';
                            }
                            $comment      = "/**\r\n\t\t * return " . $comment . "\r\n\t\t */\r\n\t\t";
                            $file_content = preg_replace('/(' . $method_name . ')/', $comment . '$1', $file_content);
                        }
                    }
                }

                file_put_contents($file_name, $file_content);
            }
        }

        public function addSpecializationsToClinic()
        {
            $clinic_manager = new ClinicManager();

            /**
             * @var ClinicModel[] $clinics ;
             */
            $clinics = $clinic_manager->getList();

            $specialty_to_clinic_manager      = new SpecialtyToClinicManager();
            $specialization_to_clinic_manager = new SpecializationToClinicManager();

            foreach($clinics as $clinic)
            {
                if($clinic->doctors)
                {

                    foreach($clinic->doctors as $doctor)
                    {
                        $specialties = $doctor->getSpecialtiesByClinicId($clinic->getId());

                        if($specialties)
                        {
                            foreach($specialties as $specialty)
                            {
                                if(!$specialty_to_clinic_manager->getOneByClinicIdAndSpecialtyId($clinic->getId(), $specialty->getId()))
                                {
                                    $specialty_to_clinic               = new SpecialtyToClinicModel();
                                    $specialty_to_clinic->specialty_id = $specialty->getId();
                                    $specialty_to_clinic->clinic_id    = $clinic->getId();
                                    echo 'add specialty: clinic_id = ' . $clinic->getId() . ' specialty_id = ' . (int)$specialty->getId() . "\r\n";
                                    $specialty_to_clinic->save();

                                    if($specialty->specializations)
                                    {
                                        $specialization_id = $specialty->specializations[0]->getId();

                                        if(!$specialization_to_clinic_manager->getOneBySpecializationIdAndClinicId($specialization_id, $clinic->getId()))
                                        {
                                            $specialization_to_clinic_model                    = new SpecializationToClinicModel();
                                            $specialization_to_clinic_model->specialization_id = $specialization_id;
                                            $specialization_to_clinic_model->clinic_id         = $clinic->getId();
                                            $specialization_to_clinic_model->save();
                                            echo 'add specialization: clinic_id = ' . $clinic->getId() . ' specialization_id = ' . (int)$specialization_id . "\r\n";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        public function importClinics()
        {
            set_time_limit(0);

            $user_manager = new UserManager();
            $city_manager = new CityManager();

            $users = array(
                'egolovkova@lookmedbook.ru',
                'estashenyk@lookmedbook.ru',
                'esankova@lookmedbook.ru',
                'sibgatullina@lookmedbook.ru',
            );

            $freelancers = array('Sisoeva', 'Samorodova');

            foreach($users as $user_email)
            {
                if(!$user_manager->getOneByLogin($user_email))
                {
                    $user           = new UserModel();
                    $user->login    = $user_email;
                    $user->role_id  = RoleModel::ACCOUNT_MANAGER;
                    $user->password = PasswordHashGenerator::generate('123456');
                    $user->save();
                }
            }

            foreach($freelancers as $freelancer_email)
            {
                $user = $user_manager->getOneByLogin($freelancer_email);

                if(!$user)
                {
                    $user = new UserModel();
                }

                $user->login    = $freelancer_email;
                $user->role_id  = RoleModel::FREELANCE_MANAGER;
                $user->password = PasswordHashGenerator::generate('123456');
                $user->save();

            }

            $tasks = array(
                array(

                    'xlsx'              => './data/.xls',
                    'json'              => './data/ufa-data.json',
                    'number_of_clinics' => 53,
                    'user'              => $user_manager->getOneByLogin('sibgatullina@lookmedbook.ru'),
                    'freelancer'        => $user_manager->getOneByLogin('Samorodova'),
                    'city'              => $city_manager->getOneByName('Уфа'),
                ),

            );


            $stop_words = array('гемотест', 'инвитро');

            $clinic_manager         = new ClinicManager();
            $clinic_to_user_manager = new ClinicToUserManager();

            $folder      = './data';
            $directories = FileHelper::getFoldersList($folder);

            foreach($directories as $directory)
            {
                $xls_files  = glob($folder . '/' . $directory . '/*.xls');
                $json_files = glob($folder . '/' . $directory . '/*.json');
                $info_file  = $folder . '/' . $directory . '/info.txt';

                echo 'Directory ' . $directory . ' start!' . "\r\n";
                if(!$xls_files || !$json_files || !file_exists($info_file))
                {
                    echo 'Directory ' . $directory . ' doesn\'t processed!' . "\r\n";
                    continue;
                }


                $settings = parse_ini_file($info_file);

                $freelancer = $user_manager->getOneByLogin(trim($settings['Freelancer']));
                $manager    = $user_manager->getOneByLogin(trim($settings['Manager']));
                $city       = $city_manager->getOneByName(trim($settings['City']));

                if(!$freelancer || !$manager)
                {
                    echo 'Directory ' . $directory . ' doesn\'t processed - invalid user!' . "\r\n";
                    continue;
                }

                if(!$city)
                {
                    echo 'Directory ' . $directory . ' doesn\'t processed - invalid city!' . "\r\n";
                    continue;
                }

                $importer = new RegionClinicsImportManager($xls_files[0], $json_files[0]);
                $importer->setNumberOfClinics($settings['Count']);
                $importer->setStopWords($stop_words);

                $importer->import($settings['City']);

                $clinics = $clinic_manager->getListByCityId($city->getId());

                if($clinics)
                {
                    foreach($clinics as $clinic)
                    {
                        if(!$clinic_to_user_manager->getOneByClinicIdAndUserId($clinic->getId(), $freelancer->getId()))
                        {
                            $clinic_to_user            = new ClinicToUserModel();
                            $clinic_to_user->clinic_id = $clinic->getId();
                            $clinic_to_user->user_id   = $freelancer->getId();
                            $clinic_to_user->save();
                        }

                        if(!$clinic_to_user_manager->getOneByClinicIdAndUserId($clinic->getId(), $freelancer->getId()))
                        {
                            $clinic_to_user            = new ClinicToUserModel();
                            $clinic_to_user->clinic_id = $clinic->getId();
                            $clinic_to_user->user_id   = $freelancer->getId();
                            $clinic_to_user->save();
                        }
                    }
                }
            }

            exit();
            /*
               foreach($tasks as $task)
               {
                   $importer = new RegionClinicsImportManager($task['xlsx'], $task['json']);
                   $importer->setNumberOfClinics($task['number_of_clinics']);
                   $importer->setStopWords($stop_words);
                   $importer->import();

                   if($task['user'])
                   {
                       $clinics = $clinic_manager->getListByCityId($task['city']->getId());

                       if($clinics)
                       {
                           foreach($clinics as $clinic)
                           {
                               if(!$clinic_to_user_manager->getOneByClinicIdAndUserId($clinic->getId(), $task['user']->getId()))
                               {
                                   $clinic_to_user = new ClinicToUserModel();
                                   $clinic_to_user->clinic_id = $clinic->getId();
                                   $clinic_to_user->user_id = $task['user']->getId();
                                   $clinic_to_user->save();
                               }

                               if(!$clinic_to_user_manager->getOneByClinicIdAndUserId($clinic->getId(), $task['freelancer']->getId()))
                               {
                                   $clinic_to_user = new ClinicToUserModel();
                                   $clinic_to_user->clinic_id = $clinic->getId();
                                   $clinic_to_user->user_id = $task['freelancer']->getId();
                                   $clinic_to_user->save();
                               }
                           }
                       }
                   }

               }

               exit();
               */
        }

        public function fillModerateListRevisionTable()
        {
            $moderate_list_revision_manager = new ModerateListRevisionManager();

            $doctor_manager         = new DoctorManager();
            $clinic_manager         = new ClinicManager();
            $specialty_manager      = new SpecialtyManager();
            $specialization_manager = new SpecializationManager();

            /**
             * @var ModerateListRevisionModel[] $list
             */
            $list = $moderate_list_revision_manager->getIterator();

            foreach($list as $row)
            {
                if(is_numeric($row->entity_id))
                {
                    if(strpos($row->list_name, 'doctor') !== FALSE)
                    {
                        if(!$doctor_manager->checkExistsById($row->entity_id))
                        {
                            $moderate_list_revision_manager->delete($row);
                            continue;
                        }
                        $row->doctor_id = $row->entity_id;
                    }

                    if(strpos($row->list_name, 'clinic') !== FALSE)
                    {
                        if(!$clinic_manager->checkExistsById($row->entity_id))
                        {
                            $moderate_list_revision_manager->delete($row);
                            continue;
                        }
                        $row->clinic_id = $row->entity_id;
                    }
                }
                else
                {
                    if(preg_match_all('/(doctor|clinic|specialty|specialization)_id=([0-9]+)/ims', $row->entity_id, $matches))
                    {
                        foreach($matches[1] as $k => $v)
                        {
                            if(!ModelManagerFactory::getByName($v)->checkExistsById($matches[2][$k]))
                            {
                                $moderate_list_revision_manager->delete($row);
                                continue;
                            }
                            $row->{$v . '_id'} = $matches[2][$k];
                        }
                    }
                }

                $row->save();
            }
        }

        public function fixClinicSchedule()
        {
            $clinic_manager = new ClinicManager();
            $iterator       = $clinic_manager->getIterator();

            set_time_limit(0);

            foreach($iterator as $clinic)
            {
                /**
                 * @var ClinicModel $clinic
                 */
                $clinic->end_time_monday   = preg_replace('/[^0-9:]/', '', $clinic->end_time_monday);
                $clinic->start_time_monday = preg_replace('/[^0-9:]/', '', $clinic->start_time_monday);

                $clinic->start_time_tuesday = preg_replace('/[^0-9:]/', '', $clinic->start_time_tuesday);
                $clinic->end_time_tuesday   = preg_replace('/[^0-9:]/', '', $clinic->end_time_tuesday);

                $clinic->start_time_wednesday = preg_replace('/[^0-9:]/', '', $clinic->start_time_wednesday);
                $clinic->end_time_wednesday   = preg_replace('/[^0-9:]/', '', $clinic->end_time_wednesday);

                $clinic->start_time_thursday = preg_replace('/[^0-9:]/', '', $clinic->start_time_thursday);
                $clinic->end_time_thursday   = preg_replace('/[^0-9:]/', '', $clinic->end_time_thursday);

                $clinic->start_time_friday = preg_replace('/[^0-9:]/', '', $clinic->start_time_friday);
                $clinic->end_time_friday   = preg_replace('/[^0-9:]/', '', $clinic->end_time_friday);

                $clinic->start_time_saturday = preg_replace('/[^0-9:]/', '', $clinic->start_time_saturday);
                $clinic->end_time_saturday   = preg_replace('/[^0-9:]/', '', $clinic->end_time_saturday);

                $clinic->start_time_sunday = preg_replace('/[^0-9:]/', '', $clinic->start_time_sunday);
                $clinic->end_time_sunday   = preg_replace('/[^0-9:]/', '', $clinic->end_time_sunday);

                $clinic->save();
            }
        }

        public function setVisitTime()
        {
            $visit_manager = new VisitManager();

            /**
             * @var VisitModel[] $visits
             */
            $visits = $visit_manager->getIterator();

            foreach($visits as $visit)
            {
                if(!$visit->create_time)
                {
                    $create_time = NULL;

                    if($visit->visit_start_time)
                    {
                        $create_time = $visit->visit_start_time;
                    }
                    elseif($visit->schedule)
                    {
                        $create_time = $visit->schedule->dt_start;
                    }

                    $visit->disableValidation();
                    $visit->create_time = $create_time;
                    $visit->save();
                }
            }
        }


        public function docx()
        {
            $report_generator = new ClinicFinanceReportGenerator();
            $clinic_manager   = new ClinicManager();

            $clinic = $clinic_manager->getOneById(1);


            $report_generator->setClinic($clinic);

            $report_generator->setDateFrom('01.10.2013');
            $report_generator->setDateTo('30.10.2013');

            $report_generator->generate('./test.docx');
            exit();
        }

        public function sendSms()
        {
            $sms_api = SmsApiFactory::getSmsApi();
            Test::dump($sms_api->sendMessage('79854126732', 'Privet'));
            exit();
        }

        public function getSMSAreaBalance()
        {
            $sms_api = SmsApiFactory::getSmsApi();
            Test::dump($sms_api->getBalance());
            exit();
        }

        public function setCityForLaboratories()
        {

            set_time_limit(0);
            $laboratory_manager = new LaboratoryManager();
            $laboratories       = $laboratory_manager->getList();

            $geocoder     = new YandexGeocoder();
            $city_manager = new CityManager();

            foreach($laboratories as $laboratory)
            {
                if(!$laboratory->city_id)
                {
                    $city_name = $geocoder->getCityNameByGeoPoint(new GeoPoint($laboratory->latitude, $laboratory->longitude));
                    $city_id   = $city_manager->getIdByCityName($city_name);

                    if($city_id)
                    {
                        $laboratory->city_id = $city_id;
                        $laboratory->save();
                    }
                }
            }
            exit();
        }

        public function geocoder()
        {
            set_time_limit(0);

            $data = array(
                array(55.5901, 37.6469),
                array(55.6028, 37.7138),
                array(55.6277, 37.6205),
                array(55.7457, 37.6706),
                array(55.7451, 37.6648),
                array(55.7923, 37.5746),
                array(55.739, 37.4951),
                array(55.7473, 37.7694),
                array(55.6666, 37.7603),
                array(55.7084, 37.7261),
                array(55.6628, 37.7521),
                array(55.8141, 37.6479),
                array(55.8277, 37.5141),
                array(55.8308, 37.3563),
                array(55.6932, 37.7221),
                array(55.8266, 37.623),
                array(55.7508, 37.5996),
                array(55.7361, 37.41),
                array(55.7608, 37.6788),
                array(55.779, 37.6159),
                array(55.7605, 37.6521),
                array(55.6468, 37.4799),
                array(55.6697, 37.501),
                array(55.81, 37.4827),
                array(55.6683, 37.5146),
                array(55.7518, 37.5326),
                array(55.569, 37.5886),
                array(55.7787, 37.5247),
                array(55.5714, 37.5739),
                array(55.6792, 37.55),
                array(55.6792, 37.55),
                array(55.6892, 37.5298),
                array(55.7818, 37.5932),
                array(55.7948, 37.607),
                array(55.79, 37.713),
                array(55.7698, 37.6471),
                array(55.6753, 37.5414),
                array(55.7395, 37.6848),
                array(55.7435, 37.549),
                array(55.7912, 37.5204),
                array(55.7662, 37.6444),
                array(55.8071, 37.5159),
                array(55.7595, 37.6031),
                array(55.7912, 37.5204),
                array(55.7799, 37.5994),
                array(55.7547, 37.8101),
                array(55.7378, 37.8587),
                array(55.7691, 37.5772),
                array(55.7778, 37.458),
                array(55.7236, 37.673),
                array(55.7862, 37.6031),
                array(55.6888, 37.466),
                array(55.7864, 37.6367),
                array(55.7594, 37.7148),
                array(55.7854, 37.596),
                array(55.7587, 37.6243),
                array(55.7456, 37.6759),
                array(55.7413, 37.5402),
                array(55.7625, 37.7773),
                array(55.8708, 37.4413),
                array(55.7658, 37.5805),
                array(55.8458, 37.5838),
                array(55.7278, 37.5249),
                array(55.7278, 37.5249),
                array(55.7845, 37.709),
                array(55.6449, 37.5194),
                array(55.6441, 37.4735),
                array(55.7088, 37.5075),
                array(55.626, 37.6194),
                array(55.6342, 37.63),
                array(55.6757, 37.7677),
                array(55.7157, 37.4749),
                array(55.7856, 37.6333),
                array(55.782, 37.6366),
                array(55.8441, 37.5769),
                array(55.7191, 37.6153),
                array(55.864, 37.5634),
                array(55.8441, 37.5769),
                array(55.7943, 37.6956),
                array(55.8071, 37.5884),
                array(55.7357, 37.6782),
                array(55.7775, 37.5876),
                array(55.6512, 37.4831),
                array(55.7512, 37.6628),
                array(55.7553, 37.6008),
                array(55.8014, 37.7763),
                array(55.7806, 37.5907),
                array(55.779, 37.713),
                array(55.8183, 37.4654),
                array(55.8006, 37.7596),
                array(55.716, 37.6465),
                array(55.6493, 37.4901),
                array(55.7747, 37.5981),
                array(55.7889, 37.6118),
                array(37.6501, 37.6501),
                array(55.7954, 37.5139),
                array(55.7044, 37.5033),
                array(55.5859, 37.6768),
                array(55.7141, 37.6747),
                array(55.818, 37.5112),
                array(55.8075, 37.5053),
                array(55.7318, 37.5325),
                array(55.7872, 37.6319),
                array(55.816, 37.5117),
                array(55.7668, 37.6435),
                array(55.6782, 37.759),
                array(55.7216, 37.4358),
                array(55.7813, 37.6037),
                array(55.7483, 37.6082),
                array(55.7734, 37.5945),
                array(55.7459, 37.8373),
                array(55.7655, 37.6392),
                array(55.7474, 37.63),
                array(55.8165, 37.6496),
                array(55.7521, 37.7133),
                array(55.815, 37.4547),
                array(55.7593, 37.6143),
                array(55.8642, 37.5855),
                array(55.6581, 37.6021),
                array(55.7654, 37.7186),
                array(55.7244, 37.4653),
                array(55.7465, 37.4277),
                array(55.6954, 37.4966),
                array(55.8183, 37.5815),
                array(55.7498, 37.5671),
                array(55.7999, 37.7437),
                array(55.7692, 37.5901),
                array(55.8894, 37.6604),
                array(55.6815, 37.7499),
                array(55.7457, 37.4992),
                array(55.8066, 37.543),
                array(55.6435, 37.6186),
                array(38.6294, 16.0618),
                array(55.8242, 37.5195),
                array(55.7904, 37.6884),
                array(55.6853, 37.5402),
                array(55.8574, 37.496),
                array(55.5681, 37.5735),
                array(55.6603, 37.7603),
                array(55.877, 37.5235),
                array(55.5418, 37.5212),
                array(55.7657, 37.4129),
                array(55.9028, 37.5856),
                array(55.7038, 37.6547),
                array(55.6989, 37.6213),
                array(55.7113, 37.8805),
                array(55.8072, 37.719),
                array(55.8809, 37.7006),
                array(55.7439, 37.602),
                array(55.9028, 37.5856),
                array(55.7766, 37.606),
                array(55.6635, 37.4869),
                array(55.7331, 37.4641),
                array(55.7687, 37.679),
                array(55.7934, 37.5905),
                array(55.7722, 37.6605),
                array(55.7799, 37.7231),
                array(55.8197, 37.5844),
                array(55.7833, 37.7299),
                array(55.7676, 37.6312),
                array(55.9811, 37.1769),
                array(56.007, 37.2101),
                array(56.1881, 36.974),
            );

            $geocoder = new YandexGeocoder();

            /**
             * @var DistrictManager $region_manager
             */
            $region_manager = ModelManagerFactory::getByName('region');

            foreach($data as $v)
            {
                $region_name = $geocoder->getDistrictInfoByGeoPoint(new GeoPoint($v[0], $v[1]));

                $region = $region_manager->getOneByName($region_name);
                if(!$region)
                {
                    echo $region_name . '<br/>';
                }
            }

            exit();
        }

        public function getMemcacheValue()
        {
            $key      = $_GET['key'];
            $memcache = Register::get('memcache');

            Test::dump($memcache->get($key));
            exit();
        }

        public function testPush()
        {
            $push_sender        = ApplePushNotificationServiceFactory::getService();
            $push_params        = new ApplePushNotificationParams();
            $push_params->alert = 'Привет';
            $push_params->badge = 1;
            $push_params->sound = 'default';
            $data               = array('mdfg' => 1, 'sdfghj' => 2);
            $token              = '213ddf2e21b3e6db95236c7aa9ce24d3856846639e5ce03151c005022cdaddfe';
            $push_sender->send($token, $push_params, $data);
        }

        public function fastlogin()
        {
            $fastlogin = new FastLogin();
            echo $fastlogin->generateHash(5, '2013-11-25', 1);
            exit();
        }

        public function testVisits()
        {
            $visit_criteria                        = new VisitSearchCriteria();
            $visit_criteria->not_has_doctor_review = TRUE;

            $visit_manager = new VisitManager();

            $visits = $visit_manager->getListByModelSearchCriteria($visit_criteria);

            Test::dump($visits);
        }

        public function testYandexRecord()
        {
            $url    = SITE_SCHEME . '://lookmedbook.ru/api/yandex';
            $result = array(
                'comment'          => '\u0442\u0435\u0441\u0442 \u0442\u0435\u0441\u0442',
                'bookType'         => 'static-resource-only',
                'resourceId'       => '388',
                'phone'            => '+79126825608',
                'serviceId'        => NULL,
                'resourceName'     => '\u0415\u0440\u043e\u0447\u043a\u0438\u043d\u0430 \u041e\u043b\u044c\u0433\u0430 \u042e\u0440\u044c\u0435\u0432\u043d\u0430',
                'bookId'           => '7bb1b52f99614dbab9286db1671565b8',
                'organizationId'   => '34',
                'bookTypeOriginal' => 'static',
                'dateTime'         => '2013-11-20T11:00:00',
                'notifyMe'         => 60,
                'fullName'         => '\u0422\u0435\u0441\u0442 \u0422\u0435\u0441\u0442',
                'email'            => ''
            );
            /* // shevel's data
            $result = array(
                'bookType' => 'static',
                'resourceId' => '1800',
                'phone' => '71234567890',
                'serviceId' => '1',
                'resourceName' => '\xd0\x95\xd0\xbb\xd0\xb8\xd0\xb7\xd0\xbe\xd0\xb2\xd0\xb0 \xd0\x92\xd0\xb8\xd0\xba\xd1\x82\xd0\xbe\xd1\x80\xd0\xb8\xd1\x8f \xd0\x93\xd0\xb5\xd0\xbd\xd0\xbd\xd0\xb0\xd0\xb4\xd1\x8c\xd0\xb5\xd0\xb2\xd0\xbd\xd0\xb0',
                'serviceName' => '\xd0\x9e\xd0\xb1\xd1\x89\xd0\xb0\xd1\x8f \xd0\xba\xd0\xbe\xd0\xbd\xd1\x81\xd1\x83\xd0\xbb\xd1\x8c\xd1\x82\xd0\xb0\xd1\x86\xd0\xb8\xd1\x8f',
                'bookId' => '202cb962ac59075b964b07152d234b70',
                'organizationId' => '155',
                'dateTime' => '2013-11-20T12:30:09',
                'attributes' => array(),
                'fullName' => '\xd0\x98\xd0\xb2\xd0\xb0\xd0\xbd\xd0\xbe\xd0\xb2 \xd0\x98\xd0\xb2\xd0\xb0\xd0\xbd \xd0\x98\xd0\xb2\xd0\xb0\xd0\xbd\xd0\xbe\xd0\xb2\xd0\xb8\xd1\x87',
                'email' => 'ivan@ivanovich.lb'
            );*/
            $post_params = array('jsonrpc' => '2.0', 'method' => 'book', 'params' => array($result), 'id' => 99);
            $post_params = json_encode($post_params);
            $result      = CurlRequestSender::post($url, $post_params);
            $result      = json_decode($result, TRUE);
            test::dump($result);
            exit();
        }

        public function virtualCards()
        {
            SiteTaskManager::createClinicVirtualDoctors(1262);
        }

        public function insertList()
        {
            $data = array(
                array(
                    'clinic_id' => 2,
                    'doctor_id' => 3
                ),
                array(
                    'clinic_id' => 5,
                    'doctor_id' => 6
                ),
            );

            $fields = array('doctor_id', 'clinic_id');

            /**
             * @var DoctorToClinicManager $doctor_to_clinic_manager
             */
            $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');
            $doctor_to_clinic_manager->insertList($data, $fields);
        }

        public function generateClinicCsvFile()
        {
            SiteTaskManager::generateClinicExcelFile();
            exit();
        }

        public function generateDoctorsSpecialtyCsvFile()
        {
            SiteTaskManager::generateDoctorsSpecialtyExcelFile();
            exit();
        }

        public function generateSpecialtyLocationCsvFile()
        {
            SiteTaskManager::generateSpecialtyLocationExcelFile();
            exit();
        }

        public function setVisitCity()
        {
            /**
             * @var VisitManager $visit_manager
             * @var VisitModel   $visit
             */

            $visit_manager = ModelManagerFactory::getByName('visit');

            foreach($visit_manager->getIterator() as $visit)
            {
                if($visit->clinic && $visit->clinic->city)
                {
                    $visit->city_id = $visit->clinic->city_id;
                    $visit->save();
                }
            }

            exit();
        }

        public function testMemcache()
        {
            $doctor_manager = new DoctorManager();


            $doctor = $doctor_manager->getOneById(202);
            set_time_limit(0);
            $start = microtime(TRUE);

            $ids  = array(202, 300, 809, 242, 345, 454, 453, 342, 205, 600);
            $data = array();
            for($i = 1; $i <= 1000; $i++)
            {
                $doctor = $doctor_manager->getSortedListByIdList($ids);
                //MemcacheAdapter::set('g'.$i, serialize($doctor));
                //$data[] = MemcacheAdapter::get('g'.$i);
            }


            echo $result = microtime(TRUE) - $start;
            echo '<br />';
            echo $result / 1000;
            Test::dump($data);
            exit();
        }

        public function php()
        {
            $var = 'str';
            $var++;
            echo $var;

            exit();
        }


        public function generateYandexCompaniesFeedNew()
        {
            ini_set("memory_limit", "128M");
            set_time_limit(0);
            $generator = new YandexServicesCompaniesFeedGenerator();
            $generator->generate(2);

            exit();
        }

        public function generateYandexContent()
        {
            set_time_limit(0);
            $yandex_content_generator = new YandexContentGenerator();
            $yandex_content_generator->sendTexts();
            exit();
        }

        public function getYandexContentToken()
        {
            $client_id                = SettingsManager::get('yandex_app_client_id');
            $yandex_content_generator = new YandexContentGenerator();
            $yandex_content_generator->getToken($client_id);
            exit();
        }

        public function generateClinicByCityCsvFile()
        {
            /**
             * @var ClinicManager $clinic_manager
             * @var ClinicModel   $clinic
             * @var CityManager   $city_manager
             * @var CityModel     $city
             */

            $city_manager = ModelManagerFactory::getByName('city');
            $result       = array();

            foreach($city_manager->getHavingClinicsListOrderByName() as $city)
            {
                $clinic_manager = ModelManagerFactory::getByName('clinic');
                $clinics        = $clinic_manager->getListByCityId($city->getId());

                foreach($clinics as $clinic)
                {
                    $clinic_info = array();

                    $clinic_info[] = $city->name;
                    $clinic_info[] = $clinic->metro_station_name;
                    $clinic_info[] = $clinic->address;
                    $clinic_info[] = $clinic->name;
                    $clinic_info[] = ClinicPageLinkViewHelper::getLink($clinic);

                    $result[] = $clinic_info;
                }
            }

            PhpHeaderHelper::csv('clinic_by_city.csv');
            $csv_generator = new CsvGenerator();
            echo $csv_generator->generateFromArray($result);
        }

        public function insertInMetroStationToClinic()
        {
            /**
             * @var ClinicManager $clinic_manager
             * @var ClinicModel   $clinic
             */

            $clinic_manager = ModelManagerFactory::getByName('clinic');

            foreach($clinic_manager->getIterator() as $clinic)
            {
                if($clinic->metro_station)
                {
                    $metro_station_to_clinic = new MetroStationToClinicModel();

                    $metro_station_to_clinic->clinic_id        = $clinic->getId();
                    $metro_station_to_clinic->metro_station_id = $clinic->metro_station_id;

                    $metro_station_to_clinic->save();
                }
            }

            exit();
        }

        public function setWatermark()
        {
            ini_set('memory_limit', '256M');
            set_time_limit(0);
            /**
             * @var ImageManager        $image_manager
             * @var ImageModel          $image
             * @var ResizedImageManager $resized_image_manager
             * @var ResizedImageModel   $resized_image
             */

            $image_manager = ModelManagerFactory::getByName('image');

            foreach($image_manager->getIterator() as $image)
            {
                if(file_exists(ABS_ROOT . $image->path))
                {
                    $image->save();

                    $resized_image_manager = ModelManagerFactory::getByName('resized_image');

                    foreach($resized_image_manager->getListByImageId($image->getId()) as $resized_image)
                    {
                        $new_filename = $resized_image->width . 'x' . $resized_image->height . '-' . $resized_image->action . '-' . $image->filename;

                        $image_resizer = new SimpleImage();
                        $image_resizer->load(ABS_ROOT . MEDIA_UPLOAD_PATH . $image->folder . $image->filename);
                        $image_resizer->resizeWithRatio($resized_image->width, $resized_image->height);

                        $image_resizer->save(ABS_ROOT . MEDIA_UPLOAD_PATH . $image->folder . $new_filename, $image->image_info[2]);
                    }
                }
            }

            exit();
        }

        public function generateImageForDoctors()
        {
            ini_set('memory_limit', '256M');
            set_time_limit(0);

            $image_to_doctor_manager = ModelManagerFactory::getByName('image_to_doctor');

            foreach($image_to_doctor_manager->getIterator() as $doctor_image)
            {
                $image_manager = ModelManagerFactory::getByName('image');
                $image         = $image_manager->getOneById($doctor_image->image_id);

                if(file_exists(ABS_ROOT . $image->path))
                {

                    $resized_image_manager = ModelManagerFactory::getByName('resized_image');

                    foreach($resized_image_manager->getListByImageId($image->getId()) as $resized_image)
                    {
                        $new_filename = $resized_image->width . 'x' . $resized_image->height . '-' . $resized_image->action . '-' . $image->filename;

//                        if (!file_exists('.' . MEDIA_UPLOAD_PATH . $image->folder . $new_filename)) {
                        $image_resizer = new SimpleImage();

                        $image_resizer->load(ABS_ROOT . MEDIA_UPLOAD_PATH . $image->folder . $image->filename);
                        if($resized_image->action == 'crop')
                        {
                            $image_resizer->crop($resized_image->width, $resized_image->height);
                        }
                        else
                        {
                            $image_resizer->resizeWithRatio($resized_image->width, $resized_image->height);
                        }

                        $image_resizer->save(ABS_ROOT . MEDIA_UPLOAD_PATH . $image->folder . $new_filename, $image->image_info[2]);

                        if($resized_image->is_with_watermark)
                        {
                            ImageWatermarkHelper::setWatermark(ABS_ROOT . MEDIA_UPLOAD_PATH . $image->folder . $new_filename);
                        }
                    }
                }
            }

        }

        public function generateImagesForProducts()
        {
            ini_set('memory_limit', '256M');
            set_time_limit(0);

            $image_manager = ModelManagerFactory::getByName('image');

            $search_params = new SearchParams();
            $search_params->addParam('folder', 'product/');

            $product = $image_manager->getListBySearchParams($search_params);
            $image_manager->generateImages($product);

            exit;
        }

        public function generateImagesForClinics()
        {
            ini_set('memory_limit', '256M');
            set_time_limit(0);

            $image_manager = ModelManagerFactory::getByName('image');

            $search_params = new SearchParams();
            $search_params->addParam('folder', 'clinic/license/');

            $clinics = $image_manager->getListBySearchParams($search_params);

            $image_manager->generateImages($clinics);

            exit;
        }

        public function testWatermark()
        {
            ImageWatermarkHelper::setWatermark('./2.jpg');
        }

        public function testCurl()
        {
            $curl_sender = new CurlRequest();
            $curl_sender->sendGetRequest('http://dengi.onliner.by');
            Test::dump($curl_sender->getResponseHeaders());
        }


        public function generateVidalManagers()
        {
            $file_generator = new ModelFilesGenerator();
            $file_generator->setManagerBaseClassName('VidalModelManager');
            $file_generator->setModelClassNamePrefix('Vidal');
            $file_generator->setManagerClassNamePrefix('Vidal');
            $db = new Db(DB_VIDAL_HOST, DB_VIDAL_USER, DB_VIDAL_PASSWORD, DB_VIDAL_NAME);
            $file_generator->setDb($db);

            $file_generator->generate();
        }

        public function vidal()
        {
            $db = Register::get('db');
            Test::dump($db->getTableFields('doctor'));
        }

        public function piluli()
        {
            $api = new PiluliApi('lookmedbook', '7Kjf238N');
            Test::dump($api->getPriceList());
        }

        public function parse()
        {
            ini_set("memory_limit", "512M");
            set_time_limit(0);
            //Test::dump(unserialize(file_get_contents('labs_all_data.ser')));

            $new_laboratories = new ServicesScheduleImportManager('labs_all_data.ser');
            $new_laboratories->import();
        }

        public function setHasLaboratoriesFlag()
        {
            /**
             * @var CityManager       $city_manager
             * @var CityModel         $city
             * @var LaboratoryManager $laboratory_manager
             * @var LaboratoryModel   $laboratory
             */

            $laboratory_manager = ModelManagerFactory::getByName('laboratory');

            foreach($laboratory_manager->getIterator() as $laboratory)
            {
                $city_manager = ModelManagerFactory::getByName('city');
                if($laboratory->city_id)
                {
                    $city = $city_manager->getOneById($laboratory->city_id);

                    if($city->is_has_laboratories == 0)
                    {
                        $city->is_has_laboratories = 1;
                        $city->save();
                    }
                }
            }
        }

        public function setWorkTimeForLaboratory()
        {
            /**
             * @var LaboratoryServiceScheduleManager $laboratory_service_schedule_manager
             * @var LaboratoryServiceScheduleModel   $laboratory_service_schedule
             * @var LaboratoryManager                $laboratory_manager
             * @var LaboratoryModel                  $laboratory
             */

            $laboratory_manager = ModelManagerFactory::getByName('laboratory');

            foreach($laboratory_manager->getIterator() as $laboratory)
            {
                if(!$laboratory->start_time_monday)
                {
                    $laboratory_service_schedule_manager = ModelManagerFactory::getByName('laboratory_service_schedule');
                    $start_time_monday                   = 99999999;
                    $end_time_monday                     = 0;
                    $start_time_tuesday                  = 99999999;
                    $end_time_tuesday                    = 0;
                    $start_time_wednesday                = 99999999;
                    $end_time_wednesday                  = 0;
                    $start_time_thursday                 = 99999999;
                    $end_time_thursday                   = 0;
                    $start_time_friday                   = 99999999;
                    $end_time_friday                     = 0;
                    $start_time_saturday                 = 99999999;
                    $end_time_saturday                   = 0;
                    $start_time_sunday                   = 99999999;
                    $end_time_sunday                     = 0;

                    if(!$laboratory->start_time_monday)
                    {
                        foreach($laboratory_service_schedule_manager->getListByLaboratoryId($laboratory->getId()) as $laboratory_service_schedule)
                        {
                            if($laboratory_service_schedule->getId() != 6)
                            {
                                if($laboratory_service_schedule->start_time_monday && $laboratory_service_schedule->start_time_monday < $start_time_monday)
                                {
                                    $start_time_monday = $laboratory_service_schedule->start_time_monday;
                                }
                                if($laboratory_service_schedule->end_time_monday > $end_time_monday)
                                {
                                    $end_time_monday = $laboratory_service_schedule->end_time_monday;
                                }

                                if($laboratory_service_schedule->start_time_tuesday && $laboratory_service_schedule->start_time_tuesday < $start_time_tuesday)
                                {
                                    $start_time_tuesday = $laboratory_service_schedule->start_time_tuesday;
                                }
                                if($laboratory_service_schedule->end_time_tuesday > $end_time_tuesday)
                                {
                                    $end_time_tuesday = $laboratory_service_schedule->end_time_tuesday;
                                }

                                if($laboratory_service_schedule->start_time_wednesday && $laboratory_service_schedule->start_time_wednesday < $start_time_wednesday)
                                {
                                    $start_time_wednesday = $laboratory_service_schedule->start_time_wednesday;
                                }
                                if($laboratory_service_schedule->end_time_wednesday > $end_time_wednesday)
                                {
                                    $end_time_wednesday = $laboratory_service_schedule->end_time_wednesday;
                                }

                                if($laboratory_service_schedule->start_time_thursday && $laboratory_service_schedule->start_time_thursday < $start_time_thursday)
                                {
                                    $start_time_thursday = $laboratory_service_schedule->start_time_thursday;
                                }
                                if($laboratory_service_schedule->end_time_thursday > $end_time_thursday)
                                {
                                    $end_time_thursday = $laboratory_service_schedule->end_time_thursday;
                                }

                                if($laboratory_service_schedule->start_time_friday && $laboratory_service_schedule->start_time_friday < $start_time_friday)
                                {
                                    $start_time_friday = $laboratory_service_schedule->start_time_friday;
                                }
                                if($laboratory_service_schedule->end_time_friday > $end_time_friday)
                                {
                                    $end_time_friday = $laboratory_service_schedule->end_time_friday;
                                }

                                if($laboratory_service_schedule->start_time_saturday && $laboratory_service_schedule->start_time_saturday < $start_time_saturday)
                                {
                                    $start_time_saturday = $laboratory_service_schedule->start_time_saturday;
                                }
                                if($laboratory_service_schedule->end_time_saturday > $end_time_saturday)
                                {
                                    $end_time_saturday = $laboratory_service_schedule->end_time_saturday;
                                }

                                if($laboratory_service_schedule->start_time_sunday && $laboratory_service_schedule->start_time_sunday < $start_time_sunday)
                                {
                                    $start_time_sunday = $laboratory_service_schedule->start_time_sunday;
                                }
                                if($laboratory_service_schedule->end_time_sunday > $end_time_sunday)
                                {
                                    $end_time_sunday = $laboratory_service_schedule->end_time_sunday;
                                }
                            }
                        }

                        $laboratory->start_time_monday = ($start_time_monday != 99999999) ? $start_time_monday : '';
                        $laboratory->end_time_monday   = ($end_time_monday != 0) ? $end_time_monday : '';

                        $laboratory->start_time_tuesday = ($start_time_tuesday != 99999999) ? $start_time_tuesday : '';
                        $laboratory->end_time_tuesday   = ($end_time_tuesday != 0) ? $end_time_tuesday : '';

                        $laboratory->start_time_wednesday = ($start_time_wednesday != 99999999) ? $start_time_wednesday : '';
                        $laboratory->end_time_wednesday   = ($end_time_wednesday != 0) ? $end_time_wednesday : '';

                        $laboratory->start_time_thursday = ($start_time_thursday != 99999999) ? $start_time_thursday : '';
                        $laboratory->end_time_thursday   = ($end_time_thursday != 0) ? $end_time_thursday : '';

                        $laboratory->start_time_friday = ($start_time_friday != 99999999) ? $start_time_friday : '';
                        $laboratory->end_time_friday   = ($end_time_friday != 0) ? $end_time_friday : '';

                        $laboratory->start_time_saturday = ($start_time_saturday != 99999999) ? $start_time_saturday : '';
                        $laboratory->end_time_saturday   = ($end_time_saturday != 0) ? $end_time_saturday : '';

                        $laboratory->start_time_sunday = ($start_time_sunday != 99999999) ? $start_time_sunday : '';
                        $laboratory->end_time_sunday   = ($end_time_sunday != 0) ? $end_time_sunday : '';

                        $laboratory->save();
                    }
                }
            }
        }

        public function test()
        {
            /**
             * @var ClinicManager                    $clinic_manager
             * @var ModerateClinicInformationManager $clinic_manager
             */

            $clinic_manager                      = ModelManagerFactory::getByName('clinic');
            $moderate_clinic_information_manager = ModelManagerFactory::getByName('moderate_clinic_information');
            $result                              = array();
            $counter                             = 0;
            $city_id_list                        = array(CityModel::MOSCOW_ID, CityModel::NOVOSIBIRSK_ID);

            $search_params = new SearchParams();
            $search_params->addParam('city_id IN', $city_id_list, 'city');
            $search_params->addParam('is_active', 1);
            $search_params->addParam('is_yandex_send', 1);

            $clinics = $clinic_manager->getListBySearchParams($search_params);

            foreach($clinics as $clinic)
            {
                $clinic_information = $moderate_clinic_information_manager->getCurrentRevision($clinic->getId());
                if($clinic_information && $clinic_information->is_yandex_send != 1)
                {
                    $counter++;
                    $clinic_info   = array();
                    $clinic_info[] = $counter;
                    $clinic_info[] = $clinic->getId();
                    $clinic_info[] = $clinic->name;
                    $clinic_info[] = $clinic->city->name;
                    $result[]      = $clinic_info;
                }
            }
            PhpHeaderHelper::csv('clinics.csv');
            $csv_generator = new CsvGenerator();
            echo $csv_generator->generateFromArray($result);
            exit();
        }

        public function loadReviews()
        {
            $csv_parser = new CsvParser('./data/reviews.csv', ';');
            $data       = $csv_parser->parse();
            unset($data[0]);

            ModelManager::disableEntityMapGlobal();
            /**
             * @var DoctorReviewManager $doctor_review_manager
             * @var ClinicReviewManager $clinic_review_manager
             * @var ClinicManager       $clinic_manager
             * @var DoctorManager       $doctor_manager
             * @var VisitManager        $visit_manager
             * @var AccountManager      $account_manager
             * @var VisitRatingManager  $visit_rating_manager
             */
            $doctor_review_manager = ModelManagerFactory::getByName('doctor_review');
            $clinic_review_manager = ModelManagerFactory::getByName('clinic_review');
            $clinic_manager        = ModelManagerFactory::getByName('clinic');
            $doctor_manager        = ModelManagerFactory::getByName('doctor');
            $visit_manager         = ModelManagerFactory::getByName('visit');
            $account_manager       = ModelManagerFactory::getByName('account');
            $visit_rating_manager  = ModelManagerFactory::getByName('visit_rating');

            foreach($data as $v)
            {
                $visit_id           = $v[1];
                $account_id         = $v[4];
                $doctor_id          = $v[7];
                $clinic_id          = $v[9];
                $doctor_review_text = $v[15];
                $clinic_review_text = $v[16];

                $doctor_review_text = preg_replace('/http:\/\/[A-Za-z0-9\-_\.\/]+/ims', '', $doctor_review_text);
                $clinic_review_text = preg_replace('/http:\/\/[A-Za-z0-9\-_\.\/]+/ims', '', $clinic_review_text);

                if(strlen($clinic_review_text) < 5)
                {
                    $clinic_review_text = NULL;
                }

                if($doctor_id && !$doctor_manager->getOneById($doctor_id))
                {
                    $doctor_id = NULL;
                }

                if($clinic_id && !$clinic_manager->getOneById($clinic_id))
                {
                    $clinic_id = NULL;
                }

                if($visit_id && !$visit_manager->getOneById($visit_id))
                {
                    $visit_id = NULL;
                }

                if($account_id && !$account_manager->getOneById($account_id))
                {
                    $account_id = NULL;
                }

                if(!$account_id || (!$doctor_id && !$clinic_id))
                {
                    continue;
                }

                if($visit_id)
                {
                    if(!$visit_rating_manager->getOneByVisitId($visit_id))
                    {
                        $visit_rating           = new VisitRatingModel();
                        $visit_rating->visit_id = $visit_id;

                        $fields = array(
                            'cabinet',
                            'waiting_time',
                            'relationship',
                            'value_for_money',
                            'diagnosis_is_clear',
                            'service_at_the_reception'
                        );

                        foreach($fields as $field)
                        {
                            $value = rand(0, 10);
                            if($value <= 8)
                            {
                                $visit_rating->{$field} = 5;
                            }
                            else
                            {
                                $visit_rating->{$field} = 4;
                            }
                        }

                        $visit_rating->is_doctor_advice = 1;
                        $visit_rating->is_clinic_advice = 1;
                        $visit_rating->save();
                    }
                }

                $add_doctor_review = TRUE;
                if(!$doctor_id || !$doctor_review_text)
                {
                    $add_doctor_review = FALSE;
                }

                if($visit_id && $doctor_review_manager->getOneByVisitIdAndAccountId($visit_id, $account_id))
                {
                    $add_doctor_review = FALSE;
                }

                if(!$visit_id && $doctor_review_manager->getOneByAccountIdAndDoctorId($account_id, $doctor_id))
                {
                    $add_doctor_review = FALSE;
                }

                if($add_doctor_review)
                {
                    $doctor_review               = new DoctorReviewModel();
                    $doctor_review->account_id   = $account_id;
                    $doctor_review->doctor_id    = $doctor_id;
                    $doctor_review->visit_id     = $visit_id;
                    $doctor_review->text         = $doctor_review_text;
                    $doctor_review->is_confirmed = 1;
                    $doctor_review->dt           = date('Y-m-d H:i:s', time() - rand(2, 30) * 24 * 60 * 60);
                    $doctor_review->save();
                }

                $add_clinic_review = TRUE;
                if(!$clinic_id || !$clinic_review_text)
                {
                    $add_clinic_review = FALSE;
                }

                if($visit_id && $clinic_review_manager->getOneByVisitIdAndAccountId($visit_id, $account_id))
                {
                    $add_clinic_review = FALSE;
                }

                if(!$visit_id && $clinic_review_manager->getOneByAccountIdAndClinicId($account_id, $clinic_id))
                {
                    $add_clinic_review = FALSE;
                }

                if($add_clinic_review)
                {
                    $clinic_review               = new ClinicReviewModel();
                    $clinic_review->account_id   = $account_id;
                    $clinic_review->clinic_id    = $clinic_id;
                    $clinic_review->visit_id     = $visit_id;
                    $clinic_review->is_confirmed = 1;
                    $clinic_review->dt           = DateHelper::now();
                    $clinic_review->text         = $clinic_review_text;
                    $clinic_review->save();
                }
            }
        }

        public function findClinics()
        {
            $excel_reader = new ExcelReader('./data/lpu.xlsx');
            $excel_data   = $excel_reader->readToArray();


            $clinic_finder = new ClinicFindManager();
            foreach($excel_data as &$v)
            {
                if($v[0] == '#')
                {
                    continue;
                }

                $name    = $v[1];
                $address = $v[2];

                if(!$name)
                {
                    continue;
                }

                echo $name . ' ' . $address . '<br />';
                $clinics = $clinic_finder->search($name, $address);
                if($clinics)
                {
                    $clinic = $clinics[0]['clinic'];
                    echo $clinics[0]['score'] . ' ' . $clinic->name . ' ' . $clinic->address;
                }
                else
                {
                    echo '-';
                }
                echo '<br />----------------------<br/><br />';


                $v[3] = (isset($clinics[0])) ? ClinicPageLinkViewHelper::getLink($clinics[0]['clinic']) : NULL;
                $v[4] = (isset($clinics[1])) ? ClinicPageLinkViewHelper::getLink($clinics[1]['clinic']) : NULL;
                $v[5] = (isset($clinics[2])) ? ClinicPageLinkViewHelper::getLink($clinics[2]['clinic']) : NULL;

            }

            $csv_generator = new CsvGenerator();

            $csv_data = $csv_generator->generateFromArray($excel_data);

            file_put_contents('./data/clinics.csv', $csv_data);
        }

        public function generateRobotsFileFromCities()
        {
            $sitePath       = dirname(dirname(dirname(__FILE__)));
            $robotsPath     = 'robots';
            $sitemapsPath   = 'sitemaps';
            $robotsFileName = 'robots_%s.txt';

            $city_manager = ModelManagerFactory::getByName('city');
            $cities       = $city_manager->getListWithClinicsOrDoctorsOrLaboratories();

            $insertData = array(
                'default' => array(
                    'User-agent' => array(
                        'Yandex' => array(
                            'Disallow: /*search*',
                            'Disallow: /*utm_',
                            'Disallow: /*_openstat',
                            'Disallow: /*?',
                            'Disallow: /*booking*',
                            'Disallow: /media/js/ajaxContent.js'
                        ),
                        '*'      => array(
                            'Disallow: /*search*',
                            'Disallow: /*utm_',
                            'Disallow: /*_openstat',
                            'Disallow: /*?',
                            'Disallow: /*booking*',
                            'Disallow: /media/js/ajaxContent.js'
                        )
                    ),
                    'Host'       => '%s.lookmedbook.ru'
                )
            );

            if(count($cities))
            {
                if(!file_exists($sitePath . '/' . $robotsPath))
                {
                    FileHelper::createFolder($robotsPath);
                }

                foreach($cities AS $cValue)
                {
                    $filePath   = $sitePath . '/' . $robotsPath . '/' . sprintf($robotsFileName, $cValue->alias);
                    $fileHandle = fopen($filePath, 'wb+');
                    if(isset($insertData[$cValue->alias]))
                    {
                        $dataArray = $insertData[$cValue->alias];
                    }
                    else
                    {
                        $dataArray = $insertData['default'];
                    }

                    $data = '';
                    if(count($dataArray))
                    {
                        foreach($dataArray AS $daKey => $daValue)
                        {
                            $blockName = $daKey;
                            $dataPart  = '';
                            if(is_array($daValue))
                            {
                                $fileName    = $cValue->alias . '.sitemap.xml';
                                $sitemapPath = $sitePath . '/' . $sitemapsPath . '/' . $fileName;

                                foreach($daValue AS $dvKey => $dvValue)
                                {
                                    $dataPart .= $blockName . ': ' . $dvKey . "\n\t";
                                    $dataPart .= implode("\n\t", $dvValue);

                                    if(file_exists($sitemapPath))
                                    {
                                        $dataPart .= "\n\tSitemap: " . SITE_SCHEME . "://" . $cValue->alias . '.lookmedbook.ru/' . $sitemapsPath . '/' . $fileName;
                                    }
                                    $dataPart .= "\n\n";
                                }


                            }
                            else
                            {
                                $dataPart .= $daKey . ': ' . sprintf($daValue, $cValue->alias);
                            }
                            $data .= $dataPart;
                        }
                    }

                    file_put_contents($filePath, $data, FILE_APPEND | LOCK_EX);
                }
            }

            exit;
        }

		public function generateDoctorXML() {
			ini_set("memory_limit", "512M");
			set_time_limit(0);

			$generator = new DoctorsGenerator();
			$generator->generate();

			exit();
		}

		public function updateCategoriesStatus()
		{
			ShopTaskManager::updateProductCategoriesActiveStatus();
		}


		public function exportDocDocServices()
        {
            $serviceManager = new ServiceCategoryManager();
            $services = $serviceManager->getList();
            $groupedServices = [];

            foreach ($services as $service) {
                $groupedServices[$service->id] = $service;
            }

            $header = [
                'docdoc_id',
                'Название',
                'docdoc_id родителя',
                'Название родителя',
                'docdoc_id корня',
                'Название корня',
            ];

            $newServices = $oldServices = [$header];

            $fold = fopen('helper-scripts/old.csv', 'w');
            $fnew = fopen('helper-scripts/new.csv', 'w');

            fputcsv($fold, $header, ';');
            fputcsv($fnew, $header, ';');

            foreach ($services as $service) {
                /** @var ServiceCategoryModel $service */

                if ($service->service_type != 'service' || !$service->docdoc_id) {
                    continue;
                }

                $v = [
                    $service->docdoc_id,
                    $service->name,
                ];

                if ($service->parent_id && isset($groupedServices[$service->parent_id])) {
                    $v[] = $groupedServices[$service->parent_id]->docdoc_id;
                    $v[] = $groupedServices[$service->parent_id]->name;
                } else {
                    $v[] = null;
                    $v[] = null;
                    $v[] = null;
                    $v[] = null;
                }

                $parentId = $service->parent_id;
                $parent = null;
                // check is old
                while ($parentId) {
                    if (!isset($groupedServices[$service->parent_id])) {
                        throw new Exception('Service is not available');
                    }
                    $parent = $groupedServices[$parentId];
                    $parentId = $parent->parent_id;
                }

                if ($parent) {
                    $v[] = $parent->docdoc_id;
                    $v[] = $parent->name;
                } else {
                    $v[] = null;
                    $v[] = null;
                }


                if (strpos($service->name, 'OLD') === 0 || $parent && strpos($parent->name, 'OLD') === 0) {
                    $oldServices[] = $v;
                    fputcsv($fold, $v, ';');
                } else {
                    $newServices[] = $v;
                    fputcsv($fnew, $v, ';');
                }
            }

            fclose($fold);
            fclose($fnew);
            exit();
        }

        public function importDocDocMapping()
        {
            $db = Register::get('db');
            $mapping = fopen('helper-scripts/mapping.csv', 'r');
            while (($data = fgetcsv($mapping)) !== false) {
                $len = count($data);
                if ($len == 9 && !empty($data[7]) && !empty($data[8])) {
                    echo $data[7], " ", $data[8], PHP_EOL;
                    // try to save
                    $query = "INSERT INTO docdoc_service_category_mapping (service_id, mapped_service_id) VALUES(?, ?)";
                    $db->query($query, [$data[7], $data[8]]);
                }
            }

            fclose($mapping);
            exit();
        }
    }
