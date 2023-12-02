<?php
	class BasketShopController extends BaseController
	{
		public function index()
		{
            RedirectManager::redirect301('/shop/catalog'); /* Закрытие доступа по требованию */

            $this->view->menu_active = 'shop';
            $this->view->page_title = 'Корзина';
			$this->render('shop/basket/index');
		}

		public function order()
		{
            RedirectManager::redirect301('/shop/catalog'); /* Закрытие доступа по требованию */

            /**
             * @var OrderManager $order_manager
             * @var OrderModel $order
             */

            if(Acc::isAuthed())
            {
                $order_manager = ModelManagerFactory::getByName('order');
                $order = $order_manager->getOneLastByAccountId(Acc::accountId());

                if($order)
                {
                    $this->view->last_order = $order;
                }
            }

            $this->view->page_title = 'Оформление заказа';
            $this->view->menu_active = 'shop';

			if($this->product_basket->getTotalPrice() < 500)
			{
				$this->render('shop/basket/not_order');
			}
		}

		public function ajaxOrder()
		{
			$order_info = $this->request('order_info');

			$shipping_type_id = isset($order_info['shipping_type_id']) ? $order_info['shipping_type_id'] : null;
			$address = isset($order_info['address']) ? $order_info['address'] : null;
			$name = isset($order_info['name']) ? $order_info['name'] : null;
			$phone_number = isset($order_info['phone_number']) ? $order_info['phone_number'] : null;
			$email = isset($order_info['email']) ? $order_info['email'] : null;
			$comment = isset($order_info['comment']) ? $order_info['comment'] : null;
			$shipping_cost = isset($order_info['shipping_cost']) ? $order_info['shipping_cost'] : null;

			$check_id = $this->request('check_id');

			$account_id = Acc::accountId();
            $new_account = null;
            $password = null;

			$phone_number = StringHelper::leaveOnlyTheNumber($phone_number);
			if(!StringHelper::isPhoneNumber($phone_number))
			{
				JsonResponse::error(ValidationErrorCodes::WRONG_DATA);
			}

			if(!Acc::accountId())
			{
				$need_check_phone_flag = false;

				if($check_id)
				{
					$account_phone_verification = new AccountPhoneVerification();
					if($account_phone_verification->checkStatusByCheckIdAndPhoneNumber($check_id, $phone_number))
					{
						/**
						 * @var AccountPhoneManager $account_phone_manager
						 */
						$account_phone_manager = ModelManagerFactory::getByName('account_phone');
						$account_phone = $account_phone_manager->getOneConfirmedByPhoneNumber($phone_number);

						if($account_phone)
						{
							$account_id = $account_phone->account_id;

							Acc::login($account_phone->account);
						} else {
							$account = new AccountModel();
							$account->email = StringHelper::leaveOnlyTheNumber($phone_number).'@user.ru';
							$password = StringGeneratorHelper::generateNumbers(6);
							$account->password = $password;
							$account->save();

							$account_phone = new AccountPhoneModel();
							$account_phone->account_id = $account->getId();
							$account_phone->phone = $phone_number;
							$account_phone->is_confirmed = 1;
							$account_phone->save();

							$account_id = $account->getId();

                            $new_account = 1;

							Acc::login($account);
						}
					} else {
						$need_check_phone_flag = true;
					}
				} else {
					$need_check_phone_flag = true;
				}

				if($need_check_phone_flag)
				{
					JsonResponse::error(ValidationErrorCodes::NEED_CHECK_PHONE);
				}
			}

            if(Acc::accountId()) {

                /**
                 * @var AccountManager $account_manager
                 * @var AccountModel $account
                 */
                $account_manager = ModelManagerFactory::getByName('account');
                $account = $account_manager->getOneById(Acc::accountId());

                if ($account->is_product_admin) {
                    /**
                     * @var AccountPhoneManager $account_phone_manager
                     */
                    $account_phone_manager = ModelManagerFactory::getByName('account_phone');
                    $account_phone = $account_phone_manager->getOneByPhone($phone_number);

                    if($account_phone)
                    {
                        $account_id = $account_phone->account_id;
                        if ($account_phone->is_confirmed != 1) {
                            $account_phone->is_confirmed = 1;
                            $account_phone->save();
                        }
                    } else {
                        $account = new AccountModel();
                        $account->email = StringHelper::leaveOnlyTheNumber($phone_number).'@user.ru';
                        $password = StringGeneratorHelper::generateNumbers(5);
                        $account->password = $password;
                        $account->save();

                        $account_phone = new AccountPhoneModel();
                        $account_phone->account_id = $account->getId();
                        $account_phone->phone = $phone_number;
                        $account_phone->is_confirmed = 1;
                        $account_phone->save();

                        $account_id = $account->getId();

                        $new_account = 1;
                    }
                }
            }


			$order_products = $this->request('order_products');

			$total_cost = 0;

			foreach($order_products as $order_product)
			{
				$total_cost += $order_product['price'] * $order_product['count'];
			}

			$total_cost += (float)$shipping_cost;


			$order = new OrderModel();
			$order->account_id = $account_id;
			$order->address = $address;
			$order->comment = $comment;
			$order->dt_order = date('Y-m-d H:i:s');
			$order->email = $email;
			$order->name = $name;
			$order->order_status_id = OrderStatusModel::LMB_IN_QUEUE;
			$order->phone_number = $phone_number;
			$order->total_cost = $total_cost;
			$order->shipping_type_id = $shipping_type_id;
			$order->shipping_cost = $shipping_cost;
			$order->save();

			$order->system_code = 'lookmedbook_' . $order->getId();
			$order->save();

			foreach($order_products as $order_product)
			{
				$product_to_order = new ProductToOrderModel();
				$product_to_order->product_id = $order_product['id'];
				$product_to_order->order_id = $order->getId();
				$product_to_order->price = $order_product['price'];
				$product_to_order->amount = $order_product['count'];
				$product_to_order->save();
			}

			$mail = new VisitMailModel();
			$mail->order_id = $order->getId();
			$mail->dt = date('Y-m-d H:i:s');

			$positions_text = '';
			$i = 1;
			foreach($order->products_info as $product_info)
			{
				$positions_text .= $i.'. '.$product_info->product->clean_name.' - '.$product_info->amount.' шт. -
				'.($product_info->price * $product_info->amount)." руб.<br />";
				$i++;
			}

			$delivery = '';

			if($order->shipping_cost)
			{
				$delivery = 'Доставка: '.(int)$order->shipping_cost.' руб.';
			} else {
				$delivery = 'Доставка бесплатно';
			}

			$tokens = array(
				'order_id' => $order->system_code,
				'total_cost' => $order->total_cost,
				'name' => $order->name,
				'order_positions' => $positions_text,
				'delivery' => $delivery,
			);

			$template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens('shop_order_message', $tokens);
			$mail->title = $template_data->title;
			$mail->text = $template_data->text;
			$mail->visit_mail_type_id = VisitMailTypeModel::SHOP_ORDER;
			$mail->save();

            if ($new_account) {
                $code = 'shop_order_sms_with_new_account';
                $tokens = array(
                    'order_id' => $order->system_code,
                    'phone' => '+'.$phone_number,
                    'password' => $password
                );
            } else {
                $code = 'shop_order_sms';
                $tokens = array(
                    'order_id' => $order->system_code
                );
            }
            $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
            SmsSender::sendMessage($phone_number, $template_data->text);

			$this->product_basket->clearBasket();

			JsonResponse::result(array(
									  'order_id' => $order->getId(),
									  'system_code' => $order->system_code
								 ));

		}

		public function ajaxAddProduct()
		{
			$product_id = $this->request('product_id');
			$count = $this->request('count');

			$result = $this->product_basket->setProductCount($product_id, $count);

			if(!$result)
			{
				JsonResponse::error(ValidationErrorCodes::ADD_TO_BASKET_ERROR);
			}
			else
			{
				/**
				 * @var ProductManager $product_manager
				 * @var ProductModel $product
				 */
				$product_manager = ModelManagerFactory::getByName('product');
				$product = $product_manager->getOneById($product_id);
				$data = array(
					'product_id' => $product->getId(),
					'price' => $product->price,
					'quantity' => $product->quantity
				);
				JsonResponse::result($data);
			}
		}

		public function ajaxDeleteProduct()
		{
			$product_id = $this->request('product_id');
			$result = $this->product_basket->deleteProduct($product_id);

			if($result)
			{
				JsonResponse::result(true);
			}
			else
			{
				JsonResponse::error(ValidationErrorCodes::WRONG_DATA);
			}
		}

		public function ajaxClearBasket()
		{
			$this->product_basket->clearBasket();
			JsonResponse::result(true);
		}
	}
