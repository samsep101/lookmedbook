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

        <div class="fields-block-inner schedule-menu grey-inner">
            <div class="row-record active-schedule-menu">
                <h3>График работы</h3>
                <ul>
                    <li>
                        <a class="active-specialty" href="/registry/doctor/schedule_view?id=<?php echo $entry_id; echo $param; ?>"">Невролог</a>
                    </li>
                    <li>
                        <a href="#">Терапевт</a>
                    </li>
                </ul>
                <input class="find-doctor" type="button" value="Добавить специализация">
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
                        <a href="/registry/doctor/schedule_edit?id=<?php echo $entry_id; echo $param; ?>"">Терапевт</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="fields-block-inner schedule-menu dark-grey-inner">
            <div class="row-record">
                <h3><a href="/registry/doctor/schedule_options?id=<?php echo $entry_id; echo $param; ?>">Настройки</a></h3>
            </div>
        </div>

    </div>

    <div class="right-schedule-content grey-inner">
        <div class="add-schedule-block">
            <div class="row-record">
                <label>Специализации врача</label>
                <select class="specialty-pick">
                    <option value="">Выберите специализацию врача</option>
                    <?php if ($specialties):?>
                        <?php foreach ($specialties as $specialty):?>
                            <option value="<?php echo $specialty->getId(); ?>"><?php echo $specialty->name; ?></option>
                        <?php endforeach?>
                    <?php endif?>
                </select>
            </div>
            <div class="row-record">
                <label>График работы врача</label>
                <!--<input type="text" placeholder="Здесь будет выбор времени"/>-->
                <input class="day-range-schedule-pick" type="text" placeholder="с__по__"/>
            </div>
            <div class="buttons flo">
                <input class="btn-1" type="submit" value="Создать график работы" onclick="return false;">
                <input class="btn-1" type="submit" value="Отменить" onclick="return false;">
            </div>
        </div>
    </div>

</div>