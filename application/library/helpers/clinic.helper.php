<?php

    class ClinicHelper
    {
        public static function getDataToLinkTheTables($type)
        {
            if($type == 'ClinicServicesModel')
            {
                $tables['table_name_for_landing_page'] = 'clinic_services';
                $tables['table_name_join']             = 'clinic_to_services';
                $tables['page_id']                     = 'clinic_id';
                $tables['join_id']                     = 'clinic_service_id';
            }
            else if($type == 'ClinicTypeModel')
            {
                $tables['table_name_for_landing_page'] = 'clinic_type';
                $tables['table_name_join']             = 'clinic_to_types';
                $tables['page_id']                     = 'clinic_id';
                $tables['join_id']                     = 'clinic_type_id';
            }
            else return array();

            return $tables;
        }
    }