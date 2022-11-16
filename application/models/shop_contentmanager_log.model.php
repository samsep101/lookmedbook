<?php
	/**
	 * @property int $id
	 * @property int $user_id
	 * @property UserModel $user
	 * @property int $product_id
	 * @property ProductModel $product
	 * @property int $is_image_uploaded
	 * @property int $is_status_confirmed
	 * @property datetime $dt
	 * @property string $date
	 *
	 * @property string $action
	 *
	 */
	class ShopContentmanagerLogModel extends DynamicModel
	{
		protected function  _field_action()
		{
			$str = '';

			if($this->is_image_uploaded)
			{
				$str .= 'загружена фотография';
			}

			if($this->is_status_confirmed)
			{
				if($str)
				{
					$str .= '; ';
				}

				$str .= ' заполнена информация';
			}

			return $str;
		}
	}