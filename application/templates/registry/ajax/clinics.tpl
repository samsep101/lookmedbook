<?php if ($clinics): ?>
<?php foreach($clinics as $clinic): ?>
    <li><?php echo ModeratePageLinkViewHelper::getClinicOrDoctorLinkView($clinic, 'clinic'); ?></li>
    <?php endforeach; ?>
<?php endif; ?>