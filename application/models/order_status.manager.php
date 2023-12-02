<?php
	class OrderStatusManager extends StaticDataModelManager
	{
		protected $table_name = "order_status";
		protected $model_name = "OrderStatusModel";

		protected $model_data = array(
			OrderStatusModel::BACK => array(
				'id' => OrderStatusModel::BACK,
				'name' => 'Возврат курьером',
			),
			OrderStatusModel::SEND => array(
				'id' => OrderStatusModel::SEND,
				'name' => 'Отправлен',
			),
			OrderStatusModel::CANCELLED => array(
				'id' => OrderStatusModel::CANCELLED,
				'name' => 'Отменен'
			),
			OrderStatusModel::CONFIRMED => array(
				'id' => OrderStatusModel::CONFIRMED,
				'name' => 'Подтвержден'
			),
			OrderStatusModel::CREATE_BY_OPERATOR => array(
				'id' => OrderStatusModel::CREATE_BY_OPERATOR,
				'name' => 'Создан оператором',
			),
			OrderStatusModel::DELIVERED => array(
				'id' => OrderStatusModel::DELIVERED,
				'name' => 'Доставлен',
			),
			OrderStatusModel::IN_PROCESS => array(
				'id' => OrderStatusModel::IN_PROCESS,
				'name' => 'В обработке',
			),
			OrderStatusModel::FILLED => array(
				'id' => OrderStatusModel::FILLED,
				'name' => 'Собран',
			),
			OrderStatusModel::IN_QUEUE => array(
				'id' => OrderStatusModel::IN_QUEUE,
				'name' => 'В очереди',
			),
			OrderStatusModel::NO_HAVE_PRODUCT => array(
				'id' => OrderStatusModel::NO_HAVE_PRODUCT,
				'name' => 'Нет лекарств',
			),
			OrderStatusModel::TRY_TO_CALL => array(
				'id' => OrderStatusModel::TRY_TO_CALL,
				'name' => 'Попытка дозвонится',
			),
			OrderStatusModel::PRODUCT_NO_MUCH => array (
				'id' => OrderStatusModel::PRODUCT_NO_MUCH,
				'name' => 'Недостаточно лекарств',
			),
			OrderStatusModel::WAIT_FOR_PAY => array(
				'id' => OrderStatusModel::WAIT_FOR_PAY,
				'name' => 'Ожидание оплаты',
			),
			OrderStatusModel::PRODUCT_ORDERED => array(
				'id' => OrderStatusModel::PRODUCT_ORDERED,
				'name' => 'Товар заказан',
			),
			OrderStatusModel::LMB_CANCELED => array(
				'id' => OrderStatusModel::LMB_CANCELED,
				'name' => 'Отменен, запрос не отправлен в piluli.ru',
			),
			OrderStatusModel::LMB_IN_QUEUE => array(
				'id' => OrderStatusModel::LMB_IN_QUEUE,
				'name' => 'Заказ не отправлен piluli.ru',
			),
			OrderStatusModel::NEED_CHECK => array(
				'id' => OrderStatusModel::NEED_CHECK,
				'name' => 'Необходимо проверить (ошибка)',
			),
		);

        public function getOneByName($name)
        {
            $sql = 'SELECT *
                    FROM order_status
                    WHERE `name` = ' ."'" .$name ."'";

            $data = $this->db->query($sql);

            return (isset($data[0])) ? $this->initOne($data[0]) : null;
        }
	}