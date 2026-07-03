<?php

class ImportController extends BaseController
{
    /**
     * @var Db
     */
    protected $db;
    protected $limit = 50;
    protected $clinicPictureInserted = 0;
    protected $clinicPictureUpdated = 0;
    protected $doctorDataUrl = 'https://lookmedbook:IzkmbB@api.docdoc.ru/public/rest/1.0.12/json/doctor/';
    protected $clinicListDataUrl = "https://lookmedbook:IzkmbB@api.docdoc.ru/public/rest/1.0.12/json/clinic/list/start/%d/count/%d/city/%d";
    protected $clinicFullInfoDataUrl = "https://lookmedbook:IzkmbB@api.docdoc.ru/public/rest/1.0.12/json/clinic/%d";
    protected $clinicGalleryDataUrl = "https://lookmedbook:IzkmbB@api.docdoc.ru/public/rest/1.0.12/json/clinic/gallery/%d";
    protected $cityRegionsDataUrl = "https://lookmedbook:IzkmbB@api.docdoc.ru/public/rest/1.0.12/json/district/city/%d";
    protected $servicesDataUrl = "https://lookmedbook:IzkmbB@api.docdoc.ru/public/rest/1.0.12/json/service/list";
    protected $diagnosticsDataUrl = "https://lookmedbook:IzkmbB@api.docdoc.ru/public/rest/1.0.12/json/diagnostic";
    protected $streetDataUrl = "https://lookmedbook:IzkmbB@api.docdoc.ru/public/rest/1.0.12/json/street/city/%d";
    protected $specialityDataUrl = "https://lookmedbook:IzkmbB@api.docdoc.ru/public/rest/1.0.12/json/speciality/city/%d/onlySimple/0";

    const SERVICE_TYPE_DIAGNOSTIC = "diagnostic";
    const SERVICE_TYPE_SERVICE = "service";

    const CITY_MOSCOW_REGION = 11;

    protected $clinicIdsToDeactivation;

    /**
     * Маппинг городов из БД на города DocDoc.ru
     * @var array
     */
    protected static $cityDocDocMapping = [
        1 => 2,   // Москва
        2 => 770, // Санкт-Петербург
        4 => 902, // Екатеринбург
        5 => 693, // Новосибирск
        6 => 738, // Пермь
        7 => 671, // Нижний Новгород
        8 => 489, // Казань
        9 => 768, // Самара
        11 => 2,  // Московская область
        13 => 875,// Уфа
        15 => 524,// Краснодар
        17 => 468,// Ростов-на-Дону
        19 => 957,// Челябинск
        21 => 63, // Воронеж
        23 => 613,// Ижевск
        24 => 525,// Красноярск
        26 => 797, // Сочи
        28 => 375, // Волгоград
        30 => 887, // Тюмень
        32 => 978, // Ярославль
    ];

    /**
     * @var array [ddCityId => [ddLineName => metro_branch.name]]
     */
    protected static $metroBranchDocDocMapping = [
        2 => [
            'Кировско-Выборгская' => 'Линия 1',
            'Московско-Петроградская' => 'Линия 2',
            'Невско-Василеостровская' => 'Линия 3',
            'Правобережная' => 'Линия 4',
            'Фрунзенская' => 'Линия 5',
        ],
        4 => ['Первая Екатеринбург' => 'Екб Линия 1 '],
        8 => ['Центральная линия' => 'Казань Центральная линия'],
        9 => ['Первая Самара' => 'Первая линия (Самара)'],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->db = Register::get('db');
    }

    /**
     * Получение всех или конкретных клиник по docdoc_id
     * @param type $docdoc_ids
     * @return type
     */
    private function _getClinics($docdoc_ids = false) {

        $manager = new ClinicManager();

        if($docdoc_ids) {
            $clinics = $manager->getListWithDocdocIdList($docdoc_ids);
        } else {
            $clinics = $manager->getListWithDocdocId();
        }

        return $clinics;
    }

    /**
     * Импорт клиник с DocDoc.ru по всем городам
     */
    public function allDocDoc()
    {
        $manager = new ClinicManager();
        $this->clinicIdsToDeactivation = $manager->getIdsWithDocDocIdByCityIds(self::$cityDocDocMapping);
        self::log("Total imported clinics: " . count($this->clinicIdsToDeactivation));
        $this->mapToDocDocSpecialities();
        foreach (self::$cityDocDocMapping as $docDocCityId=>$dbCityId) {
            self::log("Starting import clinics from cityId=$docDocCityId");
            $this->docDocByCityId($docDocCityId);
            self::log("Finished import clinics from cityId=$docDocCityId");
        }

        if ($this->clinicIdsToDeactivation) {
            $absentClinicsCount = $this->deactivateClinics($manager, $this->clinicIdsToDeactivation);
            self::log("Deactivated $absentClinicsCount clinics");
        }
    }

