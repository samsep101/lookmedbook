<?php
    class ModerateMetroStationToClinicManager extends ListModerateModelManager {

        protected $table_name = 'moderate_metro_station_to_clinic';
        protected $model_name = 'ModerateMetroStationToClinicModel';

        protected $moderated_list_name = 'metro_station_to_clinic';
        protected $moderated_entity_name = 'clinic';
        protected $fields = array(
            'metro_station_id',
        );

        protected $revision_conditions = array(
            'clinic_id'
        );
    }