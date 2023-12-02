
    <div class="review-box flo <?php if ($counter % 2 === 0) echo 'fright'; ?>">
        <span class="chk-pic"></span>
        <div class="aside">
            <p class="name"><?php echo $review->visit->account->full_name; ?></p>
            <div class="rating-item">
                <p>Сервис в регистратуре</p>
                <?php echo RateViewHelper::view($review->service_at_the_reception); ?>
            </div>
            <div class="rating-item">
                <p>Время ожидания</p>
                <?php echo RateViewHelper::view($review->waiting_time); ?>
            </div>
            <div class="rating-item">
                <p>Атмосфера</p>
                <?php echo RateViewHelper::view($review->relationship); ?>
            </div>
            <div class="rating-item">
                <p>Соответствие цене</p>
                <?php echo RateViewHelper::view($review->value_for_money); ?>
            </div>
            <div class="rating-item">
                <p>Диагноз и дальнейшее лечение ясны</p>
                <?php echo RateViewHelper::view($review->diagnosis_is_clear); ?>
            </div>
        </div>

        <div class="review-cont">
            <?php if ($clinic->advice_rate): ?>
            <div class="advice">
                <?php echo RateViewHelper::viewAdvise($clinic->advice_rate); ?>
                <span>Посоветуют друзьям</span>
            </div>
            <?php endif; ?>
            <p><?php echo $review->text; ?></p>
        </div>
    </div>
