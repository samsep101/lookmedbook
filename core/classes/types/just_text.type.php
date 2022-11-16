<?php

	class Just_textType extends Type
	{

		public function getFormValue($val = '')
		{
            if($val)
            {
                $result = '<span>' . $val . '</span>';
            } else {
                $result = '<span>-</span>';
            }
			return $result;
		}

		public function getViewValue($val = '')
		{
            if($val)
            {
                $result = '<span>' . $val . '</span>';
            } else {
                $result = '<span>-</span>';
            }
			return $result;
		}

	}