    /**
     * Импорт клиник с DocDoc.ru по всем городам
     */
    public function clinicServices()
    {
        foreach (self::$cityDocDocMapping as $docDocCityId=>$dbCityId) {
            self::log("Starting import clinics' services from cityId=$docDocCityId");
            $startFrom = 0;
            $totalCount = 1;
            $updatedClinicsCount = 0;

            while ($startFrom < $totalCount) {
                self::log("Importing {$this->limit} clinics from $startFrom...");
                try {
                    $clinics = json_decode(file_get_contents(sprintf($this->clinicListDataUrl, $startFrom, $this->limit, $docDocCityId)), true);
                    $totalCount = $clinics['Total'];
                    $docDocClinics = $clinics['ClinicList'];

                    $startFrom += $this->limit;
                    self::log("Total {$totalCount} clinics");
                } catch (\Exception $t) {
                    continue;
                }

                $docDocIds = array_column($docDocClinics, 'Id');
                $docDocClinics = array_combine($docDocIds, $docDocClinics);

                $clinicsToUpdate = $this->_getClinics($docDocIds);

                if (!empty($clinicsToUpdate)) {

                    $buf = [];
                    foreach ($clinicsToUpdate as $clinic) {
                        $buf[$clinic->docdoc_id] = [
                            $clinic,
                            $docDocClinics[$clinic->docdoc_id]
                        ];
                    }

                    $clinicsToUpdate = $buf;
                }

                foreach ($clinicsToUpdate as $items) {
                    $dbClinic = $items[0];
                    $docDocClinic = $items[1];

                    self::log("Updating #$updatedClinicsCount clinic {$docDocClinic['Name']}...");

                    if (isset($docDocClinic['Diagnostics'])) {
                        $this->importClinicServices($dbClinic->id, $docDocClinic['Diagnostics'], static::SERVICE_TYPE_DIAGNOSTIC);
                    }

                    if (isset($docDocClinic['Services']['ServiceList'])) {
                        $this->importClinicServices($dbClinic->id, $docDocClinic['Services']['ServiceList'], static::SERVICE_TYPE_SERVICE);
                    }

                    $updatedClinicsCount++;
                }
            }

            self::log("Finished import clinics' services from cityId=$docDocCityId");
        }
    }
    public function testFaceClinic()
    {
        set_time_limit(0);
        $clinicManager = new ClinicManager();
        $dbClinic = $clinicManager->getOneById(11552);

        if (!$dbClinic || !$dbClinic->docdoc_id) {
            echo "Clinic 11552 not found or no docdoc_id\n";
            return;
        }

        try {
            $fullClinicInfo = json_decode(file_get_contents(sprintf($this->clinicFullInfoDataUrl, $dbClinic->docdoc_id)), true);
            $fullClinicInfo = $fullClinicInfo['Clinic'][0];
        } catch (\Exception $exception) {
            echo "Failed to fetch full clinic info\n";
            return;
        }

        $docDocClinic = [
            'Id' => $fullClinicInfo['Id'],
            'Description' => $fullClinicInfo['Description'] ?? '',
            'Name' => $fullClinicInfo['Name'],
            'URL' => $fullClinicInfo['URL'] ?? '',
            'IsDoctor' => $fullClinicInfo['IsDoctor'] ?? 'yes',
            'IsDiagnostic' => $fullClinicInfo['IsDiagnostic'] ?? 'yes',
            'Latitude' => $fullClinicInfo['Latitude'] ?? '',
            'Longitude' => $fullClinicInfo['Longitude'] ?? '',
            'House' => $fullClinicInfo['House'] ?? '',
            'StreetId' => $fullClinicInfo['StreetId'] ?? '',
            'Phone' => $fullClinicInfo['Phone'] ?? '',
            'Logo' => $fullClinicInfo['Logo'] ?? '',
            'Rating' => $fullClinicInfo['Rating'] ?? 0,
            'Stations' => $fullClinicInfo['Stations'] ?? []
        ];

        // This will update the clinic, import doctors for it, and download ALL images that are missing or broken
        $this->createOrUpdateClinic($dbClinic, $docDocClinic, $dbClinic->city_id, true);

        echo "Clinic {$dbClinic->id} import complete!\n";
    }
    /**
     * Импорт клиник с DocDoc.ru по конкретному городу
     * @param $cityId
     */
    public function docDocByCityId($cityId)
    {
        $updatedClinicsCount = 0;
        $updatedCorrelatedClinicsCount = 0;

        $startFrom = 0;
        $totalCount = 1;

        $createdClinicsCount = 0;
        $clinicsRequireDoctorWorkingTimeFlagsUpdate = [];
        while ($startFrom < $totalCount) {
            self::log("Importing {$this->limit} clinics from $startFrom...");
            try {
                $clinics = json_decode(file_get_contents(sprintf($this->clinicListDataUrl, $startFrom, $this->limit, $cityId)), true);
                $totalCount = $clinics['Total'];
                $docDocClinics = $clinics['ClinicList'];

                $startFrom += $this->limit;
                self::log("Total {$totalCount} clinics");
            } catch (\Exception $t) {
                continue;
            }

            if (!empty($docDocClinics)) {
                $docDocIds = array_column($docDocClinics, 'Id');
                $docDocClinics = array_combine($docDocIds, $docDocClinics);

                $clinicsToUpdate = $this->_getClinics($docDocIds);

                if (!empty($clinicsToUpdate)) {

                    $buf = [];
                    foreach ($clinicsToUpdate as $clinic) {
                        $buf[$clinic->docdoc_id] = [
                            $clinic,
                            $docDocClinics[$clinic->docdoc_id]
                        ];
                    }

                    $clinicsToUpdate = $buf;
                }

                $toCreate = array_diff_key($docDocClinics, $clinicsToUpdate);
                $counter = 0;
                /** @var ClinicModel $dbClinic */
                /** @var array $docDocClinic */
                foreach ($clinicsToUpdate as list($dbClinic, $docDocClinic)) {
                    self::log("Updating #$updatedClinicsCount clinic {$docDocClinic['Name']}...");
                    $loadImage = $counter === date('N') - 1;
                    $this->createOrUpdateClinic($dbClinic, $docDocClinic, $cityId, $loadImage);
                    if ($dbClinic->isDoctorWorkingTimeFlagsUpdateRequired()) {
                        $clinicsRequireDoctorWorkingTimeFlagsUpdate[] =
                            $dbClinic->getId();
                    }
                    $updatedClinicsCount++;
                    $counter = ($counter + 1) % 7;
                    unset($this->clinicIdsToDeactivation[$dbClinic->getId()]);
                    self::log("Clinic was updated");
                }
                foreach ($toCreate as $docDocClinic) {
                    // 4493 Не обновлять "Медицина" с docdoc
                    if ($docDocClinic['Id'] == 1293) {
                        self::log("Skipped clinic {$docDocClinic['Name']}.");
                        continue;
                    }
                    $dbClinic = $this->getCorrelatedClinic($docDocClinic);
                    if (!$dbClinic) {
                        self::log("Creating #$createdClinicsCount clinic {$docDocClinic['Name']}...");
                        $dbClinic = new ClinicModel();
                        $dbClinic->is_import_enabled = 1;
                        $dbClinic->is_import_of_metro_stations_enabled = 1;
                        $createdClinicsCount++;
                    } else {
                        self::log("Updating #$updatedClinicsCount clinic {$docDocClinic['Name']}...");
                        $updatedClinicsCount++;
                        $updatedCorrelatedClinicsCount++;
                        unset($this->clinicIdsToDeactivation[$dbClinic->id]);
                    }
                    $this->createOrUpdateClinic($dbClinic, $docDocClinic, $cityId);
                    if ($dbClinic->isDoctorWorkingTimeFlagsUpdateRequired()) {
                        $clinicsRequireDoctorWorkingTimeFlagsUpdate[] =
                            $dbClinic->getId();
                    }
                    self::log("Clinic was created");
                }
            }
        }
        unset($clinics, $docDocClinics, $clinicsToUpdate);

        if ($this->clinicIdsToDeactivation) {
            $clinicsRequireDoctorWorkingTimeFlagsUpdate = array_merge(
                $clinicsRequireDoctorWorkingTimeFlagsUpdate,
                $this->clinicIdsToDeactivation
            );
        }
        $clinicManager = new ClinicManager();
        $clinics = $clinicManager
            ->getListByIds(array_filter($clinicsRequireDoctorWorkingTimeFlagsUpdate));
        if ($clinics) {
            $clinicManager->updateDoctorWorkingTimeFlags($clinics);
        }

        flush();
        self::log("UPDATE : {$updatedClinicsCount} ; UPDATE CORRELATED : {$updatedCorrelatedClinicsCount}; ");
        self::log("INSERT : {$createdClinicsCount}");
    }

    public function migrate() {
	    $imageManager = new ImageManager();

		$data =	$imageManager->getAll();
        // do {
	//$doctors = $doctorManager->getActiveList();


	for ($i = 0; $i < count($data); $i++){
//		$doctor = $doctors[$i];


		$image = $data[$i];
		  
		$path = $image["folder"].$image["filename"];

		$pwd =  getcwd();


		$full_path = $pwd . '/media/upload/' . $path;

		$isExist = file_exists($full_path);

		if (!$isExist){
			echo $full_path ."\n";
			$file = file_get_contents('https://lookmedbook.ru/media/upload/'  . $path);
			if (!$file) {
				echo 'Error get file';
			}else {
			
				file_put_contents($full_path, $file);
				//break;
			}

		}else {
			echo 'image '.$full_path." exist\n";
		}   
	
	} 
    }



    /**
     * @param $clinic
     * @return mixed ключ - ID клиники в DB, значение - ID клиники в DocDoc
     */
    public function getCorrelatedClinic($clinic)
    {
        return null; // Отключено в рамках 4229, хотфикс
        $suitableClinic = null;
        /**
         * @var ClinicManager $clinicManager
         */
        $clinicManager = ModelManagerFactory::getByName('clinic');
        $params = new ClinicSearchParams();
        $params->distance = 50;
        $params->geo_point = new GeoPoint($clinic['Latitude'], $clinic['Longitude']);
        $params->sort_by = "geo_point";
        $foundClinics = $clinicManager->getListByClinicSearchParams($params);
        if (!empty($foundClinics)) {
            foreach ($foundClinics as $foundClinic) {
                if (!$foundClinic->docdoc_id) {
                    $suitableClinic = $foundClinic;
                    break;
                }
            }
        }

        return $suitableClinic;
    }

