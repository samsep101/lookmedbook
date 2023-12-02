<?php

    class LinkHelper
    {

        public static function getSiteUrlByCity(CityModel $city)
        {
            $url = self::getDomain();
            if($city->name == 'Москва')
            {
                return SITE_SCHEME . '://' . $url;
            }
            else
            {
                return SITE_SCHEME . '://' . $city->alias . '.' . $url;
            }
        }

        public static function getCurrentCityBaseURL()
        {
            return SITE_SCHEME . "://{$_SERVER['HTTP_HOST']}";
        }

        public static function getDomain()
        {
            $url = str_replace(SITE_SCHEME . '://', '', SITE_URL);
            $url = str_replace('/', '', $url);

            return $url;
        }

        /*
         * Проверка на правильность ссылки. В случае если ссылка не корректна определеяем перенаправление.
         * Принимает два параметра: модель ($model); массив ($params).
         *
         * @param DynamicModel $model
         * @param array $params
         * */
        public static function checkLinkIsCorrectIfThereIsNoAttemptRedirect($model, $params)
        {
            $modelName = get_class($model);
            $failure = !$model;

            switch($modelName)
            {
                case 'DoctorModel' :
                {
                    $failure = self::checkDoctorLink($model, $params);
                    break;
                }
                case 'ClinicModel' :
                {
                    $failure = self::checkClinicLink($model, $params);
                    break;
                }
            }

            if(!empty($params['model']))
            {
                $modelName = ucfirst($params['model']) . 'Model';
            }

            if($failure)
            {
                switch($modelName)
                {
                    case 'ClinicModel':
                        // Для невалидных страниц клиник - 301 редирект
                        RedirectManager::redirect301(static::getCurrentCityBaseURL() . '/clinic');
                        break;
                    case 'DoctorModel' :
                        if ($model && !$model->clinic && time() - $model->is_active_updated_at > DoctorModel::INACTIVE_MAX_TIME) {
                            // Для устаревших страниц докторов - 301 редирект
                            RedirectManager::redirect301(static::getCurrentCityBaseURL() . '/doctor');
                        }
                        ErrorPageViewHelper::page404();
                        break;
                    default:
                        // TODO Временно сохранил старую логику, избавиться от неё
                        self::redirectRandomURLOrRedirect404($modelName, $params);

                }
            }
        }

        /*
         * Определяет случайную ссылки и осуществляет перенаправление. В случае если ссылку получить не удалось,
         * осуществляет перенаправление на страницу ошибки 404.
         * Принимает два параметра: строку ($modelName); массив ($params).
         *
         * @param string $modelName
         * @param array $params
         * */
        public static function redirectRandomURLOrRedirect404($modelName, $params)
        {
            $link = LinkHelper::getRandomListRedirectionURL($modelName, $params);

            if($link)
            {
                RedirectManager::redirect301($link);
                exit;
            }
            else
            {
                ErrorPageViewHelper::page404('404');
                exit();
            }
        }

        /*
         * Определяет случайную ссылки.
         * Принимает два параметра: строку ($modelName); массив ($params).
         *
         * @param string $modelName
         * @param array $params
         *
         * @return string $link;
         * */
        public static function getRandomListRedirectionURL($modelName, $params)
        {
            $link = $helper = '';
            $redirectList = array();

            switch($modelName)
            {
                case 'ClinicModel' :
                {
                    $clinic_manager = ModelManagerFactory::getByName('clinic');
                    $helper = 'ClinicPageLinkViewHelper';
                    $redirectList = $clinic_manager->getClinicRedirectList();

                    break;
                }
                case 'DoctorModel' :
                {
                    $doctor_manager = ModelManagerFactory::getByName('doctor');
                    $helper = 'DoctorPageLinkViewHelper';

                    if(!empty($params['city']))
                    {
                        $redirectList = $doctor_manager->getListByCityId($params['city']->getId());
                    }
                    else
                    {
                        $redirectList = $doctor_manager->getActiveList();
                    }

                    break;
                }
            }

            $redirectListCount = count($redirectList);

            if($redirectListCount > 0 && $helper)
            {
                $randomClinicIdentifier = rand(0, $redirectListCount - 1);
                $link = $helper::getLink($redirectList[$randomClinicIdentifier]);
            }

            return $link;
        }

        /*
         * Определяет правильность ссылки. Если она попадает под определенные
         * условия осуществляется дальнейшая обработка.
         * Принимает два параметра: модель ($model); массив ($params).
         *
         * @param DynamicModel $model
         * @param array $params
         *
         * @return boolean;
         * */
        private static function checkDoctorLink($model, $params)
        {
            if($model->clinic && $model->clinic->city->getId() != $params['city']->getId())
            {
                self::doesSuchDoctorClinicInAnotherCity($model);
            }
            else if($model->clinic && $model->clinic->city->getId())
            {
                return FALSE;
            }
            else
            {
                return TRUE;
            }

            return FALSE;
        }

        /*
         * Проверяет есть ли данный доктор в другом городе. В случае если есть, осуществляет перенаправлеине.
         * Принимает два параметра: модель ($doctor).
         *
         * @param DynamicModel $doctor
         * */
        private static function doesSuchDoctorClinicInAnotherCity($doctor)
        {
            if($doctor->clinic->city && $doctor->clinic->alias && ($link = DoctorPageLinkViewHelper::getLink($doctor)))
            {
                RedirectManager::redirect301($link);
            }
        }

        /*
         * Определяет правильность ссылки. Если она попадает под определенные
         * условия осуществляется дальнейшая обработка.
         * Принимает два параметра: модель ($model); массив ($params).
         *
         * @param DynamicModel $model
         * @param array $params
         *
         * @return boolean;
         * */
        private static function checkClinicLink($model, $params)
        {
            //какая-то злая проверка города, отключил на фиг (CyberUnit)
            if(0 and $model->city->getId() != $params['city']->getId())
            {
                self::doesSuchClinicInAnotherCity($model);
            }
            else if(!$model->is_active)
            {
                return TRUE;
            }

            return FALSE;
        }

        /*
         * Проверяет есть ли данная клиника в другом городе. В случае если есть, осуществляет перенаправлеине.
         * Принимает два параметра: модель ($clinic).
         *
         * @param DynamicModel $clinic
         * */
        private static function doesSuchClinicInAnotherCity($clinic)
        {
            if($clinic->city && $clinic->alias && ($link = ClinicPageLinkViewHelper::getLink($clinic)))
            {
                RedirectManager::redirect301($link);
            }
        }
    }