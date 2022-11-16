<?php
	class ProductBasketFactory
	{
		public static function getInstance()
		{
			return new ProductBasket();
		}
	}