    /**
     * @param ClinicModel $dbClinic
     * @param $docDocClinic
     * @param $cityId
     */
    public function createOrUpdateClinic($dbClinic, $docDocClinic, $cityId, $loadImage = true)
    {
       // IMAGE GET CONTEXT
	 $opts = [
  'http' => [
    'method' => "GET",
    'header' => implode("\n", [
    	   'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:144.0) Gecko/20100101 Firefox/144.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding: gzip, deflate, br, zstd',
            'Connection: keep-alive',
            'Upgrade-Insecure-Requests: 1',
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: none',
            'Sec-Fetch-User: ?1',
            'Priority: u=0, i',
            'Pragma: no-cache',
            'Cache-Control: no-cache'
    ])
  ]
];

$context = stream_context_create($opts);

        static $clinicManager = false;
        static $doctorManager = false;
        if ($clinicManager === false) {
            $clinicManager = new ClinicManager();
            $clinicManager->unsetEventCallback(
                ClinicManager::EVENT_AFTER_SAVE,
                ClinicManager::CB_UPDATE_DOCTOR_WORKING_TIME_FLAGS
            );
        }
        if ($doctorManager === false) {
            $doctorManager = new DoctorManager();
        }

        $docDocId = $docDocClinic['Id'];

        try {
            $fullClinicInfo = json_decode(file_get_contents(sprintf($this->clinicFullInfoDataUrl, $docDocId)), true);
            $fullClinicInfo = $fullClinicInfo['Clinic'][0];
        } catch (\Exception $exception) {
            return;
        }

        $dbClinic->disableFilter(); // оптимизация

        if (!$dbClinic->is_import_enabled) {
            self::log("Clinic was excluded from updating");
            return;
        }

        $dbClinic->is_active = true;
        $dbClinic->about = XssHelper::check($docDocClinic['Description']);

        $dbClinic->docdoc_id = $docDocClinic['Id'];
        $dbClinic->full_name = XssHelper::check($docDocClinic['Name']);
        $dbClinic->name = XssHelper::check($docDocClinic['Name']);
        $dbClinic->site = XssHelper::check($docDocClinic['URL']);
        $docDocClinic['IsDoctor'] === 'yes' ?
            $dbClinic->type_flags |= ClinicModel::TYPE_FLAG_DOCTOR :
            $dbClinic->type_flags &= ~ClinicModel::TYPE_FLAG_DOCTOR;
        $docDocClinic['IsDiagnostic'] === 'yes' ?
            $dbClinic->type_flags |= ClinicModel::TYPE_FLAG_DIAGNOSTIC_CENTER :
            $dbClinic->type_flags &= ~ClinicModel::TYPE_FLAG_DIAGNOSTIC_CENTER;
        $fullClinicInfo['OnlineRecordDoctor'] ?
            $dbClinic->type_flags |= ClinicModel::TYPE_FLAG_ONLINE_RECORD :
            $dbClinic->type_flags &= ~ClinicModel::TYPE_FLAG_ONLINE_RECORD;

        if(!empty($docDocClinic['Latitude']) && !empty($docDocClinic['Longitude'])){
            $dbClinic->latitude = $docDocClinic['Latitude'];
            $dbClinic->longitude = $docDocClinic['Longitude'];
        }

        $dbClinic->city_id = self::$cityDocDocMapping[$cityId];

        // импорт адреса
        if(!empty($docDocClinic['House']) && !empty($docDocClinic['StreetId'])) {
            if ($cityId == self::CITY_MOSCOW_REGION) {
                // Для МО вставляем город в адрес
                if (!empty($docDocClinic['Street'])) {
                    $regionInfo = $this->getRegionsInfoByDocDocId($docDocClinic['DistrictId'], $cityId);
                    $dbClinic->address = XssHelper::check($docDocClinic['Street'] . ', ' . $docDocClinic['House']);
                    if (!empty($regionInfo['Name'])) {
                        $dbClinic->address = XssHelper::check($regionInfo['Name']) . ', ' . $dbClinic->address;
                        $dbClinic->street_id = null;
                    }
                }
            } else {
                $street = $this->getStreetByDocDocId($docDocClinic['StreetId']);
                if ($street) {
                    $dbClinic->address = XssHelper::check($street->name . ', ' . $docDocClinic['House']);
                    $dbClinic->street_id = $street->id;
                }
            }
        }

        $dbClinic->about = XssHelper::check($docDocClinic['Description']);

        // phones
        $dbClinic->phone = XssHelper::check($docDocClinic['Phone']);
        $dbClinic->direct_phone = XssHelper::check($docDocClinic['PhoneAppointment']);
        $dbClinic->phone = XssHelper::check($docDocClinic['ReplacementPhone']);

        $dbClinic->region_id = $this->getRegionIdByDocDocId($docDocClinic['DistrictId'], $cityId);
        $dbClinic->email = XssHelper::check($docDocClinic['Email']);

        // парсинг расписания работы
        if (isset($docDocClinic['Schedule'])) {
            foreach ($docDocClinic['Schedule'] as $day) {
                $startTime = XssHelper::check($day['StartTime']);
                $endTime = XssHelper::check($day['EndTime']);
                switch ($day['Day']) {
                    case '0':
                        $dbClinic->start_time_monday = $startTime;
                        $dbClinic->start_time_tuesday= $startTime;
                        $dbClinic->start_time_wednesday = $startTime;
                        $dbClinic->start_time_thursday = $startTime;
                        $dbClinic->start_time_friday = $startTime;

                        $dbClinic->end_time_monday = $endTime;
                        $dbClinic->end_time_tuesday = $endTime;
                        $dbClinic->end_time_wednesday = $endTime;
                        $dbClinic->end_time_thursday = $endTime;
                        $dbClinic->end_time_friday = $endTime;
                        break;
                    case '6':
                        $dbClinic->start_time_saturday = $startTime;
                        $dbClinic->end_time_saturday = $endTime;
                        break;
                    case '7':
                        $dbClinic->start_time_sunday = $startTime;
                        $dbClinic->end_time_sunday = $endTime;
                        break;
                }
            }
        }

        $dbClinic->rate = (float) $docDocClinic['Rating'] / 2;

        $clinicManager->save($dbClinic);

        if (isset($docDocClinic['Diagnostics'])) {
            $this->importClinicServices($dbClinic->id, $docDocClinic['Diagnostics'], static::SERVICE_TYPE_DIAGNOSTIC);
        }

        if (isset($docDocClinic['Services']['ServiceList'])) {
            $this->importClinicServices($dbClinic->id, $docDocClinic['Services']['ServiceList'], static::SERVICE_TYPE_SERVICE);
        }


        if ($loadImage) {
            $this->importClinicImages($docDocId, $dbClinic->id);
        }

        if ($loadImage && isset($docDocClinic['Logo'])) {

            $needsDownload = true;
            if ($dbClinic->image_id) {
                $imageManager = new ImageManager();
                $existingImage = $imageManager->getOneById($dbClinic->image_id);
                if ($existingImage) {
                    $existingPath = ABS_ROOT . $existingImage->path;
                    if (file_exists($existingPath) && getimagesize($existingPath) !== false) {
                        $needsDownload = false;
                    }
                }
            }

            if ($needsDownload) {
                $tmp_name = ABS_ROOT . '/media/upload/clinic/tmp_clinic_' . $dbClinic->docdoc_id . '.jpg';
                self::log("tmp file name:" . $tmp_name);
                if (file_exists($tmp_name)) {
                    @unlink($tmp_name);
                }

                $data = file_get_contents($docDocClinic['Logo'], false, $context);

                // продолжаем процесс только если картинка сохранена
                if (false !== file_put_contents($tmp_name, $data)) {
                    if (getimagesize($tmp_name) !== false) {
                        $imageIds = [];
                        if ($dbClinic->image_id) {
                            $imageIds[] = $dbClinic->image_id;
                        }
                        if ($dbClinic->card_image_id) {
                            $imageIds[] = $dbClinic->card_image_id;
                        }
                        if ($imageIds) {
                            $imageManager = new ImageManager();
                            /** @var ImageModel[] $images */
                            $images = $imageManager->getListByIds($imageIds);
                            foreach ($images as $image) {
                                $image->delete();
                                @unlink(ABS_ROOT . $image->path);
                            }
                        }

                        chmod($tmp_name , 0755);

                        $image_id = ImageUploader::upload(['upload_folder' => 'clinic/logo/'], ['name' => 'clinic_'.$dbClinic->docdoc_id.'.jpg', 'tmp_name' => $tmp_name], 'clinic_'.$dbClinic->docdoc_id);

                        $dbClinic->image_id = $image_id;
                        $dbClinic->card_image_id = $image_id;

                        if (file_exists($tmp_name)) {
                            @unlink($tmp_name);
                        }
                    } else {
                        if (file_exists($tmp_name)) {
                            @unlink($tmp_name);
                        }
                    }
                }
            }
        }

        // import metro stations
        if ($dbClinic->is_import_of_metro_stations_enabled) {
            (new MetroStationToClinicManager())->deleteByClinicId($dbClinic->id);
            $stationIds = [];
            foreach ($docDocClinic['Stations'] as $stationData) {
                $fullStationData = current(array_filter(
                    $fullClinicInfo['Stations'],
                    function ($s) use ($stationData) {
                        return $stationData['Id'] === $s['Id'];
                    }
                ));
                if (is_array($stationData) && is_array($fullStationData)) {
                    $station = $this->findOrCreateMetroStation($stationData + $fullStationData);
                    if ($station) {
                        $stationIds[$station->id] = $station->id;
                    }
                }
            }
            if ($stationIds) {
                $dbClinic->metro_station_id = reset($stationIds);
                $metroStationToClinic = new MetroStationToClinicModel();
                $metroStationToClinic->clinic_id = $dbClinic->id;
                foreach ($stationIds as $stationId) {
                    $msc = clone $metroStationToClinic;
                    $msc->metro_station_id = $stationId;
                    $msc->save();
                }
            }
        }

        // get old doctors
        $oldDoctors = [];
        foreach ($doctorManager->getListByClinicId($dbClinic->id) as $bufDoctor) {
            $oldDoctors[$bufDoctor->id] = $bufDoctor;
        }

        // todo искать докторов по docdoc_id, если не найден, то искать по ФИО без докдокид, брать первого попавшегося.
        // todo что делать с делетед докторами? можно хранить docdoc_id
        $clinic_specialty = [];

        $q = "delete from doctor_to_clinic where clinic_id=?";
        $this->db->query($q, [$dbClinic->id]);

        $q = "delete from doctor_specialty_to_clinic where clinic_id=?";
        $this->db->query($q, [$dbClinic->id]);

        foreach ($fullClinicInfo['Doctors'] as $doc_id){
            try {
                $s = file_get_contents($this->doctorDataUrl . $doc_id . '/withSlots/1');
                $docdata = json_decode($s);
                if (!isset($docdata->Doctor[0])) {
                    continue;
                }
                $docdata = $docdata->Doctor[0];
            } catch (Exception $exp) {
                continue;
            }

            $docdata->Name = XssHelper::check($docdata->Name);
            $names = explode(' ', $docdata->Name);

            $last_name = isset($names[0]) ? trim($names[0]) : "";
            $first_name = isset($names[1]) ? trim($names[1]) : "";
            $second_name = isset($names[2]) ? trim($names[2]) : "";

            // Checking if the doctor was deleted
            $deletedDoctor = (new DeletedDoctorManager())->getListByQuery("
                    SELECT * FROM deleted_doctor 
                    WHERE docdoc_id=? OR (
                      docdoc_id IS NULL AND (
                        first_name LIKE ? AND second_name LIKE ? AND last_name LIKE ?
                      )
                    ) ORDER BY docdoc_id DESC limit 1",
                [$docdata->Id, $last_name, $first_name, $second_name]
            );

            if( empty( $deletedDoctor) ){

                $doctor = $doctorManager->getOneByDocDocId($docdata->Id);

                if (!$doctor) {
                    $doctor = $doctorManager->getOneByNameWithoutDocdocId($first_name, $second_name, $last_name);
                }

                if ($doctor) {
                    $isNewDoctor = false;
                    unset($oldDoctors[$doctor->id]);
                    self::log("doctor: $doctor->full_lower_name ($doctor->id)");
                } else {
                    $isNewDoctor = true;
                    $doctor = new DoctorModel();
                    self::log("new doctor: $docdata->Name");

                    $doctor->is_children = !empty($docdata->KidsReception) ? 1 : 0;
                    $doctor->is_adult = !empty($docdata->KidsReception) ? 0 : 1;
                    $doctor->is_pregnant = !empty($docdata->KidsReception) ? 0 : 1;
                    $doctor->is_handicapped = 1;
                }

                $doctor->last_name = $last_name;
                $doctor->first_name = $first_name;
                $doctor->second_name = $second_name;
                $price = $docdata->Price > 0 ? (float) $docdata->Price : '';
                if (isset($docdata->SpecialPrice) && $docdata->SpecialPrice > 0) {
                    $price = (float)$docdata->SpecialPrice;
                }

                $doctor->full_lower_name = strtolower($docdata->Name);
                $doctor->sex_id = ($docdata->Sex == 1) ? 2 : 1;
                $doctor->rate = (float) $docdata->Rating;
                $doctor->work_experience = 0;
                $doctor->advice_rate = (float) $docdata->Rating;
                $doctor->cabinet_rate = (float) $docdata->Rating;
                $doctor->relationship_rate = (float) $docdata->Rating;
                $doctor->value_for_money_rate = (float) $docdata->Rating;
                $doctor->diagnosis_is_clear_rate = (float) $docdata->Rating;
                $doctor->about = XssHelper::check($docdata->Description);
                $doctor->is_active = 1;
                $doctor->is_active_updated_at = time();
                $doctor->availability = 1;
                $doctor->is_has_morning_time = 1;
                $doctor->is_has_evening_time = 1;
                $doctor->is_has_weekend_time = 1;
                $doctor->is_leave_the_house = !empty($docdata->Departure) ? 1 : 0;
                $doctor->docdoc_id = $doc_id;
                $doctor->disableFilter();
                $doctor->save();

                foreach ($docdata->Specialities as $specialty) {
                    $specialtyManager = new SpecialtyManager();
                    $t = $specialtyManager->getOneByAliasOrSyninim($specialty->Alias)
                        ?: $specialtyManager->getOneByDocDocId($specialty->Id);
                    if (!$t){
                        self::log("specialty {$specialty->Name} ($specialty->Alias) not found");
                        continue;
                    }

                    $specialty = $t;
                    $clinic_specialty[$specialty->id] = $specialty;

                    $q = "select id from doctor_to_clinic where clinic_id=? and doctor_id=? and specialty_id=?";

                    $params = [
                        $dbClinic->id,
                        $doctor->id,
                        $specialty->id
                    ];

                    if (!$this->db->query($q, $params)) {
                        $q = "insert into doctor_to_clinic set clinic_id=?, doctor_id=?, specialty_id=?". ($price ? " , first_visit_price=?" : '');
                        $params = [
                            $dbClinic->id,
                            $doctor->id,
                            $specialty->id,
                            $price
                        ];
                        $this->db->query($q, $params);
                    }

                    $q = "insert into doctor_specialty_to_clinic set clinic_id=?, doctor_id=?, specialty_id=?";
                    $params = [
                        $dbClinic->id,
                        $doctor->id,
                        $specialty->id,
                    ];
                    $this->db->query($q, $params);
                }

		
                $needsDownload = true;
                if ($doctor->image_id) {
                    $imageManager = new ImageManager();
                    $existingImage = $imageManager->getOneById($doctor->image_id);
                    if ($existingImage) {
                        $existingPath = ABS_ROOT . $existingImage->path;
                        if (file_exists($existingPath) && getimagesize($existingPath) !== false) {
                            $needsDownload = false;
                        }
                    }
                }

                if ($needsDownload && $docdata->Img) {
                    $tmp_name = ABS_ROOT.'/media/upload/clinic/license/tmp_doctor_'.$doctor->id.'.jpg';
                    self::log("tmp file name:".$tmp_name);
                    if (file_exists($tmp_name)) {
                        @unlink($tmp_name);
                    }

                    // продолжаем процесс только если картинка сохранена
                    if(false !== file_put_contents($tmp_name, file_get_contents($docdata->Img, false, $context))) {
                        if (getimagesize($tmp_name) !== false) {
                            print_r($docdata->Img);
                            $imageIds = [];
                            if ($doctor->image_id) {
                                $imageIds[] = $doctor->image_id;
                            }
                            if ($doctor->card_image_id) {
                                $imageIds[] = $doctor->card_image_id;
                            }
                            if ($imageIds) {
                                $imageManager = new ImageManager();
                                /** @var ImageModel[] $images */
                                $images = $imageManager->getListByIds($imageIds);
                                foreach ($images as $image) {
                                    $image->delete();
                                    @unlink(ABS_ROOT . $image->path);
                                }
                            }

                            chmod($tmp_name , 0755);

                            $image_id = ImageUploader::upload(['upload_folder' => 'clinic/license/'], ['name' => 'doctor_'.$doctor->id.'.jpg', 'tmp_name' => $tmp_name], 'doctor_'.$doctor->id);
                            echo 'image_id = '.$image_id;
                            $doctor->image_id = $image_id;
                            $doctor->card_image_id = $image_id;

                            if (file_exists($tmp_name)) {
                                @unlink($tmp_name);
                            }

                            $q = "delete from image_to_doctor where doctor_id=?";
                            $this->db->query($q, [$doctor->id]);

                            $q = "insert into image_to_doctor set doctor_id=?, image_id=?";
                            $this->db->query($q, [$doctor->id, $image_id]);
                        } else {
                            echo 'скачанный файл не является картинкой';
                            if (file_exists($tmp_name)) {
                                @unlink($tmp_name);
                            }
                        }
                    } else {
                        echo 'картинка не сохранена';
                    }
                } else {
                    echo 'pass';
                }
		
                $doctor->save();
            }
        }
        $specialty_ids = [];
        foreach ($clinic_specialty as $specialty){
            $specialty_ids[$specialty->id] = $specialty->id;
        }

        if ($specialty_ids){
            $q = "select DISTINCT specialization_id from specialty_to_specialization where specialty_id in (".implode(',', $specialty_ids).")";
            $specializations = $this->db->query($q);
            foreach ($specializations as $specialization){
                $specialization_id = $specialization['specialization_id'];

                if (!$specialization_id)
                    continue;

                $params = [$dbClinic->id, $specialization_id];
                $q = "select id from specialization_to_clinic where clinic_id=? and specialization_id=?";
                if (!$this->db->query($q, $params)){
                    $q = "insert into specialization_to_clinic set clinic_id=?, specialization_id=?";
                    $this->db->query($q, $params);
                }
            }
        }

        self::log("Reindex old doctors");
        foreach ($oldDoctors as $oldDoctor) {
            ElasticaTask::indexDoctor($oldDoctor->id);
        }

        $clinicManager->save($dbClinic);
    }

