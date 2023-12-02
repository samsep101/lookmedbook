    <div class="review-item flo clinics_reviews">
        <div class="info-card reviews-page-card info-card-gr flo">
            <?php if ($review->clinic_id):?>
                <div class="avatar">
                    <?php echo ClinicAvatarViewHelper::viewOnCard($review->clinic, 74, 31); ?>
                </div>

                <div class="descr">
                    <p class="name">
                            <a href="<?php echo ClinicPageLinkViewHelper::getLink($review->clinic); ?>">
                            <span class="post">
                                <?php echo $review->clinic->name; ?>
                            </span>
                            <?php if ($review->doctor && $review->doctor->getId() != DoctorModel::RESERVED_DOCTOR_SLOT):?>
                                <?php echo $review->doctor->full_name; ?>
                            <?php endif;?>
                        </a>
                    </p>

                    <div class="location">
                        <p>
                            <strong><?php echo $review->clinic->name; ?></strong> <br>
                            <?php if ($review->clinic->metro_station): ?>
                                <?php if ($review->clinic->metro_station->metro_branch): ?>
                                    <?php echo MetroBranchIconViewHelper::getImage($review->clinic->metro_station->metro_branch)?>
                                <?php endif; ?>
                                <?php echo $review->clinic->metro_station->name; ?> <br  />
                            <?php endif; ?>
                            <?php echo $review->clinic->address; ?>
                        </p>
                    </div>
                </div>
            <?php endif;?>
        </div>
        <?php $one_visit_rating = $review->getClinicReviewRatingByVisitId($review->visit_id); ?>
        <div class="rating-col">
            <div class="rating-item">
                <p>Кабинет</p>
                <?php echo RateViewHelper::view($one_visit_rating->cabinet); ?>
            </div>
            <div class="rating-item">
                <p>Время ожидания</p>
                <?php echo RateViewHelper::view($one_visit_rating->waiting_time); ?>
            </div>
            <div class="rating-item">
                <p>Отношение к пациенту</p>
                <?php echo RateViewHelper::view($one_visit_rating->relationship); ?>
            </div>
            <div class="rating-item">
                <p>Соответствие цене</p>
                <?php echo RateViewHelper::view($one_visit_rating->value_for_money); ?>
            </div>
            <div class="rating-item">
                <p>Диагноз и дальнейшее лечение ясны</p>
                <?php echo RateViewHelper::view($one_visit_rating->diagnosis_is_clear); ?>
            </div>
        </div>
        <div class="review-col">
            <?php if ($review->clinic_id):?>
                <?php if ($review->clinic->advice_rate): ?>
                    <?php echo RateViewHelper::viewAdvise($review->clinic->advice_rate); ?>
                        <span>Посоветуют <br>друзьям</span>
                    </div>
                <?php endif; ?>
            <?php endif;?>
            <p><?php if ($review->text) echo $review->text; ?></p>
        </div>
    </div>
