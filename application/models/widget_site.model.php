<?php
	/**
	 * @property int $id
	 * @property int $widget_id
	 * @property WidgetModel $widget
	 * @property string $host
	 * @property string $name
	 * @property int $is_active
	 * @property string $identifier
     * @property string $element_id
     * @property int $city_id
     * @property CityModel $city
     * @property int $specialty_id
     * @property SpecialtyModel $specialty
     * @property boolean $require_sms
     *
     * @property string $applicable_element_id
     * @property int $applicable_city_id
     * @property CityModel $applicable_city
     * @property int $applicable_specialty_id
     * @property SpecialtyModel $applicable_specialty
     * @property boolean $applicable_require_sms
     * @property boolean $applicable_layout
     * @property string $counters_code
     * @property string $yandex_metrics_id
     * @property string $google_analytics_id
     * @property string $applicable_yandex_metrics_id
     * @property string $applicable_google_analytics_id
	 *
     * @property string $settings_url
	 */
	class WidgetSiteModel extends DynamicModel
    {

        protected function _field_settings_url()
        {
            return SITE_URL.'/media/widget/'.$this->identifier.'.js';
        }

        protected function _field_applicable_element_id()
        {
            if(!isset($this->element_id) || !$this->element_id)
            {
                return $this->widget->element_id;
            } else {
                if(isset($this->element_id))
                {
                    return $this->element_id;
                } else {
                    return null;
                }
            }
        }

        protected function _field_applicable_city_id()
        {
            if(!isset($this->city_id) || !$this->city_id)
            {
                return $this->widget->city_id;
            } else {
                return $this->city_id;
            }
        }

        protected function _field_applicable_city()
        {
            /**
             * @var CityManager $city_manager
             */
            $city_manager = ModelManagerFactory::getByName('city');

            return $city_manager->getOneById($this->applicable_city_id);
        }

        protected function _field_applicable_specialty_id()
        {
            if(!isset($this->specialty_id) || !$this->specialty_id)
            {
                return $this->widget->specialty_id;
            } else {
                return $this->specialty_id;
            }
        }

        protected function _field_applicable_specialty()
        {
            /**
             * @var SpecialtyManager $specialty_manager
             */
            $specialty_manager = ModelManagerFactory::getByName('specialty');
            return $specialty_manager->getOneById($this->applicable_specialty_id);
        }

        protected function _field_applicable_require_sms()
        {
            if(!isset($this->require_sms) || !$this->require_sms)
            {
                return $this->widget->require_sms;
            } else {
                return $this->require_sms;
            }
        }

        protected function _field_applicable_layout()
        {
            if(!isset($this->layout) || !$this->layout)
            {
                return $this->widget->layout;
            } else {
                return $this->layout;
            }
        }

        protected function _field_applicable_counters()
        {
            if(!isset($this->counters) || !$this->counters)
            {
                return $this->widget->counters;
            } else {
                return $this->counters;
            }
        }

        protected function _field_applicable_size()
        {
            if(!isset($this->size) || !$this->size)
            {
                return $this->widget->size;
            } else {
                return $this->size;
            }
        }

        protected function _field_applicable_yandex_metrics_id()
        {
            if(!isset($this->yandex_metrics_id) || !$this->yandex_metrics_id)
            {
                return $this->widget->yandex_metrics_id;
            } else {
                return $this->yandex_metrics_id;
            }
        }

        protected function _field_applicable_google_analytics_id()
        {
            if(!isset($this->google_analytics_id) || !$this->google_analytics_id)
            {
                return $this->widget->google_analytics_id;
            } else {
                return $this->google_analytics_id;
            }
        }


        protected function _field_widget_code()
        {
            return htmlentities('<script src="'.SITE_URL.'/media/widget/'.$this->identifier.'.js"></script>');
        }

		protected function _field_example_link()
		{
			return '<a href="/admin/example/widget?widget_id='.$this->getId().'" target="_blank">Пример</a>';
		}

        

	}