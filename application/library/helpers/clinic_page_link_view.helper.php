<?php
	class ClinicPageLinkViewHelper extends AliasLinkViewHelper
	{
		public static function getLink(ClinicModel $clinic)
		{
			return parent::getLink('clinic', $clinic);
		}
	}