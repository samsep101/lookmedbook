<?php

class YandexServicesCompaniesFeedGenerator
{
  protected $dom_document_instance;
  protected $companies_node;
  protected $static_service_only_flag;

  protected $book_mode = 'static';
  protected $home_resource = 0;

  public function __construct()
  {
    $this->dom_document_instance = new DOMDocument('1.0', 'utf-8');

    $companies = $this->dom_document_instance->createElement('companies');
    $this->dom_document_instance->appendChild($companies);

    $companies->setAttribute('version', '1.0');
    $companies->setAttribute('xmlns:xi', 'http://www.w3.org/2001/XInclude');
    $companies->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
    $companies->setAttribute('xsi:noNamespaceSchemaLocation', 'companies.xsd');

    $this->companies_node = $companies;
  }

  public function saveClinicProblem($clinic_id, $problem)
  {
    $clinic_problem = new ClinicProblemModel();
    $clinic_problem->clinic_id = $clinic_id;
    $clinic_problem->text = $problem;
    $clinic_problem->dt = date('Y-m-d H:i:s');

    $clinic_problem->save();
  }

  public function static_clinic_validate($clinic)
  {
    if ($clinic->getId() == 80)
      return false;

    if (!$clinic->just_its_specialties && !$clinic->not_virtual_doctors) {
      $this->saveClinicProblem($clinic->getId(), ClinicProblemModel::BLIND);
      return false;
    } else if ($clinic->just_its_specialties && !$clinic->not_virtual_doctors) {
      //$this->saveClinicProblem($clinic->getId(), ClinicProblemModel::STATIC_SERVICE_ONLY);
      $this->static_service_only_flag = true;
      $this->book_mode = 'static-service-only';
      //return false;
    } /*else if (!$clinic->just_its_specialties && $clinic->doctors)
            {
                $this->saveClinicProblem($clinic->getId(), ClinicProblemModel::STATIC_RESOURCE_ONLY);
                return false;
            }*/
    else {
      $match_flag = true;
      /*
      foreach ($clinic->just_its_specialties as $specialty)
      {
          $clinic_doctor = ModelManagerFactory::getByName('doctor_specialty_to_clinic')->getOneByClinicIdAndSpecialtyId($clinic->getId(), $specialty->getId());
          if (!$clinic_doctor)
          {
              $this->saveClinicProblem($clinic->getId(), 'По специализации '.$specialty->name.' отсутствуют врачи');
              $match_flag = false;
          }
      }
*/
      $doctor_specialties_to_clinic = ModelManagerFactory::getByName('doctor_specialty_to_clinic')->getListByClinicId($clinic->getId());
      foreach ($doctor_specialties_to_clinic as $doctor_specialty_to_clinic) {
        if ($doctor_specialty_to_clinic and !empty($doctor_specialty_to_clinic->doctor) and !empty($doctor_specialty_to_clinic->doctor->is_virtual)) {
          $specialty_to_clinic = ModelManagerFactory::getByName('specialty_to_clinic')->checkExistsBySpecialtyIdAndClinicId($doctor_specialty_to_clinic->specialty_id, $clinic->getId());
          if (!$specialty_to_clinic) {
            $this->saveClinicProblem($clinic->getId(), 'Врач ' . $doctor_specialty_to_clinic->doctor->full_name . ' привязан к клинике, хотя клиника не работает по специальности ' . $doctor_specialty_to_clinic->specialty->name);

            //$match_flag = false;
          }
        }
      }

      $real_clinic_doctors = $clinic->getRealClinicDoctors();
      if (count($real_clinic_doctors) == 1) {
        if ($real_clinic_doctors[0]->reviews_count == 0) {
          $this->saveClinicProblem($clinic->getId(), 'Клиника с 1 врачом и без отзывов, невозможно бронирование в режиме static');
          $match_flag = false;
        }
      }

      if (!$match_flag) return false;
    }
    return true;
  }

