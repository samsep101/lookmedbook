<?php
	/**
	 * @var View $this
	 * @var int $clinic_id
	 * @var DoctorModel[] $doctors
	 * @var int $page
	 */
?>
<?php $param =  (RegistryAccessHelper::checkManagerAuth()) ? '&clinic_id='.$clinic_id : ''; ?>

<?php $doctors_count = 0; ?>
<?php if ($doctors): ?>
    <?php foreach ($doctors as $doctor): ?>
        <?php if ($doctors_count < 10): ?>
            <div class="doctor-record">
                <?php echo DoctorAvatarViewHelper::viewOnCard($doctor, 74, 111, $param, true); ?>
                <div class="doctor-info">
                    <span><a href="/registry/doctor/information?id=<?php echo $doctor->getId(); echo $param;?>"> <?php echo $doctor->moderate_full_name; ?></a></span>
                    <span><?php echo $doctor->specialties_names?></span>
                </div>

                <div class="remove-feature-block">
                    <a class="unbound-doctor bound-doctor" data-id="<?php echo $doctor->getId();?>" data-specialty-id="<?php echo $doctor->specialty_id;?>" data-first-visit-price="<?php echo $doctor->first_visit_price;?>" data-second-visit-price="<?php echo $doctor->second_visit_price;?>" href="javascript:void(0)">Отвязать врача от клиники</a>
                </div>

                <div class="publish-button">
                    <?php if ($doctor->is_active == 1):?>
                        <input class="btn-1" data-id="<?php echo $doctor->getId(); ?>" type="submit" value="Снять">
                    <?php else:?>
                        <input class="btn-appoint" data-id="<?php echo $doctor->getId(); ?>" type="submit" value="Опубликовать">
                    <?php endif?>
                </div>
            </div>
        <?php endif?>
        <?php $doctors_count++;?>
    <?php endforeach; ?>
<?php else: ?>
    <p>По вашему запросу врачей не найдено</p>
<?php endif; ?>
    <div class="paging">
        <?php if (!empty($page) and $page != 1): ?>
            <a class="paging-previous">Предыдущая</a>
        <?php endif; ?>
        <?php if ($doctors_count==11): ?>
            <a class="paging-next">Следующая</a>
        <?php endif; ?>
    </div>

