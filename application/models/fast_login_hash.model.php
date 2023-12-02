<?php
	/**
	 * @property int $id
	 * @property int $account_id
	 * @property AccountModel $account
	 * @property datetime $dt_expire
	 * @property int $max_usage_count
	 * @property int $usage_count
	 * @property string $hash
	 */
	class FastLoginHashModel extends DynamicModel
	{
		public function __construct()
		{
			$this->setDefaultValue('usage_count', 0);
		}
    }