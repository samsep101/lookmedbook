<?php
    class SpecialtyApiController extends ApiController
    {
        public $layout = 'ajax';


        public function getCachedMethods()
        {
            return array(
                'getList' => array(
                    'tags' => array(
                        'specialty:list',
                        'specialty'
                    )
                )
            );
        }

        // получение списка всех специальностей
        public function getList()
        {
            /**
            * @var SpecialtyManager $specialty_manager
            * @var SpecialtyModel $specialty
            */

            $city_id = $this->request('city_id');
            $specialty_manager = ModelManagerFactory::getByName('specialty');

            if (!$city_id){
                $specialties = $specialty_manager->getList();
            } else {
                $specialties = $specialty_manager->getHavingDoctorsListByCityId($city_id);
            }

            if (!$specialties)
                ApiHeader::error(ApiRequestErrors::SPECIALTIES_NOT_EXIST);

            foreach ($specialties as $specialty) {
                $result[] = array(
                    'specialty_id' => $specialty->getId(),
                    'name' => $specialty->name,
                    'is_adult' => ($specialty->for_whom == 2 || $specialty->for_whom == 1) ? 1 : 0,
                    'is_child' => ($specialty->for_whom == 3 || $specialty->for_whom == 1) ? 1 : 0,
                );
            }

            ApiHeader::response($result, $this->e_tag);
        }
    }