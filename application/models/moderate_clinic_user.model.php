<?php

	/**
	 * @property int $id
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 * @property string $fio
	 * @property int $moderate_status_id
	 * @property ModerateStatusModel $moderate_status
	 * @property string $phone
	 * @property string $email
	 * @property string $site
	 * @property int $revision_number
	 *
	 */
    class ModerateClinicUserModel extends ModerateModel {
        protected $fields = array(
            'fio',
            'phone',
            'email',
            'site'
		);
	}