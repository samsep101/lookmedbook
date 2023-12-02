<?php

    $map = array(
        array(
            'url'        => '/',
            'action'     => 'index',
            'controller' => 'index',
        ),
        array(
            'url'        => '/passwordRecovery',
            'action'     => 'passwordRecovery',
            'controller' => 'account',
        ),
        array(
            'url'        => '/passwordNew',
            'action'     => 'passwordNew',
            'controller' => 'account',
        ),
        array(
            'url'        => '/admin/index/log/exportCSVWithAccountActivity',
            'action'     => 'exportCSVWithAccountActivity',
            'controller' => 'account',
        ),
        array(
            'url'        => '/admin/index/log/exportCSVSearchLog',
            'action'     => 'exportCSVSearchLog',
            'controller' => 'account',
        ),
        array(
            'url'        => '/account/my_disease/archive',
            'action'     => 'my_disease_archive',
            'controller' => 'account',
        ),
        array(
            'url'        => '/account/message/get',
            'action'     => 'get',
            'controller' => 'message',
        ),
        array(
            'url'        => '/about',
            'action'     => 'about',
            'controller' => 'index',
        ),
        array(
            'url'        => '/view_table',
            'action'     => 'index_table',
            'controller' => 'index',
        ),
        array(
            'url'        => '/map',
            'action'     => 'map',
            'controller' => 'index',
        ),
        array(
            'url'        => '/page/:code',
            'action'     => 'index',
            'controller' => 'page',
        ),
        array(
            'url'        => '/news/view/:id',
            'action'     => 'view',
            'controller' => 'news',
        ),
        array(
            'url'        => '/articles/view/:id',
            'action'     => 'view',
            'controller' => 'articles',
        ),
        array(
            'url'        => '/license',
            'action'     => 'license',
            'controller' => 'index',
        ),

        // роутинги для контроллера DoctorController
        array(
            'url'        => '/doctor/search',
            'action'     => 'search',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor',
            'action'     => 'index',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/get',
            'action'     => 'get',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxSearch',
            'action'     => 'ajaxSearch',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxAddToMyDoctorList',
            'action'     => 'ajaxAddToMyDoctorList',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxGetReviewsList',
            'action'     => 'ajaxGetReviewsList',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxGetScheduleSingleDay',
            'action'     => 'ajaxGetScheduleSingleDay',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxGetScheduleTimes',
            'action'     => 'ajaxGetScheduleTimes',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxGetScheduleTimesByDay',
            'action'     => 'ajaxGetScheduleTimesByDay',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxGetVisitPrice',
            'action'     => 'ajaxGetVisitPrice',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxSaveAccountFullName',
            'action'     => 'ajaxSaveAccountFullName',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxSaveVisitData',
            'action'     => 'ajaxSaveVisitData',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxSetPhoneNumber',
            'action'     => 'ajaxSetPhoneNumber',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxUpdateVisitTime',
            'action'     => 'ajaxUpdateVisitTime',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/getDoctorPhotos',
            'action'     => 'getDoctorPhotos',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/:specialty/:district/:region/:metro',
            'action'     => 'index',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/:specialty/:district/:region_street',
            'action'     => 'index',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/:specialty/:district',
            'action'     => 'index',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/:id',
            'action'     => 'get',
            'controller' => 'doctor'
        ),

        // роутинги для контроллера Clinic
        array(
            'url'        => '/clinic/search',
            'action'     => 'search',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic',
            'action'     => 'index',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/ajaxAddToMyClinicList',
            'action'     => 'ajaxAddToMyClinicList',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/ajaxGetDoctorsList',
            'action'     => 'ajaxGetDoctorsList',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/ajaxGetReviewsList',
            'action'     => 'ajaxGetReviewsList',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/ajaxSearch',
            'action'     => 'ajaxSearch',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/get',
            'action'     => 'get',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/:val1/:val2/:val3/:val4',
            'action'     => 'get',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/:val1/:val2/:val3',
            'action'     => 'get',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/:val1/:val2',
            'action'     => 'get',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/:val1',
            'action'     => 'get',
            'controller' => 'clinic'
        ),

        // роутинги для контроллера Action
        array(
            'url'        => '/action/:id',
            'action'     => 'get',
            'controller' => 'action'
        ),

        // роутинги для контроллера Disease
        array(
            'url'        => '/disease',
            'action'     => 'search',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/ajaxAddToArchive',
            'action'     => 'ajaxAddToArchive',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/ajaxGetBeforeBlock',
            'action'     => 'ajaxGetBeforeBlock',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/ajaxAddToMyDiseaseList',
            'action'     => 'ajaxAddToMyDiseaseList',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/ajaxAddUnderstandOpinion',
            'action'     => 'ajaxAddUnderstandOpinion',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/ajaxGetUnderstandBlock',
            'action'     => 'ajaxGetUnderstandBlock',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/clearDiseaseAltNamesAndDiseaseTagsAndAndDiseaseBlocksAndSpecialtyToDisease',
            'action'     => 'clearDiseaseAltNamesAndDiseaseTagsAndAndDiseaseBlocksAndSpecialtyToDisease',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/moreSearchResults',
            'action'     => 'moreSearchResults',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/parseDiseasesAndDiseaseBlocks',
            'action'     => 'parseDiseasesAndDiseaseBlocks',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/search',
            'action'     => 'search',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/searchResults',
            'action'     => 'searchResults',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/get',
            'action'     => 'get',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/ajaxGetDiseaseCardContent',
            'action'     => 'ajaxGetDiseaseCardContent',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/:id/:card',
            'action'     => 'get',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/:id',
            'action'     => 'get',
            'controller' => 'disease'
        ),

        // роутинги для контроллера Catalog
        array(
            'url'        => '/shop/catalog/',
            'action'     => 'index',
            'controller' => 'catalog',
            'folder'     => 'shop'
        ),
        array(
            'url'        => '/shop/catalog/ajaxSearch',
            'action'     => 'ajaxSearch',
            'controller' => 'catalog',
            'folder'     => 'shop'
        ),
        array(
            'url'        => '/shop/catalog/search',
            'action'     => 'search',
            'controller' => 'catalog',
            'folder'     => 'shop'
        ),
        array(
            'url'        => '/shop/catalog/ajaxLiveSearch',
            'action'     => 'ajaxLiveSearch',
            'controller' => 'catalog',
            'folder'     => 'shop'
        ),
        array(
            'url'        => '/shop/catalog/ajaxSearchLog',
            'action'     => 'ajaxSearchLog',
            'controller' => 'catalog',
            'folder'     => 'shop'
        ),
//        array(
//            'url'        => '/shop/catalog/payments',
//            'action'     => 'payments',
//            'controller' => 'catalog',
//            'folder'     => 'shop'
//        ),
//        array(
//            'url'        => '/shop/catalog/delivery',
//            'action'     => 'delivery',
//            'controller' => 'catalog',
//            'folder'     => 'shop'
//        ),
//        array(
//            'url'        => '/shop/catalog/choise',
//            'action'     => 'choise',
//            'controller' => 'catalog',
//            'folder'     => 'shop'
//        ),
//        array(
//            'url'        => '/shop/catalog/getLicenseImages',
//            'action'     => 'getLicenseImages',
//            'controller' => 'catalog',
//            'folder'     => 'shop'
//        ),
        array(
            'url'        => '/shop/catalog/:category_alias',
            'action'     => 'index',
            'controller' => 'catalog',
            'folder'     => 'shop'
        ),
        array(
            'url'        => '/shop/catalog/:parent_category_alias/:category_alias',
            'action'     => 'index',
            'controller' => 'catalog',
            'folder'     => 'shop'
        ),

        // роутинги для контроллера Product
        array(
            'url'        => '/shop/product/:id',
            'action'     => 'get',
            'controller' => 'product',
            'folder'     => 'shop'
        ),

        // Роутинги для Widget API
        array(
            'url'        => '/widget_api/specialty/getList',
            'action'     => 'getList',
            'controller' => 'specialty',
            'folder'     => 'api'
        ),
        array(
            'url'        => '/widget_api/city/getList',
            'action'     => 'getList',
            'controller' => 'city',
            'folder'     => 'api'
        ),
        array(
            'url'        => '/widget_api/accountPhone/sendCode',
            'action'     => 'sendCode',
            'controller' => 'AccountPhone',
            'folder'     => 'api'
        ),
        array(
            'url'        => '/widget_api/accountPhone/confirm',
            'action'     => 'sendCode',
            'controller' => 'AccountPhone',
            'folder'     => 'api'
        ),
        array(
            'url'        => '/landing/:id',
            'action'     => 'index',
            'controller' => 'landing'
        ),
        array(
            'url'        => '/smap/(.)+',
            'action'     => 'index',
            'controller' => 'smap'
        ),
        array(
            'url'        => '/sitemap',
            'action'     => 'index',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/sitemap/doctor/:specialty_id',
            'action'     => 'doctorsByArea',
            'controller' => 'sitemap',
        ),
        array(
            'url'        => '/sitemap/clinic/:specialization_id',
            'action'     => 'clinicsByArea',
            'controller' => 'sitemap',
        ),
        array(
            'url'        => '/zayavka',
            'action'     => 'index',
            'controller' => 'oneClickSubscribe'
        ),
        array(
            'url'        => '/specialoffer',
            'action'     => 'index',
            'controller' => 'specialoffer'
        ),
        [
            'url'        => '/privacy-policy',
            'action'     => 'privacyPolicy',
            'controller' => 'UserAgreement',
        ],
        [
            'url'        => '/user-agreement',
            'action'     => 'index',
            'controller' => 'UserAgreement',
        ],
        [
            'url'        => '/medicina-4prc',
            'action'     => 'medicina4prc',
            'controller' => 'StaticPage',
        ],
        [
            'url'        => '/kartoteka',
            'action'     => 'kartoteka',
            'controller' => 'StaticPage',
        ],
        [
            'url'        => '/contacts',
            'action'     => 'contacts',
            'controller' => 'StaticPage',
        ],
// 4458 Убрать страницу федеральных клиник
//        array(
//            'url'        => '/federal-medical-centers',
//            'action'     => 'index',
//            'controller' => 'ClinicGroup',
//        ),
);

    // роутинг для контроллера Uslugi
    if(Application::config('section.services.available', false)){

        array_push($map, ['url' => '/uslugi',                          'controller' => 'uslugi', 'action' => 'index']);
        array_push($map, ['url' => '/uslugi/ajaxSearchClinics',        'controller' => 'uslugi', 'action' => 'ajaxSearchClinics']);
        array_push($map, ['url' => '/uslugi/ajaxSearchDoctors',        'controller' => 'uslugi', 'action' => 'ajaxSearchDoctors']);
        array_push($map, ['url' => '/uslugi/district-:district',       'controller' => 'uslugi', 'action' => 'district']);
        array_push($map, ['url' => '/uslugi/area-:district',           'controller' => 'uslugi', 'action' => 'area']);
        array_push($map, ['url' => '/uslugi/metro-:metro',             'controller' => 'uslugi', 'action' => 'metro']);
        array_push($map, ['url' => '/uslugi/street-:street',           'controller' => 'uslugi', 'action' => 'street']);
        array_push($map, ['url' => '/uslugi/:cat/district-:district',  'controller' => 'uslugi', 'action' => 'slug_district']);
        array_push($map, ['url' => '/uslugi/:cat/area-:district',      'controller' => 'uslugi', 'action' => 'slug_area']);
        array_push($map, ['url' => '/uslugi/:cat/metro-:metro',        'controller' => 'uslugi', 'action' => 'slug_metro']);
        array_push($map, ['url' => '/uslugi/:cat/street-:street',      'controller' => 'uslugi', 'action' => 'slug_street']);
        array_push($map, ['url' => '/uslugi/:cat/:slug',               'controller' => 'uslugi', 'action' => 'slug']);
        array_push($map, ['url' => '/uslugi/:cat',                     'controller' => 'uslugi', 'action' => 'category']);
    }

    Register::add('map', $map);
