<div class="doctor-record">
    <?php echo DoctorAvatarViewHelper::viewOnCard($doctor, 74, 111); ?>
    <div class="doctor-info">
        <?php echo $doctor->full_name; ?>
    </div>
</div>

<div class="fields-block-inner schedule-menu grey-inner">
    <div class="row-record active-schedule-menu">
        <h3>График работы</h3>
        <ul>
            <?php foreach($doctor_specialties as $doctor_specialty): ?>
            <li>
                <a  <?php echo ($specialty && ($specialty->getId() == $doctor_specialty->getId())) ? 'class="active-specialty"' : ''; ?>
                href="<?php echo RegistryScheduleLinkViewHelper::getScheduleViewLink($doctor, $clinic, $doctor_specialty); ?>"><?php echo $doctor_specialty->name; ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<!--
<div class="fields-block-inner schedule-menu dark-grey-inner">
    <div class="row-record">
        <h3>Расписание</h3>
        <ul>
            <li>
                <a href="#">Невролог</a>
            </li>
            <li>
                <a href="/registry/doctor/schedule_edit?id=<?php echo $entry_id; ?>">Терапевт</a>
            </li>
        </ul>
    </div>
</div>
-->
<!---
<div class="fields-block-inner schedule-menu dark-grey-inner">
    <div class="row-record">
        <h3><a href="/registry/doctor/schedule_options?id=<?php echo $entry_id;?>">Настройки</a></h3>
    </div>
</div>
--->
