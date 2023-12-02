<?php
	class ServicePageLinkViewHelper extends AliasLinkViewHelper
	{
		public static function getLink(ServiceCategoryModel $model, $cityId)
		{
		    if (!$model->parent) {
                return parent::getLink('uslugi', $model);
            } else {
		        $root = $model->parent;
		        while ($root->parent != null) {
		            $root = $root->parent;
                }
                return parent::getLink("uslugi/{$root->alias}", $model);
            }
		}
	}