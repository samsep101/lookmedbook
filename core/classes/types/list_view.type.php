<?php
	class List_viewType extends Type {

		public function getFormValue()
		{
			return '';
		}

		public function getViewValue(array $list)
		{
			$str  = '';

			if ($list)
			{
				foreach($list as $el)
				{
					$str1 = $el->{$this->fieldInfo['field_name']};
					$str .= $str1.', ';
				}
			}

			$result = trim($str, ', ');

			return $result;
		}
	}