    protected function findOrCreateMetroStation($stationData)
    {
        if (empty($stationData['CityId'])) {
            self::log('Unknown metro station: ' . print_r($stationData, true));
            return null;
        }
        $branchName = isset(self::$metroBranchDocDocMapping[$stationData['CityId']][$stationData['LineName']])
            ? self::$metroBranchDocDocMapping[$stationData['CityId']][$stationData['LineName']]
            : $stationData['LineName'];
        /** @var MetroStationManager $stationManager */
        $stationManager = ModelManagerFactory::getByName('metro_station');
        $station = $stationManager->getOneByNameAndBranchName($stationData['Name'], $branchName);
        if (!$station) {
            /** @var MetroBranchManager $branchManager */
            $branchManager = ModelManagerFactory::getByName('metro_branch');
            $branch = $branchManager->getOneByName($branchName);
            if (!$branch) {
                self::log("Unknown metro line: '{$stationData['LineName']}'");
                return null;
            }
            $station = new MetroStationModel();
            $station->name = $stationData['Name'];
            $station->metro_branch_id = $branch->id;
            $station->longitude = $stationData['Longitude'];
            $station->latitude = $stationData['Latitude'];
            $station->save();
        }
        return $station;
    }

    public function importClinicServices($dbClinicId, $services, $type)
    {
        self::log("Starting import {$type}...");

        $serviceManager = new ServiceToClinicManager();

        $oldServices = $serviceManager->getListByClinicId($dbClinicId, $type);
        $groupedByDocDoc = [];
        foreach ($oldServices as $oldService) {
            $groupedByDocDoc[$oldService->docdoc_id] = $oldService;
        }

        $newServices = [];
        foreach ($services as $service) {
            $ddServiceId = null;

            if (isset($service['Id'])) {
                $ddServiceId = $service['Id'];
            } elseif ($service['ServiceId']) {
                $ddServiceId = $service['ServiceId'];
            }

            if (!$ddServiceId) {
                continue;
            }

            $serviceId = $this->getDocdocMappedService($ddServiceId);
            if (!$serviceId) {
                $serviceId = $ddServiceId;
            }

            $dbService = $this->getServiceByDocDocId($serviceId, $type);
            if ($dbService) {
                if (isset($groupedByDocDoc[$serviceId])) {
                    $clinicService = $groupedByDocDoc[$serviceId];
                } else {
                    // try to save
                    $clinicService = new ServiceToClinicModel();
                }
                $clinicService->clinic_id = $dbClinicId;
                $clinicService->service_id = $dbService->id;
                if (isset($service['Price'])) {
                    $clinicService->price = $service['Price'];
                }

                $clinicService->disableFilter();
                if (!$clinicService->save()) {
                    self::log("Save error: id={$serviceId}. The clinic's {$type} wasn't saved. Skip...");
                    continue;
                }
                $newServices[$serviceId] = $serviceId;
                self::log("The {$type} id={$serviceId} was saved");
            } else {
                self::log("The {$type} id={$serviceId} wasn't found. Skip...");
            }
        }

        // remove
        $toDelete = array_diff_key($groupedByDocDoc, $newServices);
        foreach ($toDelete as $item) {
            /** @var ServiceToClinicModel $item */
            $item->delete();
        }

        $deletedCount = count($toDelete);

        self::log("{$deletedCount} {$type} was deleted");
        self::log("Import {$type} finished");
    }

