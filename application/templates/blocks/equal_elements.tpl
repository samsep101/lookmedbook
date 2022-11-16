<?php
    /**
     * @var string $equal_elements_type
     * @var EqualClinicModel[] $equal_clinics
     * @var EqualDoctorModel[] $equal_doctors
     * @var ClinicModel $clinic
     * @var DoctorModel $doctor
     * @var SpecializationModel $specialization
     */
?>

<script type="text/javascript">
    $(document).ready(function() {
        var equal_elements_block_controller = new EqualElementsBlockController();
        equal_elements_block_controller.init();
    });
</script>

<div class="equal-elements">
    <?php if($equal_elements_type == 'clinic'): ?>
        <p class="clinic-service-list">
            <?php $seo_text = 'Карточка медицинского учреждения ' .$clinic->name .': '; ?>
            <?php $number = 0; ?>
            <?php foreach($clinic->specializations as $specialization): ?>
                <?php if($number != 0): ?>
                    <?php $seo_text .= ', '; ?>
                <?php endif; ?>
                <?php $seo_text .= $specialization->name; ?>
                <?php $number++; ?>
            <?php endforeach; ?>
            <?php $seo_text .= ' и другие услуги'; ?>
            <?php echo $seo_text; ?>
        </p>
    <?php else: ?>
    <?php endif; ?>

    <?php if($equal_elements_type == 'clinic'): ?>
        <div class="heading-line">
            <p>
                <span>
                    Сервис <?php echo SITE_NAME; ?> поможет записаться на прием в клинику online.
                </span>
            </p>
        </div>
    <?php endif; ?>

    <p class="all-elements" <?php echo ($equal_elements_type == 'doctor') ? 'style="text-align: left; margin-left: 20px"' : ''; ?>>
        <?php if($equal_elements_type == 'clinic'): ?>
            <a class="show-all" href="javascript:void(0)">
                Другие медицинские учреждения в <?php echo($clinic->district->formal_name); ?>
            </a>
        <?php else: ?>
            <a class="show-all" href="javascript:void(0)">
                Другие врачи в <?php echo($doctor->district->formal_name); ?>
            </a>
        <?php endif; ?>
    </p>
    <div class="equal-elements-container">
        <?php $number = 1; ?>
        <?php if($equal_elements_type == 'clinic'): ?>
            <?php $total_count = count($equal_clinics); ?>
            <?php foreach($equal_clinics as $equal_clinic):
                  if (!is_object($equal_clinic->clinic))
                    continue;
            ?>
                <?php if($number % 2 == 1): ?>
                    <ul>
                <?php endif; ?>
                <li>
                    <a href="<?php echo ClinicPageLinkViewHelper::getLink($equal_clinic->clinic);
                    ?>">
                        <?php echo $equal_clinic->clinic->name; ?>
                    </a>
                </li>
                <?php if($number % 2 == 0 || $number == $total_count): ?>
                    </ul>
                <?php endif; ?>
                <?php $number++; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <?php $total_count = count($equal_doctors); ?>
            <?php foreach($equal_doctors as $equal_doctor): ?>
				<?php if ($equal_doctor->doctor): ?>
					<?php if($number % 2 == 1): ?>
						<ul>
					<?php endif; ?>
					<li>
						<a href="<?php echo DoctorPageLinkViewHelper::getLink($equal_doctor->doctor); ?>">
							<?php echo $equal_doctor->doctor->full_name; ?>
						</a>
					</li>
					<?php if($number % 2 == 0 || $number == $total_count): ?>
						</ul>
					<?php endif; ?>
					<?php $number++; ?>
				<?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>