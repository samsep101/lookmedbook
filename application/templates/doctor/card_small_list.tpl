<?php if ($doctors): ?>
   <div class="cards">
        <?php foreach($doctors as $doctor): ?>
            <?php $this->doctor = $doctor; ?>
            <?php $this->block('doctor/card_small'); ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>