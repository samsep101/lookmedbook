<?php

class SiteStatisticManager
{
  protected $table_name = 'system_access_ip';
  protected $model_name = 'SiteStatisticModel';

  public function getCurrentStatisticInformation()
  {
    $db = Register::get('db');

    $sql = 'SELECT
					COUNT(DISTINCT c.id) clinics,
					COUNT(DISTINCT dc.doctor_id) doctors,
					(SELECT COUNT(id) FROM specialty) all_specialties,
					COUNT(DISTINCT sc.specialty_id) specialties_by_clinics,
					COUNT(DISTINCT dc.specialty_id) specialties_by_doctors
					FROM clinic c
					LEFT JOIN specialty_to_clinic sc on sc.clinic_id = c.id
					LEFT JOIN doctor_to_clinic dc on dc.clinic_id = c.id
					INNER JOIN city ON city.id = c.city_id
					WHERE city.service_flag = 1';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getCurrentStatisticInformationByCityId($city_id)
  {
    $db = Register::get('db');

    $sql = 'select
					count(distinct c.id) clinics,
					count(distinct dc.doctor_id) doctors,
					city.name city_name,
					city.id city_id,
					(select count(id) from specialty) all_specialties,
					count(distinct sc.specialty_id) specialties_by_clinics,
					count(distinct dc.specialty_id) specialties_by_doctors
					from clinic c
					left join specialty_to_clinic sc on sc.clinic_id = c.id
					left join doctor_to_clinic dc on dc.clinic_id = c.id
					INNER JOIN city ON c.city_id = city.id
					WHERE c.city_id = ' . $city_id;

    $data = $db->query($sql);
    return ($data[0]) ? $data[0] : array();
  }

  public function getDoctorsClinicsSpecialtiesList()
  {
    $db = Register::get('db');

    $sql = 'select
					c.name clinic,
					count(distinct dc.doctor_id + "&" + c.id) doctor,
					count(distinct dc.specialty_id + "&" + c.id) doctor_specialties,
					count(distinct sc.specialty_id + "&" + c.id) clinic_specialties
					from clinic c
					left join specialty_to_clinic sc on sc.clinic_id = c.id
					left join doctor_to_clinic dc on dc.clinic_id = c.id
					group by c.id
					order by c.name';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getDoctorsList()
  {
    $db = Register::get('db');

    $sql = 'select
					d.last_name last_name,
					d.first_name first_name,
					d.second_name middle_name
					from doctor d
					left join doctor_to_clinic dc on dc.doctor_id = d.id
					where dc.doctor_id is null
					order by d.last_name, d.first_name, d.second_name';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getDoctorAndClinicWithSpecialtiesList()
  {
    $db = Register::get('db');

    $sql = 'select
					s.name specialty,
					count(distinct dc.doctor_id + "&" + s.id) doctors,
					count(distinct sc.id + "&" + s.id) clinics
					FROM specialty s
					left join specialty_to_clinic sc on s.id = sc.specialty_id
					left join doctor_to_clinic dc on s.id = dc.specialty_id
					group by s.id
					order by s.name';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getSpecialtiesListWithoutDoctor()
  {
    $db = Register::get('db');

    $sql = 'select
					s.name specialty
					from specialty s
					left join doctor_to_clinic dc on dc.specialty_id = s.id
					where dc.specialty_id is null
					order by s.name';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getSpecialtiesListWithoutClinic()
  {
    $db = Register::get('db');

    $sql = 'select
					s.name specialty
					from specialty s
					left join specialty_to_clinic sc on sc.specialty_id = s.id
					where sc.specialty_id is null
					order by s.name';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getDoctorsClinicsSpecialtiesListByCityId($city_value)
  {
    $db = Register::get('db');

    $sql = 'SELECT
					c.name clinic,
					COUNT(distinct dc.doctor_id + "&" + c.id) doctor,
					COUNT(distinct dc.specialty_id + "&" + c.id) doctor_specialties,
					COUNT(distinct sc.specialty_id + "&" + c.id) clinic_specialties
					FROM clinic c
					LEFT JOIN specialty_to_clinic sc on sc.clinic_id = c.id
					LEFT JOIN doctor_specialty_to_clinic dc on dc.clinic_id = c.id ';

    if ($city_value == 'all') {
      $sql .= ' INNER JOIN city ON city.id = c.city_id
						WHERE city.service_flag = 1 ';
    } else {
      $sql .= ' WHERE c.city_id = ' . (int)$city_value . ' ';
    }

    $sql .= ' GROUP BY c.id
					ORDER BY c.name';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getDoctorsListByCityId($city_id)
  {
    $db = Register::get('db');

    $sql = 'SELECT *
					FROM doctor d
					WHERE (
					 SELECT COUNT(*)
					 FROM doctor_to_clinic d2c
					 INNER JOIN clinic c ON d2c.clinic_id = c.id
					 WHERE c.city_id = ' . (int)$city_id . '
					  AND d2c.doctor_id = d.id
					)  = 0
					ORDER BY d.last_name, d.first_name, d.second_name';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getDoctorListWithoutClinic()
  {
    $db = Register::get('db');

    $sql = 'SELECT *
					FROM doctor d
					WHERE d.id NOT IN
					(
						SELECT d2c.doctor_id
						FROM doctor_to_clinic d2c
						INNER JOIN doctor d ON d2c.doctor_id = d.id
					)';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getDoctorAndClinicWithSpecialtiesListByCityId($city_value)
  {
    $db = Register::get('db');

    $sql = 'SELECT s.name specialty,
						   COUNT(distinct dc.doctor_id + "&" + s.id) doctors,
						   COUNT(distinct sc.id + "&" + s.id) clinics
					FROM (
					  SELECT s.id, s.name, c.id AS clinic_id
					  FROM  specialty AS s, clinic AS c
					  %s) AS s
					LEFT JOIN specialty_to_clinic AS sc ON sc.specialty_id = s.id
					AND sc.clinic_id = s.clinic_id
					LEFT JOIN doctor_specialty_to_clinic AS dc ON dc.specialty_id = s.id
					AND dc.clinic_id = s.clinic_id';

    if ($city_value == 'all') {
      $sql = sprintf($sql, ' INNER JOIN city ON city.id = c.city_id
						WHERE city.service_flag = 1');
    } else {
      $sql = sprintf($sql, ' WHERE c.city_id = ' . (int)$city_value);
    }

    $sql .= ' GROUP BY s.id
					ORDER BY s.name';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getSpecialtiesListWithoutDoctorByCityId($city_value)
  {
    $db = Register::get('db');

    $sql = 'SELECT *
					FROM specialty s
					WHERE (
					 SELECT COUNT(*)
					 FROM doctor d
					 INNER JOIN doctor_to_clinic d2c ON d.id = d2c.doctor_id
					 INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = d.id
					 INNER JOIN clinic c ON c.id = ds2c.clinic_id';

    if ($city_value == 'all') {
      $sql .= ' INNER JOIN city ON city.id = c.city_id
						WHERE city.service_flag = 1 ';
    } else {
      $sql .= ' WHERE c.city_id = ' . (int)$city_value . ' ';
    }

    $sql .= ' AND ds2c.specialty_id = s.id
					)  = 0';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getSpecialtiesListWithoutClinicByCityId($city_value)
  {
    $db = Register::get('db');

    $sql = 'SELECT *
					FROM specialty s
					WHERE (
					 SELECT COUNT(*)
					 FROM clinic c
					 INNER JOIN specialty_to_clinic s2c ON c.id = s2c.clinic_id';

    if ($city_value == 'all') {
      $sql .= ' INNER JOIN city ON city.id = c.city_id
						WHERE city.service_flag = 1 ';
    } else {
      $sql .= ' WHERE c.city_id = ' . (int)$city_value . ' ';
    }

    $sql .= ' AND s2c.specialty_id = s.id
					)  = 0';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getVisitsByCityId($city_value)
  {
    $db = Register::get('db');

    $sql = 'SELECT
						c.name clinic_name,
						c.id clinic_id,
						v.id id,
						v.comment comment,
						v.visit_start_time visit_time,
						s.name specialty_name,
						d.last_name doctor_last_name,
						d.first_name doctor_first_name,
						d.second_name doctor_second_name,
						v.full_name account,
						v.status_id status,
						v.create_time create_time
					FROM visit v
					LEFT JOIN clinic c ON v.clinic_id = c.id
					LEFT JOIN specialty s ON v.specialty_id = s.id
					LEFT JOIN doctor d ON v.doctor_id = d.id';

    if ($city_value == 'all') {
      $sql .= ' INNER JOIN city ON city.id = c.city_id
						WHERE city.service_flag = 1 ';
    } else {
      $sql .= ' WHERE c.city_id = ' . (int)$city_value . ' ';
    }

    $sql .= 'ORDER BY c.name';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getVisitsByCityIdAndMonth($city_value, $month, $year)
  {
    $db = Register::get('db');

    $sql = 'SELECT
						c.name clinic_name,
						c.id clinic_id,
						v.id id,
						v.comment comment,
						v.visit_start_time visit_time,
						s.name specialty_name,
						d.last_name doctor_last_name,
						d.first_name doctor_first_name,
						d.second_name doctor_second_name,
						v.full_name account,
						v.status_id status,
						v.create_time create_time,
						v.admin_comment admin_comment
					FROM visit v
					LEFT JOIN clinic c ON v.clinic_id = c.id
					LEFT JOIN specialty s ON v.specialty_id = s.id
					LEFT JOIN doctor d ON v.doctor_id = d.id';

    if ($city_value == 'all') {
      $sql .= ' INNER JOIN city ON city.id = c.city_id
                WHERE city.service_flag = 1 ';
    } else {
      $sql .= ' WHERE c.city_id = ' . (int)$city_value . ' ';
    }

    $sql .= 'AND (DATE_FORMAT(create_time, "%Y%m") = "' . $year . $month . '"
						OR DATE_FORMAT(visit_start_time, "%Y%m") = "' . $year . $month . '") ';

    $sql .= 'ORDER BY c.name';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getVisitsWithNullClinicId()
  {
    $db = Register::get('db');

    $sql = 'SELECT
						v.id id,
						v.comment comment,
						v.visit_start_time visit_time,
						s.name specialty_name,
						d.last_name doctor_last_name,
						d.first_name doctor_first_name,
						d.second_name doctor_second_name,
						v.full_name account,
						v.status_id status,
						v.admin_comment admin_comment
					FROM visit v
					LEFT JOIN specialty s ON v.specialty_id = s.id
					LEFT JOIN doctor d ON v.doctor_id = d.id
					WHERE v.clinic_id IS NULL';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getAppealsByMonth($month, $year)
  {
    $db = Register::get('db');

    $sql = 'SELECT *, "'.$year.$month.'"
        FROM appeal
          WHERE DATE_FORMAT(dt_create, "%Y%m") = "'.$year.$month.'"';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }

  public function getVisitsStatisticByDate($date_from, $date_to)
  {
    $db = Register::get('db');

    $sql = 'SELECT COUNT(*) as count, "all_visits" name
					FROM visit
					WHERE (create_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
						AND create_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"
						AND visit_start_time is null
					)
					OR (visit_start_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
						AND visit_start_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"
					)

					UNION
					SELECT COUNT(*) as count, "yandex_visits" name
					FROM visit v
					WHERE (create_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
						AND create_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"
						AND visit_start_time is null
						AND yandex_id is not null
					)
					OR (visit_start_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
						AND visit_start_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"
						AND yandex_id is not null
					)

					UNION
					SELECT COUNT(*) as count, "mobile_visits" name
					FROM visit v
					WHERE (create_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
						AND create_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"
						AND visit_start_time is null
						AND from_mobile = 1
					)
					OR (visit_start_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
						AND visit_start_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"
						AND from_mobile = 1
					)

					UNION
					SELECT COUNT(*) as count, "appeal_visits" name
					FROM visit v
					WHERE (create_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
						AND create_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"
						AND visit_start_time is null
						AND appeal_id is not null
					)
					OR (visit_start_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
						AND visit_start_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"
						AND appeal_id is not null
					)

					UNION
					SELECT COUNT(*) as count, "site_visits" name
					FROM visit v
					WHERE (create_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
						AND create_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"
						AND visit_start_time is null
						AND appeal_id is null
						AND yandex_id is null
						AND from_mobile is null
					)
					OR (visit_start_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
						AND visit_start_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"
						AND appeal_id is null
						AND yandex_id is null
						AND from_mobile is null
					)

					UNION
					SELECT COUNT(*) as count, "cancelled_visits" name
					FROM visit v
					WHERE create_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
					AND create_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"
					AND status_id = ' . VisitModel::CANCELLED . '

					UNION
					SELECT COUNT(*) as count, "not_visited_visits" name
					FROM visit v
					WHERE visit_start_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
					AND visit_start_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"
					AND status_id = ' . VisitModel::NOT_VISITED . '

					UNION
					SELECT COUNT(*) as count, "visited_visits" name
					FROM visit v
					WHERE visit_start_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
					AND visit_start_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"
					AND status_id = ' . VisitModel::VISITED . '

					UNION
					SELECT COUNT(*) as count, "other_visits" name
					FROM visit v
					WHERE create_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
					AND create_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"
					AND status_id not in (' . VisitModel::CANCELLED . ',' . VisitModel::VISITED . ',' . VisitModel::NOT_VISITED . ')';

    $data = $db->query($sql);
    return ($data) ? $data : array();
  }
}