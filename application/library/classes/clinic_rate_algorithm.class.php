<?php
    class ClinicRateAlgorithm
    {
        public static function calculate(ClinicModel $clinic)
        {
            $ratings = self::getRatings($clinic);

            if (!count($ratings))
                return FALSE;
            return self::calculateValues($ratings);
        }

        public static function getRatings(ClinicModel $clinic)
        {
            $visit_rating_manager = new VisitRatingManager();
            return $visit_rating_manager->getListByClinicId($clinic->getId());
        }

        public static function calculateValues(array $ratings)
        {
            $total_rate = 0;
            $advice = 0;
            $advice_count = 0;

            if ($ratings)
                foreach ($ratings as $rating) {
                    $middle_rate = self::calculateReviewMiddleRate($rating);
                    $total_rate += $middle_rate;

                    if ($rating->is_clinic_advice !== NULL) {
                        $advice += $rating->is_clinic_advice;
                        $advice_count++;
                    }
                }

            if (count($ratings)) {
                $rating = $total_rate / count($ratings);
            } else {
                $rating = NULL;
            }

            if ($advice_count) {
                $advice_rate = $advice / $advice_count;
            } else {
                $advice_rate = NULL;
            }

            return array(
                'rate'        => $rating,
                'advice_rate' => $advice_rate
            );
        }

        private static function calculateReviewMiddleRate(VisitRatingModel $rating)
        {
            $middle_rating = $rating->cabinet;
            $middle_rating += $rating->waiting_time;
            $middle_rating += $rating->relationship;
            $middle_rating += $rating->value_for_money;
            $middle_rating += $rating->diagnosis_is_clear;
            $middle_rating += $rating->service_at_the_reception;
            $middle_rating /= 6;

            return $middle_rating;
        }
    }