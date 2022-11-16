<?php

    class CommentType extends Type
    {

        public function getFormValue()
        {

            /* not valid */
            $commentModel = new Orm(DB_PREFIX . 'comment', 'id');
            $comments = $commentModel
                ->select()
                ->fields(DB_PREFIX . 'account.login')
                ->join(DB_PREFIX . 'account', DB_PREFIX . 'account.id = pre_comment.user_id')
                ->where('type = ?', $this->fieldInfo['comment_type'])
                ->where('type_id = ?', $this->indexValue)
                ->order('created_at ASC')
                ->fetchAll();
//		var_dump($comments);
            if (!count($comments))
                return '<h2>Нет комментариев</h2>';

            $content = '<table class="list"><thead><tr><th style="width: 120px;">Автор</th><th>Комментарий</th></tr></thead><tbody>';

            foreach ($comments as $comment) {
                $content .= '<tr><td valign="top"><b>' . $comment['login'] . '</b>
			<br>' . date('d.m.Y H:i:s', $comment['created_at']) . '
			<br><br>
			<label><input type="checkbox" name="form[' . $this->getFieldName() . '][]" value="' . $comment['id'] . '"> удалить</label>
			</td><td>';
                $content .= htmlspecialchars($comment['comment']) . '</td></tr>';
            }
            $content .= '</tbody></table>';
//		var_dump($content);
            return $content;
        }

        public function getViewValue()
        {
            return htmlspecialchars($this->value);
        }

        function getSaveValue($values)
        {
            $db = Register::get('db');

            if (is_array($values)) {
                foreach ($values as $val) {
                    $sql = 'DELETE FROM ' . DB_PREFIX . 'comment WHERE id=' . $val;
                    $db->query($sql);
                }
            }
            return Type::NOT_SET;
        }

    }