  public function generate($feed_version)
  {
    $clinic_manager = new ClinicManager();

    $city_id_list = array(CityModel::MOSCOW_ID, CityModel::NOVOSIBIRSK_ID, CityModel::SOLNECHNOGORSK_ID, CityModel::LUBERTSI_ID);

    $search_params = new SearchParams();
    $search_params->addParam('is_active', 1);

    if ($feed_version < 3) {
      $search_params->addParam('is_yandex_send', 1);
      $search_params->addParam('city_id IN', $city_id_list, 'city');
    } elseif ($feed_version == 4) {
      $clinicFilter = array(2756, 1153, 174, 182, 312, 1606, 1166, 1165, 1607, 1159, 1157, 1604,
        1605, 1768, 1769, 1770, 1160, 1163, 2116, 52, 143, 2500, 88, 108, 2501, 2761,
        2125, 309, 116, 36, 3515, 168, 241, 3480, 1155, 802, 2507, 3471, 3484, 3486,
        3487, 3488, 3489, 3490, 3491, 3492, 3493, 3494, 3495, 226, 3531, 3534, 319,
        3538, 3540, 3541, 3542, 3543, 3544, 3545, 3546, 3547, 3548, 3549, 3550,
        1045, 775, 303, 3479, 275, 2, 2144, 2145, 2241, 1612, 3604, 227, 173, 175,
        176, 189, 254, 805, 3474, 3507, 67, 132, 3508, 313, 153, 157, 127, 125);
      $search_params->addParam('id IN', $clinicFilter);
    }

    $clinics = $clinic_manager->getListBySearchParams($search_params);

    $clinic_problem_manager = new ClinicProblemManager();
    $clinic_problem_manager->truncateTable();

    foreach ($clinics as $clinic) {
      $this->static_service_only_flag = false;
      $this->book_mode = 'static';
      $this->home_resource = 0;
      $static_match = $this->static_clinic_validate($clinic);

      if ($static_match) {
        if ($feed_version == 1 || $feed_version == 3 || $feed_version == 4) {
          $this->createClinicNode($clinic);
        } else if ($feed_version == 2) {
          $this->createClinicNodeNew($clinic);
        }
      }
    }

    if ($feed_version == 1 || $feed_version == 3 || $feed_version == 4) {
      $known_features_node = $this->dom_document_instance->createElement('known-features');

      $feature_node = $this->dom_document_instance->createElement('known-enum-single');
      $feature_node->setAttribute('name', 'hogh_school_ownship');
      $known_features_node->appendChild($feature_node);

      $feature_node = $this->dom_document_instance->createElement('enum-value', 'state_high_school');
      $feature_node->setAttribute('name', 'hogh_school_ownship');
      $known_features_node->appendChild($feature_node);

      $feature_node = $this->dom_document_instance->createElement('enum-value', 'nonstate_high_school');
      $feature_node->setAttribute('name', 'hogh_school_ownship');
      $known_features_node->appendChild($feature_node);

      $feature_node = $this->dom_document_instance->createElement('known-boolean');
      $feature_node->setAttribute('name', 'home_visit');
      $known_features_node->appendChild($feature_node);

      $this->companies_node->appendChild($known_features_node);
    }

    $this->dom_document_instance->formatOutput = true;

    if ($feed_version == 1) {
      $this->dom_document_instance->save('booking/test.xml');
    } else if ($feed_version == 2) {
      $this->dom_document_instance->save('booking/test-2.xml');
    } else if ($feed_version == 3) {
      $this->dom_document_instance->save('booking/yandex-all-clinic.xml');
    } else if ($feed_version == 4) {
      $this->dom_document_instance->save('booking/a5_top50_clinics_lmb.xml');
    }
  }

