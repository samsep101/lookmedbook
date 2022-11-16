<?php

class SystemController extends BaseController
{

  public function __construct()
  {
    SiteStatisticAccessHelper::checkAccess();
  }

  public function index()
  {
    ini_set('memory_limit', '2048M');
    global $memory_allocation_costil1;
    $memory_allocation_costil1 = 1;
    $this->layout = 'system';

    $docx_error = $this->request('docx_error');

    $site_statistic_manager = new SiteStatisticManager();

    $this->view->current_statistic_information = $site_statistic_manager->getCurrentStatisticInformation();

    $city_manager = new CityManager();
    $cities = $city_manager->getList();

    $information = array();
    foreach ($cities as $city) {
      if ($city->service_flag) {
        $information[] = $site_statistic_manager->getCurrentStatisticInformationByCityId($city->getId());
      }
    }

    $this->view->city_current_statistic_information = $information;
    $this->view->docx_error = $docx_error;
    $this->view->doctors = $site_statistic_manager->getDoctorListWithoutClinic();

    $this->view->this_year = (int)date('Y');
  }

  public function ajaxGetStatistics()
  {
    $this->layout = 'ajax';

    $city_value = $this->request('city_value');

    $site_statistic_manager = new SiteStatisticManager();

    $this->view->doctors_clinics_specialties = $site_statistic_manager->getDoctorsClinicsSpecialtiesListByCityId($city_value);
    $this->view->doctors_clinics_with_specialties = $site_statistic_manager->getDoctorAndClinicWithSpecialtiesListByCityId($city_value);
    $this->view->specialties_without_doctor = $site_statistic_manager->getSpecialtiesListWithoutDoctorByCityId($city_value);
    $this->view->specialties_without_clinic = $site_statistic_manager->getSpecialtiesListWithoutClinicByCityId($city_value);

    $clinic_doctor_specialty = $this->renderInString('system/blocks/clinic_doctor_specialty');
    $specialties_doctor_clinic = $this->renderInString('system/blocks/specialties_doctor_clinic');
    $specialties = $this->renderInString('system/blocks/specialties');

    $result = array('clinic_doctor_specialty' => $clinic_doctor_specialty, 'specialties_doctor_clinic' => $specialties_doctor_clinic, 'specialties' => $specialties,);

    JsonResponse::result($result);
  }

  public function ajaxGetVisitInformationByMonth()
  {
    $this->layout = 'ajax';

    $months = array('январь' => '01', 'февраль' => '02', 'март' => '03', 'апрель' => '04', 'май' => '05', 'июнь' => '06', 'июль' => '07', 'август' => '08', 'сентябрь' => '09', 'октябрь' => '10', 'ноябрь' => '11', 'декабрь' => '12');
    $month = $months[$this->request('month')];
    $year = (int)$this->request('year');

    $city_value = $this->request('city_value');

    $site_statistic_manager = new SiteStatisticManager();
    $visits = $site_statistic_manager->getVisitsByCityIdAndMonth($city_value, $month, $year);
    $visits_without_clinic = $site_statistic_manager->getVisitsWithNullClinicId();

    if ($city_value == 'all') {
      $visits = array_merge($visits, $visits_without_clinic);
    }

    $clinic_id = null;
    $clinic = array();
    $result = array();

    $appeals = $site_statistic_manager->getAppealsByMonth($month, $year);
    $result[] = count($appeals);

    foreach ($visits as $visit) {
      if ($visit['visit_time'] && date('n', strtotime($visit['visit_time'])) != $month) {
        continue;
      }

      if ($visit['clinic_id'] == $clinic_id) {
        $clinic[] = array('visit' => $visit,);
      } else {
        $result[] = array_merge(array(), $clinic);
        $clinic = array();
        $clinic[] = array('visit' => $visit,);
      }

      $clinic_id = $visit['clinic_id'];
    }

    $result[] = array_merge(array(), $clinic);

    $this->view->visit_to_clinic = $result;

    $html = $this->renderInString('system/blocks/clinic_visits');

    $response = array('html' => $html, 'information' => $result);

    JsonResponse::result($response);
  }

  public function generateDocx()
  {
    $clinic_id = $this->request('clinic_id');
    $visit_month = $this->request('visit_month');
    $visit_year = $this->request('visit_year');

    $months = array('январь' => '01', 'февраль' => '02', 'март' => '03', 'апрель' => '04', 'май' => '05', 'июнь' => '06', 'июль' => '07', 'август' => '08', 'сентябрь' => '09', 'октябрь' => '10', 'ноябрь' => '11', 'декабрь' => '12');

    $last_day = (int)date('t', strtotime($visit_year . '-' . $months[$visit_month]));
    $date_from = '01.' . $months[$visit_month] . '.' . $visit_year;
    $date_to = $last_day . '.' . $months[$visit_month] . '.' . $visit_year;

    $visit_manager = new VisitManager();
    $visits = $visit_manager->getListByClinicIdAndVisitStatusIdAndDate($clinic_id, VisitModel::VISITED, $date_from, $date_to);
    if ($visits) {

      $report_generator = new ClinicFinanceReportGenerator();
      $clinic_manager = new ClinicManager();

      $clinic = $clinic_manager->getOneById($clinic_id);

      $report_generator->setClinic($clinic);

      $report_generator->setDateFrom($date_from);
      $report_generator->setDateTo($date_to);

      $filename = str_replace('/', '_', $clinic->alias) . '_' . $months[$visit_month] . '_' . $visit_year . '.docx';
      $file_path = ABS_ROOT . '/media/reports/' . $filename;

      $report_generator->generate($file_path);

      PhpHeaderHelper::word2007($filename, filesize($file_path));
      ob_end_clean();
      flush();
      readfile($file_path);

      FileHelper::deleteFile($file_path);
      exit();
    } else {
      $this->redirectUrl(SITE_URL . '/system/index?docx_error=1#clinic-visits');
    }
  }

