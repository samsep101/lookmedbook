<?php
/**
 * @var ClinicModel  $clinic
 * @var View         $this
 * @var bool         $is_closed_card
 * @var AccountModel $current_account
 * @var Cache_Lite   $cache
 * @var string[]     $additionalBlocks [alias => path]
 */

$doctor_total_count = isset($clinic->additional_params['doctors_main_specialty']['total_doctors']) ? count($clinic->additional_params['doctors_main_specialty']['total_doctors']) : 0;
?>




<div class="rating">
    <?php echo RateViewHelper::view($clinic->rate, 1, $clinic->is_best); ?>

		<?php if($clinic->is_best) { ?>
			<div class="is_best_recomm">Рекомендуем</div>
		<?php } ?>

    <?php if (count($clinic->reviews)): ?>
    <div class="comments-count">
        <a href="<?php echo ClinicPageLinkViewHelper::getLink($clinic); ?>#reviews">
            <?php echo StringHelper::getCorrectSuffixForReview(count($clinic->reviews));?>
        </a>
    </div>
    <?php endif; ?>

</div>


<?php echo (isset($is_closed_card) && $is_closed_card == 1) ? '<div class="close" data-id="'.$clinic->getId().'"></div>' : ''; ?>

<?php
	$cache_id = 'clinic_small_card_'.$clinic->getId();
	?>

<div class="avatar">
    <?php echo ClinicAvatarViewHelper::viewOnCard($clinic, 74, 31); ?>
</div>


    <p class="name"><a href="<?php echo ClinicPageLinkViewHelper::getLink($clinic); ?>"><span class="post"><?php echo $clinic->name; ?></span></a></p>

    <div class="clearfix"></div>

    <div class="type-and-service-area">
        <?php if (isset($additionalBlocks['clinicInfo']))
            $this->block($additionalBlocks['clinicInfo']);
        ?>
        <?php if(!empty($clinic->additional_params['multidisciplinary'])) { ?>
            <div class="multidisciplinary">
                Многопрофильная клиника
            </div>
        <?php } ?>
        <div class="types-services-list">
            <?php if(!empty($clinic->additional_params['twenty-four-hours'])) { ?>
                <div class="tsl-item">
                    <div class="tsl-description">
                        <div class="tsl-text">
                            Круглосуточная
                        </div>
                        <div class="tsl-pointer"></div>
                    </div>
                    <div class="tsl-icon icon-twenty-four-hours"></div>
                </div>
            <?php } ?>
            <?php if(!empty($clinic->additional_params['accepts-children'])) { ?>
                <div class="tsl-item">
                    <div class="tsl-description">
                        <div class="tsl-text">
                            Принимает детей
                        </div>
                        <div class="tsl-pointer"></div>
                    </div>
                    <div class="tsl-icon icon-accepts-children"></div>
                </div>
            <?php } ?>
            <?php if(!empty($clinic->additional_params['have-ramp'])) { ?>
                <div class="tsl-item">
                    <div class="tsl-description">
                        <div class="tsl-text">
                            Есть пандус
                        </div>
                        <div class="tsl-pointer"></div>
                    </div>
                    <div class="tsl-icon icon-have-ramp"></div>
                </div>
            <?php } ?>
            <?php if(!empty($clinic->additional_params['payment-cards'])) { ?>
                <div class="tsl-item">
                    <div class="tsl-description">
                        <div class="tsl-text">
                            Оплата картой
                        </div>
                        <div class="tsl-pointer"></div>
                    </div>
                    <div class="tsl-icon icon-payment-cards"></div>
                </div>
            <?php } ?>
            <?php if(!empty($clinic->additional_params['medical-certificates'])) { ?>
                <div class="tsl-item">
                    <div class="tsl-description">
                        <div class="tsl-text">
                            Больничные листы
                        </div>
                        <div class="tsl-pointer"></div>
                    </div>
                    <div class="tsl-icon icon-medical-certificates"></div>
                </div>
            <?php } ?>
            <?php if(!empty($clinic->additional_params['leave-the-house'])) { ?>
                <div class="tsl-item">
                    <div class="tsl-description">
                        <div class="tsl-text">
                            Выезд на дом
                        </div>
                        <div class="tsl-pointer"></div>
                    </div>
                    <div class="tsl-icon icon-leave-the-house"></div>
                </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="address-and-time-area">
        <?php if (!empty($current_account) && $current_account->is_call_centre_operator && $clinic->not_work) { ?>
            <div class="not-work-message">
                НЕ РАБОТАЕМ
            </div>
        <?php } else { ?>
            <div class="aata-time">
                <?php echo ScheduleViewHelper::schedule_in_table($clinic); ?>
            </div>
        <?php } ?>

        <div class="aata-address">
            <div class="aata-street">
                <img width="12px" height="16px" src="/media/images/small_placemark_for_street.png"><?php echo $clinic->address; ?>
            </div>
            <div class="aata-metro">
                <?php if ($clinic->metro_stations) { ?>
                    <?php foreach ($clinic->metro_stations as $metro_station) { ?>
                        <?php if ($metro_station->metro_branch) { ?>
                            <?php echo MetroBranchIconViewHelper::getImage($metro_station->metro_branch); ?>
                        <?php } ?>
                        <?php echo $metro_station->name;?><br>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

        <div class="specialization-link">
            <?php
                $count = isset($clinic->additional_params['doctors_main_specialty']['count']) ? $clinic->additional_params['doctors_main_specialty']['count'] : 0;
                if(!empty($specialization) && $count) {
            ?>
                По специализации <span class="specialization-name"><?php echo $specialization->name; ?></span> в клинике  <a href="<?php echo ClinicPageLinkViewHelper::getLink($clinic); ?>?spzn_id=<?php echo $specialization->getId(); ?>#divider-shadow"><?php echo $count; ?> <?php echo SpecialtyHelper::getDoctorWordForm($count); ?></a>
            <?php
                } elseif(empty($specialization) && !empty($clinic->additional_params['doctors_main_specialty'])) {
                    $doctor_total_count = count($clinic->additional_params['doctors_main_specialty']['total_doctors']);
                    $specialization_total_count = count($clinic->additional_params['doctors_main_specialty']['total_specializations']);
                } ?>
        </div>

    <div class="btns flo">
        <div class="row">
            <div class="col-lg-6 col-md-6 col col-sm-6 col-xs-12 mb15">
                <?php if (!$clinic->visit_disallow): ?>
                    <?= $clinic->getRecordButton() ?>
                <?php endif; ?>
            </div>
            <div class="col-lg-6 col-md-6 col col-sm-6 col-xs-12">
                <a class="btn-appoint btn-border" href="<?php echo ClinicPageLinkViewHelper::getLink($clinic); ?>">Подробнее о клинике</a>
            </div>
        </div>
        <div class="clearfix"></div>

    </div>


<?php $this->block('clinic/blocks/call-centre-operator-hint'); ?>
