<?php
class YandexApiController extends Controller
{
    public $layout = 'ajax';

    private $yandex_request;
    private $book_id;
    private $method;

    // получение запроса из яндекса
    public function getRequest()
    {
        return $this->yandex_request;
    }

    // получение яндекс id
    public function getBookId()
    {
        return $this->book_id;
    }

    // получение метода запроса
    public function getMethod()
    {
        return $this->method;
    }

    // обработка запроса из яндекса
    public function index()
    {

        $json = file_get_contents('php://input');
        $this->yandex_request = $json;
        $data = json_decode($json, true);

        if ($data) {
            $params = $data["params"];
            $method = $data["method"];
            if (!$data["id"]) {
                $id = 1;
            } else {
                $id = $data["id"];
            }

            if (!$method || !$params || !$id) {
                $this->processError(ApiRequestErrors::INVALID_PARAMS, 'INVALID PARAMS', $id);
            } else if (!method_exists($this, $method)) {
                $this->processError(ApiRequestErrors::METHOD_NOT_FOUND, $method, $id);
            } else {
                $this->method = $method;
                $this->$method($params, $id);
            }
        } else
            $this->processError(200, 'INVALID', 20);
    }

    // получение слотов расписания для клиники
    public function getSlots($params, $id)
    {
        if (!isset($params[0]))
            $this->processError(ApiRequestErrors::INVALID_PARAMS, 'INVALID PARAMS', $id);

        $clinic_id = $params[0];

        $clinic_manager = new ClinicManager();
        $clinic = $clinic_manager->getOneById($clinic_id);

        if (!$clinic) {
            $this->processError(ApiRequestErrors::INVALID_ORGANIZATION, 'INVALID ORGANIZATION', $id);
        }

        $result = array();
        $this->processResponse($result, $id);
    }

