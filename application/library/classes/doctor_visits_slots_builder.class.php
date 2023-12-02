<?php

class DoctorVisitsSlotsBuilder
{
  private $graphics;

  private $doctor_id;
  private $clinic_id;
  private $specialty_id;

  private $date_from;
  private $date_to;
  private $schedule_type_id;

  /*
          public function __construct($doctor_id, $clinic_id, $specialty_id)
          {
              $this->doctor_id = $doctor_id;
              $this->clinic_id = $clinic_id;
              $this->specialty_id = $specialty_id;

              $this->date_from = date('Y-m-d');
              $this->date_to = date('Y-m-d H:i:s', time() +15*24*60*60);

              $doctor_schedule_manager = new DoctorScheduleManager();
              //$this->graphics = $doctor_schedule_manager->getListByDoctorIdAndClinicIdSpecialtyIdAndDateInterval($this->doctor_id, $this->clinic_id, $this->specialty_id, $this->date_from, $this->date_to);
              $this->graphics = $doctor_schedule_manager->getListByDateInterval($this->date_from, $this->date_to);
          }
  */
  public function getDoctorSchedules()
  {
    //$this->date_from = date('Y-m-d');
    $this->date_from = date('Y-m-d');
    $this->date_to = date('Y-m-d H:i:s', time() + 15 * 86400);

    $doctor_schedule_manager = new DoctorScheduleManager();
    //$graphics = $doctor_schedule_manager->getListByDateInterval($this->date_from, $this->date_to);
    $graphics = $doctor_schedule_manager->getCurrentList();

    foreach ($graphics as $graphic) {
      //if ($graphic->doctor_id && $graphic->clinic_id && $graphic->specialty_id) {
      $this->schedule_type_id = $graphic->schedule_type_id;
      $this->formVisitsSlots($graphic);
      //}
    }

    $doctor_specialty_to_clinic_manager = new DoctorSpecialtyToClinicManager();

    foreach ($doctor_specialty_to_clinic_manager->getIterator() as $doctor_specialty_to_clinic) {
      //@TODO По требованию заказчика выводим расписание врачей как у клиники.

      //$doctor_schedule = $doctor_schedule_manager->getOneCurrentByDoctorIdAndClinicIdAndSpecialtyId($doctor_specialty_to_clinic->doctor_id, $doctor_specialty_to_clinic->clinic_id, $doctor_specialty_to_clinic->specialty_id);
      $doctor_schedule = false;
      if (!$doctor_schedule) {
        $this->schedule_type_id = 'clinic';
        $this->formVisitsSlots($doctor_specialty_to_clinic);
      }
    }
  }

