<?php
	class ModerateCommentViewHelper
	{
		public static function getComment($entry_id, $moderate_comment_type_id, $revision_number)
		{
			$moderate_comment_manager = ModelManagerFactory::getByName('moderate_comment');
			$moderate_comment = $moderate_comment_manager->getComment($entry_id, $moderate_comment_type_id, $revision_number);

			if ($moderate_comment)
			{
				return '<div style="clear:both; padding-top: 5px;">
							<div class="moderate_comment"><b>Комментарий '.SITE_NAME.':</b> '.$moderate_comment->comment.'
							</div>
							<input type="hidden" value="'.$moderate_comment->comment.'" />
						</div>';
			} else {
				return '';
			}
		}

		public static function getInput($entry_id, $moderate_comment_type_id, $revision_number)
		{
			$html = self::getComment($entry_id, $moderate_comment_type_id, $revision_number);

			if (!$html)
			{
				$html = '<input type="hidden" value="" />';
			}

			$html .=    '<div class="buttons">
							<img class="edit" src="/media/images/pen.png" />
							<img class="delete" src="/media/images/cross.png" />
						 </div>
						 ';

			$id = 'moderate-comment-block'.$entry_id.'-'.$moderate_comment_type_id;

			$html = '
				<script type="text/javascript">
					$(document).ready(function(){
						moderate_comment_block_controller = new ModerateCommentBlockController();
						moderate_comment_block_controller.setContainer("#'.$id.'");
						moderate_comment_block_controller.setData('.$entry_id.', '.$moderate_comment_type_id.', '.(isset($revision_number) ? $revision_number : 2).');
						moderate_comment_block_controller.init();
					});
				</script>
				<div id="'.$id.'" style="clear:both; padding-top: 5px;">
				'.$html.'
				</div>
			';

			return $html;
		}

		public static function getView($entry_id, $moderate_comment_type_id, $revision_number)
		{
			if (Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER))
			{
				return self::getInput($entry_id, $moderate_comment_type_id, $revision_number);
			} else {
				return self::getComment($entry_id, $moderate_comment_type_id, $revision_number);
			}
		}
	}