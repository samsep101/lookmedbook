<?php $param =  (RegistryAccessHelper::checkAuth()) ? '?clinic_id='.$entry_id : ''; ?>

<div class="show_example_page">
    <a href="/registry/example/showClinicPage?id=<?php echo $entry_id;?>" target="_blank">
        <input class="btn-appoint longest-button" type="button" value="Предпросмотр страницы"/>
    </a>
</div>

<div class="cab-page-2" style="width: 1250px;">
    <div class="nav-2">
        <ul>
            <li class="<?php echo (isset($menu_active) && $menu_active == 'information') ? 'ui-state-active' : '';?>">
                <a href="/registry/clinic/information<?php echo $param;?>">О клинике</a>
            </li>
            <li class="<?php echo (isset($menu_active) && $menu_active == 'license') ? 'ui-state-active' : ''; ?>">
                <a href="/registry/clinic/license<?php echo $param;?>">Лицензия</a>
            </li>
            <li class="<?php echo (isset($menu_active) && $menu_active == 'photos') ? 'ui-state-active' : ''; ?>">
                <a href="/registry/clinic/photos<?php echo $param;?>">Фотографии</a>
            </li>
            <li class="<?php echo (isset($menu_active) && $menu_active == 'description') ? 'ui-state-active' : '';?>">
                <a href="/registry/clinic/description<?php echo $param;?>">Описание</a>
            </li>
            <li class="<?php echo (isset($menu_active) && $menu_active == 'service') ? 'ui-state-active' : ''; ?>">
                <a href="/registry/clinic/service<?php echo $param;?>">Сервис</a>
            </li>
            <li class="<?php echo (isset($menu_active) && $menu_active == 'requisites') ? 'ui-state-active' : '';?>">
                <a href="/registry/clinic/requisites<?php echo $param;?>">Реквизиты</a>
            </li>
            <li class="<?php echo (isset($menu_active) && $menu_active == 'services') ? 'ui-state-active' : ''; ?>">
                <a href="/registry/clinic/services<?php echo $param;?>">Услуги</a>
            </li>
            <li class="<?php echo (isset($menu_active) && $menu_active == 'brif_information') ? 'ui-state-active' : ''; ?>">
                <a href="/registry/clinic/brif_information<?php echo $param;?>">Информация по брифу</a>
            </li>
            <li class="<?php echo (isset($menu_active) && $menu_active == 'action') ? 'ui-state-active' : ''; ?>">
                <a href="/registry/clinic/action<?php echo $param;?>">Акции</a>
            </li>

            <?php if (isset($clinic) && !$clinic->is_region): ?>
                <li class="<?php echo (isset($menu_active) && $menu_active == 'doctors') ? 'ui-state-active' : ''; ?>">
                    <a href="/registry/clinic/doctors<?php echo $param;?>">Врачи клиники</a>
                </li>
            <?php endif; ?>

            <li class="<?php echo (isset($menu_active) && $menu_active == 'time') ? 'ui-state-active' : ''; ?>">
                <a href="/registry/clinic/time<?php echo $param;?>">Время работы клиники</a>
            </li>
            <li class="<?php echo (isset($menu_active) && $menu_active == 'seo') ? 'ui-state-active' : ''; ?>">
                <a href="/registry/clinic/seo<?php echo $param;?>">SEO</a>
            </li
        </ul>
    </div>
</div>