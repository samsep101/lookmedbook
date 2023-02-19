<?php

use app\library\helpers\Morpher;

class SeoTextViewHelper
	{
        public static function getDoctorPageDescription(DynamicModel $specialty, DynamicModel $address_object) {
            $geo = '';
            $specialtyPlural = $specialty->lp_genitive_name_plural;
            $specialtyGenitive = $specialty->genitive_name;

            switch(get_class($address_object)) {
                case 'CityModel' : {
                    $geo = 'в ' . $address_object->prepositional_name;
                    break;
                } case 'DistrictModel' : {
                    $geo = 'в ' . $address_object->formal_name;
                    break;
                } case 'RegionModel' : {
                    $geo = 'района ' . $address_object->name;
                    break;
                } case 'StreetModel' : {
                    $geo = 'улица ' . $address_object->name;
                    break;
                } case 'MetroStationModel' : {
                    $geo = $address_object->name;
                    break;
                }

            }

            $specialtyAndGeo = $specialtyPlural . ' ' . $geo;

            $seoText = 'На нашем сервисе вы всегда сможете найти %s.
            Мы работаем с лучшими и проверенными специалистами, которые готовы помочь вам в решении проблемы.
            Все отзывы на нашем сервисе оставлены пациентами, которые были на приеме у врача.
            Благодаря этому мы можем вам посоветовать хорошего %s.';

            return sprintf($seoText, $specialtyAndGeo, $specialtyGenitive);
        }

        public static function getClinicListingPageDescription($specialization, $address_object)
        {
            if (empty($specialization)) {
                return 'Медицинские центры и клиники ' . SeoTextViewHelper::getAddressObjectName($address_object) . ': цены, отзывы, рейтинги и запись на прием на ' . SITE_NAME;
            } else {
                return $specialization->name . ' – медицинские центры и клиники ' . SeoTextViewHelper::getAddressObjectName($address_object) . '. Запись на прием онлайн, фото, цены, отзывы – ' . SITE_NAME . '.';
            }
        }

        public static function getClinicPageDescription($page, DynamicModel $address_object) {
            $geo = '';
            $pluralName = isset($page->plural_name) ? $page->plural_name : null;
            $genitiveName = isset($page->genitive_name) ? $page->genitive_name : null;

            if (!$pluralName && !$genitiveName) {
                return '';
            }

            switch(get_class($address_object)) {
                case 'CityModel' : {
                    $geo = ' в ' . $address_object->prepositional_name;
                    break;
                } case 'DistrictModel' : {
                    $geo = ' в ' . $address_object->formal_name;
                    break;
                } case 'RegionModel' : {
                    $geo = ' в районе ' . $address_object->name;
                    break;
                } case 'StreetModel' : {
                    $geo = " {$address_object->name}";
                    break;
                } case 'MetroStationModel' : {
                    $geo = " рядом с метро {$address_object->name}";
                    break;
                }

            }

            if(get_class($page) == 'ClinicServicesModel')
            {
                $specialtyAndGeo = $genitiveName . $geo;

                $seoText = 'На нашем сервисе вы всегда сможете найти клинику предоставляющую %s.
                Мы работаем с лучшими и проверенными клиниками, которые готовы помочь вам в решении проблемы.
                Все отзывы на нашем сервисе оставлены пациентами, которые были на приеме у врача.
                Благодаря этому мы можем вам посоветовать хорошую клинику оказывающей услугу "%s".';

                $result_text = sprintf($seoText, $specialtyAndGeo, $pluralName);
            }
            else if(get_class($page) == 'ClinicTypeModel')
            {
                $specialtyAndGeo = $pluralName . $geo;

                $seoText = 'На нашем сервисе вы всегда сможете найти %s.
                Мы работаем с лучшими и проверенными клиниками, которые готовы помочь вам в решении проблемы.
                Все отзывы на нашем сервисе оставлены пациентами, которые были на приеме у врача.
                Благодаря этому мы можем вам посоветовать хорошую %s.';

                $result_text = sprintf($seoText, $specialtyAndGeo, $genitiveName);
            } else if (get_class($page) == 'SpecializationModel') {
                $seoText = "На нашем сервисе вы всегда сможете найти клинику, работающую по специальности %s{$geo}.
                Мы работаем с лучшими и проверенными клиниками, которые готовы помочь вам в решении проблемы.
                Все отзывы на нашем сервисе оставлены пациентами, которые были на приеме у врача.";

                if (extension_loaded('morpher')) {
                    $result_text = sprintf($seoText, $page->name, Morpher::inflect($page->name, 'rod'));
                } else {
                    $result_text = sprintf($seoText, $page->name, WordDeclination::getInstance()->toGenitive($page->name));
                }
            }
            else return '';

            return $result_text;
        }

		public static function getTextBySpecialtyIdAndAddressObject($specialty_id, $address_object)
		{
			if ($address_object) {
				$seo_text_manager = new SeoTextManager();
				$text = $seo_text_manager->getOneBySpecialtyIdAndAddressObject($specialty_id, $address_object);

				return $text ? $text->text : '';
			} else {
				return '';
			}
		}
    public static function getTextBySpecialtyId($specialty_id)
    {
        $seo_text_manager = new SeoTextManager();
        $text = $seo_text_manager->getOneBySpecialtyId($specialty_id);

        return $text ? $text->text : '';

    }

        /**
         * @param MetroStationModel|StreetModel|RegionModel|DistrictModel|CityModel|null $model
         * @return string
         */
        public static function getAddressObjectName(DynamicModel $model = null)
        {
            if (!$model) {
                return '';
            }
            if ($model instanceof MetroStationModel) {
                return 'на метро ' . $model->name;
            }
            if ($model instanceof StreetModel) {
                /** @var RegionModel $region */
                $regions = $model->regions;
                $region = reset($regions);
                $city = $region->district->city;
                if (extension_loaded('morpher')) {
                    return Morpher::inflect($model->street_type->name, 'gde')
                        . ' ' . Morpher::inflect($model->name, 'im')
                        . ' ' . Morpher::inflect($city->name, 'gde');
                } else {
                    return 'на ' . $model->street_type->genitive_name
                        . ' ' . $model->name . ' ' . ' в ' . $city->prepositional_name;
                }
            }
            if ($model instanceof RegionModel) {
                $city = $model->district->city;
                if (extension_loaded('morpher')) {
                    return 'в районе ' . $model->name . ' ' . Morpher::inflect($city->name, 'gde');
                } else {
                    return 'в районе ' . $model->name . ' в ' . $city->prepositional_name;
                }
            }
            if ($model instanceof DistrictModel) {
                if (extension_loaded('morpher')) {
                    return Morpher::inflect($model->name, 'gde') . ' ' . Morpher::inflect($model->city->name, 'gde');
                } else {
                    return 'в ' . $model->formal_name . ' в ' . $model->city->prepositional_name;
                }
            }
            if ($model instanceof CityModel) {
                if (extension_loaded('morpher')) {
                    return Morpher::inflect($model->name, 'gde');
                } else {
                    return 'в ' . $model->prepositional_name;
                }
            }
        }

        public static  function getAddressObjectNamePagesForTop(DynamicModel $model = NULL)
		{
			if ($model) {
				if (get_class($model) == 'MetroStationModel')
					return 'возле метро '.$model->name.' района '.$model->region->name;
				elseif(get_class($model) == 'StreetModel')
					return 'возле '.$model->street_type->genitive_name.' '.$model->name;
				elseif(get_class($model) == 'RegionModel')
					return $model->name.' в '.$model->parent->city->prepositional_name;
				elseif(get_class($model) == 'DistrictModel')
					return ' в ' . $model->city->prepositional_name . ' ' . $model->formal_name;
				elseif(get_class($model) == 'CityModel')
					return 'в '.$model->prepositional_name;

			}
			return '';
		}

		public static  function getAddressObjectOnlyName(DynamicModel $model = NULL)
		{
            if ($model) {
                if (get_class($model) == 'MetroStationModel')
                    return $model->name;
                elseif(get_class($model) == 'StreetModel')
                    return $model->street_type->genitive_name;
                elseif(get_class($model) == 'RegionModel')
                    return $model->name;
                elseif(get_class($model) == 'DistrictModel')
                    return $model->formal_name;
                elseif(get_class($model) == 'CityModel')
                    return $model->name;

            }
			return '';
		}

        /**
         * @param SpecialtyModel $specialty
         * @param MetroStationModel|StreetModel|RegionModel|DistrictModel|CityModel|null $address_object
         * @return string
         */
        public static function getSpecialtyH1($specialty, $address_object)
        {
            if ($address_object instanceof CityModel && $specialty) {
                // #4429 Для городов меняем формат H1
                $specialtyText = StringHelper::startProposalWord($specialty->plural_name);
                if (mb_strpos(mb_strtolower($specialtyText), 'врач') === false) {
                    $specialtyText = 'Врачи ' . $specialtyText;
                }
                return $specialtyText;
            }
            $subject = $specialty
                ? StringHelper::startProposalWord($specialty->plural_name)
                : 'Врачи';
            return $subject . ' ' . SeoTextViewHelper::getAddressObjectName($address_object);
        }

        /**
         * @param SpecializationModel $specialization
         * @param MetroStationModel|StreetModel|RegionModel|DistrictModel|CityModel|null $address_object
         * @return string
         */
		public static function getSpecializationH1($specialization, $address_object)
        {
            $subject = $specialization
                ? StringHelper::upperCaseFirstSymbol($specialization->name) . ' – клиники и центры'
                : 'Медицинские центры и клиники';
            return $subject . ' ' . SeoTextViewHelper::getAddressObjectName($address_object);
        }

		public static function getTitle($specialty, $address_object, $search_flags)
		{
            $html = '';
            $seo_doctors='Врачи';
            if ($search_flags['visit_type']=='home' && $search_flags['doctor_type']!='children')
                $seo_doctors='Врачи на дом';
            else if ($search_flags['visit_type']!='home' && $search_flags['doctor_type']=='children')
                $seo_doctors='Детские врачи';
            else if ($search_flags['visit_type']=='home' && $search_flags['doctor_type']=='children')
                $seo_doctors='Детские врачи на дом';


            if (extension_loaded('morpher')) {
                if($specialty) {
                    $text = Morpher::inflect($specialty->name,'im mn');
                    if (mb_strpos(mb_strtolower($text), 'врач') === false) {
                        $text = $seo_doctors . ' ' . $text;
                    }
                    $html = StringHelper::startProposalWord($text)   . ' ' . SeoTextViewHelper::getAddressObjectName($address_object) . ' - запись на прием, цены, отзывы и рейтинги на '.SITE_NAME;
                } elseif($address_object) {
                    $html = $seo_doctors.' '.SeoTextViewHelper::getAddressObjectName($address_object) . ' - запись на прием, цены, отзывы и рейтинги на '.SITE_NAME;
                } else {
                    $html = 'Найти хорошего врача в Москве онлайн. Поиск врачей по всем специальностям, отзывы, рейтинг, запись на прием – '.SITE_NAME;
                }
            }  else {
                if($specialty) {
                    $html = $seo_doctors.' ' . SeoTextViewHelper::getAddressObjectName($address_object) . ' - запись на прием, цены, отзывы и рейтинги на '.SITE_NAME;
                } elseif($address_object) {
                    $html = $seo_doctors.' '.SeoTextViewHelper::getAddressObjectName($address_object) . ' - запись на прием, цены, отзывы и рейтинги на '.SITE_NAME;
                } else {
                    $html = 'Найти хорошего врача в Москве онлайн. Поиск врачей по всем специальностям, отзывы, рейтинг, запись на прием – '.SITE_NAME;
                }
            }


			return $html;

		}

		public static function getDescription($specialty, $address_object, $search_flags)
		{
		    $specialtyGenitiveName = $specialty ? $specialty->genitive_name : "";
            $seo_doctors='врача '. $specialtyGenitiveName;
            if (isset($search_flags['visit_type'], $search_flags['doctor_type'])) {
                if ($search_flags['visit_type']=='home' && $search_flags['doctor_type']!='children')
                    $seo_doctors='врача '.$specialtyGenitiveName.' на дом';
                else if ($search_flags['visit_type']!='home' && $search_flags['doctor_type']=='children')
                    $seo_doctors='детского врача '.$specialtyGenitiveName;
                else if ($search_flags['visit_type']=='home' && $search_flags['doctor_type']=='children')
                    $seo_doctors='детского врача '.$specialtyGenitiveName.' на дом';
            }

            $html = 'Ищете '.$seo_doctors.' '.SeoTextViewHelper::getAddressObjectName($address_object).'? '.SITE_NAME.' поможет выбрать опытного врача по отзывам и рейтингам клиентов, узнать стоимость и записаться на прием.';
            return $html;
		}

        public static function getTopNumberH1($specialty, $address_object) {
            if (!$specialty)
            {
                $text = 'Найти врача ' . self::getAddressObjectNamePagesForTop($address_object) . ' онлайн';
            }
            else
            {
                $text = 'Найти '.DOCTORA.' ' . $specialty->genitive_name . ' ' . self::getAddressObjectNamePagesForTop($address_object) . ' онлайн';
            }

            return $text;
        }

        /**
         * @param ClinicModel $clinic
         * @return string
         */
        public static function GetClinicSeoTitle( DynamicModel $clinic ) {

            if( $clinic ){

                $moderateSeo = self::getModerateClinicSeo( $clinic );
                $address_to_title = !!($clinic->type_flags & ClinicModel::TYPE_FLAG_DOCTOR);
                $metro_to_title = 0;
                $city_to_title = 0;

                if( !empty($moderateSeo) ){

                    if( $moderateSeo->seo_title != "" ){ return  $moderateSeo->seo_title; }
                    $address_to_title   = $moderateSeo->address_to_title;
                    $metro_to_title     = $moderateSeo->metro_to_title;
                    $seo_address        = $moderateSeo->seo_address;

                }

                $seo_address = !empty($seo_address) ? $seo_address : $clinic->address;
                $street = str_replace(['д.', '.'], ['', ''], $seo_address ?: '');
                $metro = $clinic->metro_station ? $clinic->metro_station->name : null;
                $city = $clinic->city->name;
                // Биомед на ул Луковского (м Суконная слобода, Казань) - врачи, отзывы, цены, телефоны и адреса, запись на прием на Loo kMedBook
                return $title = vsprintf('%s%s%s%s - врачи, отзывы, цены, телефоны и адреса, запись на прием на %s', [
                    trim($clinic->getSeoName()),
                    (!empty($metro) && $metro_to_title)    ?   " м ".trim($metro)."," :   "",
                    (!empty($street) && $address_to_title)   ?   " на ".trim($street)    :   "",
                    (!empty($city) && $city_to_title)     ?   " (". trim($city).")"   :   "",
                    SITE_NAME
                ]);
                }else{

                    return $title = 'Медицинские центры и клиники: цены, отзывы, рейтинги и запись на прием на '.SITE_NAME;

                }
        }

        /**
         * @param ClinicModel $clinic
         * @return string
         */
        public static function newGetClinicPageDescription( DynamicModel $clinic ){
            if( $clinic ){

                $moderateSeo = self::getModerateClinicSeo( $clinic );
                $address_to_description = 1;
                $metro_to_description = 1;

                if( !empty($moderateSeo) ){


                    if( $moderateSeo->seo_descritpion != "" ){ return  $moderateSeo->seo_descritpion; }
                    $address_to_description   = $moderateSeo->address_to_description;
                    $metro_to_description     = $moderateSeo->metro_to_description;
                    $seo_address              = $moderateSeo->seo_address;

                }

                $seo_address = !empty($seo_address) ? $seo_address : $clinic->address;
                if( $seo_address ){
                        $street = str_replace(['д.', '.'], ['', ''], $seo_address);
                        /* $addressArr = explode( ',', $address );
                        if(count($addressArr) >= 2){
                            $street = $addressArr[ count( $addressArr ) - 2 ].','.$addressArr[ count( $addressArr ) - 1];
                        } else {
                            $street = $address;
                        } */
                        $metro = $clinic->metro_station ? $clinic->metro_station->name : null;
                        $city = $clinic->city->name;

                        // Биомед на ул Луковского (м Суконная слобода, Казань) - врачи, отзывы, цены, телефоны и адреса, запись на прием на Loo kMedBook
                        return $description = vsprintf('Интересует %s%s%s%s? Отзывы и рейтинг от реальных клиентов, актуальные цены, телефоны и адреса, а также возможность записи на удобное время на %s. Заходите!', [
                            trim($clinic->getSeoName()),
                            (!empty($metro) && $metro_to_description)       ?   " м ".trim($metro)."," :   "",
                            (!empty($street) && $address_to_description)    ?   " на ".trim($street)    :   "",
                            (!empty($city))     ?   " (". trim($city).")"   :   "",
                            SITE_NAME
                        ]);
                        //return 'Интересует '.$clinic->name.'? Отзывы и рейтинг от реальных клиентов, актуальные цены, телефоны и адреса, а также возможность записи на удобное время на '.SITE_NAME.'. Заходите!';
                    } else {
                        return 'Ищете медицинские центры и клиники '.$clinic->name.' '. static::getSeoAddress($clinic) .'? '.SITE_NAME.' поможет выбрать лучшие клиники и медицинские центры по отзывам, рейтингу и стоимости. Заходите!';
                    }
            }
        }

    public static function getModerateClinicSeo(DynamicModel $clinic = NULL){

            if( $clinic ){

                $moderateSeo = ( new ModerateClinicSeoManager() )->getByClinicId($clinic->id);

                if( $moderateSeo ){

                    return $moderateSeo;

                }else{

                    return false;

                }

            }

        }

        public static function getSeoAddress(DynamicModel $clinic) {

                if ($clinic->district)
                  return self::getAddressObjectName($clinic->district);
                elseif ($clinic->region)
                  return self::getAddressObjectName($clinic->region);
                elseif ($clinic->street)
                  return self::getAddressObjectName($clinic->street);
                elseif ($clinic->metro_station)
                  return self::getAddressObjectName($clinic->metro_station);
                else
                  return self::getAddressObjectName($clinic->city);

        }

	}
