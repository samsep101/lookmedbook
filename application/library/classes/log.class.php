<?php

    class Log
    {
        const SEARCH_CITY = 1;
        const SEARCH_METRO_OR_ADDRESS = 2;
        const FIND_GEOIP_CITY = 3;

        public function writeActivityByAccountId($code, $log, $account_id, $no_doctor = null)
        {
            $log_manager = new LogManager();
            $log_model = new LogModel();
            $log_model->log = $log;
            $log_model->code = $code;
            $log_model->account_id = $account_id;
            $log_model->dt = date('Y-m-d H:i:s');
            $log_model->is_city_without_doctor = $no_doctor;

            if ($log_manager->save($log_model))
                return TRUE;
            else
                return FALSE;
        }
    }