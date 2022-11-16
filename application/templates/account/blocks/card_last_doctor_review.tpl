    <div class="review-item flo doctors_reviews">
        <div class="info-card reviews-page-card info-card-gr flo">
            <div class="avatar">
                <?php if ($review->doctor_id && $review->doctor_id != DoctorModel::RESERVED_DOCTOR_SLOT):?>
                    <?php echo DoctorAvatarViewHelper::viewOnCard($review->doctor, 74, 111); ?>
                <?php elseif ($review->clinic):?>
                    <?php echo ClinicAvatarViewHelper::viewOnCard($review->clinic, 74, 31);?>
                <?php endif;?>
            </div>
            <div class="descr">
                <?php if ($review->doctor_id && $review->doctor_id != DoctorModel::RESERVED_DOCTOR_SLOT):?>
                    <p class="name">
                        <a href="<?php echo DoctorPageLinkViewHelper::getLink($review->doctor); ; ?>">
                            <span class="post">
                                <?php echo $review->doctor->specialties_names; ?>
                            </span>
                            <?php echo $review->doctor->full_name; ?>
                        </a>
                    </p>
                <?php elseif ($review->clinic && $review->specialty):?>
                    <p class="name"><span class="post"><?php echo StringHelper::startProposalWord($review->specialty->name); ?></span></p>
                <?php endif;?>

                <?php if ($review->clinic):?>
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
                <?php endif;?>
            </div>
        </div>

        <?php $one_visit_rating = $review->getDoctorReviewRatingByVisitId($review->visit_id); ?>

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
            <?php if ($review->doctor): ?>
                <?php if ($review->doctor->advice_rate): ?>
                    <?php echo RateViewHelper::viewDoctorAdvise($review->doctor->advice_rate); ?>
                        <span>Посоветуют <br>друзьям</span>
                <?php endif; ?>
            <?php endif;?>
            <p><?php if ($review->text) echo $review->text; ?></p>
        </div>

        </div>
    </div>