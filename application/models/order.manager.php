<?php
	class OrderManager extends ModelManager
	{
		protected $table_name = "order";
		protected $model_name = "OrderModel";

		public function beforeSave(DynamicModel $model)
		{
			/**
			 * @var OrderModel $model
			 */
			$model->phone_number = preg_replace('/[^0-9]/ims', '', $model->phone_number);

			if($model->order_status_id === 0)
			{
				$model->order_status_id = OrderStatusModel::IN_QUEUE;
			}

			if($model->statusChanged())
			{
				$order_status_change = new OrderStatusChangeModel();
				$order_status_change->prev_order_status_id = $model->getPrevOrderStatusId();
				$order_status_change->new_order_status_id = $model->getNewOrderStatusId();
				$order_status_change->order_id = $model->getId();
				$order_status_change->save();
			}

			$model->dt_update = date('Y-m-d H:i:s');
		}

		/**
		 * @var int $account_id
		 * @return OrderModel[]
		 */
		public function getListByAccountId($account_id)
		{
			$data = $this->orm_model->select()->where('account_id = ?', $account_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $system_code
		 * @return OrderModel[]
		 */
		public function getListBySystemCode($system_code)
		{
			$data = $this->orm_model->select()->where('system_code = ?', $system_code)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $shipping_type_id
		 * @return OrderModel[]
		 */
		public function getListByShippingTypeId($shipping_type_id)
		{
			$data = $this->orm_model->select()->where('shipping_type_id = ?', $shipping_type_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $payment_type_id
		 * @return OrderModel[]
		 */
		public function getListByPaymentTypeId($payment_type_id)
		{
			$data = $this->orm_model->select()->where('payment_type_id = ?', $payment_type_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $order_status_id
		 * @return OrderModel[]
		 */
		public function getListByOrderStatusId($order_status_id)
		{
			$data = $this->orm_model->select()->where('order_status_id = ?', $order_status_id)->fetchAll();
			return $this->initList($data);
		}
        /**
         * @var int $account_id
         * @return OrderModel[]
         */
        public function getListByAccountIdOrderByDateWithLimit($account_id, $offset, $limit = 4)
        {
            $sql = 'SELECT *
                    FROM `order`
                    WHERE account_id = ' .(int)$account_id .'
                    ORDER BY dt_order DESC
                    LIMIT ' .(int)$offset .', ' .(int)$limit;

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        public function updateStateById($id, $order_status)
        {
            $sql = 'UPDATE `order`
                    SET order_status_id = ' .(int)$order_status .'
                    WHERE id = ' .(int)$id;

            $this->db->query($sql);
        }

        public function deleteOneByOrderId($order_id)
        {
            $sql = 'DELETE
                    FROM `order`
                    WHERE id = ' .(int)$order_id;

            $this->db->query($sql);
        }

		public function getListByModelSearchCriteria(ModelSearchCriteria $criteria)
		{
			/**
			 * @var OrderSearchCriteria $criteria
			 */
			if($criteria->dt_from)
			{
				$criteria->dt_from = date('Y-m-d', strtotime($criteria->dt_from));
			}
			if($criteria->dt_to)
			{
				$criteria->dt_to = date('Y-m-d', strtotime($criteria->dt_to));
			}

			$search_params = $criteria->getSearchParams();
			if (!$search_params)
				$search_params = new SearchParams();

			if ($criteria->page && $criteria->by_page)
			{
				$limit = $criteria->by_page;
				$offset = ($criteria->page - 1) * $criteria->by_page;
				$search_params->setOffsetAndLimit($offset, $limit);
			}

			if($criteria->dt_from)
			{
				$search_params->addParam('dt_order >=', $criteria->dt_from);
			}

			if($criteria->dt_to)
			{
				$search_params->addParam('dt_order <=', $criteria->dt_to);
			}

			if($criteria->id)
			{
				$search_params->addParam('id', (int)$criteria->id);
			}

			if($criteria->phone_number)
			{
				$search_params->addParam('phone_number LIKE', $criteria->phone_number.'%');
			}


			return $this->getListBySearchParams($search_params);
		}

		/**
		 * @param $system_code
		 *
		 * @return OrderModel
		 */
		public function getOneBySystemCode($system_code)
		{
			$data = $this->orm_model->select()->where('system_code = ?', $system_code)->fetchOne();
			return $this->initOne($data);
		}

        public function getOneLastByAccountId($account_id)
        {
            $sql = 'SELECT *
                    FROM `order`
                    WHERE account_id = ' .(int)$account_id .'
                    ORDER BY dt_order DESC';

            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : null;
        }


		/**
		 *
		 * @return OrderModel[] $order
		 */
		public function getNotProcessedOrdersByMinutesCount($minutes_count)
		{
			$sql = 'SELECT *
					FROM `order`
					WHERE dt_update <= "'.date('Y-m-d H:i:s', time() - (int)$minutes_count*60).'"
						AND order_status_id = '.OrderStatusModel::IN_QUEUE;

			$data = $this->db->query($sql);

			return $this->initList($data);
		}
	}