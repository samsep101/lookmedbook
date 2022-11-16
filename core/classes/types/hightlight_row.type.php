<?php
    class Hightlight_rowType extends CategoryType
    {
        public function getViewValue($val) {
			$color = '';
			if (isset($this->fieldInfo['colors'][$val]))
				$color = $this->fieldInfo['colors'][$val];

            $result = '<span class="highlight_color" data-color="'.$color.'"></span>';
                   
            return $result;
    	}
    }