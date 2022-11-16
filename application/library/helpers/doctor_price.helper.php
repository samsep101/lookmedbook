<?php

class DoctorPriceHelper
{

  public static function getPricesForDoctor($doctors, $clinic_id, $specialty_id = 0)
  {

    foreach ($doctors AS $dKey => $dValue) {
      $specialties = $dValue->specialties;
      $doctor = &$doctors[$dKey];

      $first_visit_price = $second_visit_price = 0;

      if (!count($specialties)) continue;

      $doctorSpecialtyToClinic = ModelManagerFactory::getByName('doctor_specialty_to_clinic');
      if (!$specialty_id) {
        foreach ($specialties AS $sValue) {
          $doctor->specialty = $doctorSpecialtyToClinic->getOneByDoctorIdAndClinicIdAndSpecialtyId($dValue->id, $clinic_id, $sValue->id);
          if (!$doctor->specialty) {
              continue;
          }
          $first_visit_price_tmp = $doctor->getFirstVisitPrice($clinic_id);
          $second_visit_price_tmp = $doctor->getSecondVisitPrice($clinic_id);

          $summ_tmp = $first_visit_price_tmp + $second_visit_price_tmp;
          $summ = $first_visit_price + $second_visit_price;

          if (!$first_visit_price && !$second_visit_price) {
            $first_visit_price = $first_visit_price_tmp;
            $second_visit_price = $second_visit_price_tmp;
          }

          if (($first_visit_price_tmp > 0 && $second_visit_price_tmp > 0) &&
            ($first_visit_price > 0 && $second_visit_price > 0) &&
            ($summ_tmp < $summ)
          ) {
            $first_visit_price = $first_visit_price_tmp;
            $second_visit_price = $second_visit_price_tmp;
            $doctor->min_price = 1;
          }
        }
      } else {
        $doctor->specialty = $doctorSpecialtyToClinic->getOneByDoctorIdAndClinicIdAndSpecialtyId($dValue->id, $clinic_id, $specialty_id);
        $first_visit_price = $doctor->getFirstVisitPrice($clinic_id, $doctor->specialty->specialty_id);
        $second_visit_price = $doctor->getSecondVisitPrice($clinic_id, $doctor->specialty->specialty_id);
      }

      $doctor->first_visit_price = $first_visit_price;
      $doctor->second_visit_price = $second_visit_price;
    }

    return $doctors;
  }
}