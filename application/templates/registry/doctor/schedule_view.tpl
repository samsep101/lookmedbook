<script>
    <?php if ($specialty): ?>
        $(document).ready(function(){
            var controller = new DoctorScheduleController();
            controller.doctor_id = <?php echo $doctor->getId(); ?>;
            controller.clinic_id = <?php echo $clinic->getId(); ?>;
            controller.specialty_id = <?php echo $specialty->getId(); ?>;
            controller.possible_days_range = <?php echo json_encode($days_range); ?>;
            <?php if (!$show_buttons): ?>
                controller.blocked = 1;
            <?php endif; ?>
            controller.schedule_id = <?php echo $schedule->getId(); ?>;
            controller.data = <?php echo json_encode($schedule->structured['schedule_info'], JSON_FORCE_OBJECT); ?>;
            controller.even_numbers = '<?php echo $even_numbers; ?>';
            controller.odd_numbers = '<?php echo $odd_numbers; ?>';
            controller.init();
        });
    <?php endif; ?>
</script>

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
            <?php if (isset($schedules_list)): ?>
                <ul class="schedules-list">
                    <?php if ($schedule_top_menu_active == 'current'): ?>
                        <h2>Текущие графики</h2>
                    <?php else: ?>
                        <h2>Прошедшие графики</h2>
                    <?php endif; ?>
                    <?php foreach($schedules_list as $current_schedule): ?>
                        <li>
                            <?php if ($schedule->getId() != $current_schedule->getId()): ?>
                                <a href="/registry/doctor/schedule_view?schedule_id=<?php echo $current_schedule->getId(); ?>">
                                    c <?php echo date('d-m-Y', strtotime($current_schedule->date_from)); ?>
                                    <?php if ($current_schedule->date_to): ?>
                                    по <?php echo date('d-m-Y', strtotime($current_schedule->date_to)); ?>
                                    <?php endif; ?>
                                </a>
                            <?php else: ?>
                                c <?php echo date('d-m-Y', strtotime($current_schedule->date_from)); ?>
                                <?php if ($current_schedule->date_to): ?>
                                по <?php echo date('d-m-Y', strtotime($current_schedule->date_to)); ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <div class="view-schedule-block schedule-view grey-inner">
                <?php $this->block('registry/doctor/blocks/schedule'); ?>

                <?php if ($show_buttons): ?>
                    <div class="buttons flo">
                        <input class="btn-appoint long_but" type="submit" name="save" value="Сохранить" onclick="return false;">
                        <input class="btn-1" type="submit" value="Опубликовать на <?php echo SITE_NAME; ?>" name="publish" onclick="return false;">
                    </div>
                <?php endif;?>

            </div>
        <?php else: ?>
            Выберите специализацию на вкладке "График"
        <?php endif; ?>
    </div>

</div>

