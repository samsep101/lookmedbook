<script type="text/javascript">
    $(document).ready(function () {
        var controller = new PersonalRoomDoctorsVisitsPastController();
        controller.init();
    });
</script>

<div class="inner-3">
    <div class="cab-page flo">

        <?php $this->active_top_menu = 'visits'; ?>
        <?php $this->block('blocks/personal-room-top-menu'); ?>

        <ul class="sub-menu sub-menu-var">
            <li><a href="/account/doctorsVisitsComing">Предстоящие</a></li>
            <li class="active"><a href="/account/doctorsVisitsPast">Прошедшие</a></li>
        </ul>

        <div class="cab-cont flo">
            <?php if (!$close_visits_note_attribute): ?>
                <div class="owl-block">
                    <div class="note"> <span id="close_settings_note" class="close"></span> <span class="corn"></span>
                        <div class="note-cont">
                            <p class="center-align"><img src="/media/images/note-pic4.png" alt=""></p>
                            <p>Ваш отзыв отправляется на модерацию. После проверки нашими сотрудниками он будет опубликован</p>
                        </div>
                    </div>
                </div>
            <?php endif?>
            <h2>Прошедшие</h2>

            <?php if (count($visits)): ?>

                <?php foreach ($visits as $visit): ?>
                    <?php if (!$visit->checkVisitReviewByAccountId(Acc::accountId())): ?>
                        <div class="appoint appoint-var">
                            <h3>Вы были на приеме <?php echo DateViewHelper::date($visit->visit_start_time, 'full'); ?>. Ваша оценка <br>
                                поможет другим пользователям!</h3>
                            <?php $this->visit = $visit; ?>
                            <?php if ($visit->status_id == VisitModel::VISITED || $visit->status_id == VisitModel::FEDDBACK): ?>
                                <?php $this->leave_review = 1; ?>
                            <?php elseif ($visit->status_id == VisitModel::NOT_VISITED): ?>
                                <?php $this->leave_review = 0; ?>
                            <?php endif; ?>
                            <?php $this->block('doctor/card_tiny'); ?>
                        </div>
                    <?php endif?>
                <?php endforeach?>

                <div class="rev-block rev-past">
                    <div class="section visible">
                        <?php foreach ($visits as $visit): ?>
                            <?php if ($visit->checkVisitReviewByAccountId(Acc::accountId())): ?>
                                <h4><?php echo DateViewHelper::date($visit->schedule->dt_start, 'full'); ?></h4>
                                <div class="review-item flo">

                                    <?php $this->visit = $visit; ?>
                                    <?php $this->leave_review = 0; ?>
                                    <?php $this->block('doctor/card_tiny'); ?>

                                    <div class="review-inf">
                                        <!--<p><a href="#">Посмотреть мой отзыв</a></p>-->
                                        <div class="rev-status-block">
                                            <p>Состояние отзыва</p>
                                            <?php if (!$visit->checkConfirmedReviews()):?>
                                                <span class="rev-status">На модерации</span>
                                            <?php else:?>
                                                <span class="rev-status published">Опубликован</span>
                                            <?php endif?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif?>
                        <?php endforeach?>
                    </div>
                </div>
            <?php endif?>
        </div>
    </div>
</div>