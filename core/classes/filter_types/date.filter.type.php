<?php
    class DateFilterType extends FilterType
    {
        public function __construct($field_name, $settings)
        {
            $this->field_name = $field_name;
            $this->settings = $settings;


        }

        public function getView($val)
        {
            $html = '<input type="text" id="'.$this->getElementName().'" value="'.$val.'" name="'.$this->getElementName().'" class="filter-element" />';

            $html .= '<script type="text/javascript">
                        $(document).ready(function(){
                            Calendar.setup(
                                {
                                    ifFormat:"%d-%m-%Y",
                                    daFormat:"%d-%m-%Y",
                                    inputField: \''.$this->getElementName().'\',
                                    button: \''.$this->getElementName().'\',
                                    eventName: \'click\',
                                    value : "2013-05-01"
                                });
                        });
                      </script>';

            return $html;
        }
    }