<?php
    class DoctorRateAlgorithm
    {
        public static function calculate(DoctorModel $doctor)
        {
            $ratings = self::getRatings($doctor);

            if (!count($ratings))
                return FALSE;

            return self::calculateValues($ratings);
        }

        public static function getRatings(DoctorModel $doctor)
        {
            $visit_rating_manager = new VisitRatingManager();
            return $visit_rating_manager->getListByDoctorId($doctor->getId());
        }

        public static function calculateValues(array $ratings)
        {
            $doctor_rate = new DoctorRate();

            $advice = 0;
            $advice_count = 0;

            if ($ratings)
                foreach ($ratings as $rating) {
                    $doctor_rate->cabinet += $rating->cabinet;
                    $doctor_rate->waiting_time += $rating->waiting_time;
                    $doctor_rate->relationship += $rating->relationship;
                    $doctor_rate->value_for_money += $rating->value_for_money;
                    $doctor_rate->diagnosis_is_clear += $rating->diagnosis_is_clear;

                    $middle_rate = self::calculateReviewMiddleRate($rating);
                    $doctor_rate->total += $middle_rate;

                    if ($rating->is_doctor_advice !== NULL) {
                        $doctor_rate->advice += $rating->is_doctor_advice;
                        $advice_count++;
                    }
                }

            if (count($ratings)) {
                $doctor_rate->total /= count($ratings);
                $doctor_rate->waiting_time /= count($ratings);
                $doctor_rate->relationship/= count($ratings);
                $doctor_rate->value_for_money /= count($ratings);
                $doctor_rate->diagnosis_is_clear /= count($ratings);
            }

            if ($advice_count) {
               $doctor_rate->advice /= $advice_count;
            }

            return $doctor_rate;
        }

        private static function calculateReviewMiddleRate(VisitRatingModel $rating)
        {
            $middle_rating = $rating->cabinet;
            $middle_rating += $rating->waiting_time;
            $middle_rating += $rating->relationship;
            $middle_rating += $rating->value_for_money;
            $middle_rating += $rating->diagnosis_is_clear;

            $middle_rating /= 5;

            return $middle_rating;
        }
    }