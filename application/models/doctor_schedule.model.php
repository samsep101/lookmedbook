<?php
	/**
	 * @property int $id
	 * @property int $is_active
	 * @property int $doctor_id
	 * @property DoctorModel $doctor
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 * @property int $specialty_id
	 * @property SpecialtyModel $specialty
	 * @property int $schedule_type_id
	 * @property ScheduleTypeModel $schedule_type
	 * @property string $date_from
	 * @property string $date_to
	 * @property int $visit_slot_time
	 * @property string $first_week_monday_start_time
	 * @property string $first_week_monday_end_time
	 * @property string $first_week_monday_break_start_time
	 * @property string $first_week_monday_break_end_time
	 * @property int $first_week_monday_visit_type_id
	 * @property VisitTypeModel $first_week_monday_visit_type
	 * @property string $first_week_tuesday_start_time
	 * @property string $first_week_tuesday_end_time
	 * @property string $first_week_tuesday_break_start_time
	 * @property string $first_week_tuesday_break_end_time
	 * @property int $first_week_tuesday_visit_type_id
	 * @property VisitTypeModel $first_week_tuesday_visit_type
	 * @property string $first_week_wednesday_start_time
	 * @property string $first_week_wednesday_end_time
	 * @property string $first_week_wednesday_break_start_time
	 * @property string $first_week_wednesday_break_end_time
	 * @property int $first_week_wednesday_visit_type_id
	 * @property VisitTypeModel $first_week_wednesday_visit_type
	 * @property string $first_week_thursday_start_time
	 * @property string $first_week_thursday_end_time
	 * @property string $first_week_thursday_break_start_time
	 * @property string $first_week_thursday_break_end_time
	 * @property int $first_week_thursday_visit_type_id
	 * @property VisitTypeModel $first_week_thursday_visit_type
	 * @property string $first_week_friday_start_time
	 * @property string $first_week_friday_end_time
	 * @property string $first_week_friday_break_start_time
	 * @property string $first_week_friday_break_end_time
	 * @property int $first_week_friday_visit_type_id
	 * @property VisitTypeModel $first_week_friday_visit_type
	 * @property string $first_week_saturday_start_time
	 * @property string $first_week_saturday_end_time
	 * @property string $first_week_saturday_break_start_time
	 * @property string $first_week_saturday_break_end_time
	 * @property int $first_week_saturday_visit_type_id
	 * @property VisitTypeModel $first_week_saturday_visit_type
	 * @property string $first_week_sunday_start_time
	 * @property string $first_week_sunday_end_time
	 * @property string $first_week_sunday_break_start_time
	 * @property string $first_week_sunday_break_end_time
	 * @property int $first_week_sunday_visit_type_id
	 * @property VisitTypeModel $first_week_sunday_visit_type
	 * @property string $second_week_monday_start_time
	 * @property string $second_week_monday_end_time
	 * @property string $second_week_monday_break_start_time
	 * @property string $second_week_monday_break_end_time
	 * @property int $second_week_monday_visit_type_id
	 * @property VisitTypeModel $second_week_monday_visit_type
	 * @property string $second_week_tuesday_start_time
	 * @property string $second_week_tuesday_end_time
	 * @property string $second_week_tuesday_break_start_time
	 * @property string $second_week_tuesday_break_end_time
	 * @property int $second_week_tuesday_visit_type_id
	 * @property VisitTypeModel $second_week_tuesday_visit_type
	 * @property string $second_week_wednesday_start_time
	 * @property string $second_week_wednesday_end_time
	 * @property string $second_week_wednesday_break_start_time
	 * @property string $second_week_wednesday_break_end_time
	 * @property int $second_week_wednesday_visit_type_id
	 * @property VisitTypeModel $second_week_wednesday_visit_type
	 * @property string $second_week_thursday_start_time
	 * @property string $second_week_thursday_end_time
	 * @property string $second_week_thursday_break_start_time
	 * @property string $second_week_thursday_break_end_time
	 * @property int $second_week_thursday_visit_type_id
	 * @property VisitTypeModel $second_week_thursday_visit_type
	 * @property string $second_week_friday_start_time
	 * @property string $second_week_friday_end_time
	 * @property string $second_week_friday_break_start_time
	 * @property string $second_week_friday_break_end_time
	 * @property int $second_week_friday_visit_type_id
	 * @property VisitTypeModel $second_week_friday_visit_type
	 * @property string $second_week_saturday_start_time
	 * @property string $second_week_saturday_end_time
	 * @property string $second_week_saturday_break_start_time
	 * @property string $second_week_saturday_break_end_time
	 * @property int $second_week_saturday_visit_type_id
	 * @property VisitTypeModel $second_week_saturday_visit_type
	 * @property string $second_week_sunday_start_time
	 * @property string $second_week_sunday_end_time
	 * @property string $second_week_sunday_break_start_time
	 * @property string $second_week_sunday_break_end_time
	 * @property int $second_week_sunday_visit_type_id
	 * @property VisitTypeModel $second_week_sunday_visit_type
	 * @property string $even_numbers_start_time
	 * @property string $even_numbers_end_time
	 * @property string $even_numbers_break_start_time
	 * @property string $even_numbers_break_end_time
	 * @property string $even_numbers_visit_type_id
	 * @property VisitTypeModel $even_numbers_visit_type
	 * @property int $even_numbers_monday
	 * @property int $even_numbers_tuesday
	 * @property int $even_numbers_wednesday
	 * @property int $even_numbers_thursday
	 * @property int $even_numbers_friday
	 * @property int $even_numbers_saturday
	 * @property int $even_numbers_sunday
	 * @property string $odd_numbers_start_time
	 * @property string $odd_numbers_end_time
	 * @property string $odd_numbers_break_start_time
	 * @property string $odd_numbers_break_end_time
	 * @property int $odd_numbers_visit_type_id
	 * @property VisitTypeModel $odd_numbers_visit_type
	 * @property int $odd_numbers_monday
	 * @property int $odd_numbers_tuesday
	 * @property int $odd_numbers_wednesday
	 * @property int $odd_numbers_thursday
	 * @property int $odd_numbers_friday
	 * @property int $odd_numbers_saturday
	 * @property int $odd_numbers_sunday
	 *
	 * @property array $structured
	 */
	class DoctorScheduleModel extends DynamicModel
	{
		public function _field_structured()
		{
			$result = array();

			$result['doctor_id'] = $this->doctor_id;
			$result['clinic_id'] = $this->clinic_id;
			$result['specialty_id'] = $this->specialty_id;
			$result['schedule_info'] = array('schedule_type_id' => $this->schedule_type_id, 'date_from' => date('d-m-Y', strtotime($this->date_from)), 'date_to' => $this->date_to ? date('d-m-Y', strtotime($this->date_to)) : '', 'visit_slot_time' => $this->visit_slot_time, 'first_week' => array(), 'second_week' => array(), 'even_numbers' => array('is_active' => $this->even_numbers_start_time ? 1 : 0, 'start_time' => $this->even_numbers_start_time, 'end_time' => $this->even_numbers_end_time, 'break' => array('start_time' => $this->even_numbers_break_start_time, 'end_time' => $this->even_numbers_break_end_time,), 'visit_type_id' => $this->even_numbers_visit_type_id, 'days' => array()), 'odd_numbers' => array('is_active' => $this->odd_numbers_start_time ? 1 : 0, 'start_time' => $this->odd_numbers_start_time, 'end_time' => $this->odd_numbers_end_time, 'break' => array('start_time' => $this->odd_numbers_break_start_time, 'end_time' => $this->odd_numbers_break_end_time,), 'visit_type_id' => $this->odd_numbers_visit_type_id, 'days' => array()),);

			$days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');

			foreach($days as $day)
			{
				if($this->{'first_week_' . $day . '_start_time'})
				{
					$result['schedule_info']['first_week'][$day] = array('start_time' => $this->{'first_week_' . $day . '_start_time'}, 'end_time' => $this->{'first_week_' . $day . '_end_time'}, 'break' => array('start_time' => $this->{'first_week_' . $day . '_break_start_time'}, 'end_time' => $this->{'first_week_' . $day . '_break_end_time'},), 'visit_type_id' => $this->{'first_week_' . $day . '_visit_type_id'},);
				}

				if($this->{'second_week_' . $day . '_start_time'})
				{
					$result['schedule_info']['second_week'][$day] = array('start_time' => $this->{'second_week_' . $day . '_start_time'}, 'end_time' => $this->{'second_week_' . $day . '_end_time'}, 'break' => array('start_time' => $this->{'second_week_' . $day . '_break_start_time'}, 'end_time' => $this->{'second_week_' . $day . '_break_end_time'},), 'visit_type_id' => $this->{'second_week_' . $day . '_visit_type_id'},);
				}

				$result['schedule_info']['even_numbers']['days'][$day] = $this->{'even_numbers_' . $day};
				$result['schedule_info']['odd_numbers']['days'][$day] = $this->{'odd_numbers_' . $day};
			}

			return $result;
		}

		public function getDoctorWorkTimeByDate($date)
		{
			$current_time = strtotime($date);
			$time_from = null;
			$time_to = null;

			switch($this->schedule_type_id)
			{
				case 1:
					$counter = 'first';
					$week_day = DateHelper::getDayOfWeekNameByDate($current_time);

					$time_from = $this->{$counter . '_week_' . $week_day . '_start_time'};
					$time_to = $this->{$counter . '_week_' . $week_day . '_end_time'};
					break;
				case 2:
					$counter = (date('W', $current_time) % 2 == 0) ? 'second' : 'first';
					$week_day = DateHelper::getDayOfWeekNameByDate($current_time);

					$time_from = $this->{$counter . '_week_' . $week_day . '_start_time'};
					$time_to = $this->{$counter . '_week_' . $week_day . '_end_time'};
					break;
				case 3:
					$month_start = date('Y-m-01', $current_time);
					$week_in_month = date('W', $current_time) - (date('W', strtotime($month_start)) - 1);
					$counter = ($week_in_month % 2 == 0) ? 'second' : 'first';
					$week_day = DateHelper::getDayOfWeekNameByDate($current_time);

					$time_from = $this->{$counter . '_week_' . $week_day . '_start_time'};
					$time_to = $this->{$counter . '_week_' . $week_day . '_end_time'};
					break;
				case 4:
					$counter = (date('j', $current_time) % 2 == 0) ? 'even' : 'odd';
					$week_day = DateHelper::getDayOfWeekNameByDate($current_time);

					if($this->{$counter . '_numbers_' . $week_day})
					{
						$time_from = $this->{$counter . '_numbers_start_time'};
						$time_to = $this->{$counter . '_numbers_end_time'};
					}
					break;
			}


			if(!$time_from || !$time_to)
			{
				return false;
			}

			if(((int)$time_from == 0) && ((int)$time_to == 0))
			{
				return '24<br />часа';
			}
			if((int)$time_to == 0)
			{
				$time_to = 24;
			}
			return ($time_from && $time_to) ? 'c ' . (int)$time_from . '<br>до ' . (int)$time_to : false;
		}
	}