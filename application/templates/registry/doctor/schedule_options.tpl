<?php $param =  (RegistryAccessHelper::checkManagerAuth()) ? '&clinic_id='.$clinic_id : ''; ?>

<script>
    $(function() {
        var doctor_schedule_controller = new DoctorScheduleController();
        doctor_schedule_controller.init();
    });
</script>

<div class="schedule-content">

    <div class="left-schedule-menu">
        <div class="doctor-record">
            <?php echo DoctorAvatarViewHelper::viewOnCard($doctor, 74, 111, $param); ?>
            <div class="doctor-info">
                <?php echo $doctor->full_name; ?>
            </div>
        </div>

        <div class="fields-block-inner schedule-menu dark-grey-inner">
            <div class="row-record">
                <h3>График работы</h3>
                <ul>
                    <li>
                        <a href="/registry/doctor/schedule_view?id=<?php echo $entry_id; echo $param; ?>"">Невролог</a>
                    </li>
                    <li>
                        <a href="#">Терапевт</a>
                    </li>
                </ul>
                <a href="/registry/doctor/schedule?id=<?php echo $entry_id; echo $param; ?>"><input class="find-doctor" type="button" value="Добавить специализацию"></a>
            </div>
        </div>

        <div class="fields-block-inner schedule-menu dark-grey-inner">
            <div class="row-record">
                <h3>Расписание</h3>
                <ul>
                    <li>
                        <a href="#">Невролог</a>
                    </li>
                    <li>
                        <a href="#">Терапевт</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="fields-block-inner schedule-menu grey-inner">
            <div class="row-record active-schedule-menu">
                <h3><a href="/registry/doctor/schedule_options?id=<?php echo $entry_id; echo $param; ?>">Настройки</a></h3>
            </div>
        </div>

    </div>

    <div class="right-schedule-content grey-inner">
        <div class="schedule-options-block">
            <h3>Настройки</h3>
            <h2>Запись на прием</h2>
            <div class="row-record">
                <label>Записываться на прием на <?php echo SITE_NAME; ?> на</label>
                <select class="specialty-pick">
                    <option value="">2 недели</option>
                </select>
            </div>
        </div>
    </div>

</div>