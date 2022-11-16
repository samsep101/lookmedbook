<?php
    class WidgetSettingsCodeGenerator
    {
        public function generate(WidgetSiteModel $widget_site)
        {
            $text = "var params = {\r\n";
            $text .= "  elementId: '".$widget_site->applicable_element_id."',\r\n";
            $text .= "  resourceUrl: '".$widget_site->widget->resource_url."',\r\n";
            $text .= "  cityId: ".(int)$widget_site->applicable_city_id.",\r\n";
            $text .= "  specialtyId: ".(int)$widget_site->applicable_specialty_id.",\r\n";
            if($widget_site->require_sms)
            {
                $text .= "  requireSms: true,\r\n";
            } else {
                $text .= "  requireSms: false, \r\n";
            }

            if($widget_site->applicable_layout)
            {
                $text .= "  layout: ".$widget_site->applicable_layout.",\r\n";
            } else {
                $text .= "  layout: {},\r\n";
            }

            if($widget_site->applicable_size)
            {
                $text .= "  size: '".$widget_site->applicable_size."',\r\n";
            } else {
                $text .= "  size: 'default',\r\n";
            }

            $text .= "  yandexMetrikaId: '".$widget_site->applicable_yandex_metrics_id."',\r\n";
            $text .= "  googleAnalyticsId: '".$widget_site->applicable_google_analytics_id."',\r\n";
            $text .= "  siteIdentifier: '".$widget_site->identifier."'\r\n";
            $text .= "};\r\n\r\n";

            $text .= '
(function (d, w) {
    w.lmbAsyncInit = function() {
        try {
            LMB.init(params);
        } catch(e) { }
    };
    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = params.resourceUrl + "js/miniwd.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window);'."\r\n\r\n";

            $text .= $widget_site->widget->counters_code;

            $filename = ABS_ROOT . '/media/widget/'.$widget_site->identifier.'.js';
            file_put_contents($filename, $text);
        }
    }