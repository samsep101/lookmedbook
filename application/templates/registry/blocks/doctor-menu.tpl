<?php
	/**
	 * @var View $this
	 * @var DoctorModel $doctor
	 *
	 */
?>
<?php $param =  (RegistryAccessHelper::checkAuth() && isset($clinic_id) && $clinic_id) ? '&clinic_id='.$clinic_id : ''; ?>

<div class="up-info">
    <?php if (!$param): ?>
        <div class="back-for-doctors">
            <a href="/registry/doctor?<?php echo $param; ?>"> ← Назад к списку врачей</a>
        </div>
    <?php else: ?>
        <div class="back-for-doctors">
            <a href="/registry/clinic/doctors?<?php echo $param; ?>"> ← Назад к списку врачей</a>
        </div>
    <?php endif; ?>

    <?php if (isset($doctor)): ?>
            <div class="doctor-fio">
				<?php echo $doctor->moderate_full_name; ?>
            </div>
    <?php endif; ?>

    <div class="show_example_page">
        <a href="/registry/example/showDoctorPage?id=<?php echo $entry_id;?>" target="_blank">
            <input class="btn-appoint longest-button" type="button" value="Предпросмотр страницы"/>
        </a>
    </div>
</div>

<div class="cab-page-2">
    <div class="nav-2">
        <ul>
            <li class="<?php echo (isset($menu_active) && $menu_active == 'information') ? 'ui-state-active' : '';?>">
                <a href="/registry/doctor/information?id=<?php echo $entry_id; echo $param; ?>">О враче</a>
            </li>
            <?php if($doctor->clinics_for_all): ?>
                <li class="<?php echo (isset($menu_active) && $menu_active == 'specialties') ? 'ui-state-active' : ''; ?>">
                    <a href="/registry/doctor/specialties?id=<?php echo $entry_id; ?>">Специализации и цены</a>
                </li>
            <?php endif; ?>

            <li class="<?php echo (isset($menu_active) && $menu_active == 'photos') ? 'ui-state-active' : ''; ?>">
                <a href="/registry/doctor/photos?id=<?php echo $entry_id; echo $param; ?>">Фотографии</a>
            </li>
            <!--
            <li class="<?php echo (isset($menu_active) && $menu_active == 'education') ? 'ui-state-active' : ''; ?>">
                <a href="/registry/doctor/education?id=<?php echo $entry_id; echo $param; ?>">Образование</a>
            </li>
            -->
            <?php if ($doctor->clinics_for_all): ?>
                <li class="<?php echo (isset($menu_active) && $menu_active == 'schedule') ? 'ui-state-active' : '';?>">
                    <a href="/registry/doctor/schedule_view?id=<?php echo $entry_id; ?>">График работы врача</a>
                </li>
            <?php endif; ?>
            <li class="<?php echo (isset($menu_active) && $menu_active == 'clinics') ? 'ui-state-active' : ''; ?>">
                <a href="/registry/doctor/clinics?id=<?php echo $entry_id; echo $param; ?>">Клиники</a>
            </li>

        </ul>
    </div>
</div>