  public function formVisitsSlots($graphic)
  {
    for ($i = 0; $i <= 15; $i++) {
      $former_day = strtotime($this->date_from) + $i * 86400;
      $current_time = strtotime(date('Y-m-d', $former_day));
      $schedule_manager = new ScheduleManager();

      $times = array();
      $time_from = null;
      $time_to = null;

      switch ($this->schedule_type_id) {
        case 1:
          $counter = 'first';
          $week_day = DateHelper::getDayOfWeekNameByDate($current_time);

          $time_from = $graphic->{$counter . '_week_' . $week_day . '_start_time'};
          $time_to = $graphic->{$counter . '_week_' . $week_day . '_end_time'};
          break;
        case 2:
          $counter = (date('W', $current_time) % 2 == 0) ? 'second' : 'first';
          $week_day = DateHelper::getDayOfWeekNameByDate($current_time);

          $time_from = $graphic->{$counter . '_week_' . $week_day . '_start_time'};
          $time_to = $graphic->{$counter . '_week_' . $week_day . '_end_time'};
          break;
        case 3:
          $month_start = date('Y-m-01', $current_time);
          $week_in_month = date('W', $current_time) - (date('W', strtotime($month_start)) - 1);
          $counter = ($week_in_month % 2 == 0) ? 'second' : 'first';
          $week_day = DateHelper::getDayOfWeekNameByDate($current_time);

          $time_from = $graphic->{$counter . '_week_' . $week_day . '_start_time'};
          $time_to = $graphic->{$counter . '_week_' . $week_day . '_end_time'};
          break;
        case 4:
          $counter = (date('j', $current_time) % 2 == 0) ? 'even' : 'odd';
          $week_day = DateHelper::getDayOfWeekNameByDate($current_time);

          if ($graphic->{$counter . '_numbers_' . $week_day}) {
            $time_from = $graphic->{$counter . '_numbers_start_time'};
            $time_to = $graphic->{$counter . '_numbers_end_time'};
          }
          break;
        case 'clinic':
          $week_day = DateHelper::getDayOfWeekNameByDate($current_time);

          $time_from = empty($graphic->clinic->{'start_time_' . $week_day}) ? 0 : $graphic->clinic->{'start_time_' . $week_day};
          $time_to = empty($graphic->clinic->{'end_time_' . $week_day}) ? 0 : $graphic->clinic->{'end_time_' . $week_day};
          break;
      }

      if ($time_from && $time_to) {

        $time_from = preg_replace('/[^0-9:]/ims', '', $time_from);
        $time_to = preg_replace('/[^0-9:]/ims', '', $time_to);

        $int_time_from = (int)$time_from;
        $int_time_to = (int)$time_to;
        if ($int_time_to == 0) $int_time_to = 24;

        if ($int_time_from < 6 && $int_time_to < 6) {
          $times[] = array(
            'start_time' => $time_from,
            'end_time' => $time_to,
          );
        }
        if ($int_time_from < 6 && $int_time_to >= 6) {
          $times[] = array(
            'start_time' => $time_from,
            'end_time' => '06:00',
          );
        }

        if ($int_time_from < 6 && $int_time_to < 12 && $int_time_to > 6) {
          $times[] = array(
            'start_time' => '06:00',
            'end_time' => $time_to,
          );
        }
        if ($int_time_from <= 6 && $int_time_to >= 12) {
          $times[] = array(
            'start_time' => '06:00',
            'end_time' => '12:00',
          );
        }
        if ($int_time_from > 6 && $int_time_to <= 12) {
          $times[] = array(
            'start_time' => $time_from,
            'end_time' => $time_to,
          );
        }
        if ($int_time_from == 6 && $int_time_to < 12) {
          $times[] = array(
            'start_time' => '06:00',
            'end_time' => $time_to,
          );
        }
        if ($int_time_from > 6 && $int_time_to > 12 && $int_time_from < 12) {
          $times[] = array(
            'start_time' => $time_from,
            'end_time' => '12:00',
          );
        }

        if ($int_time_from < 12 && $int_time_to < 17 && $int_time_to > 12) {
          $times[] = array(
            'start_time' => '12:00',
            'end_time' => $time_to,
          );
        }
        if ($int_time_from <= 12 && $int_time_to >= 17) {
          $times[] = array(
            'start_time' => '12:00',
            'end_time' => '17:00',
          );
        }
        if ($int_time_from > 12 && $int_time_to <= 17) {
          $times[] = array(
            'start_time' => $time_from,
            'end_time' => $time_to,
          );
        }
        if ($int_time_from == 12 && $int_time_to < 17) {
          $times[] = array(
            'start_time' => '12:00',
            'end_time' => $time_to,
          );
        }
        if ($int_time_from > 12 && $int_time_to > 17 && $int_time_from < 17) {
          $times[] = array(
            'start_time' => $time_from,
            'end_time' => '17:00',
          );
        }

        if ($int_time_from <= 17 && $int_time_to > 17 && $int_time_to != 24) {
          $times[] = array(
            'start_time' => '17:00',
            'end_time' => $time_to,
          );
        }
        if ($int_time_from > 17 && $int_time_to > 17 && $int_time_to != 24) {
          $times[] = array(
            'start_time' => $time_from,
            'end_time' => $time_to,
          );
        }
        if ($int_time_from <= 17 && $int_time_to > 17 && $int_time_to == 24) {
          $times[] = array(
            'start_time' => '17:00',
            'end_time' => '23:59',
          );
        }
        if ($int_time_from > 17 && $int_time_to > 17 && $int_time_to == 24) {
          $times[] = array(
            'start_time' => $time_from,
            'end_time' => '23:59',
          );
        }
      }

      $existing_slots = $schedule_manager->getListByDoctorIdAndClinicIdAndSpecialtyIdAndDate($graphic->doctor_id, $graphic->clinic_id, $graphic->specialty_id, $current_time);
      $clear_slots = 0;

      if (!$times) $clear_slots = 1;

      if ($existing_slots) {
        if ($times) {
          if (count($existing_slots) == count($times)) {
            foreach ($existing_slots as $existing) {
              $equal = false;
              $slot_time_start = date('H:i', strtotime($existing->dt_start));
              $slot_time_end = date('H:i', strtotime($existing->dt_end));

              foreach ($times as $time) {
                $time_start_time = date('H:i', strtotime($time['start_time']));
                $time_end_time = date('H:i', strtotime($time['end_time']));
                if (($slot_time_start == $time_start_time) && ($slot_time_end == $time_end_time)) {
                  $equal = true;
                }
              }
              if (!$equal) {
                $clear_slots = 1;
                break;
              }
            }
          } else $clear_slots = 1;
        } else $clear_slots = 1;
      } else $clear_slots = 1;

      if ($clear_slots && $existing_slots) {
        foreach ($existing_slots as $existing) {
          $schedule_manager->deleteById($existing->id);
        }
      }

      if ($times && $clear_slots) {
        foreach ($times as $time) {
          $slot = new ScheduleModel();

          $slot->clinic_id = $graphic->clinic_id;
          $slot->doctor_id = $graphic->doctor_id;
          $slot->specialty_id = $graphic->specialty_id;
          $slot->dt_start = date('Y-m-d ' . $time['start_time'], $current_time);
          $slot->dt_end = date('Y-m-d ' . $time['end_time'], $current_time);
          $slot->is_busy = 0;
          $slot->save();
        }
      }
    }
  }

