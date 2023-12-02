<?php
	class DoctorPageLinkViewHelper extends AliasLinkViewHelper
	{
		public static function getLink(DoctorModel $doctor)
		{
            if ($doctor->is_virtual == 1)
                return false;
            else
			    return parent::getLink('doctor', $doctor);
		}
	}