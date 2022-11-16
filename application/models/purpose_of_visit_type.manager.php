<?php
	class PurposeOfVisitTypeManager extends StaticDataModelManager
	{
		protected $model_name = 'PurposeOfVisitTypeModel';

		protected $model_data = array(
            1 => array('id' => 1, 'name' => 'Услуга'),
            2 => array('id' => 2, 'name' => 'Диагностика'),
        );
	}