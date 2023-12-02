<?php
	class PaymentTypeManager extends  StaticDataModelManager
	{
		protected $model_data = array(
			PaymentTypeModel::CASH => array(
				'id' => PaymentTypeModel::CASH,
				'name' => 'Оплата наличными'
			),
			PaymentTypeModel::SBERBANK => array(
				'id' => PaymentTypeModel::SBERBANK,
				'name' => 'Предварительная оплата (через Сберанк)'
			),
		);

		protected $model_name = 'PaymentTypeModel';
	}