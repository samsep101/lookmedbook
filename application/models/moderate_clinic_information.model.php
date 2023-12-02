<?php

    /**
     * @property int                 $id
     * @property int                 $clinic_id
     * @property ClinicModel         $clinic
     * @property string              $name
     * @property string              $full_name
     * @property int                 $clinic_type_id
     * @property ClinicTypeModel     $clinic_type
     * @property int                 $is_children
     * @property int                 $is_adult
     * @property int                 $is_pregnant
     * @property int                 $is_handicapped
     * @property int                 $is_card_pay
     * @property int                 $is_cache_pay
     * @property int                 $city_id
     * @property CityModel           $city
     * @property string              $address
     * @property string              $latitude
     * @property string              $longitude
     * @property string              $director_fio
     * @property string              $site
     * @property int                 $moderate_status_id
     * @property ModerateStatusModel $moderate_status
     * @property int                 $revision_number
     * @property string              $dt
     * @property string              $rate
     * @property int                 $is_active
     * @property int                 $not_work
     * @property int                 $postcode
     * @property int                 $is_contract
     * @property int                 $is_prescribe_sick_leave
     * @property int                 $only_children
     *
     * @property string              $contract_number
     * @property date                $date_contract
     * @property string              $legal_entity
     */
    class ModerateClinicInformationModel extends ModerateModel
    {
        protected $fields = array(
            'name',
            'full_name',
//            'clinic_type_id',
            'is_children',
            'is_adult',
            'is_pregnant',
            'is_handicapped',
            'is_card_pay',
            'is_cash_pay',
            'city_id',
            'only_children',
            'only_adult',
            'address',
            'top_phone',
            'director_fio',
            'site',
            'latitude',
            'longitude',
            'is_active',
            'not_work',
            'redirect_list',
            'rate',
            'postcode',
            'is_contract',
            'is_prescribe_sick_leave',
            'contract_number',
            'date_contract',
            'legal_entity',
            'is_state',
            'is_yandex_send',
        );
    }