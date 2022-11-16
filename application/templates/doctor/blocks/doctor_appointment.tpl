<?php
    /**
     * @var DoctorModel $doctor
     */
?>

<a onclick="
    send('//ad.adriver.ru/cgi-bin/rle.cgi?sid=194132&sz=zapis&bt=55&pz=0&rnd=![rnd]');
    var block = new RecordToTheDoctorBlockController(<?php echo $doctor->getId(); ?>, $(this));
    block.action_for_counters = 'button';
    block.init();
    " href="#record-to-the-doctor-popup-<?php echo $doctor->getId(); ?>" class="btn-appoint">Записаться на прием к врачу</a>