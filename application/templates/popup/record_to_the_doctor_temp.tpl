<div id="record-to-the-doctor-popup-<?php echo $doctor->getId(); ?>" class="booking reg-popup record-to-the-doctor-popup" style="width: 460px">
    <img class="logo" alt="" src="/media/images/main_logo.png">
    <div class="all">
            <h2 class="ctitle">Записаться к врачу вы можете:</h2>
        <?php foreach($doctor->clinics as $clinic): ?>
        <div class="clinics">
            <br>
            <?php if ($clinic->phones): ?>
            <b>Номера телефонов:</b>
            <ul class="phones" style="color: #1F8EBE">
                <?php foreach($clinic->phones as $phone): ?>
                <li><?php echo PhoneFormatViewHelper::view($phone->phone_number); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <h3><?php echo $clinic->name; ?></h3>
            <div class="addr">
                <?php echo $clinic->address; ?>
            </div>

        </div>
        <?php endforeach; ?>
    </div>
</div>