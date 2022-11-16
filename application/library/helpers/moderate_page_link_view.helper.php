<?php
	class ModeratePageLinkViewHelper
	{
		public function getView(array $info)
		{
			$clinic = NULL;
			$doctor = NULL;
			switch ($info['type_id'])
			{
				case ModeratePageTypeModel::CLINIC_ABOUT:
				case ModeratePageTypeModel::CLINIC_DESCRIPTION:
				case ModeratePageTypeModel::CLINIC_FEATURES:
				case ModeratePageTypeModel::CLINIC_LICENSE:
				case ModeratePageTypeModel::CLINIC_REQUISITES:
				case ModeratePageTypeModel::CLINIC_SPECIALTIES:
                //case ModeratePageTypeModel::CLINIC_TIME:
				case ModeratePageTypeModel::CLINIC_PHOTOS:
					$clinic = ModelManagerFactory::getByName('clinic')->getOneById($info['entry_id']);
					break;
				case ModeratePageTypeModel::DOCTOR_ABOUT:
				case ModeratePageTypeModel::DOCTOR_SPECIALTIES:
				case ModeratePageTypeModel::DOCTOR_PHOTOS:
					$doctor = ModelManagerFactory::getByName('doctor')->getOneById($info['entry_id']);
					break;
			}

			if ($doctor)
			{
				$clinic = $doctor->clinic;
			}

			if (!$clinic)
			{
				return;
			}

			$text = $clinic->name.': ';
			$link = '';

			switch ($info['type_id'])
			{
				case ModeratePageTypeModel::CLINIC_ABOUT:
					$text .= 'О клинике';
					$link .= 'clinic/information';
					break;
				case ModeratePageTypeModel::CLINIC_DESCRIPTION:
					$text .= 'Описание';
					$link .= 'clinic/description';
					break;
				case ModeratePageTypeModel::CLINIC_FEATURES:
					$text .= 'Сервис';
					$link .= 'clinic/service';
					break;
				case ModeratePageTypeModel::CLINIC_LICENSE:
					$text .= 'Лицензия';
					$link .= 'clinic/license';
					break;
				case ModeratePageTypeModel::CLINIC_PHOTOS:
					$text .= 'Фотографии';
					$link .= 'clinic/photos';
					break;
				case ModeratePageTypeModel::CLINIC_REQUISITES:
					$text .= 'Реквизиты';
					$link .= 'clinic/requisites';
					break;
				case ModeratePageTypeModel::CLINIC_SPECIALTIES:
					$text .= 'Услуги';
					$link .= 'clinic/services';
					break;
				case ModeratePageTypeModel::DOCTOR_ABOUT:
					$text .= 'О враче '.$doctor->full_name;
					$link .= 'doctor/information';
					break;
				case ModeratePageTypeModel::DOCTOR_SPECIALTIES:
					$text .= 'Специальности врача: '.$doctor->full_name;
					$link .= 'doctor/specialties';
					break;
				case ModeratePageTypeModel::DOCTOR_PHOTOS:
					$text .= 'Фотографии врача: '.$doctor->full_name;
					$link .= 'doctor/photos';
					break;
				/*
                case ModeratePageTypeModel::CLINIC_TIME:
                    $text .= 'Время работы клиники';
                    $link .= 'clinic/time';
                    break;
				*/
			}

			$html = '<a href="/registry/'.$link.'?';

			if ($doctor)
			{
				$html .= 'id='.$doctor->getId();
			} else {
				$html .= 'clinic_id='.$clinic->getId();
			}

			$html .= '">'.$text.'</a>';

			return $html;
		}

        public static  function getClinicOrDoctorLinkView($entry, $type)
        {
            if (!$entry)
            {
                return '';
            }

            $link = '';
            switch ($type)
            {
                case 'clinic':
                    $text = $entry->name;
                    $link .= 'clinic/information';
                    break;
                case 'doctor':
                    $text = $entry->full_name;
                    $link .= 'doctor/information';
                    break;
            }


            $html = '<a href="/registry/'.$link.'?';

            if ($type == 'doctor')
            {
                $html .= 'id='.$entry->getId();
            } else {
                $html .= 'clinic_id='.$entry->getId();
            }

            $html .= '">'.$text.'</a>';

            return $html;
        }
	}