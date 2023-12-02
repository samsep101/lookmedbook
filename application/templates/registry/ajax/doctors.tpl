<?php if ($doctors): ?>
<?php foreach($doctors as $doctor): ?>
    <li><?php echo ModeratePageLinkViewHelper::getClinicOrDoctorLinkView($doctor, 'doctor'); ?></li>
    <?php endforeach; ?>
<?php endif; ?>