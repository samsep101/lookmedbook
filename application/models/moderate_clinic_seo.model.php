<?php

	/**
	 * @property int $id
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 * @property int $metro_to_title
	 * @property int $address_to_title
	 * @property int $metro_to_description
	 * @property int $address_to_description
	 * @property string $seo_title
	 * @property string $seo_descritpion
	 *
	 */
    class ModerateClinicSeoModel extends ModerateModel {
        protected $fields = array(
            "id",
            "clinic_id",
            "metro_to_title",
            "address_to_title",
            "metro_to_description",
            "address_to_description",
            "seo_title",
            "seo_descritpion"
		);
	}