  private function createClinicNode(ClinicModel $clinic)
  {
    $dom = $this->dom_document_instance;

    $company_node = $this->dom_document_instance->createElement('company');
    $company_node->setAttribute('id', $clinic->getId());

    $company_node->appendChild($this->dom_document_instance->createElement('book-mode', $this->book_mode));

    $name_node = $this->dom_document_instance->createElement('name', $this->replaceIllegalCharacters($clinic->name));
    $name_node->setAttribute('lang', 'ru');
    $company_node->appendChild($name_node);

    $company_node->appendChild($dom->createElement('post-index', $clinic->postcode));

    $address_node = $dom->createElement('address', $clinic->city->name . ', ' . $clinic->address);
    $address_node->setAttribute('lang', 'ru');
    $company_node->appendChild($address_node);

    $country_node = $dom->createElement('country', 'Россия');
    $country_node->setAttribute('lang', 'ru');
    $company_node->appendChild($country_node);

    $admin_area_node = $dom->createElement('admn-area', $clinic->city->region);
    $admin_area_node->setAttribute('lang', 'ru');
    $company_node->appendChild($admin_area_node);

    $locality_name_node = $dom->createElement('locality-name', $clinic->city->name);
    $locality_name_node->setAttribute('lang', 'ru');
    $company_node->appendChild($locality_name_node);

    if ($clinic->street) {
      $street_node = $dom->createElement('street', $clinic->street->name . ' ' . $clinic->street->street_type->name);
      $street_node->setAttribute('lang', 'ru');
      $company_node->appendChild($street_node);
    }

    $house_node = $dom->createElement('house', $clinic->house);
    $company_node->appendChild($house_node);

    $coordinates_node = $dom->createElement('coordinates');
    $coordinates_node->appendChild($dom->createElement('lon', (float)$clinic->longitude));
    $coordinates_node->appendChild($dom->createElement('lat', (float)$clinic->latitude));
    $company_node->appendChild($coordinates_node);

    if ($clinic->phones) {
      foreach ($clinic->phones as $phone) {
        $phone_node = $dom->createElement('phone');
        $phone_node->appendChild($dom->createElement('ext'));
        $phone_node->appendChild($dom->createElement('info'));
        $phone_node->appendChild($dom->createElement('type', 'phone'));
        $phone_node->appendChild($dom->createElement('number', PhoneFormatViewHelper::view_with_brackets($phone->phone_number)));

        $company_node->appendChild($phone_node);
      }
    }

    if ($clinic->emails) {
      $email_node = $dom->createElement('email', $clinic->emails[0]->email);
      $company_node->appendChild($email_node);
    }

    $company_node->appendChild($dom->createElement('info-page', ClinicPageLinkViewHelper::getLink($clinic)));

    $company_node->appendChild($dom->createElement('rubric-id', 184106108));
    $company_node->appendChild($dom->createElement('rubric-id', 184106104));
    if ($clinic->clinic_type_id == ClinicTypeModel::SPECIFIC) {
      $specialty_to_clinic_1 = ModelManagerFactory::getByName('doctor_specialty_to_clinic')->getOneByClinicIdAndSpecialtyId($clinic->getId(), SpecialtyModel::GYNECOLOGIST);
      $specialty_to_clinic_2 = ModelManagerFactory::getByName('doctor_specialty_to_clinic')->getOneByClinicIdAndSpecialtyId($clinic->getId(), SpecialtyModel::OBSTETRICIAN_GYNECOLOGIST);
      if ($specialty_to_clinic_1 || $specialty_to_clinic_2)
        $company_node->appendChild($dom->createElement('rubric-id', 53437260559));
    } else if ($clinic->clinic_type_id == ClinicTypeModel::STOMATOLOGY) {
      $company_node->appendChild($dom->createElement('rubric-id', 184106130));
    } else if ($clinic->clinic_type_id == ClinicTypeModel::PLASTICS) {
      $company_node->appendChild($dom->createElement('rubric-id', 184105808));
    }

    if ($clinic->is_children == 1)
      $company_node->appendChild($dom->createElement('rubric-id', 184105986));
    else
      $company_node->appendChild($dom->createElement('rubric-id', 184106014));

    $company_node->appendChild($dom->createElement('actualization-date', DateViewHelper::getActualizationDate()));

    if ($clinic->card_image || $clinic->images) {
      $photo_counter = 1;
      $photos_node = $dom->createElement('photos');
      $photos_node->setAttribute('gallery-url', ClinicPageLinkViewHelper::getLink($clinic));
      if ($clinic->card_image) {
        $photo_node = $dom->createElement('photo');
        $photo_node->setAttribute('type', 'logo');
        $photo_node->setAttribute('url', SITE_URL . $clinic->card_image->path);
        $photo_node->setAttribute('alt', $clinic->name . ', фотография ' . $photo_counter);
        $photos_node->appendChild($photo_node);
        $photo_counter++;
      }

      if ($clinic->images) {
        foreach ($clinic->images as $image) {
          $photo_node = $dom->createElement('photo');
          $photo_node->setAttribute('url', SITE_URL . $image->crop(475, 300)->path);
          $photo_node->setAttribute('alt', $clinic->name . ', фотография ' . $photo_counter);
          $photos_node->appendChild($photo_node);
          $photo_counter++;
        }
      }

      $company_node->appendChild($photos_node);
    }

    $reviews_node = $dom->createElement('reviews');

    if ($clinic->reviews) {
      foreach ($clinic->reviews as $review) {
        $review_node = $dom->createElement('review');
        $review_node->appendChild($dom->createElement('locale', 'ru'));
        $review_node->appendChild($dom->createElement('url', ClinicPageLinkViewHelper::getLink($clinic) . '#clinic-review-' . $review->getId()));
        $review_node->appendChild($dom->createElement('description', $review->text));
        $review_node->appendChild($dom->createElement('rating', $review->total_rating));

        $reviewer_node = $dom->createElement('reviewer');
        $vcard_node = $reviewer_node->appendChild($dom->createElement('vcard'));
        if(!empty($review->account)) {
          $chld = $dom->createElement('fn', $review->account->nick);
          $vcard_node->appendChild($chld);
        }
        $reviewer_node->appendChild($vcard_node);
        $review_node->appendChild($reviewer_node);

        $review_node->appendChild($dom->createElement('reviewsurl', ClinicPageLinkViewHelper::getLink($clinic)));
        $review_node->appendChild($dom->createElement('dtreviewed', str_replace(' ', 'T', $review->dt)));

        $reviews_node->appendChild($review_node);
      }
    } else {
      $review_node = $dom->createElement('review');
      $reviewer_node = $dom->createElement('reviewer');
      $vcard_node = $reviewer_node->appendChild($dom->createElement('vcard'));
      $review_node->appendChild($dom->createElement('locale', ''));
      $review_node->appendChild($dom->createElement('url', ''));
      $review_node->appendChild($dom->createElement('description', ''));
      $review_node->appendChild($dom->createElement('rating', ''));
      $vcard_node->appendChild($dom->createElement('fn', ''));
      $reviewer_node->appendChild($vcard_node);
      $review_node->appendChild($reviewer_node);
      $review_node->appendChild($dom->createElement('reviewsurl', ''));
      $review_node->appendChild($dom->createElement('dtreviewed', ''));
      $reviews_node->appendChild($review_node);
    }

    $company_node->appendChild($reviews_node);

    if (!$this->static_service_only_flag) {
      $clinic_specialties = $clinic->doctor_specialties;
      if ($clinic_specialties) {
        $services_node = $dom->createElement('services');

        foreach ($clinic_specialties as $specialty) {
          $doctor_specialties_to_clinic = ModelManagerFactory::getByName('doctor_specialty_to_clinic')->getListByClinicIdAndSpecialtyId($clinic->getId(), $specialty->getId());

          if ($clinic->not_virtual_doctors && $doctor_specialties_to_clinic) {
            $active = false;
            foreach ($doctor_specialties_to_clinic as $record) {
              if ($record and !empty($record->doctor) and !empty($record->doctor->is_active) and !empty($record->doctor->is_virtual)) {
                $active = true;
              }
            }
            if ($active) {
              $service_node = $dom->createElement('service');
              $service_node->setAttribute('id', $specialty->getId());
              $service_node->appendChild($dom->createElement('title', $specialty->name));

              $min_price = $clinic->getMinFirstVisitPriceBySpecialtyIdAndPurposeOfVisitId($specialty->getId(), PurposeOfVisitModel::FIRST_VISIT_ID);
              if ($min_price) {
                $price_node = $dom->createElement('price');
                $price_node->setAttribute('currency', 'RUB');
                $price_node->setAttribute('value', $min_price);
                $price_node->setAttribute('not-less', true);
                $service_node->appendChild($price_node);
              }

              $schedules_node = $dom->createElement('schedules');

              foreach ($doctor_specialties_to_clinic as $doctor_specialty_to_clinic) {
                if ($doctor_specialty_to_clinic->doctor->is_active == 1 && !$doctor_specialty_to_clinic->doctor->is_virtual) {
                  $schedule_node = $dom->createElement('schedule');
                  $schedule_node->setAttribute('res-id', $doctor_specialty_to_clinic->doctor_id);

                  $schedules_node->appendChild($schedule_node);
                }
              }
              $service_node->appendChild($schedules_node);

              $services_node->appendChild($service_node);
            }
          }
        }
        $company_node->appendChild($services_node);
      }

      if ($clinic->not_virtual_doctors) {
        $resources_node = $dom->createElement('resources');

        foreach ($clinic->not_virtual_doctors as $doctor) {
          $resource_node = $dom->createElement('resource');
          $resource_node->setAttribute('id', $doctor->getId());
          $resource_node->appendChild($dom->createElement('name', $doctor->full_name));
          $resource_node->appendChild($dom->createElement('description', $doctor->specialties_names));
          if ($doctor->card_image_id and !empty($doctor->card_image)) {
            $resource_node->appendChild($dom->createElement('photo', SITE_URL . $doctor->card_image->crop(100, 100)->path));
          }
          if ($doctor->last_review) {
            $reviews_node = $dom->createElement('reviews');


            $review_node = $dom->createElement('review');
            $review_node->appendChild($dom->createElement('locale', 'ru'));
            $review_node->appendChild($dom->createElement('url', DoctorPageLinkViewHelper::getLink($doctor) . '#doctor-review-' . $doctor->last_review->getId()));
            $review_node->appendChild($dom->createElement('description', $doctor->last_review->text));
            $review_node->appendChild($dom->createElement('rating', $doctor->last_review->total_rating));

            $reviewer_node = $dom->createElement('reviewer');

            $vcard_node = $reviewer_node->appendChild($dom->createElement('vcard'));
            $vcard_node->appendChild($dom->createElement('fn', $doctor->last_review->account->nick));
            $reviewer_node->appendChild($vcard_node);
            $review_node->appendChild($reviewer_node);

            $review_node->appendChild($dom->createElement('reviewsurl', DoctorPageLinkViewHelper::getLink($doctor)));
            $review_node->appendChild($dom->createElement('dtreviewed', str_replace(' ', 'T', $doctor->last_review->dt)));

            $reviews_node->appendChild($review_node);


            $resource_node->appendChild($reviews_node);
          }

          $this->home_resource = ($doctor->is_leave_the_house) ? 1 : 0;

          $resources_node->appendChild($resource_node);
        }
        $company_node->appendChild($resources_node);
      }
    } else {
      $services_node = $dom->createElement('services');

      foreach ($clinic->just_its_specialties as $specialty) {
        $service_node = $dom->createElement('service');
        $service_node->setAttribute('id', $specialty->getId());
        $service_node->appendChild($dom->createElement('title', $specialty->name));

        $min_price = $clinic->getMinFirstVisitPriceBySpecialtyIdAndPurposeOfVisitId($specialty->getId(), PurposeOfVisitModel::FIRST_VISIT_ID);
        if ($min_price) {
          $price_node = $dom->createElement('price');
          $price_node->setAttribute('currency', 'RUB');
          $price_node->setAttribute('value', $min_price);
          $price_node->setAttribute('not-less', true);
          $service_node->appendChild($price_node);
        }

        $schedules_node = $dom->createElement('schedules');

        $schedule_node = $dom->createElement('schedule');
        $schedule_node->setAttribute('res-id', DoctorModel::RESERVED_DOCTOR_SLOT);
        $schedules_node->appendChild($schedule_node);

        $service_node->appendChild($schedules_node);

        $services_node->appendChild($service_node);
      }

      $company_node->appendChild($services_node);

      $resources_node = $dom->createElement('resources');

      $reserved_doctor = ModelManagerFactory::getByName('doctor')->getOneById(DoctorModel::RESERVED_DOCTOR_SLOT);

      $resource_node = $dom->createElement('resource');
      $resource_node->setAttribute('id', $reserved_doctor->getId());
      $resource_node->appendChild($dom->createElement('name', trim($reserved_doctor->full_name)));
      $resources_node->appendChild($resource_node);

      $company_node->appendChild($resources_node);
    }

    $clinic_type = ($clinic->is_state) ? 'state_high_school' : 'nonstate_high_school';
    $this->createCompanyFeatureNode($company_node, 'feature-enum-single', 'hogh_school_ownship', $clinic_type);

    if ($this->home_resource)
      $this->createCompanyFeatureNode($company_node, 'feature-boolean', 'home_visit', $this->home_resource);

    $this->companies_node->appendChild($company_node);
  }

