<?php
	class Vidal_finderType extends Type
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
							var popup = new VidalSearchPopupController();
							popup.search_query = "'.$val.'";
							popup.product_id = '.(int)$row->getId().';
							popup.button =  $("#vidal-find-button");
							popup.init();
						});';
			$html .= '</script>
					  <input id="vidal-find-button" type="button" value="Поиск в базе VIDAL" />';

			return $html;
		}

	}