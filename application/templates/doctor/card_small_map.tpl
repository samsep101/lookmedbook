<?php
    /**
     * @var int $clinic_id;
     * @var DoctorModel $doctor
     * @var DoctorReviewModel[] $reviews
     */
    $doctor_clinic = false;
?>

<div class="pop-col-side">
    <div class="avatar">
        <?php echo DoctorAvatarViewHelper::viewOnCard($doctor, 74, 111, '', false, 1); ?>
    </div>
</div>
<div class="descr">
    <p class="name">
        <?php if ($clinic_id): ?>
            <?php $doctor_clinic = $doctor->getOneClinicbyId($clinic_id); ?>
        <?php endif;?>
        <?php if($doctor->is_virtual && $doctor_clinic): ?>
            <?php echo $doctor->full_name; ?>
            <span class="post">
                    <?php echo $doctor->specialties_names; ?>
                </span>
        <?php else: ?>
            <a href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>">
                <?php echo $doctor->full_name; ?>
                <span class="post">
                        <?php echo $doctor->specialties_names; ?>
                    </span>
            </a>
        <?php endif; ?>
    </p>
    <div class="info-area">
        <?php if ($clinic_id): ?>
            <?php if ($doctor_clinic): ?>
                <div class="address">
                    <p>
                        <?php if ($doctor_clinic->metro_station): ?>
                            <?php if ($doctor_clinic->metro_station->metro_branch): ?>
                                <?php echo MetroBranchIconViewHelper::getImage($doctor_clinic->metro_station->metro_branch)?>
                            <?php endif; ?>
                            <?php echo $doctor_clinic->metro_station->name; ?> <br  />
                        <?php endif; ?>
                        <?php echo $doctor_clinic->address; ?>
                    </p>
                </div>
            <?php endif; ?>
            <?php if ($doctor->getFirstVisitPriceByClinicId($clinic_id)): ?>
                <p>Первый визит: <strong><?php echo $doctor->getFirstVisitPriceByClinicId($clinic_id); ?> руб.</strong></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<div class="rating-block">
    <?php echo RateViewHelper::viewSmall($doctor->rate, 0, $doctor->is_best); ?>

		<?php if($doctor->is_best) { ?>
			<div class="is_best_recomm">Рекомендуем</div>
		<?php } ?>

    <?php $reviews = $doctor->reviews; ?>
    <?php if($reviews): ?>
        <div class="reviews-map-count">
            <?php if (!Acc::isAuthed()): ?>
                <a class="showTip el" data-url="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>#reviews" href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>#reviews">
                    <?php echo ($doctor->reviews_count) ? StringHelper::getCorrectSuffixForReview($doctor->reviews_count) : ''; ?>
                </a>
            <?php else: ?>
                <a class="showTip el" href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>#reviews">
                    <?php echo ($doctor->reviews_count) ? StringHelper::getCorrectSuffixForReview($doctor->reviews_count) : ''; ?>
                </a>
            <?php endif?>
        </div>
    <?php endif; ?>
</div>

<div class="appointment-button">
    <?php
        $block_data = array(
            'is_small_card' => true
        );
        $this->hide_bookmark = true;
        $this->block('doctor/blocks/card_buttons_virtual', $block_data);
    ?>
</div>
