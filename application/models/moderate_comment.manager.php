<?php
	class ModerateCommentManager extends ModelManager
	{
		protected $table_name = 'moderate_comment';
		protected $model_name = 'ModerateCommentModel';

		/**
		 * Получение комментария модератора
		 *
		 * @param $entry_id идентификатор записи, которая сейчас редактируется
		 * @param $moderate_comment_type_id тип комментария (типы перечислены в ModerateCommentTypeModel)
		 * @param $revision_number номер ревизии
		 *
		 * @return ModerateCommentModel
		 */
		public function getComment($entry_id, $moderate_comment_type_id, $revision_number)
		{
			$data = $this->orm_model->select()->where('entry_id = ? AND moderate_comment_type_id = ? AND revision_number = ?', $entry_id, $moderate_comment_type_id, $revision_number)->fetchOne();

			return $this->initOne($data);
		}
	}