    /**
     * @param $docDocId
     * @param $type
     * @return ServiceCategoryModel|null
     */
    public function getServiceByDocDocId($docDocId, $type)
    {
        static $dbServices = false;
        if ($dbServices === false) {
            $serviceManager = new ServiceCategoryManager();
            $buf = $serviceManager->getList();
            $dbServices = [];
            foreach ($buf as $item) {
                /** @var ServiceCategoryModel $item */
                if ($item->docdoc_id) {
                    $dbServices[$item->docdoc_id . $item->service_type] = $item;
                }
            }
        }

        $key = $docDocId . $type;

        return isset($dbServices[$key]) ? $dbServices[$key] : null;
    }

    public function importClinicImages($docDocClinicId, $dbClinicId)
    {
        try {
            $pictures = json_decode(file_get_contents(sprintf($this->clinicGalleryDataUrl, $docDocClinicId)), true);
            $pictures = $pictures['ImageList'];
        } catch (\Exception $exception) {
            return;
        }

        $imageManager = new ImageManager();

        /** @var ImageModel[] $clinicImages */
        $clinicImages = $imageManager->getListByClinicId($dbClinicId);
        foreach ($clinicImages as $clinicImage) {
            $clinicImage->delete();
            @unlink(ABS_ROOT . $clinicImage->path);
        }

        $imageIds = [];
        foreach ($pictures as $idx => $picture) {
            if (isset($picture['url'])){
                $tmpName = ABS_ROOT.'/media/upload/clinic/license/tmp_clinic_'.$docDocClinicId . '_' . $idx .'.jpg';
                self::log("tmp file name:" . $tmpName);
                if (file_exists($tmpName)) {
                    @unlink($tmpName);
                }

                // продолжаем процесс только если картинка сохранена
                if(false !== file_put_contents($tmpName, file_get_contents($picture['url']))){
                    if (getimagesize($tmpName) !== false) {
                        chmod($tmpName , 0755);
                        $imageId = ImageUploader::upload(['upload_folder' => 'clinic/license/'], ['name' => 'clinic_' . $dbClinicId . '_' . $idx . '.jpg', 'tmp_name' => $tmpName], 'clinic_'.$dbClinicId . '_' . $idx);
                        $imageIds[] = $imageId;

                        if (file_exists($tmpName)) {
                            @unlink($tmpName);
                        }
                    } else {
                        if (file_exists($tmpName)) {
                            @unlink($tmpName);
                        }
                    }
                }
            }
        }

        $q = "delete from image_to_clinic where clinic_id=?";
        $this->db->query($q, [$dbClinicId]);

        if ($imageIds) {
            $q = "INSERT INTO image_to_clinic (clinic_id, image_id) VALUES ";
            foreach ($imageIds as $imageId) {
                $q .= '(' . (int)$dbClinicId . ',' . (int)$imageId . '),';
            }
            $q = rtrim($q, ',');

            $this->db->query($q);
        }
        $this->clinicPictureInserted += count($imageIds);
    }

