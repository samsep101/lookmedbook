<?php
    /**
     *	@var int $clinic_id;
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
        <?php if($doctor->is_virtual && $doctor_clinic): ?>
            <a class="clinic" href="<?php echo ClinicPageLinkViewHelper::getLink($doctor_clinic); ?>">
                <?php echo $doctor_clinic->name; ?>
            </a>
        <?php else: ?>
        <?php echo RateViewHelper::viewSmall($doctor->rate, 0, $doctor->is_best); ?>
        <?php endif; ?>
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
    <?php
        $block_data = array(
            'is_small_card' => true
        );
        $this->block('doctor/blocks/card_buttons', $block_data);
    ?>
