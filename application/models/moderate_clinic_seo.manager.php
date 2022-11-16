<?php
	class ModerateClinicSeoManager extends EntityModerateModelManager
	{
		protected $table_name = 'moderate_clinic_seo';
		protected $model_name = 'ModerateClinicSeoModel';

		protected $moderated_entity_name = 'seo';

		protected $fields = array(
            "clinic_id",
            "metro_to_title",
            "address_to_title",
            "metro_to_description",
            "address_to_description",
            "seo_title",
            "seo_descritpion"
        );

        public function getByClinicId( $clinic_id ){

            $sql = "SELECT * FROM {$this->table_name} WHERE clinic_id = {$clinic_id }";

            $res = $this->getListByQuery($sql);

            //var_dump($res[0]); exit;

            return isset($res[0]) ? $res[0] : null;

        }
	}