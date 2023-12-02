<?php if ($doctors):?>
    <div class="doctor-list">
        <?php $this->block('registry/manage/blocks/doctor_results'); ?>
    </div>
<?php else:?>
    <p class="no-results">По вашему запросу врачей не найдено</p>
<?php endif?>