  private function createClinicNodeNew(ClinicModel $clinic)
  {
    $dom = $this->dom_document_instance;

    $company_node = $this->dom_document_instance->createElement('company');
    $company_node->setAttribute('id', $clinic->getId());

    $company_node->appendChild($this->dom_document_instance->createElement('book-mode', $this->book_mode));

    $name_node = $this->dom_document_instance->createElement('name', $this->replaceIllegalCharacters($clinic->name));
    $name_node->setAttribute('lang', 'ru');
    $company_node->appendChild($name_node);

    $company_node->appendChild($dom->createElement('post-index', $clinic->postcode));

    $address_node = $dom->createElement('address', $clinic->city->name . ', ' . $clinic->address);
    $address_node->setAttribute('lang', 'ru');
    $company_node->appendChild($address_node);

    $country_node = $dom->createElement('country', 'Россия');
    $country_node->setAttribute('lang', 'ru');
    $company_node->appendChild($country_node);

    $admin_area_node = $dom->createElement('admn-area', $clinic->city->region);
    $admin_area_node->setAttribute('lang', 'ru');
    $company_node->appendChild($admin_area_node);

    $locality_name_node = $dom->createElement('locality-name', $clinic->city->name);
    $locality_name_node->setAttribute('lang', 'ru');
    $company_node->appendChild($locality_name_node);

    if ($clinic->street) {
      $street_node = $dom->createElement('street', $clinic->street->name . ' ' . $clinic->street->street_type->name);
      $street_node->setAttribute('lang', 'ru');
      $company_node->appendChild($street_node);
    }

    $house_node = $dom->createElement('house', $clinic->house);
    $company_node->appendChild($house_node);

    $coordinates_node = $dom->createElement('coordinates');
    $coordinates_node->appendChild($dom->createElement('lon', (float)$clinic->longitude));
    $coordinates_node->appendChild($dom->createElement('lat', (float)$clinic->latitude));
    $company_node->appendChild($coordinates_node);

    if ($clinic->phones) {
      foreach ($clinic->phones as $phone) {
        $phone_node = $dom->createElement('phone');
        $phone_node->appendChild($dom->createElement('ext'));
        $phone_node->appendChild($dom->createElement('info'));
        $phone_node->appendChild($dom->createElement('type', 'phone'));
        $phone_node->appendChild($dom->createElement('number', PhoneFormatViewHelper::view_with_brackets($phone->phone_number)));

        $company_node->appendChild($phone_node);
      }
    }

    if ($clinic->emails) {
      $email_node = $dom->createElement('email', $clinic->emails[0]->email);
      $company_node->appendChild($email_node);
    }

    $company_node->appendChild($dom->createElement('info-page', ClinicPageLinkViewHelper::getLink($clinic)));

    $company_node->appendChild($dom->createElement('rubric-id', 184106108));
    $company_node->appendChild($dom->createElement('rubric-id', 184106104));
    if ($clinic->clinic_type_id == ClinicTypeModel::SPECIFIC) {
      $specialty_to_clinic_1 = ModelManagerFactory::getByName('doctor_specialty_to_clinic')->getOneByClinicIdAndSpecialtyId($clinic->getId(), SpecialtyModel::GYNECOLOGIST);
      $specialty_to_clinic_2 = ModelManagerFactory::getByName('doctor_specialty_to_clinic')->getOneByClinicIdAndSpecialtyId($clinic->getId(), SpecialtyModel::OBSTETRICIAN_GYNECOLOGIST);
      if ($specialty_to_clinic_1 || $specialty_to_clinic_2)
        $company_node->appendChild($dom->createElement('rubric-id', 53437260559));
    } else if ($clinic->clinic_type_id == ClinicTypeModel::STOMATOLOGY) {
      $company_node->appendChild($dom->createElement('rubric-id', 184106130));
    } else if ($clinic->clinic_type_id == ClinicTypeModel::PLASTICS) {
      $company_node->appendChild($dom->createElement('rubric-id', 184105808));
    }

    if ($clinic->is_children == 1)
      $company_node->appendChild($dom->createElement('rubric-id', 184105986));
    else
      $company_node->appendChild($dom->createElement('rubric-id', 184106014));

    $company_node->appendChild($dom->createElement('actualization-date', DateViewHelper::getActualizationDate()));

    if ($clinic->card_image || $clinic->images) {
      $photo_counter = 1;
      $photos_node = $dom->createElement('photos');
      $photos_node->setAttribute('gallery-url', ClinicPageLinkViewHelper::getLink($clinic));
      if ($clinic->card_image) {
        $photo_node = $dom->createElement('photo');
        $photo_node->setAttribute('type', 'logo');
        $photo_node->setAttribute('url', SITE_URL . $clinic->card_image->path);
        $photo_node->setAttribute('alt', $clinic->name . ', фотография ' . $photo_counter);
        $photos_node->appendChild($photo_node);
        $photo_counter++;
      }

      if ($clinic->images) {
        foreach ($clinic->images as $image) {
          $photo_node = $dom->createElement('photo');
          $photo_node->setAttribute('url', SITE_URL . $image->crop(475, 300)->path);
          $photo_node->setAttribute('alt', $clinic->name . ', фотография ' . $photo_counter);
          $photos_node->appendChild($photo_node);
          $photo_counter++;
        }
      }

      $company_node->appendChild($photos_node);
    }

    $reviews_node = $dom->createElement('reviews');

    if ($clinic->reviews) {

      foreach ($clinic->reviews as $review) {
        $review_node = $dom->createElement('review');
        $review_node->appendChild($dom->createElement('locale', 'ru'));
        $review_node->appendChild($dom->createElement('url', ClinicPageLinkViewHelper::getLink($clinic) . '#clinic-review-' . $review->getId()));
        $review_node->appendChild($dom->createElement('description', $review->text));
        $review_node->appendChild($dom->createElement('rating', $review->total_rating));

        $reviewer_node = $dom->createElement('reviewer');
        $vcard_node = $reviewer_node->appendChild($dom->createElement('vcard'));
        if(!empty($review->account)) {
          $vcard_node->appendChild($dom->createElement('fn', $review->account->nick));
        }
        $reviewer_node->appendChild($vcard_node);
        $review_node->appendChild($reviewer_node);

        $review_node->appendChild($dom->createElement('reviewsurl', ClinicPageLinkViewHelper::getLink($clinic)));
        $review_node->appendChild($dom->createElement('dtreviewed', str_replace(' ', 'T', $review->dt)));

        $reviews_node->appendChild($review_node);
      }
    } else {
      $review_node = $dom->createElement('review');
      $reviewer_node = $dom->createElement('reviewer');
      $vcard_node = $reviewer_node->appendChild($dom->createElement('vcard'));
      $reviews_node->appendChild($review_node);
      $review_node->appendChild($dom->createElement('locale', ''));
      $review_node->appendChild($dom->createElement('url', ''));
      $review_node->appendChild($dom->createElement('description', ''));
      $review_node->appendChild($dom->createElement('rating', ''));
      $vcard_node->appendChild($dom->createElement('fn', ''));
      $reviewer_node->appendChild($vcard_node);
      $review_node->appendChild($reviewer_node);
      $review_node->appendChild($dom->createElement('reviewsurl', ''));
      $review_node->appendChild($dom->createElement('dtreviewed', ''));
    }

    $company_node->appendChild($reviews_node);

    if (!$this->static_service_only_flag) {
      $clinic_specialties = $clinic->doctor_specialties;
      if ($clinic_specialties) {
        $services_node = $dom->createElement('services');

        foreach ($clinic_specialties as $specialty) {
          $doctor_specialties_to_clinic = ModelManagerFactory::getByName('doctor_specialty_to_clinic')->getListByClinicIdAndSpecialtyId($clinic->getId(), $specialty->getId());

          if ($clinic->not_virtual_doctors && $doctor_specialties_to_clinic) {
            $active = false;
            foreach ($doctor_specialties_to_clinic as $record) {
              if ($record->doctor->is_active && !$record->doctor->is_virtual)
                $active = true;
            }
            if ($active) {
              $service_node = $dom->createElement('service');
              $service_node->setAttribute('id', $specialty->getId());
              $service_node->appendChild($dom->createElement('title', $specialty->name));

              $min_price = $clinic->getMinFirstVisitPriceBySpecialtyIdAndPurposeOfVisitId($specialty->getId(), PurposeOfVisitModel::FIRST_VISIT_ID);
              if ($min_price) {
                $price_node = $dom->createElement('price');
                $price_node->setAttribute('currency', 'RUB');
                $price_node->setAttribute('value', $min_price);
                $price_node->setAttribute('not-less', true);
                $service_node->appendChild($price_node);
              }

              $schedules_node = $dom->createElement('schedules');

              foreach ($doctor_specialties_to_clinic as $doctor_specialty_to_clinic) {
                if (!empty($doctor_specialty_to_clinic->doctor) and is_object($doctor_specialty_to_clinic->doctor) and $doctor_specialty_to_clinic->doctor->is_active == 1 and !$doctor_specialty_to_clinic->doctor->is_virtual) {
                  $schedule_node = $dom->createElement('schedule');
                  $schedule_node->setAttribute('res-id', $doctor_specialty_to_clinic->doctor_id);

                  $schedules_node->appendChild($schedule_node);
                }
              }
              $service_node->appendChild($schedules_node);

              $services_node->appendChild($service_node);
            }
          }
        }
        $company_node->appendChild($services_node);
      }

      if ($clinic->not_virtual_doctors) {
        $resources_node = $dom->createElement('resources');

        foreach ($clinic->not_virtual_doctors as $doctor) {
          $resource_node = $dom->createElement('resource');
          $resource_node->setAttribute('id', $doctor->getId());
          $resource_node->appendChild($dom->createElement('name', $doctor->full_name));
          $resource_node->appendChild($dom->createElement('description', $doctor->specialties_names));
          if ($doctor->card_image_id and !empty($doctor->card_image)) {
            $resource_node->appendChild($dom->createElement('photo', SITE_URL . $doctor->card_image->crop(100, 100)->path));
          }
          if ($doctor->last_review) {
            $reviews_node = $dom->createElement('reviews');


            $review_node = $dom->createElement('review');
            $review_node->appendChild($dom->createElement('locale', 'ru'));
            $review_node->appendChild($dom->createElement('url', DoctorPageLinkViewHelper::getLink($doctor) . '#doctor-review-' . $doctor->last_review->getId()));
            $review_node->appendChild($dom->createElement('description', $doctor->last_review->text));
            $review_node->appendChild($dom->createElement('rating', $doctor->last_review->total_rating));

            $reviewer_node = $dom->createElement('reviewer');

            $vcard_node = $reviewer_node->appendChild($dom->createElement('vcard'));
            $vcard_node->appendChild($dom->createElement('fn', $doctor->last_review->account->nick));
            $reviewer_node->appendChild($vcard_node);
            $review_node->appendChild($reviewer_node);

            $review_node->appendChild($dom->createElement('reviewsurl', DoctorPageLinkViewHelper::getLink($doctor)));
            $review_node->appendChild($dom->createElement('dtreviewed', str_replace(' ', 'T', $doctor->last_review->dt)));

            $reviews_node->appendChild($review_node);


            $resource_node->appendChild($reviews_node);
          }

          $resources_node->appendChild($resource_node);
        }
        $company_node->appendChild($resources_node);
      }
    } else {
      $services_node = $dom->createElement('services');

      foreach ($clinic->just_its_specialties as $specialty) {
        $service_node = $dom->createElement('service');
        $service_node->setAttribute('id', $specialty->getId());
        $service_node->appendChild($dom->createElement('title', $specialty->name));

        $min_price = $clinic->getMinFirstVisitPriceBySpecialtyIdAndPurposeOfVisitId($specialty->getId(), PurposeOfVisitModel::FIRST_VISIT_ID);
        if ($min_price) {
          $price_node = $dom->createElement('price');
          $price_node->setAttribute('currency', 'RUB');
          $price_node->setAttribute('value', $min_price);
          $price_node->setAttribute('not-less', true);
          $service_node->appendChild($price_node);
        }

        $schedules_node = $dom->createElement('schedules');

        $schedule_node = $dom->createElement('schedule');
        $schedule_node->setAttribute('res-id', DoctorModel::RESERVED_DOCTOR_SLOT);
        $schedules_node->appendChild($schedule_node);

        $service_node->appendChild($schedules_node);

        $services_node->appendChild($service_node);
      }

      $company_node->appendChild($services_node);

      $resources_node = $dom->createElement('resources');

      $reserved_doctor = ModelManagerFactory::getByName('doctor')->getOneById(DoctorModel::RESERVED_DOCTOR_SLOT);

      $resource_node = $dom->createElement('resource');
      $resource_node->setAttribute('id', $reserved_doctor->getId());
      $resource_node->appendChild($dom->createElement('name', trim($reserved_doctor->full_name)));
      $resources_node->appendChild($resource_node);

      $company_node->appendChild($resources_node);
    }

    $this->companies_node->appendChild($company_node);
  }

  protected function createCompanyFeatureNode($node, $element, $name, $value)
  {
    $feature_node = $this->dom_document_instance->createElement($element);
    $feature_node->setAttribute('name', $name);
    $feature_node->setAttribute('value', $value);
    $node->appendChild($feature_node);
  }

  protected function replaceIllegalCharacters($text)
  {
    $illegalCharacters = array(
      '"', '©', '®', '™', '?', 'Ј', '„', '“', '«', '»', '>', '<', '≥', '≤', '≈', '≠', '≡', '§', '&', '∞', '\''
    );
    $replaceCharacters = array(
      '&quot;', '&copy;', '&reg;', '&trade;', '&euro;', '&pound;', '&bdquo;', '&ldquo;',
      '&laquo;', '&raquo;', '&gt;', '&lt;', '&ge;', '&le;', '&asymp;', '&ne;', '&equiv;',
      '&sect;', '&amp;', '&infin;', '&apos;'
    );
    return str_replace($illegalCharacters, $replaceCharacters, $text);
  }
}