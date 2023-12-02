<?php if ($clinics):?>
    <div class="clinic-list">
        <?php $this->block('registry/manage/blocks/clinic_results'); ?>
    </div>
<?php else:?>
    <p class="no-results">По вашему запросу клиник не найдено</p>
<?php endif?>