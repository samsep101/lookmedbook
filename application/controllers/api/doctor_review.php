<?php
    class DoctorReviewApiController extends ApiController
    {
        public $layout = 'ajax';

        public function getCachedMethods()
        {
            return array(
                'getList' => array(
                    'tags' => array(
                        'doctor_review',
                        'doctor:%doctor_id%',
                    )
                ),
            );
        }

        public function getList()
        {
            $doctor_id = $this->request('doctor_id');

            if (!$doctor_id)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $doctor_review_manager = new DoctorReviewManager();
            $reviews = $doctor_review_manager->getListByDoctorId($doctor_id);

            if ($reviews){
                $result = array();

                foreach ($reviews as $review){
                    $account_manager = new AccountManager();
                    $account = $account_manager->getOneById($review->account_id);

                    $visit_rating_manager = new VisitRatingManager();
                    $visit_rating = $visit_rating_manager->getOneByVisitIdAndAccountId($review->visit_id, $account->getId());

                    $rating = null;
                    if ($visit_rating){
                        $rating = $visit_rating->total_doctor_rating;
                    }

                    $result[] = array(
                        'id' => (int)$review->getId(),
                        'visit_id' => (int)$review->visit_id,
                        'account' => array(
                            'account_id' => (int)$account->getId(),
                            'first_name' => ($account->first_name) ? $account->first_name : '',
                            'last_name' => ($account->last_name) ? $account->last_name : '',
                        ),
                        'doctor_id' => (int)$review->doctor_id,
                        'text' => $review->text,
                        'date' => ($review->dt) ? @strtotime($review->dt) : 0,
                        'is_confirmed' => (int)$review->is_confirmed,
                        'rating' => ($rating) ? (int)$rating : 0,
                        'is_doctor_advice' => ($visit_rating->is_doctor_advice) ? $visit_rating->is_doctor_advice : 0,
                    );
                }

                ApiHeader::response($result, $this->e_tag);
            } else {
                ApiHeader::error(ApiRequestErrors::DOCTOR_REVIEWS_NOT_EXIST);
            }
        }
    }