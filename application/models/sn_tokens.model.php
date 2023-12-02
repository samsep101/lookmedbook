<?php

	/**
	 * @property int $id
	 * @property int $account_id
	 * @property AccountModel $account
	 * @property string $sn_name
	 * @property string $token
	 * @property string $uid
	 * @property int $task_status_id
	 * @property TaskStatusModel $task_status
	 * @property datetime $dt_start
	 *
	 */
    class SnTokensModel extends DynamicModel {
		const VK_NAME = 'vk';
		const FB_NAME = 'fb';
		const MAILRU_NAME = 'mailru';
		const OK_NAME = 'ok';

	}