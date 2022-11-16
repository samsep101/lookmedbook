<?php
	/**
	 * @property int $id
	 * @property string $name
	 */
	class OrderStatusModel extends DynamicModel
	{
		const IN_QUEUE = 15;
		const IN_PROCESS = 1;
		const TRY_TO_CALL = 2;
		const CONFIRMED = 3;
		const DELIVERED = 4;
		const CANCELLED = 5;
		const SEND = 6;
		const NO_HAVE_PRODUCT = 7;
		const WAIT_FOR_PAY = 8;
		const CREATE_BY_OPERATOR = 9;
		const PRODUCT_ORDERED = 10;
		const PRODUCT_NO_MUCH = 11;
		const BACK = 12;
		const FILLED = 13;

		const NEED_CHECK = 50;

		const LMB_IN_QUEUE = 100;
		const LMB_CANCELED = 101;
	}