    // запись на прием
    public function book($params, $id)
    {
        $params = $params[0];

        if (!$params) {
            $this->processError(ApiRequestErrors::INVALID_PARAMS, 'INVALID PARAMS', $id);
        }

        if (!isset($params["organizationId"]) && !isset($params["phone"]) && !isset($params["bookId"])) {
            $this->processError(ApiRequestErrors::INVALID_PARAMS, 'INVALID PARAMS', $id);
        }

        $this->book_id = $params["bookId"];

        $clinic_id = $params["organizationId"];
        if (isset($params["serviceId"])) $specialty_id = $params["serviceId"];
        if (isset($params["resourceId"])) $doctor_id = $params["resourceId"];

        $clinic_manager = new ClinicManager();
        $clinic = $clinic_manager->getOneById($clinic_id);

        if (!$clinic) {
            $this->processError(ApiRequestErrors::INVALID_ORGANIZATION, 'INVALID ORGANIZATION', $id);
        }

        $specialty_to_clinic_manager = new SpecialtyToClinicManager();

        if (isset($specialty_id)) {
            if (!ModelManagerFactory::getByName('specialty')->getOneById($specialty_id)) {
                $this->processError(ApiRequestErrors::INVALID_SERVICE, 'INVALID SERVICE', $id);
            }
            if ($params["bookType"] == 'static-service-only') {
                if (!$specialty_to_clinic_manager->checkExistsBySpecialtyIdAndClinicId($specialty_id, $clinic_id)) {
                    $this->processError(ApiRequestErrors::INVALID_SERVICE, 'INVALID SERVICE', $id);
                }
            } else {
                if (!ModelManagerFactory::getByName('doctor_specialty_to_clinic')->getOneByClinicIdAndSpecialtyId($clinic_id, $specialty_id)) {
                    $this->processError(ApiRequestErrors::INVALID_SERVICE, 'INVALID SERVICE', $id);
                }
            }

        }
        /*
        if (isset($specialty_id)) {
            if (!ModelManagerFactory::getByName('specialty')->getOneById($specialty_id))
                $this->processError(ApiRequestErrors::INVALID_SERVICE, 'INVALID SERVICE', $id);
            //else if (!$specialty_to_clinic_manager->checkExistsBySpecialtyIdAndClinicId($specialty_id, $clinic_id))
            else if (!ModelManagerFactory::getByName('doctor_specialty_to_clinic')->getOneByClinicIdAndSpecialtyId($clinic_id, $specialty_id))
                $this->processError(ApiRequestErrors::INVALID_SERVICE, 'INVALID SERVICE', $id);
        }*/

        if (isset($doctor_id)) {
            if (!ModelManagerFactory::getByName('doctor')->getOneById($doctor_id)) {
                $this->processError(ApiRequestErrors::INVALID_RESOURCE, 'INVALID RESOURCE', $id);
            }
            else if (isset($specialty_id) && $doctor_id != DoctorModel::RESERVED_DOCTOR_SLOT) {
                if (!ModelManagerFactory::getByName('doctor_specialty_to_clinic')->getOneByDoctorIdAndClinicIdAndSpecialtyId($doctor_id, $clinic_id, $specialty_id)) {
                    $this->processError(ApiRequestErrors::INVALID_RESOURCE, 'INVALID RESOURCE', $id);
                }
            }
        }
        //echo json_encode(array("element" => $params["resourceId"]));
        //exit();
        $new_account = false;
        $save_email = true;
        $account_id = null;
        $phone = preg_replace('/[^0-9]/', '', $params["phone"]);
        $phone = substr_replace($phone, 7, 0, 1);

        $existing_phone = ModelManagerFactory::getByName('account_phone')->getOneByPhone($phone);
        if ($existing_phone) {
            if ($existing_phone->is_confirmed == 1) {
                $account_id = $existing_phone->account_id;
            }
            else {
                ModelManagerFactory::getByName('account_phone')->deleteByPhone($phone);
                $new_account = true;
            }
        } else if (isset($params["email"])) {
            $existing_account = ModelManagerFactory::getByName('account')->getOneByEmail($params["email"]);
            if ($existing_account) { /*
                $account_id = $existing_account->getId();

                $account_phone = new AccountPhoneModel();
                $account_phone->phone = $phone;
                $account_phone->account_id = $account_id;
                $account_phone->is_confirmed = 1;

                $account_phone->save();*/
                $save_email = false;
                $new_account = true;
            } else {
                $new_account = true;
            }
        } else {
            $new_account = true;
        }

        if ($new_account) {
            $account = new AccountModel();
            if (isset($params["fullname"])) {
                $account->full_name = $params["fullname"];

                $name_parts = explode(" ", $params["fullname"]);
                if ($name_parts[0]) $account->last_name = $name_parts[0];
                if ($name_parts[1]) $account->first_name = $name_parts[1];
                if ($name_parts[2]) $account->middle_name = $name_parts[2];
            }
            if (isset($params["email"]) && $save_email) {
                $account->email = $params["email"];
            } else {
                $account->email = $phone.'@user.ru';
            }

            $account_password = StringGeneratorHelper::generateNumbers(5);
            $account->password = $account_password;
            $account->setValidator(new WithoutValidator());

            if ($account->save()) {
                $account_id = $account->getId();

                $account_phone = new AccountPhoneModel();
                $account_phone->phone = $phone;
                $account_phone->account_id = $account_id;
                $account_phone->is_confirmed = 1;

                $account_phone->save();

                $tokens = array(
                    'email' => $account->email,
                    'password' => $account_password
                );

                $code = 'sms_account_registration';
                $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
                SmsSender::sendMessage($phone, $template_data->text);
            }
        }

        $fast_login = new FastLogin();
        $dt_expire = date('Y-m-d H:i:s', time() + 7*24*60*60);
        $hash = $fast_login->generateHash($account_id, $dt_expire, 100000);
        $visit_url = SITE_URL.'/account/doctorsVisitsComing?fastlogin='.$hash;

        $doctor_to_clinic_manager = new DoctorToClinicManager();
        $price = $doctor_to_clinic_manager->getFirstVisitPriceByDoctorIdAndClinicId($doctor_id, $clinic_id);

        $visit_start_time = date('Y-m-d H:i:s', strtotime($params["dateTime"]));

        $visit = new VisitModel();
        $visit->clinic_id = $clinic_id;
        if (isset($doctor_id)) $visit->doctor_id = $doctor_id;
        $visit->account_id = $account_id;
        if (isset($specialty_id)) $visit->specialty_id = $specialty_id;
        if (isset($params["fullname"])) $visit->full_name = $params["fullname"];
        $visit->schedule_id = VisitModel::RESERVED_TIME_SLOT;
        $visit->phone = $phone;
        $visit->is_first_visit = 1;
        if ($price) $visit->price = $price;
        if (isset($params["comment"])) $visit->comment = $params["comment"];
        $visit->visit_start_time = $visit_start_time;
        $visit->yandex_id = $params["bookId"];
        $visit->visit_channel_id = VisitChannelModel::YANDEX;
        if (isset($params["notifyMe"])) $visit->notify_minutes = (int)$params["notifyMe"];

        if ($visit->save()) {

            $result = array(
                'status' => 'ACCEPTED',
                'url' => $visit_url
            );
            $this->processResponse($result, $id);
        } else
            $this->processError(ApiRequestErrors::SAVE_ERROR, 'VISIT SAVE ERROR', $id);
    }

