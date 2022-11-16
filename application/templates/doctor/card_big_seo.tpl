<?php
	/**
	 *	@var DoctorModel $doctor;
	 *  @var View $this;
     *  @var AccountModel $current_account
	 * @var IHtmlCache $cache
  	 */
?>

<?php
    $this->is_red = (isset($is_red) && $is_red) ? $is_red : 0;
    if (isset($_COOKIE['already_registred_account']))
        $already_registred_account = 1;
    else
        $already_registred_account = 0;
?>
<?php $uncommented_visit = ''; ?>

<?php if (isset($current_account) && $current_account) {$uncommented_visit = $doctor->getOneLastUncommentedVisitByAccountId($current_account->getId());}?>
<?php if ($uncommented_visit) $unique_id = $uncommented_visit->getUniqueId().rand(0,100000); ?>

    <script>
        $(document).ready(function () {
            var block_controller = new DoctorBigCardController('#doctor-big-card-<?php echo $doctor->getUniqueId(); ?>', <?php echo $doctor->getId(); ?>, <?php echo ($doctor->my_doctor) ? 1 : 2; ?>, <?php echo ((isset($counter))&&($counter % 2 == 0) && $columned_list) ? 1 : 2; ?>);

            <?php if (isset($current_account) && $current_account):?>
                block_controller.is_current_account = 1;
            <?php endif;?>
            <?php if (isset($current_account) && $current_account && $current_account->is_call_centre_operator):?>
                block_controller.is_call_center_operator = 1;
            <?php endif;?>
            <?php if ($uncommented_visit):?>
                block_controller.is_uncommented_visit = 1;
                block_controller.review_block_unique_id = <?php echo $unique_id;?>;
                block_controller.review_unique_id = <?php echo $uncommented_visit->getUniqueId();?>;
            <?php endif;?>
            <?php if ($doctor->last_review):?>
                block_controller.is_doctor_last_review = 1;
                block_controller.doctor_link = "<?php echo DoctorPageLinkViewHelper::getLink($doctor);?>";
                block_controller.review_text = "<?php echo StringHelper::trim($doctor->last_review->text, 70);?>";
            <?php endif;?>
            <?php if (Acc::isAuthed()):?>
                block_controller.authed_user = 1;
            <?php endif;?>
            <?php if ($doctor->reviews_count):?>
                block_controller.is_reviews_count = 1;
            <?php endif;?>

            block_controller.init();
        });
    </script>

    <?php $cache_id = 'doctor_card_'.$doctor->getId(); ?>
    <?php if (isset($specialty_id) && $specialty_id): ?>
        <?php $cache_id.='_specialty_'.$specialty_id; ?>
    <?php endif; ?>
    <?php if (isset($clinic_id) && $clinic_id): ?>
        <?php $cache_id.='_clinic_'.$clinic_id; ?>
    <?php endif; ?>
    <?php if (isset($purpose_of_visit_id) && $purpose_of_visit_id): ?>
        <?php $cache_id.='_purpose_'.$purpose_of_visit_id; ?>
    <?php endif; ?>
    <?php if ($current_account && $current_account->is_call_centre_operator): ?>
        <?php $cache_id.='_call_center_operator'; ?>
    <?php endif; ?>
    <?php if (!$cache->start($cache_id,  'doctor_card_block')): ?>
        <div class="info-card doctor-card-<?php echo $doctor->getId(); ?> doctor-big-card flo" id="doctor-big-card-<?php echo $doctor->getUniqueId(); ?>" itemscope itemtype="http://schema.org/Person">

            <?php echo (isset($is_closed_card) && $is_closed_card == 1) ? '<span class="close" data-id="'.$doctor->getId().'"></span>' : ''; ?>
            <?php if (isset($map_card)): ?>
                 <span class="corn-top"></span>
            <?php endif?>
            <div class="avatar_buttons">
                <div class="avatar">
                    <?php if($is_seo_page) { ?>
                        <?php echo DoctorAvatarViewHelper::viewOnCard($doctor, 74, 111, '', false, false, true); ?>
                    <?php } else { ?>
                        <?php echo DoctorAvatarViewHelper::viewOnCard($doctor, 74, 111); ?>
                    <?php } ?>
                </div>

            <?php if($is_virtual): ?>
                <?php $this->block('doctor/blocks/card_buttons_virtual'); ?>
            <?php else: ?>
                <?php $this->block('doctor/blocks/card_buttons'); ?>
            <?php endif; ?>

            </div>
            <div class="descr">
                <div class="fixed_title" style="height: 75px;">
                    <div class="name">
                        <?php if(isset($is_virtual) && $is_virtual): ?>
                            <span class="post" itemprop="role">
                                <?php echo StringHelper::startProposalWord($doctor->specialties[0]->name); ?>
                            </span>
                        <?php else: ?>
                            <a href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>">
                                <span class="post" itemprop="role">
                                    <?php echo $doctor->specialties_names; ?>
                                </span>
                            </a>
                        <?php endif; ?>

                        <div class="rating" itemscope itemtype="http://schema.org/AggregateRating">
                            <meta itemprop="name" content="<?php echo $doctor->full_name; ?>"/>

                            <?php echo RateViewHelper::view($doctor->rate, 0, $doctor->is_best); ?>
		                        <?php if($doctor->is_best) { ?>
			                        <div class="is_best_recomm">Рекомендуем</div>
		                        <?php } ?>

                            <div class="comments-count">
                                <?php if (!Acc::isAuthed()): ?>
                                    <a class="showTip el" data-url="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>#reviews" href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>#reviews">
                                <?php else: ?>
                                    <a class="showTip el" href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>#reviews">
                                <?php endif?>
                                    <span itemprop="reviewCount">
                                        <?php echo ($doctor->reviews_count) ? StringHelper::getCorrectSuffixForReview($doctor->reviews_count) : ''; ?>
                                    </span>
                                </a>
                            </div>
                        </div>

                        <a href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>" itemprop="url">
                            <p class="doctorname_clear"><span itemprop="name"><?php echo $doctor->full_name; ?></span></p>
                        </a>
                        <?php $this->block('doctor/blocks/call-centre-operator-hint'); ?>
					</div>
				</div>

                <div class="info-box">
                    <?php $this->specialtyIDForDoctorCard = (isset($specialtyIDForDoctorCard)) ? $specialtyIDForDoctorCard : null; ?>
                    <?php $this->specialty_id = (isset($specialty_id)) ? $specialty_id : null; ?>
                    <?php $this->clinic_id = (isset($clinic_id)) ? $clinic_id : null; ?>
                    <?php $this->purpose_of_visit_id = (isset($purpose_of_visit_id)) ? $purpose_of_visit_id : null; ?>
                    <?php $this->week_schedule = 1; ?>
                    <?php $this->is_big_card = 1; ?>
                    <?php $this->is_big_card = 1; ?>
                    <?php $this->block('doctor/blocks/schedule_and_clinics'); ?>
                </div>
            </div>
        </div>
        <?php $cache->end(); ?>
    <?php endif; ?>