  public function cleanDoctorSchedules()
  {
    $doctor_schedule_manager = new DoctorScheduleManager();
    $graphics = $doctor_schedule_manager->getList();

    foreach ($graphics as $graphic) {
      if (!$graphic->doctor_id || !$graphic->clinic_id || !$graphic->specialty_id)
        $doctor_schedule_manager->deleteById($graphic->getId());
      if (!$graphic->first_week_monday_start_time && !$graphic->first_week_monday_end_time &&
        !$graphic->first_week_tuesday_start_time && !$graphic->first_week_tuesday_end_time &&
        !$graphic->first_week_wednesday_start_time && !$graphic->first_week_wednesday_end_time &&
        !$graphic->first_week_thursday_start_time && !$graphic->first_week_thursday_end_time &&
        !$graphic->first_week_friday_start_time && !$graphic->first_week_friday_end_time &&
        !$graphic->first_week_saturday_start_time && !$graphic->first_week_saturday_end_time &&
        !$graphic->first_week_sunday_start_time && !$graphic->first_week_sunday_end_time &&

        !$graphic->second_week_monday_start_time && !$graphic->second_week_monday_end_time &&
        !$graphic->second_week_tuesday_start_time && !$graphic->second_week_tuesday_end_time &&
        !$graphic->second_week_wednesday_start_time && !$graphic->second_week_wednesday_end_time &&
        !$graphic->second_week_thursday_start_time && !$graphic->second_week_thursday_end_time &&
        !$graphic->second_week_friday_start_time && !$graphic->second_week_friday_end_time &&
        !$graphic->second_week_saturday_start_time && !$graphic->second_week_saturday_end_time &&
        !$graphic->second_week_sunday_start_time && !$graphic->second_week_sunday_end_time &&

        !$graphic->even_numbers_start_time && !$graphic->even_numbers_end_time &&
        !$graphic->odd_numbers_start_time && !$graphic->odd_numbers_end_time
      )
        $doctor_schedule_manager->deleteById($graphic->getId());
    }
  }
}