  public function generateVisitStatisticXls()
  {
    $visit_month_from = $this->request('month_from');
    $visit_year_from = $this->request('year_from');
    $visit_month_to = $this->request('month_to');
    $visit_year_to = $this->request('year_to');

    if ($visit_month_from && $visit_year_from && $visit_month_to && $visit_year_to) {
      $months = array('1' => 'Январь', '2' => 'Февраль', '3' => 'Март', '4' => 'Апрель', '5' => 'Май', '6' => 'Июнь', '7' => 'Июль', '8' => 'Август', '9' => 'Сентябрь', '10' => 'Октябрь', '11' => 'Ноябрь', '12' => 'Декабрь');
      $rows = array('all_visits' => '9', 'yandex_visits' => '7', 'mobile_visits' => '6', 'appeal_visits' => '4', 'site_visits' => '5', 'cancelled_visits' => '10', 'not_visited_visits' => '11', 'visited_visits' => '12', 'other_visits' => '13', 'widget_visits' => '8');

      if (date('Y-m', strtotime('01.' . $visit_month_to . '.' . $visit_year_to)) > date('Y-m')) {
        $visit_month_to = (int)date('m');
        $visit_year_to = (int)date('Y');
      }
      if (date('Y-m', strtotime('01.' . $visit_month_from . '.' . $visit_year_from)) > date('Y-m')) {
        $visit_month = $visit_month_from = (int)date('m');
        $visit_year = $visit_year_from = (int)date('Y');
      } else {
        $visit_month = (int)$visit_month_from;
        $visit_year = (int)$visit_year_from;
      }

      $print_year = true;
      $counter = 0;

      /**
       * @var AppealManager $appeal_manager
       */

      $site_statistic_manager = new SiteStatisticManager();
      $appeal_manager = ModelManagerFactory::getByName('appeal');

      $result = array();
      $result[1] = array(' ');
      $result[2] = array(' ');
      $result[3] = array('Обращений');
      $result[4] = array('Заявок по телефону');
      $result[5] = array('Заявок через сайт');
      $result[6] = array('Заявок через моб.');
      $result[7] = array('Заявок через Яндекс');
      $result[8] = array('Заявок через виджет');
      $result[9] = array('Всего заявок');
      $result[10] = array('Отмены');
      $result[11] = array('Не были у врача');
      $result[12] = array('Были у врача');
      $result[13] = array('Заявки в обработке');

      while ($visit_month <= $visit_month_to || $visit_year <= $visit_year_to) {
        $month_half = 1;
        while ($month_half <= 2) {
          if ($print_year) {
            $result[1][] = $visit_year;
          } else {
            $result[1][] = ' ';
          }

          if ($month_half == 1) {
            $date_from = '01.' . $visit_month . '.' . $visit_year;
            $date_to = '15.' . $visit_month . '.' . $visit_year;
          } else {
            $last_day = (int)date('t', strtotime($visit_year . '-' . $visit_month));
            $date_from = '16.' . $visit_month . '.' . $visit_year;
            $date_to = $last_day . '.' . $visit_month . '.' . $visit_year;
          }

          $result[2][] = ' ' . $months[$visit_month] . ' ' . $month_half;

          $visit_counters = $site_statistic_manager->getVisitsStatisticByDate($date_from, $date_to);

          $appeal_search_params = new AppealSearchParams();
          $appeal_search_params->dt_create_from = $date_from;
          $appeal_search_params->dt_create_to = $date_to;
          $appeal_counter = $appeal_manager->getListByModelSearchCriteria($appeal_search_params);

          $result[3][] = count($appeal_counter);
          foreach ($visit_counters as $visit_counter) {
            $result[$rows[$visit_counter['name']]][] = $visit_counter['count'];
          }
          $result[8][] = 0;

          $print_year = false;
          if ($month_half == 2) {
            if ($visit_month == 12) {
              $visit_month = 1;
              $visit_year++;
              $print_year = true;
            } else {
              $visit_month++;
            }
          }
          $month_half++;
          $counter++;
        }
        if (($visit_month > $visit_month_to && $visit_year == $visit_year_to) || ($visit_year > $visit_year_to)) break;
      }

      if ($counter > 2) {
        $filename = $visit_month_from . '_' . $visit_year_from . '-' . $visit_month_to . '_' . $visit_year_to . '.csv';
      } else {
        $filename = $visit_month_from . '_' . $visit_year_from . '.csv';
      }
      PhpHeaderHelper::csv($filename);
      $csv_generator = new CsvGenerator();
      echo $csv_generator->generateFromArray($result);
      exit();
    } else {
      $this->redirectUrl('/');
    }
  }
}
