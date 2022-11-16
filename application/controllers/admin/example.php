<?php
	class ExampleAdminController extends Controller
	{
		public function widget()
		{
			$widget_site_id = $this->request('widget_id');

			/**
			 * @var WidgetSiteManager $widget_site_manager
			 */
			$widget_site_manager = ModelManagerFactory::getByName('widget_site');

			$widget_site = $widget_site_manager->getOneById($widget_site_id);

			echo '<html>';
			echo '<body>';
			echo '<div id="'.$widget_site->applicable_element_id.'" style="width:400px">';
			echo '</div>';
			echo '<script src="'.SITE_URL.'/media/widget/'.$widget_site->identifier.'.js"></script>';
			echo '</body>';
			echo '</html>';
			exit();
		}
	}