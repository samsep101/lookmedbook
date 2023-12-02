<?php if ($last_reviews): ?>
    <?php $reviews_count=0;?>
    <?php foreach ($last_reviews as $review): ?>
        <?php if ($reviews_count < $per_page):?>
            <?php if ($review->visit->schedule && $review->visit->schedule->dt_end): ?>
                <h4><?php echo DateViewHelper::date($review->visit->dt); ?></h4>
            <?php endif; ?>

            <?php $this->review = $review; ?>
            <?php $this->visit_rating = $visit_rating; ?>

            <?php $this->block('account/blocks/card_last_doctor_review'); ?>
        <?php endif?>
        <?php $reviews_count++;?>
    <?php endforeach; ?>
<?php endif; ?>
