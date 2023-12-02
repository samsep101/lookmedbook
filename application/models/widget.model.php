<?php
	/**
	 * @property int $id
	 * @property string $name
	 * @property string $code
	 * @property string $element_id
     * @property int $city_id
     * @property CityModel $city
     * @property int $specialty_id
     * @property SpecialtyModel $specialty
     * @property boolean $require_sms
     *
     * @property string $identifier
     * @property string $resource_url
     *
     * @property string $accept_html
     * @property string $banner_html
     * @property string $banner_240_160_html
     * @property string $form_html
     * @property string $notification_html
	 */
	class WidgetModel extends DynamicModel
    {
        public function __construct()
        {
            $this->disableFilter();
        }

        protected function _field_identifier()
        {
            return md5($this->getId().'widget');
        }

        protected function _field_resource_url()
        {
            return SITE_URL.'/media/widget/'.$this->folder.'/build/';
        }
	}