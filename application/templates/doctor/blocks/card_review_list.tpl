<?php if ($reviews): ?>
    <?php $counter = 1; ?>
    <?php $this->doctor = $doctor; ?>
    <?php foreach($reviews as $review): ?>
        <?php $this->counter = $counter; ?>
        <?php $this->review = $review; ?>
        <?php $this->block('doctor/blocks/card_review'); ?>
        <?php if($counter % 2 == 0 || count($reviews) == 1) echo '<div class="clearfix"></div>'; ?>
    <?php endforeach; ?>
<?php endif; ?>