<?php
/**
 * @var View $this
 * @var ClinicModel $clinic
 * @var IHtmlCache $this->cache
 */
?>
<?php if(!$this->cache->start('clinic-check-card-'.$clinic->getId(), 'clinic:'.$clinic->getId())): ?>
<p class="name-clinic"><span class="time"><?php echo date('H:i', strtotime($clinic->dt_publish)); ?></span> Клиника <?php echo $clinic->name; ?> <span>- <?php echo ($clinic->clinic_type) ? $clinic->clinic_type->name : ''; ?>
       <?php echo RegionStatusViewHelper::getStatusImage($clinic->clinic_status_id); ?></span>
</p>
<table class="region-clinic"  border='0' cellpadding='0' cellspacing='0' align='left'>
    <tr>
        <td>Логотип</td>
        <td>Описание</td>
        <td>Специализации</td>
        <td>Фрилансер: <?php echo $clinic->freelancer ? $clinic->freelancer->login : 'не указан'; ?></td>
    </tr>
    <tr>
        <td><?php echo ClinicAvatarViewHelper::getImageView($clinic, $clinic->moderate_card_image, 100, 50); ?></td>
        <td>
            <div class="about">
                <?php echo StringHelper::trim($clinic->moderate_about, 600, '...'); ?>
            </div>
        </td>
        <td>
            <div class="special">
                <?php if ($clinic->moderate_specializations): ?>
                    <?php foreach($clinic->moderate_specializations as $specialization): ?>
                        <?php echo $specialization->name; ?><br />
                    <?php endforeach; ?>
                <?php else: ?>
                    не указаны
                <?php endif; ?>
            </div>
        </td>
        <td>
            <a href="/registry/clinic/information?clinic_id=<?php echo $clinic->getId(); ?>" class="btn-appoint" target="_blank">Редактировать клинику</a>
            <?php if ($clinic->site): ?>
                <a href="<?php echo strip_tags($clinic->site); ?>" class="btn-appoint" target="_blank">Перейти на сайт клиники</a>
            <?php endif; ?>
            <a href="<?php echo ClinicPageLinkViewHelper::getLink($clinic); ?>" class="btn-appoint" target="_blank">Просмотр страницы LMB</a>
        </td>
    </tr>
</table>

<?php $this->cache->end();?>
<?php endif; ?>