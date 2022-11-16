<?php
	class SeoBreadcrumbsViewHelper {

		public static function getViewForSpecialtyPages($specialty, DynamicModel $address_object, $default = 0, $classes = array())
		{
            $breadCrumbsPart = ' &rarr; Врачи';
            $links = '';

            if($specialty && !$default) {
                $current = $address_object;
                
                if (get_class($current) != 'CityModel') $links = $current->seo_name;
                else $links = StringHelper::upperCaseFirstSymbol($specialty->plural_name);

                while ($current = $current->parent)
                {
                    if (get_class($current) != 'CityModel') $name = $current->seo_name;
                    else $name = StringHelper::upperCaseFirstSymbol($specialty->plural_name);

                    $links = '<a href="'.SeoLinkViewHelper::getSpecialtyPageLink($specialty, $current).'">'.$name.'</a>'.' &rarr; '.$links;
                }

                $breadCrumbsPart = ' &rarr; <a href="/doctor" title="Врачи">Врачи</a> &rarr; ';
            }

            if (empty($classes))
            {
                $classes = array('inner-2');
            }

			$html = '<div class="breadcrumbs ' . implode(' ', $classes) . '">';
			$html .= '<a href="/" title="Главная">Главная</a>' . $breadCrumbsPart . $links;
			$html .= '</div>';

			return $html;
		}
	}