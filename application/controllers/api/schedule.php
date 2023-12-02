<?php
    class ScheduleApiController extends ApiController
    {
        public $layout = 'ajax';

        public function getCachedMethods()
        {
            return array(
                'getList' => array(
                    'tags' => array(
                        'schedule',
                        'schedule:list',
                    )
                ),
            );
        }

        // получение слотов врача
        public function getList()
        {
            $doctor_id = $this->request('doctor_id');
            $specialty_id = $this->request('specialty_id');
            $clinic_id = $this->request('clinic_id');
            $dt_start = $this->request('dt_start');
            $dt_end = $this->request('dt_end');

            if (!$doctor_id || !$specialty_id || $clinic_id || !$dt_start || $dt_end)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $schedule_manager = new ScheduleManager();
            $schedules = $schedule_manager->getListByClinicIdAndDoctorIdAndSpecialtyIdAndDate($clinic_id, $doctor_id, $specialty_id, $dt_start, $dt_end);

            $result = array();

            if (!$schedules)
                ApiHeader::error(ApiRequestErrors::SCHEDULES_NOT_EXIST);

            foreach ($schedules as $schedule) {
                $result[] = array(
                    'schedule_id' => $schedule->getId(),
                    'doctor_id' => $doctor_id,
                    'clinic_id' => $clinic_id,
                    'specialty_id' => $specialty_id,
                    'dt_start' => $schedule->dt_start,
                    'dt_end' => $schedule->dt_end,
                );
            }

            ApiHeader::response($result, $this->e_tag);
        }

        public function reserveSlot()
        {
            $slot_id = $this->request('slot_id');

            if (!$slot_id)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $schedule_manager = new ScheduleManager();

            if (!$schedule = $schedule_manager->getOneById($slot_id))
                ApiHeader::error(ApiRequestErrors::SLOTS_NOT_EXIST);

            ApiHeader::response(true, $this->e_tag);
        }
    }