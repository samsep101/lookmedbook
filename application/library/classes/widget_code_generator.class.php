<?php
    class WidgetCodeGenerator
    {
        public function generate(WidgetModel $widget)
        {
            $text = $widget->code;
            file_put_contents(ABS_ROOT . '/media/widget/'.$widget->identifier.'.js', $text);
        }
    }