    /*
        public function reportSlots($params, $id)
        {
            $this->processError('-500', 'No slots', $id);
        }
    */

    // получение статуса записи
    public function getBookStatus($params, $id)
    {
        if (!isset($params[0]))
            $this->processError(ApiRequestErrors::INVALID_PARAMS, 'INVALID PARAMS', $id);

        $this->book_id = $params[0];
        $visit_id = $params[0];

        $visit_manager = new VisitManager();
        $visit = $visit_manager->getOneByYandexId($visit_id);

        if ($visit) {
            $status_name = VisitModel::getBookStatusNameForYandexByStatusId($visit->status_id);
            $this->processResponse($status_name, $id);
            exit();
        } else {
            $this->processError(ApiRequestErrors::INVALID_BOOK_RECORD, 'Invalid book record', $id);
        }
    }

    // отмена записи
    public function cancelBook($params, $id)
    {
        if (!isset($params[0]))
            $this->processError(ApiRequestErrors::INVALID_PARAMS, 'INVALID PARAMS', $id);

        $this->book_id = $params[0];
        $visit_id = $params[0];

        $visit_manager = new VisitManager();
        $visit = $visit_manager->getOneByYandexId($visit_id);

        if ($visit) {
            $visit->status_id = VisitModel::CANCELLED;
            $visit->setWhenceCanceled('из Яндекса');
            $visit->save();
            $this->processResponse(1, $id);
        } else {
            $this->processError(ApiRequestErrors::INVALID_BOOK_RECORD, 'Invalid book record', $id);
        }
    }

    /*
        public function changeBookDetails($params, $id)
        {
            if (!isset($params[0]) && (!isset($params[1])))
                $this->processError(ApiRequestErrors::INVALID_PARAMS, 'INVALID PARAMS', $id);

            $visit_id = $params[0];
            $comment = $params[1];

            $visit_manager = new VisitManager();
            $visit = $visit_manager->getOneByYandexId($visit_id);

            if (!$visit) {
                $this->processError(ApiRequestErrors::INVALID_BOOK_RECORD, 'Invalid book record', $id);
            }

            $visit_change = new VisitChangeModel();
            $visit_change->visit_id = $visit->getId();
            $visit_change->comment = $comment;
            $visit_change->dt = date('Y-m-d H:i:s');

            if ($visit_change->save()) {
                $this->processResponse(1, $id);
            }
            else
                $this->processError(ApiRequestErrors::SAVE_ERROR, 'VISIT CHANGE SAVE ERROR', $id);
        }*/

    // обработка ошибки запроса
    public function processError($error, $error_text, $id)
    {
        $response = json_encode(array(
                'jsonrpc' => '2.0',
                'error' => array(
                    'code' => $error,
                    'message' => $error_text
                ),
                'id' => $id
            )
        );

        $this->saveYandexLog($response);
        ApiHeader::yandex_error($error, $error_text, $id);
    }

    // формирование ответа на запрос яндекса
    public function processResponse($result, $id)
    {
        $response = json_encode(array('jsonrpc' => '2.0', 'result' => $result, 'id' => $id));

        $this->saveYandexLog($response);
        ApiHeader::yandex_response($result, $id);
    }

    // сохранение логов взаимодействия с яндексом
    public function saveYandexLog($response)
    {
        $yandex_log = new YandexLogModel();

        $visit = ModelManagerFactory::getByName('visit')->getOneByYandexId($this->getBookId());
        if ($visit) {
            $yandex_log->visit_id = $visit->getId();
        }
        $yandex_log->yandex_id = $this->getBookId();
        $yandex_log->method = $this->getMethod();
        $yandex_log->direction = 'Из Яндекса';
        $yandex_log->request = $this->getRequest();
        $yandex_log->response = $response;
        $yandex_log->dt = date('Y-m-d H:i:s');

        $yandex_log->save();

    }

    public function getBannedSlots($params, $id)
    {
        if (!isset($params[0]))
            $this->processError(ApiRequestErrors::INVALID_PARAMS, 'INVALID PARAMS', $id);

        $clinic_id = $params[0];

        $clinic_manager = new ClinicManager();
        $clinic = $clinic_manager->getOneById($clinic_id);

        if (!$clinic) {
            $this->processError(ApiRequestErrors::INVALID_ORGANIZATION, 'INVALID ORGANIZATION', $id);
        }

        $result = array();
        $this->processResponse($result, $id);
    }
}