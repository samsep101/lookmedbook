<?php
	/**
	 * @property int $id
	 * @property int $prev_order_status_id
	 * @property OrderStatusModel $prev_order_status
	 * @property int $new_order_status_id
	 * @property OrderStatusModel $new_order_status
	 * @property string $dt
	 *
	 */
	class OrderStatusChangeModel extends DynamicModel {


		public function _field_prev_order_status()
		{
			if(!isset($this->prev_order_status))
			{
				/**
				 * @var OrderStatusManager $order_status_manager
				 */
				$order_status_manager = ModelManagerFactory::getByName('order_status');
				$this->prev_order_status = $order_status_manager->getOneById($this->prev_order_status_id);
			}

			return $this->prev_order_status;
		}

		public function _field_new_order_status()
		{
			if(!isset($this->new_order_status))
			{
				/**
				 * @var OrderStatusManager $order_status_manager
				 */
				$order_status_manager = ModelManagerFactory::getByName('order_status');
				$this->new_order_status = $order_status_manager->getOneById($this->new_order_status_id);
			}

			return $this->new_order_status;
		}
	}