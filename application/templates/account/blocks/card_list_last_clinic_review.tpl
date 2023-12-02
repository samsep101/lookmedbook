<?php if ($last_reviews): ?>
    <?php $prev_visit_date = ''; ?>
    <?php $reviews_count=0;?>
    <?php foreach ($last_reviews as $review): ?>
        <?php if ($reviews_count < $per_page):?>
            <?php $current_visit_date = DateViewHelper::date($review->visit->dt); ?>
            <?php if ($prev_visit_date != $current_visit_date): ?>
                <h3><?php echo $current_visit_date; ?></h3>
            <?php endif; ?>
            <?php $prev_visit_date = $current_visit_date; ?>
            <?php $this->review = $review; ?>
            <?php $this->visit_rating = $visit_rating; ?>
            <?php $this->block('account/blocks/card_last_clinic_review'); ?>
        <?php endif?>
        <?php $reviews_count++;?>
    <?php endforeach; ?>
<?php endif; ?>