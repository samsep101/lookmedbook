<?php $unique_id = $visit->getUniqueId().rand(0,100000); ?>
<script>
    $(document).ready(function(){
        block_controller = new VisitRemindBlockController(<?php echo $visit->getUniqueId(); ?>, '', <?php echo $unique_id; ?>);
        block_controller.init();
    });
</script>
<div class="visit-remind-block" id="visit-remind-block-<?php echo $unique_id; ?>">
    <div class="info-card info-card-gr flo">
        <div class="avatar">
            <?php if ($visit->doctor_id && $visit->doctor_id != DoctorModel::RESERVED_DOCTOR_SLOT):?>
                <?php echo DoctorAvatarViewHelper::viewOnCard($visit->doctor, 74, 111); ?>
            <?php elseif ($visit->clinic_id):?>
                <?php echo ClinicAvatarViewHelper::viewOnCard($visit->clinic, 74, 31);?>
            <?php endif;?>
        </div>
        <a class="review-link rev-popup-open" href="#add-review-popup-<?php echo $unique_id; ?>" id="<?php echo $unique_id; ?>">
            <span></span>Оставить отзыв
        </a>

        <div class="descr">
            <?php if ($visit->doctor_id && $visit->doctor_id != DoctorModel::RESERVED_DOCTOR_SLOT):?>
                <p class="name">
                    <a href="<?php echo DoctorPageLinkViewHelper::getLink($visit->doctor); ; ?>">
                        <span class="post"><?php echo $visit->doctor->specialties_names; ?></span>
                        <?php echo $visit->doctor->full_name; ?>
                    </a>
                </p>
            <?php elseif ($visit->clinic_id && $visit->specialty_id):?>
                <p class="name"><span class="post"><?php echo StringHelper::startProposalWord($visit->specialty->name); ?></span></p>
            <?php endif;?>
            <?php if ($visit->clinic_id):?>
                <div class="location">
                    <p><strong><a href="<?php echo ClinicPageLinkViewHelper::getLink($visit->clinic); ?>"><?php echo $visit->clinic->name; ?></a></strong> <br>
                    <?php if ($visit->clinic->metro_station): ?>
                        <?php if ($visit->clinic->metro_station->metro_branch): ?>
                            <?php echo MetroBranchIconViewHelper::getImage($visit->clinic->metro_station->metro_branch)?>
                        <?php endif; ?>
                        <?php echo $visit->clinic->metro_station->name; ?> <br/>
                    <?php endif; ?>
                    <?php echo $visit->clinic->address; ?></p>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>