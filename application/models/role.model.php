<?php
	/**
	 * @property int $id
	 * @property string $name
	 *
	 */
	class RoleModel extends DynamicModel
	{
		const ACCOUNT_ADMIN = 1;
		const ACCOUNT_SUPER_MANAGER = 2;
		const ACCOUNT_REGISTRY = 3;
		const ACCOUNT_MANAGER = 4;
		const FREELANCE_MANAGER = 5;

		const CALL_CENTRE_OPERATOR = 6;
		const ESHOP_MANAGER = 7;
		const ESHOP_CONTENT_MANAGER = 8;
	}