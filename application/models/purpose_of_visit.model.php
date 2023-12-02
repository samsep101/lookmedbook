<?php
	/**
	 * @property int $id
	 * @property string $name
	 * @property int $purpose_of_visit_type_id
	 * @property PurposeOfVisitTypeModel $purpose_of_visit_type
	 *
	 */
    class PurposeOfVisitModel extends DynamicModel
	{
        const FIRST_VISIT_ID = 283;
        const SECOND_VISIT_ID = 284;
	}