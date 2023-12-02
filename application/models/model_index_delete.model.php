<?php
	/**
	 * @property int $id
	 * @property string $name
	 * @property int $model_id
	 * @property int $is_deleted
	 *
	 */
	class ModelIndexDeleteModel extends DynamicModel
	{
		public function __construct()
		{
			$this->setDefaultValue('is_deleted', 0);
		}
	}