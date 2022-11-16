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
                        <a href="/registry/doctor/schedule_view?id=<?php echo $entry_id; echo $param; ?>">Невролог</a>
                    </li>
                    <li>
                        <a href="#">Терапевт</a>
                    </li>
                </ul>
                <a href="/registry/doctor/schedule?id=<?php echo $entry_id; echo $param; ?>"><input class="find-doctor" type="button" value="Добавить специализацию"></a>
            </div>
        </div>

        <div class="fields-block-inner schedule-menu grey-inner">
            <div class="row-record active-schedule-menu">
                <h3>Расписание</h3>
                <ul>
                    <li>
                        <a href="#">Невролог</a>
                    </li>
                    <li>
                        <a class="active-specialty" href="#">Терапевт</a>
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

        <div class="view-schedule-block schedule-edit grey-inner">

            <!--all-day-->

            <!--<div id="">-->
                <!--<table class="schedule-table">-->
                    <!--<tr>-->
                        <!--<td></td>-->
                        <!--<td>Пн</td>-->
                        <!--<td>Вт</td>-->
                        <!--<td>Ср</td>-->
                        <!--<td>Чт</td>-->
                        <!--<td>Пт</td>-->
                        <!--<td>Сб</td>-->
                        <!--<td>Вс</td>-->
                    <!--</tr>-->
                    <!--<tr>-->
                        <!--<td></td>-->
                        <!--<td>15.08</td>-->
                        <!--<td>16.08</td>-->
                        <!--<td>17.08</td>-->
                        <!--<td>18.08</td>-->
                        <!--<td>19.08</td>-->
                        <!--<td>20.08</td>-->
                        <!--<td>21.08</td>-->
                    <!--</tr>-->
                    <!--<tr class="schedule-grid">-->
                        <!--<td>8:00</td>-->
                    <!--</tr>-->
                    <!--<tr class="schedule-grid">-->
                        <!--<td>9:00</td>-->
                    <!--</tr>-->
                    <!--<tr class="schedule-grid">-->
                        <!--<td>10:00</td>-->
                    <!--</tr>-->
                    <!--<tr class="schedule-grid">-->
                        <!--<td>11:00</td>-->
                    <!--</tr>-->
                    <!--<tr class="schedule-grid">-->
                        <!--<td>12:00</td>-->
                    <!--</tr>-->
                    <!--<tr class="schedule-grid">-->
                        <!--<td>13:00</td>-->
                    <!--</tr>-->
                    <!--<tr class="schedule-grid">-->
                        <!--<td>14:00</td>-->
                    <!--</tr>-->
                    <!--<tr class="schedule-grid">-->
                        <!--<td>15:00</td>-->
                    <!--</tr>-->
                    <!--<tr class="schedule-grid">-->
                        <!--<td>16:00</td>-->
                    <!--</tr>-->
                    <!--<tr class="schedule-grid">-->
                        <!--<td>17:00</td>-->
                    <!--</tr>-->
                    <!--<tr class="schedule-grid">-->
                        <!--<td>18:00</td>-->
                    <!--</tr>-->
                    <!--<tr class="schedule-grid">-->
                        <!--<td>19:00</td>-->
                    <!--</tr>-->
                    <!--<tr class="schedule-grid">-->
                        <!--<td>20:00</td>-->
                    <!--</tr>-->
                <!--</table>-->
            <!--</div>-->


            <!-- ******************************************** -->
            <div class='calendar-work head-none'></div>
            <div class="for-calendar">
                <p><span class="work"></span>Работает по графику</p>
                <p><span class="edit"></span>Изменение расписания</p>
                <p><span class="relax"><img src="/media/images/registry/palm.png"/></span>Отпуск</p>
                <p><span class="nowork"></span>Не работает</p>
            </div>

            <div class="buttons flo">
                <input class="btn-appoint long_but" type="submit" name="save" value="Сохранить" onclick="return false;">
                <input class="btn-1" type="submit" value="Опубликовать на <?php echo SITE_NAME; ?>" onclick="return false;">
            </div>

            <!-- ******************************************** -->

        </div>
    </div>

</div>