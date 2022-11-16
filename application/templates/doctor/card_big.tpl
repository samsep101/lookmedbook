<?php
/**
 * 	@var DoctorModel $doctor;
 *  @var View $this;
 *  @var AccountModel $current_account
 * @var IHtmlCache $cache
 */

$this->is_red = (isset($is_red) && $is_red) ? $is_red : 0;
if (isset($_COOKIE['already_registred_account'])) {
    $already_registred_account = 1;
} else {
    $already_registred_account = 0;
}
$uncommented_visit = '';

if (isset($current_account) && $current_account) {
    $uncommented_visit = $doctor->getOneLastUncommentedVisitByAccountId($current_account->getId());
}
if ($uncommented_visit) {
    $unique_id = $uncommented_visit->getUniqueId() . rand(0, 100000);
}
?>
<script>
    $(document).ready(function () {
        var block_controller = new DoctorBigCardController('#doctor-big-card-<?php echo $doctor->getUniqueId(); ?>', <?php echo $doctor->getId(); ?>, <?php echo ($doctor->my_doctor) ? 1 : 2; ?>, <?php echo ((isset($counter)) && ($counter % 2 == 0) && $columned_list) ? 1 : 2; ?>);

<?php if (isset($current_account) && $current_account): ?>
            block_controller.is_current_account = 1;
<?php endif; ?>
<?php if (isset($current_account) && $current_account && $current_account->is_call_centre_operator): ?>
            block_controller.is_call_center_operator = 1;
<?php endif; ?>
<?php if ($uncommented_visit): ?>
            block_controller.is_uncommented_visit = 1;
            block_controller.review_block_unique_id = <?php echo $unique_id; ?>;
            block_controller.review_unique_id = <?php echo $uncommented_visit->getUniqueId(); ?>;
<?php endif; ?>
<?php if ($doctor->last_review): ?>
            block_controller.is_doctor_last_review = 1;
            block_controller.doctor_link = "<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>";
            block_controller.review_text = "<?php echo StringHelper::trim($doctor->last_review->text, 70); ?>";
<?php endif; ?>
<?php if (Acc::isAuthed()): ?>
            block_controller.authed_user = 1;
<?php endif; ?>
<?php if ($doctor->reviews_count): ?>
            block_controller.is_reviews_count = 1;
<?php endif; ?>

        block_controller.init();

        //$('.js-clone-btn').html
    });
</script>

<?php
$cache_id = 'doctor_card_without_seo' . $doctor->getId();
if (isset($specialty_id) && $specialty_id) {
    $cache_id.='_specialty_' . $specialty_id;
}
if (isset($clinic_id) && $clinic_id) {
    $cache_id.='_clinic_' . $clinic_id;
}
if (isset($purpose_of_visit_id) && $purpose_of_visit_id) {
    $cache_id.='_purpose_' . $purpose_of_visit_id;
}
if ($current_account && $current_account->is_call_centre_operator) {
    $cache_id.='_call_center_operator';
}
?>
<?php if (!$cache->start($cache_id, 'doctor_card_block_without_seo')): ?>
    <div class="info-card doctor-card-<?php echo $doctor->getId(); ?> doctor-big-card flo" id="doctor-big-card-<?php echo $doctor->getUniqueId(); ?>">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-2 col-xs-4">
                <?php echo (isset($is_closed_card) && $is_closed_card == 1) ? '<span class="close" data-id="' . $doctor->getId() . '"></span>' : ''; ?>
                <?php if (isset($map_card)): ?>
                    <span class="corn-top"></span>
                <?php endif; ?>
                <div class="avatar_buttons">
                    <div class="avatar">
                        <?php echo DoctorAvatarViewHelper::viewOnCard($doctor, 74, 111); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-10 col-xs-8">
                <div class="descr">
                    <div class="fixed_title" style="height: 75px;">
                        <div class="name">
                            <div class="row">
                                <div class="col-lg-8">
                                    <?php if (isset($is_virtual) && $is_virtual): ?>
                                        <span class="post">
                                            <?php echo StringHelper::startProposalWord($doctor->specialties[0]->name); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="post">
                                            <?php echo $doctor->specialties_names; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-lg-4">

                                    <div class="rating">

                                        <?php echo RateViewHelper::view($doctor->rate, 1, $doctor->is_best); ?>
                                        <?php if ($doctor->is_best) { ?>
                                            <div class="is_best_recomm">Рекомендуем</div>
                                        <?php } ?>

                                        <div class="comments-count">
                                            <?php if (!Acc::isAuthed()): ?>
                                                <a class="showTip el" data-url="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>#reviews" href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>#reviews">
                                                <?php else: ?>
                                                    <a class="showTip el" href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>#reviews">
                                                    <?php endif ?>
                                                    <?php echo ($doctor->reviews_count) ? StringHelper::getCorrectSuffixForReview($doctor->reviews_count) : ''; ?>
                                                </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <a href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>" class="doctor-name">
                                        <p class="doctorname_clear"><?php echo $doctor->full_name; ?></p>
                                    </a>
                                    <?php $this->block('doctor/blocks/call-centre-operator-hint'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-md-push-3 col-lg-push-0 col-sm-2 col-sm-push-2 col-xs-8 col-xs-push-4 ">
                <div class="avatar_buttons js-clone-btn-donor">
                    <?php if (isset($is_virtual) and $is_virtual): ?>
                        <?php $this->block('doctor/blocks/card_buttons_virtual'); ?>
                    <?php else: ?>
                        <?php $this->block('doctor/blocks/card_buttons'); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="both hidden-lg hidden-md"></div>
            <div class="col-lg-9 col-md-12 col-sm-12">
                <div class="info-box">
                    <?php $this->specialtyIDForDoctorCard = isset($doctor->specialtyIDForDoctorCard) ? $doctor->specialtyIDForDoctorCard : (isset($specialtyIDForDoctorCard) ? $specialtyIDForDoctorCard : null); ?>
                    <?php $this->specialty_id = isset($doctor->specialtyIDForDoctorCard) ? $doctor->specialtyIDForDoctorCard : (isset($specialty_id) ? $specialty_id : null); ?>
                    <?php $this->clinic_id = (isset($clinic_id)) ? $clinic_id : null; ?>
                    <?php $this->purpose_of_visit_id = (isset($purpose_of_visit_id)) ? $purpose_of_visit_id : null; ?>
                    <?php $this->week_schedule = 1; ?>
                    <?php $this->is_big_card = 1; ?>
                    <?php $this->is_seo_page = empty($is_seo_page) ? '' : $is_seo_page; ?>
                    <?php $this->page_type = empty($page_type) ? '' : $page_type; ?>
                    <?php $this->block('doctor/blocks/schedule_and_clinics'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php $cache->end(); ?>
<?php endif; ?>
