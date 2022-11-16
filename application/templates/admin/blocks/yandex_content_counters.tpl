<?php if ($yandex_counters):?>
    <p><b>Всего текстов: </b><?php echo $yandex_counters->content_active?></p>
    <p><b>Тексты не в яндексе: </b><?php echo $yandex_counters->content_not_in_yandex?></p>
    <p><b>Тексты в яндексе: </b><?php echo $yandex_counters->content_in_yandex?></p>
    <p><b>Тексты в яндексе, требующие обновления: </b><?php echo $yandex_counters->content_to_update?></p>
<?php endif;?>