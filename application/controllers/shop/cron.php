<?php
	class CronShopController extends BaseController
	{
		public function __construct()
		{
			parent::__construct();
		}

		/**
		 * Отвечает за обновление базы товаров.
		 * Получает весь список товаров piluli.ru и добавляет
		 * в базу те, которых на данный момент нет
		 */
		public function updateProductList()
		{
			ShopTaskManager::updateProductList();
		}

		/**
		 * Обновление наличия товаров
		 */
		public function updateAvailabilityOfProducts()
		{
			ShopTaskManager::updateAvailabilityOfProducts();
		}

		/**
		 * Отправка созданных заказов в piluli.ru
		 */
		public function createOrders()
		{
			ShopTaskManager::createOrders();
		}

		/**
		 * Обновление статусов заказов
		 */
		public function updateOrdersStatuses()
		{
			ShopTaskManager::updateOrdersStatuses();
		}

		/**
		 * Обновление заказов, которые не обновились при помощи метода updateOrdersStatuses
		 */
		public function updateNotProcessedOrders()
		{
			ShopTaskManager::updateNotProcessedOrders();
		}

		/**
		 * Обновление статусов категорий (активный/не активный)
		 */
		public function updateCategoriesStatus()
		{
			ShopTaskManager::updateProductCategoriesActiveStatus();
		}

		/**
		 * Загрузка изображений для новых товаров
		 */
		public function downloadProductsImages()
        {
            ShopTaskManager::downloadProductsImage();
        }

		public function beforeAction()
		{
			set_time_limit(0);
		}

		public function beforeRender()
		{
			exit();
		}
	}