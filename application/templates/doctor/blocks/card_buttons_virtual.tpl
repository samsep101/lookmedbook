<?php
    if (!isset($is_small_card))
        $is_small_card = false;
    if (!isset($doctor_page))
        $doctor_page = false;
?>


<div class="btns flo">
    <?php if (isset($example_page)): ?>
        <a href="javascript:void(0)" class="btn-appoint">Записаться</a>
    <?php else: ?>
        <a onclick="
                if (window.is_test == 1) {
                    $(this).attr('href','javascript:void(0)');
                }
                else {
                    send('//ad.adriver.ru/cgi-bin/rle.cgi?sid=194132&sz=zapis&bt=55&pz=0&rnd=![rnd]')
                    var block = new RecordToTheDoctorBlockController(<?php echo $doctor->getId(); ?>, $(this), null);
                    block.action_for_counters = 'button';
                    block.init();
                }
            " href="#record-to-the-doctor-popup-<?php echo $doctor->getId(); ?>" class="btn-appoint
                        <?php echo $is_small_card ? 'btn-appoint-sm' : '' ; ?>
                    ">Записаться</a>
    <?php endif; ?>
</div>