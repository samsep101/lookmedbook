<?php
	class ModelCommentsGenerator {

		public static function generate($fields)
		{
			$html ='	/**'."\r\n";

			$ext = '';
			foreach($fields as $field)
			{
				$field_str = "	 * @property ";

				if ($field['Type'] == 'ext')
				{
					$ext .= "\t * @property  $".$field['Field']."\r\n";
				} else {
					$type = 'string';

					if (strpos($field['Type'], 'int') !== false)
					{
						$type = 'int';
					}

					if (strpos($field['Type'], 'datetime') !== false)
					{
						$type = 'datetime';
					}

					$field_str .= $type.' ';
					$field_str .= '$'.$field['Field'];

					$field_str .= "\r\n";

					$html .= $field_str;

					if (preg_match('/^(.+)_id$/ims', $field['Field'], $matches))
					{
						$model_name = StringHelper::toCamelCase($matches[1]).'Model';

						$field_str = "\t * @property ".$model_name.' $'.$matches[1];
						$field_str .= "\r\n";
						$html .= $field_str;
					}
				}
			}

			$html .= "\t *\r\n";
			$html .= $ext;

			$html .= "\t */";


			return $html;
		}
	}