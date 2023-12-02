<div class="nav-22">
    <ul>
        <li <?php echo ($schedule_top_menu_active == 'past') ? 'class="ui-state-active"' : ''; ?>>
            <a href="<?php echo RegistryScheduleLinkViewHelper::getSchedulePastLink($doctor, $clinic, $specialty); ?>">Прошедшие графики</a>
        </li>
        <li <?php echo ($schedule_top_menu_active == 'current') ? 'class="ui-state-active"' : ''; ?>>
            <a href="<?php echo RegistryScheduleLinkViewHelper::getScheduleViewLink($doctor, $clinic, $specialty); ?>">График работы врача</a>
        </li>
        <li <?php echo ($schedule_top_menu_active == 'create') ? 'class="ui-state-active"' : ''; ?>>
            <a href="<?php echo RegistryScheduleLinkViewHelper::getScheduleCreateLink($doctor, $clinic, $specialty); ?>">Создать новый график работы</a>
        </li>
    </ul>
</div>