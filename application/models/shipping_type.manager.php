<?php
	class ShippingTypeManager extends StaticDataModelManager
	{
		protected $model_name = 'ShippingTypeModel';
		protected $model_data = array(
			ShippingTypeModel::AUTO => array(
				'id' => ShippingTypeModel::AUTO,
				'name' => 'Автомобилем',
			),
			ShippingTypeModel::MOSCOW_BUTOVO => array(
				'id' => ShippingTypeModel::MOSCOW_BUTOVO,
				'name' => 'Москва в пределах МКАД + Бутово',
			),
			ShippingTypeModel::TO_10KM => array(
				'id' => ShippingTypeModel::TO_10KM,
				'name' => 'Ближнее Подмосковье, до 10 км от МКАД',
			),
			ShippingTypeModel::FROM_10KM => array(
				'id' => ShippingTypeModel::FROM_10KM,
				'name' => 'Подмосковье, от 10 км от МКАД',
			),
			ShippingTypeModel::EMS => array(
				'id' => ShippingTypeModel::EMS,
				'name' => 'По России EMS',
			),
		);
	}