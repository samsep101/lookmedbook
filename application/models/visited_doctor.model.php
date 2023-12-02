<?php
	class VisitedDoctorModel extends DoctorModel
	{
		public function getUniqueId()
		{
			return $this->getId() . '-' . $this->visit_id;
		}
	}