<?php
	class DiseasePageLinkViewHelper extends AliasLinkViewHelper
	{
		public static function getLink(DiseaseModel $model)
		{
			return parent::getLink('disease', $model);
		}
	}