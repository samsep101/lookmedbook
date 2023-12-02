<?php

	/**
	 * @property int $id
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 * @property string $text
	 * @property string $dt
	 *
	 */
    class ClinicProblemModel extends DynamicModel {
		const BLIND = 'Отсутствуют специализации и врачи';
		const STATIC_SERVICE_ONLY = 'Отсутствуют врачи в клинике';
		const STATIC_RESOURCE_ONLY = 'Отсутствуют специализации у клиники';
	}