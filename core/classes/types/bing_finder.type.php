<?php
    class Bing_finderType extends Type
    {
        /**
         * Get HTML-code for form appearing of current type<br>
         * Used by generator while edit or add form rendering
         * @return string
         */
        public function getFormValue($val = '', $row = '')
        {
            $html = '';
            $html .= '<script type="text/javascript">';
            $html .= ' $(document).ready(function(){
							var popup = new BingSearchPopupController();
							popup.search_query = "'.$val.'";
							popup.product_id = ' .(int)$row->getId().';
							popup.clean_name = ' ."'" .$row->clean_name ."'" .';
							popup.button =  $("#bing-find-button");
							popup.init();
						});';
            $html .= '</script>
					  <input id="bing-find-button" type="button" value="Поиск изображений Bing" />';

            return $html;
        }
    }