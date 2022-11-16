<?php

    class InputType extends Type
    {

        public function getFormValue($val = '')
        {

            $value = htmlspecialchars($val);

			$style = (isset($this->fieldInfo['style'])) ? $this->fieldInfo['style'] : '';
            $inputType = (isset($this->fieldInfo['inputType'])) ? $this->fieldInfo['inputType'] : 'text';


            $szResult = '<input type="'. $inputType .'" name="'.$this->getHtmlElementName().'" ';

            $szResult .= 'value="' . $value . '" ';

			$szResult .= 'style="'.$style.'" ';

			if (isset($this->fieldInfo['placeholder']))
			{
				$szResult .= ' placeholder="'.$this->fieldInfo['placeholder'].'" ';
			}

            $szResult .= '>';
            if (!empty($this->fieldInfo['label']))
                $szResult .= '<span class="label">* ' . $this->fieldInfo['label'] . '</span>';

            if (isset($this->fieldInfo['script']))
            {
                $szResult .= '<script>'.$this->fieldInfo['script'].'</script>';
            }

            return $szResult;
        }

        public function getViewValue($value)
        {
            return htmlspecialchars($value);
        }

    }