    public function docDocDiagnostic()
    {
        $attemptCount = 0;
        $diagnostics = [];
        while ($attemptCount < 5) {
            try {
                $diagnostics = json_decode(file_get_contents($this->diagnosticsDataUrl), true);
                $diagnostics = $diagnostics['DiagnosticList'];
                break;
            } catch (\Exception $exception) {
                $attemptCount++;
            }
        }

        // получить корневую диагностику, если нет, то создать
        $serviceManager = new ServiceCategoryManager();
        $coreDiagnostic = $serviceManager->getOneByDocDocId(-1, ServiceCategoryModel::TYPE_DIAGNOSTIC);
        if (!$coreDiagnostic) {
            self::log("Create core diagnostic");
            $coreDiagnostic = new ServiceCategoryModel();
            $coreDiagnostic->name = "Диагностика";
            $coreDiagnostic->alias = "diagnostic";
            $coreDiagnostic->docdoc_id = -1;
            $coreDiagnostic->service_type = static::SERVICE_TYPE_DIAGNOSTIC;
            $coreDiagnostic->is_active = true;
            if (!$coreDiagnostic->save()) {
                self::log("Core diagnostic wasn't created");
                exit(-1);
            }
        }

        $coreId = $coreDiagnostic->id;

        foreach ($diagnostics as $diagnostic) {
            self::log("Create diagnostic docDocId=" . $diagnostic['Id'] . "; name=" . $diagnostic['Name']);
            $dbDiagnosticId = $this->fillCategoryService($diagnostic, static::SERVICE_TYPE_DIAGNOSTIC, $coreId);
            if ($dbDiagnosticId) {
                if (isset($diagnostic['SubDiagnosticList'])) {
                    foreach ($diagnostic['SubDiagnosticList'] as $subDiagnostic) {
                        self::log("Create subdiagnostic docDocId=" . $subDiagnostic['Id'] . "; name=" . $subDiagnostic['Name']);
                        $this->fillCategoryService($subDiagnostic, static::SERVICE_TYPE_DIAGNOSTIC, $dbDiagnosticId, "{$diagnostic['Name']} ");
                    }
                }
            }
        }
    }

    /**
     * @param string $serviceType
     *
     * @return ServiceCategoryModel[]
     */
    public function getAllDocDocServices($serviceType = null)
    {

        static $dbServices = false;
        if ($dbServices === false) {
            $serviceManager = new ServiceCategoryManager();
            $buf = $serviceManager->getList();
            $dbServices = [];
            foreach ($buf as $item) {
                /** @var ServiceCategoryModel $item */
                if ($item->docdoc_id) {
                    if ($serviceType === null || $serviceType == $item->service_type) {
                        $dbServices[$item->docdoc_id.$item->service_type] = $item;
                    }
                }
            }
        }

        return $dbServices;
    }

    public function fillCategoryService($categoryService, $type, $parentId = null, $namePrefix = '')
    {
        static $dbServices = false;

        if ($dbServices === false) {
            $dbServices = $this->getAllDocDocServices();
        }

        if (isset($dbServices[$categoryService['Id'] . $type])) {
            $dbService = $dbServices[$categoryService['Id'] . $type];
        } else {
            $dbService = new ServiceCategoryModel();
        }

        $dbService->service_type = $type;
        $dbService->docdoc_id = $categoryService['Id'];

        if ($namePrefix !== 'Биопсия ' || !$dbService->name) {
            $dbService->name = $namePrefix . $categoryService['Name'];
        }
        $dbService->parent_id = $parentId;
        $dbService->is_active = true;

        if (isset($categoryService['SectorId'])) {
            $ddSpecialtyId = $categoryService['SectorId'];
            $dbService->specialty_id = $this->getSpecialtyIdByDocDocId($ddSpecialtyId);
        } else {
            $mappedServices = $this->getDocdocMappedService($categoryService['Id'], true);

            if ($mappedServices) {
                /** @var DocdocServiceCategoryManager $manager */
                $manager = ModelManagerFactory::getByName('docdoc_service_category');
                foreach ($mappedServices as $mappedService) {
                    /** @var DocdocServiceCategoryModel $ddServiceCategory */
                    $ddServiceCategory = $manager->getOneByDocDocId($mappedService);

                    if ($ddServiceCategory && $ddServiceCategory->sector_id) {
                        $dbService->specialty_id = $this->getSpecialtyIdByDocDocId($ddServiceCategory->sector_id);
                    }
                }
            }
        }

        if (!isset($dbService->alias) && isset($categoryService['Name'])) {
            $dbService->alias = StringTransliterationHelper::formatAlias($categoryService['Name']);
        }

        if (isset($categoryService['DiagnosticaId'])) {
            $dId = $categoryService['DiagnosticaId'];
            if (isset($dbServices[$dId . static::SERVICE_TYPE_DIAGNOSTIC])) {
                $dbService->linked_service_id = $dbServices[$dId . static::SERVICE_TYPE_DIAGNOSTIC]->id;
            }
        } else {
            $dbService->linked_service_id = null;
        }

        if (!$dbService->save()) {
            self::log("Error! Cannot save diagnostic docDocId=" . $categoryService['Id'] . "; name=" . $categoryService['Name']);
            return false;
        }

        $dbServices[$dbService->docdoc_id . $type] = $dbService;

        return $dbService->id;

    }

