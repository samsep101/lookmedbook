<?php
	class PiluliRequestErrorManager extends ModelManager
	{
		protected $table_name = "piluli_request_error";
		protected $model_name = "PiluliRequestErrorModel";

		public function beforeSave(PiluliRequestErrorModel $model)
		{
			if($model->isNew())
			{
				$model->dt = date('Y-m-d H:i:s');
			}
		}

	}