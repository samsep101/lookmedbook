<?php

    class ModerateClinicInformationManager extends EntityModerateModelManager
    {
        protected $table_name = 'moderate_clinic_information';
        protected $model_name = 'ModerateClinicInformationModel';

        protected $moderated_entity_name = 'clinic';
        protected $fields = array(
            'name',
            'full_name',
//            'clinic_type_id',
            'is_children',
            'is_adult',
            'is_pregnant',
            'is_handicapped',
            'is_card_pay',
            'is_cache_pay',
            'city_id',
            'address',
            'top_phone',
            'director_fio',
            'site',
            'latitude',
            'longitude',
            'rate',
            'postcode',
            'is_contract',
            'is_active',
            'not_work',
            'redirect_list',
            'is_prescribe_sick_leave',
            'only_children',
            'only_adult',
            'contract_number',
            'date_contract',
            'legal_entity',
            'is_state',
            'is_yandex_send',
        );

        protected function beforeSave(DynamicModel $model)
        {
            /**
             * @var ModerateClinicInformationModel $model
             */
            if($model->date_contract) $model->date_contract = DateHelper::toMysqlDateFormat($model->date_contract);
        }

        protected function afterSave($model)
        {
            $clinic_manager = new ClinicManager();
            $clinic         = $clinic_manager->getOneById($model->clinic_id);

            $clinic->is_active = $model->is_active;
            $clinic->save();
        }
    }