<?php
	/**
	 * @property int $id
	 * @property string $phone
	 * @property string $name
	 * @property string $description
	 *
     * @property string name_with_phone
	 */
	class TargetCallModel extends DynamicModel
    {
        protected function _field_name_with_phone()
        {
            $value = $this->name;
            $value .= ($this->phone) ? ' (' .$this->phone .')' : '';

            return $value;
        }
	}