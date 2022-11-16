        <div class="info-card doctor-card-<?php echo $doctor->getId(); ?> doctor-big-card flo" id="doctor-big-card-<?php echo $doctor->getUniqueId(); ?>">

            <?php echo (isset($is_closed_card) && $is_closed_card == 1) ? '<span class="close" data-id="'.$doctor->getId().'"></span>' : ''; ?>
            <?php if (isset($map_card)): ?>
                 <span class="corn-top"></span>
            <?php endif; ?>
            <div class="avatar_buttons">
                <div class="avatar">
                    <?php echo DoctorAvatarViewHelper::viewOnCard($doctor, 74, 111); ?>
                </div>

            <?php if(isset($is_virtual) and $is_virtual): ?>
                <?php $this->block('doctor/blocks/card_buttons_virtual'); ?>
            <?php else: ?>
                <?php $this->block('doctor/blocks/card_buttons'); ?>
            <?php endif; ?>

            </div>
            <div class="descr">
                <div class="fixed_title" style="height: 75px;">
                    <div class="name">
                        <?php if(isset($is_virtual) && $is_virtual): ?>
                            <span class="post">
                                <?php echo StringHelper::startProposalWord($doctor->specialties[0]->name); ?>
                            </span>
                        <?php else: ?>
                            <a href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>">
                                <span class="post">
                                    <?php echo $doctor->specialties_names; ?>
                                </span>
                            </a>
                        <?php endif; ?>

                        <div class="rating">

                            <?php echo RateViewHelper::view($doctor->rate, 1, $doctor->is_best); ?>
                            <?php if($doctor->is_best) { ?>
                              <div class="is_best_recomm">Рекомендуем</div>
                            <?php } ?>

                            <div class="comments-count">
                                <?php if (!Acc::isAuthed()): ?>
                                    <a class="showTip el" data-url="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>#reviews" href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>#reviews">
                                <?php else: ?>
                                    <a class="showTip el" href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>#reviews">
                                <?php endif?>
                                    <?php echo ($doctor->reviews_count) ? StringHelper::getCorrectSuffixForReview($doctor->reviews_count) : ''; ?>
                                    </a>
                            </div>
                        </div>

                        <a href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>">
                            <p class="doctorname_clear"><?php echo $doctor->full_name; ?></p>
                        </a>
                        <?php $this->block('doctor/blocks/call-centre-operator-hint'); ?>
					</div>
				</div>

                <div class="info-box">
                    <?php $this->specialtyIDForDoctorCard = isset($doctor->specialtyIDForDoctorCard) ? $doctor->specialtyIDForDoctorCard : (isset($specialtyIDForDoctorCard) ? $specialtyIDForDoctorCard : null); ?>
                    <?php $this->specialty_id = isset($doctor->specialtyIDForDoctorCard) ? $doctor->specialtyIDForDoctorCard : (isset($specialty_id) ? $specialty_id : null); ?>
                    <?php $this->clinic_id = (isset($clinic_id)) ? $clinic_id : null; ?>
                    <?php $this->purpose_of_visit_id = (isset($purpose_of_visit_id)) ? $purpose_of_visit_id : null; ?>
                    <?php $this->week_schedule = 1; ?>
                    <?php $this->is_big_card = 1; ?>
                    <?php $this->is_seo_page = empty($is_seo_page)?'':$is_seo_page; ?>
                    <?php $this->page_type = empty($page_type)?'':$page_type; ?>
                    <?php $this->block('doctor/blocks/schedule_and_clinics'); ?>
                </div>
            </div>
        </div>
