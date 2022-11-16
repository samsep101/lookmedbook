<script type="text/javascript">
    $(document).ready(function () {
        var controller = new PersonalRoomReviewsController('<?php echo $current_account->getId(); ?>');
        controller.init();
    });
</script>

<div class="inner-3">
    <div class="cab-page flo">

        <?php $this->active_top_menu = 'profile'; ?>
        <?php $this->block('blocks/personal-room-top-menu'); ?>

        <?php $this->active_left_menu = 'reviews'; ?>
        <?php $this->block('blocks/personal-room-left-menu'); ?>

        <div class="cab-cont">
            <?php if (!$close_review_note_attribute): ?>
                <div class="owl-block">
                    <div class="note">
                        <span class="close" id="close_review_note"></span>
                        <span class="corn"></span>
                        <div class="note-cont">
                            <p class="center-align">
                                <img src="/media/images/note-pic3.png" alt=""></p>
                            <p>Ваша оценка поможет другим пользователям выбрать лучшего доктора и клинику!</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <h2>Отзывы</h2>

            <?php if ($visits): ?>
                <div class="appoint">
                    <?php $prev_visit_date = ''; ?>
                    <?php foreach ($visits as $visit): ?>

                        <?php if (!$visit->checkVisitReviewByAccountId(Acc::accountId())): ?>
                            <?php $current_visit_date = DateViewHelper::date($visit->dt); ?>
                            <?php if ($prev_visit_date != $current_visit_date): ?>
                                    <h3>Вы были на приеме <?php echo $current_visit_date; ?>.</h3>
                            <?php endif; ?>
                            <?php $prev_visit_date = $current_visit_date; ?>
                            <?php $this->visit = $visit; ?>
                            <?php $this->block('review/blocks/visit-remind'); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </div>
            <?php endif; ?>
            <div class="rev-block">
                <?php if ($review_tabs):?>
                    <ul class="switch flo">
                        <li class="doctors_switch active"><a><i></i>Доктора</a></li>
                        <li class="clinics_switch"><a><i></i>Клиники</a></li>
                    </ul>
                <?php endif?>
                <div class="section visible">

                    <div id="last_doctors_reviews_container">
                    </div>

                    <div id="more_doctor_reviews" style="display: none">
                        <a class="view-more" id="view_more_doctor_reviews" href="javascript:void(0)"><i></i>Показать ещё</a>
                    </div>
                </div>

                <div class="section">

                    <div id="clinics_reviews_container">
                    </div>

                    <div id="more_clinic_reviews" style="display: none">
                        <a class="view-more" id="view_more_clinic_reviews" href="javascript:void(0)"><i></i>Показать ещё</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
