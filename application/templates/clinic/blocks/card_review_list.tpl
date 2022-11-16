<?php if ($reviews): ?>
    <?php $counter = 1; ?>
    <?php $this->clinic = $clinic; ?>
    <?php foreach($reviews as $review): ?>
        <?php $this->counter = $counter; ?>
        <?php $this->review = $review; ?>
        <?php $this->block('clinic/blocks/card_review'); ?>
        <?php $counter++; ?>
    <?php endforeach; ?>
<?php endif; ?>