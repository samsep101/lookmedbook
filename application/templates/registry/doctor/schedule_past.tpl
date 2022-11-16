<?php $this->block('registry/doctor/blocks/select_clinic'); ?>
<div class="schedule-content">

    <div class="left-schedule-menu">
        <?php $this->block('registry/doctor/blocks/left_schedule_menu'); ?>
    </div>

    <div class="right-schedule-content grey-inner">
        <?php if ($specialty): ?>
            <div class="cab-page-22 white-inner">
                <?php $this->block('registry/doctor/blocks/schedule_top_menu'); ?>
            </div>
            <?php if (isset($schedules_list) && $schedules_list): ?>
                <ul class="schedules-list">
                    <h2>Прошедшие графики</h2>
                    <?php foreach($schedules_list as $current_schedule): ?>
                    <li>
                        <a href="/registry/doctor/schedule_view?schedule_id=<?php echo $current_schedule->getId(); ?>">
                            c <?php echo date('d-m-Y', strtotime($current_schedule->date_from)); ?>
                            <?php if ($current_schedule->date_to): ?>
                            по <?php echo date('d-m-Y', strtotime($current_schedule->date_to)); ?>
                            <?php endif; ?>
                        </a>

                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="info-message"> Прошедших графиков нет</div>
            <?php endif; ?>

        <?php else: ?>
        Выберите специализацию на вкладке "График"
        <?php endif; ?>
    </div>

</div>

