<?php
class RegistryScheduleLinkViewHelper
{

	public static function getScheduleViewLink($doctor, $clinic, $specialty)
	{
		$link = '/registry/doctor/schedule_view?';

		if ($doctor)
			$link .= '&id='.$doctor->getId();

		if ($clinic)
			$link .= '&clinic_id='.$clinic->getId();

		if ($specialty)
			$link .= '&specialty_id='.$specialty->getId();

		return $link;
	}

	public static function getSchedulePastLink($doctor, $clinic, $specialty)
	{
		$link = '/registry/doctor/schedule_past?';

		if ($doctor)
			$link .= '&id='.$doctor->getId();

		if ($clinic)
			$link .= '&clinic_id='.$clinic->getId();

		if ($specialty)
			$link .= '&specialty_id='.$specialty->getId();

		return $link;
	}

	public static function getScheduleCreateLink($doctor, $clinic, $specialty)
	{
		$link = '/registry/doctor/schedule_create?';

		if ($doctor)
			$link .= '&id='.$doctor->getId();

		if ($clinic)
			$link .= '&clinic_id='.$clinic->getId();

		if ($specialty)
			$link .= '&specialty_id='.$specialty->getId();

		return $link;
	}
}