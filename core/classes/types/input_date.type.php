<?php

class Input_dateType extends Type
{

	public function getFormValue($val = '')
	{

		$style = (isset($this->fieldInfo['style'])) ? $this->fieldInfo['style'] : '';

		if ($val)
		{
			$format = $this->fieldInfo['format']  ? $this->fieldInfo['format'] : 'Y-m-d';
			$value = date($format, strtotime($val));
		} else {
			$value = '';
		}

		$szResult = '<input type="text" name="'.$this->getHtmlElementName().'" ';

		$szResult .= 'value="' . $value . '" ';

		$szResult .= 'style="'.$style.'" ';

		$szResult .= '>';
		if (!empty($this->fieldInfo['label']))
			$szResult .= '<span class="label">* ' . $this->fieldInfo['label'] . '</span>';

		return $szResult;
	}

	public function getViewValue($value)
	{
		return htmlspecialchars($value);
	}

}