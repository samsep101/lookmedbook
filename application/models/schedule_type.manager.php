<?php
	class ScheduleTypeManager extends StaticDataModelManager
	{
		protected $model_name = 'ScheduleTypeModel';

		protected $model_data = array(
            1 => array('id' => 1, 'name' => 'По дням недели'), 2 => array('id' => 2, 'name' => 'По дням недели, недели чередуются, первая неделя - первая в году'), 3 => array('id' => 3, 'name' => 'По дням недели, недели чередуются, первая неделя - первая в месяце'), 4 => array('id' => 4, 'name' => 'По четный и нечетным числам'),);
	}