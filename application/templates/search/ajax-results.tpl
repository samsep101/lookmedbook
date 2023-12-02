<?php if (!empty($clinics)): ?>
    <?php foreach($clinics as $clinic): ?>
        <a href="<?= ClinicPageLinkViewHelper::getLink($clinic); ?>"><li><b>Клиника</b> <?= htmlspecialchars($clinic->name); ?></li></a>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($doctors)): ?>
    <?php foreach($doctors as $doctor): ?>
        <a href="<?= DoctorPageLinkViewHelper::getLink($doctor); ?>"><li><b>Доктор</b> <?= htmlspecialchars($doctor->full_name); ?></li></a>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($diseases)): ?>
    <?php foreach($diseases as $disease): ?>
        <a href="<?= DiseasePageLinkViewHelper::getLink($disease); ?>"><li><b>Заболевание</b> <?= htmlspecialchars($disease->title); ?></li></a>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($services)): ?>
    <?php foreach($services as $service): ?>
        <a href="<?= ServicePageLinkViewHelper::getLink($service, $cityId); ?>"><li><b>Услуга</b> <?= htmlspecialchars($service->name); ?></li></a>
    <?php endforeach; ?>
<?php endif; ?>