    public function docDocServices()
    {
        $attemptCount = 0;
        $services = [];
        while ($attemptCount < 5) {
            try {
                $services = json_decode(file_get_contents($this->servicesDataUrl), true);
                $services = $services['ServiceList'];
                break;
            } catch (\Exception $exception) {
                $attemptCount++;
            }
        }
        if (!$services) {
            return;
        }

        $idx = 0;
        $servicesToDeactivate = $this->getAllDocDocServices(static::SERVICE_TYPE_SERVICE);
        $importRecursiveServices = function ($parent, $curLevel) use ($services, &$idx, &$importRecursiveServices, &$servicesToDeactivate) {
            $lastDbServiceId = $parent;
            while (isset($services[$idx]) && $curLevel <= $services[$idx]['Depth']) {
                $service = $services[$idx];
                if ($curLevel < $service['Depth']) {
                    $importRecursiveServices($lastDbServiceId, $curLevel+1);
                } else {
                    self::log("Service docdoc_id={$service['Id']} {$service['Name']} level " . $curLevel);
                    $mappedService = $this->getDocdocMappedService($service['Id']);
                    if ($curLevel != 0) {
                        if (is_null($mappedService)) {
                            $service['Name'] = str_replace("OLD ", '', $service['Name']);
                            $lastDbServiceId = $this->fillCategoryService($service, static::SERVICE_TYPE_SERVICE, $parent);
                        } else {
                            // get by docdoc
                            $serviceCategoryManager = new ServiceCategoryManager();

                            /** @var ServiceCategoryModel $serviceToDelete */
                            $serviceToDelete = $serviceCategoryManager->getOneByDocDocId($service['Id'], ServiceCategoryModel::TYPE_SERVICE);
                            if ($serviceToDelete) {
                                $serviceToClinicManager = new ServiceToClinicManager();
                                $serviceToClinicManager->deleteByServiceCategoryId($serviceToDelete->id);
                                $serviceToDelete->delete();
                            }

                            $dbService = $serviceCategoryManager->getOneByDocDocId($mappedService, ServiceCategoryModel::TYPE_SERVICE);
                            if ($dbService) {
                                if (!$dbService->is_active) {
                                    $dbService->is_active = true;
                                    $dbService->save();
                                }
                                $lastDbServiceId = $dbService->id;
                            }
                            unset($servicesToDeactivate[$mappedService.static::SERVICE_TYPE_SERVICE]);
                        }
                        unset($servicesToDeactivate[$service['Id'].static::SERVICE_TYPE_SERVICE]);
                    }
                    $idx++;
                }
            }
        };

        $importRecursiveServices(null, 0);
        foreach ($servicesToDeactivate as $service) {
            if ($service->is_active) {
                $service->is_active = 0;
                $service->save();
                self::log("Service docdoc_id={$service->docdoc_id} Id={$service->id} {$service->name} was deactivated");
            }
        }
    }

    public function getDocdocMappedService($docdocServiceId, $reverse = false)
    {
        static $cache = false;
        static $reverseCache = false;

        if ($cache == false) {
            $query = "SELECT * FROM docdoc_service_category_mapping";
            $mappedValues = $this->db->query($query);

            $reverseCache = [];
            foreach ($mappedValues as $mappedValue) {
                $cache[$mappedValue['service_id']] = $mappedValue['mapped_service_id'];
                if (empty($reverseCache[$mappedValue['mapped_service_id']])) {
                    $reverseCache[$mappedValue['mapped_service_id']] = [];
                }

                $reverseCache[$mappedValue['mapped_service_id']][] = $mappedValue['service_id'];
            }
        }

        if ($reverse) {
            return isset($reverseCache[$docdocServiceId]) ? $reverseCache[$docdocServiceId] : [];
        }
        return isset($cache[$docdocServiceId]) ? $cache[$docdocServiceId] : null;
    }

    public function update_clinic()
    {
        $update = "";
        $new    = "";
        $path   = __DIR__.'/../../imports/';
        if( ( $sh = fopen ( $path."clinic.csv", "r" ) ) !== FALSE ){

            while ( ($data = fgetcsv( $sh, 1000, ";" )) !== FALSE ){
                echo "______________________________".PHP_EOL;

                $clinic = ( new ClinicManager() )->getOneById( (int) $data[0] );
                if(!empty ( $clinic ) ){

                    $clinic->name = $data[1];
                    $clinic->save();
                    $update ++;

                }else{

                    $new ++;
                }

            }

            fclose( $sh );
        }
        echo $update. ": ". $new.PHP_EOL;
        die(" ready ");
    }

    public function importDocDocStreets()
    {
        $streetTypeManager = new StreetTypeManager();
        $dbStreetTypes = $streetTypeManager->getList();
        $groupedStreetTypes = [];
        foreach ($dbStreetTypes as $dbStreetType) {
            $groupedStreetTypes[$dbStreetType->name] = $dbStreetType->getId();
        }
        $streetManager = new StreetManager();
        /** @var StreetDocdocManager $streetDocdocManager */
        $streetDocdocManager = ModelManagerFactory::getByName('street_docdoc');

        foreach (self::$cityDocDocMapping as $docDocCityId=>$dbCityId) {
            self::log("Starting import streets from cityId=$docDocCityId");
            $this->importDocDocStreetsByCityId($docDocCityId, $streetManager, $streetDocdocManager, $groupedStreetTypes);
            self::log("Finished import streets from cityId=$docDocCityId");
        }
    }

    /**
     * @param int $cityId
     * @param StreetManager $streetManager
     * @param StreetDocdocManager $streetDocdocManager
     * @param array $groupedStreetTypes
     * @throws \Exception
     */
    public function importDocDocStreetsByCityId($cityId, $streetManager, $streetDocdocManager, $groupedStreetTypes)
    {
        $docDocStreets = json_decode(file_get_contents(sprintf($this->streetDataUrl, $cityId)), true);
        if (!isset($docDocStreets['StreetList'])) {
            static::log("(EE) Invalid response for city $cityId");
            return;
        }
        $docDocStreets = $docDocStreets['StreetList'];

        $dbCityId = self::$cityDocDocMapping[$cityId];

        foreach ($docDocStreets as $i => $ddStreet) {
            $dbStreet = $streetManager->getOneByDocDocId($ddStreet['Id']);
            if ($dbStreet) {
                if ((int)$dbStreet->city_id === $dbCityId) {
                    continue;
                }
                $streetDocdocManager->deleteByDocdocId($ddStreet['Id']);
                $dbStreet = null;
            }
            // Для улиц из МО не производим "Умное сопоставление", поскольку они импортируются в Москву
            if ($cityId == self::CITY_MOSCOW_REGION) {
                continue;
            }

            $ddStreetName = XssHelper::check($ddStreet['Title']);
            $dbStreet = $streetManager->getOneByNameAndCityId($ddStreetName, $dbCityId);
            if (!$dbStreet) {
                // smart поиск
                list($ddStreetType, $ddStreetNameVariations) = AddressHelper::parseStreet($ddStreetName);
                $ddStreetName = reset($ddStreetNameVariations);
                foreach ($ddStreetNameVariations as $name) {
                    $dbStreet = $streetManager->getOneByPrefixAndNameAndCityId($ddStreetType, $name, $dbCityId);
                    if ($dbStreet) {
                        $ddStreetName = $name;
                        break;
                    }
                }
                if (!isset($groupedStreetTypes[$ddStreetType])) {
                    static::log("Unknown street type '$ddStreetType' returned for street " . $ddStreet['Title']);
                    continue;
                }
                if (!$dbStreet) {
                    $dbStreet = new StreetModel();
                    $dbStreet->prefix = $ddStreetType;
                    $dbStreet->name = $ddStreetName;
                    $dbStreet->street_type_id = $groupedStreetTypes[$ddStreetType];
                    $dbStreet->city_id = $dbCityId;
                    if (!$dbStreet->save()) {
                        static::log('(EE) invalid model data: ' . implode(';', $dbStreet->getValidator()->getErrorMessages()));
                        continue;
                    }
                    static::log("{$dbStreet->id} {$dbStreet->name} was created");
                }
            }

            $streetDocdoc = new StreetDocdocModel();
            $streetDocdoc->docdoc_id = $ddStreet['Id'];
            $streetDocdoc->street_id = $dbStreet->id;
            if (!$streetDocdoc->save()) {
                static::log('(EE) invalid model data: ' . implode(';', $streetDocdoc->getValidator()->getErrorMessages()));
                continue;
            }
            static::log("{$dbStreet->id} {$dbStreet->name} reference id {$streetDocdoc->id} was created");
        }
    }

