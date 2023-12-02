<?php
	class MessageManager extends ModelManager
	{
		protected $table_name = 'message';
		protected $model_name = 'MessageModel';


        /**
		 * @return MessageModel[]
		 */
		public function getListByToAccountId($to_account_id)
		{
			$data = $this->orm_model->select()->where('to_account_id = ?', $to_account_id)->fetchAll();
			return (count($data)) ? $this->initList($data) : array();
		}

        /**
		 * @return MessageModel[]
		 */
		/**
		 * @return MessageModel[]
		 */
		public function getListByToAccountIdWithPadding($to_account_id, $page, $per_page)
		{
			$page = ($page - 1) * $per_page;

			$data = $this->orm_model->select()->where('to_account_id = ?', $to_account_id)->limit($page, $per_page)->order('dt DESC')->fetchAll();
			return (count($data)) ? $this->initList($data) : array();
		}

        /**
		 * @return MessageModel[]
		 */
		public function getListByDt($dt)
		{
			$data = $this->orm_model->select()->where('dt = ?', $dt)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return MessageModel[]
		 */
		public function getListByIsReaded($is_readed)
		{
			$data = $this->orm_model->select()->where('is_readed = ?', $is_readed)->fetchAll();
			return $this->initList($data);
		}

		public function getCountUnreadedByAccountId($account_id)
		{
			$data = $this->orm_model->select()->where('to_account_id = ? and is_readed is Null', $account_id)->fetchAll();
			$count = count($data);
			return ($count ? $count : '');
		}

		public function readMessage($account_id, $message_id)
		{
			$sql = 'UPDATE
                    message
                    SET is_readed = 1
                    WHERE to_account_id = ' . (int)$account_id . ' and id = ' . (int)$message_id;

			$db = Register::get('db');

			$db->query($sql);
		}

	}
