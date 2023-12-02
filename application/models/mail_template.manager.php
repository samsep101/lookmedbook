<?php
	class MailTemplateManager extends ModelManager
	{
		protected $table_name = 'mail_template';
		protected $model_name = 'MailTemplateModel';

        /**
		 * @return MailTemplateModel
		 */
		public function getOneByCodeAndTokens($code)
		{
			$sql = 'SELECT *
                    FROM mail_template
                    WHERE code = "' . $this->db->escape($code) . '"';

			$data = $this->db->query($sql);

			return (isset($data[0])) ? $data[0] : null;
		}
	}