    public function mapToDocDocSpecialities()
    {
        try {
            $ddSpecialities = json_decode(file_get_contents(sprintf($this->specialityDataUrl, 1)), true);
            $ddSpecialities = $ddSpecialities['SpecList'];
        } catch (\Exception $t) {
            return null;
        }

        /** @var SpecialtyManager $specialtyManager */
        $specialtyManager = ModelManagerFactory::getByName('specialty');

        foreach ($ddSpecialities as $ddSpeciality) {
            // try to update by name
            $specialty = $specialtyManager->getOneByDocDocId((int)$ddSpeciality['Id']);
            if (!$specialty) {
                $specialty = $specialtyManager->getOneByName(trim($ddSpeciality['Name']));
                if ($specialty) {
                    $specialty->docdoc_id = $ddSpeciality['Id'];
                    $specialty->disableFilter(); // фикс
                    $specialty->save();
                } else {
                    self::log('Specialty wasn\'t found: ' . $ddSpeciality['Name'] . ' id=' . $ddSpeciality['Id']);
                }
            }
        }
        self::log('Done!');
    }

    public function docdocServicesForMapping()
    {
        $attemptCount = 0;
        $services = [];
        while ($attemptCount < 5) {
            try {
                $services = json_decode(file_get_contents($this->servicesDataUrl), true);
                $services = $services['ServiceList'];
                break;
            } catch (\Exception $exception) {
                $attemptCount++;
            }
        }

        $idx = 0;
        $importRecursiveServices = function ($parent, $curLevel) use ($services, &$idx, &$importRecursiveServices) {
            $lastDbServiceName = $parent;
            while (isset($services[$idx]) && $curLevel <= $services[$idx]['Depth']) {
                $service = $services[$idx];
                if ($curLevel < $service['Depth']) {
                    $importRecursiveServices($lastDbServiceName, $curLevel+1);
                } else {
                    self::log("Service docdoc_id={$service['Id']} {$service['Name']} level " . $curLevel);
                    if ($curLevel != 0) {
                        $serviceCategoryManager = new DocdocServiceCategoryManager();
                        $dbService = $serviceCategoryManager->getOneByDocDocId($service['Id']);
                        if (!$dbService) {
                            $dbService = new DocdocServiceCategoryModel();
                        }
                        $dbService->full_name = $parent . '/' . $service['Name'];
                        $dbService->docdoc_id = $service['Id'];
                        $dbService->sector_id = $service['SectorId'];
                        $dbService->save();

                        $lastDbServiceName = $parent . '/' . $service['Name'];
                    }
                    $idx++;
                }
            }
        };

        $importRecursiveServices('', 0);
    }

    protected function getRegionsInfoByDocDocId($docDocRegionId, $cityId)
    {
        static $result = false;
        static $cachedCityId = false;

        if ($cachedCityId !== $cityId) {
            $result = false;
            $cachedCityId = $cityId;
        }

        if ($result === false) {
            $result = [];
            try {
                $docDocRegions = json_decode(file_get_contents(sprintf($this->cityRegionsDataUrl, $cityId)), true);
                $docDocRegions = $docDocRegions['DistrictList'];
            } catch (\Exception $t) {
                return null;
            }

            $regionManager = new RegionManager();
            $dbRegions = $regionManager->getListByCityId(self::$cityDocDocMapping[$cityId]);
            $regions = [];
            foreach ($dbRegions as $model) {
                /** @var RegionModel $model */
                $regions[$model->name] = $model->id;
            }

            foreach ($docDocRegions as $region) {
                $regionName = $region['Name'];
                $result[$region['Id']] = [
                    'Name' => $regionName,
                    'SiteId' => null,
                ];
                if (isset($regions[$regionName])) {
                    $result[$region['Id']]['SiteId'] = $regions[$regionName];
                }
            }
        }

        return isset($result[$docDocRegionId]) ? $result[$docDocRegionId] : null;
    }

    protected function getRegionIdByDocDocId($docDocRegionId, $cityId)
    {
        static $matchTable = false;
        static $cachedCityId = false;

        if ($cachedCityId !== $cityId) {
            $matchTable = false;
            $cachedCityId = $cityId;
        }

        if ($matchTable === false) {

            $docDocRegions = $this->getRegionsInfoByDocDocId($docDocRegionId, $cityId);
            $matchTable = [];
            foreach ($docDocRegions as $id => $region) {
                if (!empty($region['SiteId'])) {
                    $matchTable[$id] = $region['SiteId'];
                }
            }
        }

        return isset($matchTable[$docDocRegionId]) ? $matchTable[$docDocRegionId] : null;
    }

    protected function getRegionIdByName($regionName)
    {
        static $regions = false;
        if ($regions === false) {
            $regionManager = new RegionManager();
            $res = $regionManager->getList();
            $regions = [];
            foreach ($res as $model) {
                /** @var RegionModel $model */
                $regions[$model->name] = $model->id;
            }
        }

        return isset($regions[$regionName]) ? $regions[$regionName] : null;
    }

    /**
     * @param $streetId
     * @return StreetModel
     */
    protected function getStreetByDocDocId($streetId)
    {
        static $streets = [];
        static $manager = false;

        if ($manager === false) {
            $manager = new StreetManager();
        }

        if (!isset($streets[$streetId])) {
            $street = $manager->getOneByDocDocId($streetId);
            $streets[$streetId] = $street ? $street : false;
        }

        return $streets[$streetId];
    }

    protected function getSpecialtyIdByDocDocId($ddSpecialtyId)
    {
        static $specialtyIds = false;

        if ($specialtyIds === false) {
            $manager = new SpecialtyManager();
            $specialties = $manager->getByDocDocSpecialtyId();
            $specialtyIds = [];
            foreach ($specialties as $specialty) {
                $specialtyIds[$specialty->docdoc_id] = $specialty->id;
            }
        }

        return isset($specialtyIds[$ddSpecialtyId]) ? $specialtyIds[$ddSpecialtyId] : null;
    }

    protected static function log($text)
    {
        echo date("d/m/Y H:i:s") . " $text" . PHP_EOL;
    }

    public function beforeRender()
    {
        exit();
    }

    /**
     * @param ClinicManager $clinicManager
     * @param $clinicIds
     * @param $absentClinicsCount
     * @return mixed
     */
    private function deactivateClinics($clinicManager, $clinicIds)
    {
        self::log("Deactivation clinics...");
        $absentClinicsCount = 0;
        // start to remove clinics by Ids
        $clinics = $clinicManager->getListByIds($clinicIds);
        foreach ($clinics as $clinic) {
            /** @var ClinicModel $clinic */
            self::log("Deactivation clinic {$clinic->name}...");
            $clinic->is_active = 0;
            $clinic->save();
            foreach ($clinic->doctors as $doctor) {
                ElasticaTask::indexDoctor($doctor->getId());
            }
            $absentClinicsCount++;
        }
        self::log("Clinics deactivation was finished.");
        self::log("Total deactivated clinics: $absentClinicsCount");
        return $absentClinicsCount;
    }
}
