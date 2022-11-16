<?php
/**
 * @var DoctorReviewModel|VisitRatingModel $review
 * @var DoctorModel $doctor
 */
?>
    <div class="review-box flo <?php if ($counter % 2 == 0) echo 'fright';?>">
        <span class="chk-pic"></span>

        <div class="aside">
            <p class="name">Оценка пациента</p>

            <div class="rating-item">
                <p>Сервис в регистратуре</p>
                <?php echo RateViewHelper::viewDoctorReviewRate($review->service_at_the_reception); ?>
            </div>
            <div class="rating-item">
                <p>Время ожидания</p>
                <?php echo RateViewHelper::viewDoctorReviewRate($review->waiting_time); ?>
            </div>
            <div class="rating-item">
                <p>Атмосфера</p>
                <?php echo RateViewHelper::viewDoctorReviewRate($review->relationship); ?>
            </div>
            <div class="rating-item">
                <p>Соответствие цене</p>
                <?php echo RateViewHelper::viewDoctorReviewRate($review->value_for_money); ?>
            </div>
            <div class="rating-item">
                <p>Диагноз и дальнейшее лечение ясны</p>
                <?php echo RateViewHelper::viewDoctorReviewRate($review->diagnosis_is_clear); ?>
            </div>
        </div>
        <div class="review-cont">
            <?php if ($doctor->advice_rate): ?>
            <div class="advice">
                <?php echo RateViewHelper::viewAdvise($doctor->advice_rate); ?>
                <span>Посоветуют друзьям</span>
            </div>
            <?php endif; ?>
            <div class="comment-data">
                <img src="/media/images/account_image.gif" alt=""/>
                <p class="author-name"><?= $review->getAccountNameOrFallback() ?></p>
                <span class="comment-date"><?php echo DateViewHelper::date($review->dt, 'dd.mm.YYYY'); ?></span>
            </div>
            <p class="comment-text"><?php echo $review->text;?></p>
        </div>
    </div>
