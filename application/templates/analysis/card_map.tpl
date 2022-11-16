
    <p class="h-txt">
        <b>Инвитро</b>
        <small>
            <?php if ($metro_station): ?>
                (м. <?php echo $metro_station->name; ?>)
            <?php endif; ?>
        </small>
    </p>
    <div class="left">
        <p class="address"><?php echo $laboratory->address; ?></p>

<!-- Блок работы лаборатории на сегодня (пока не нужен)
        <div class="red">
            <?php $day_name = strtolower(date('l', strtotime("Today"))); ?>
            <?php if($laboratory->{'start_time_'.$day_name} && $laboratory->{'end_time_'.$day_name}): ?>
                <?php echo DateViewHelper::date(strtotime("Today"), "day_and_month"); ?> прием анализов с <?php echo $laboratory->{'start_time_'.$day_name}; ?> до <?php echo $laboratory->{'end_time_'.$day_name}; ?>.
            <?php else: ?>
                <?php echo DateViewHelper::date(strtotime("Today"), "day_and_month"); ?> лаборатория не работает.
            <?php endif; ?>
        </div>
-->

    </div>
    <script>
        $('.pass').click(function (){
            $('.get').removeClass('.active');
            $('.pass').addClass('.active');
            $('.section.get').css('display', 'none');
            $('.section.pass').css('display', 'block');
        });

        $('.get').click(function (){

        });
    </script>
    <div class="right location-box">
        <ul class="tabs">
            <li class="pass active" onclick="$('.get').removeClass('active');
                                             $('.pass').addClass('active');
                                             $('.section.get').css('display', 'none');
                                             $('.section.pass').css('display', 'block');">
                <a href="javascript:void(0);">сдать</a>
            </li>
            <li class="get" onclick="$('.pass').removeClass('active');
                                     $('.get').addClass('active');
                                     $('.section.pass').css('display', 'none');
                                     $('.section.get').css('display', 'block');">
                <a href="javascript:void(0);">получить</a>
            </li>
        </ul>
        <div class="box">
            <div class="section pass" style="display: block;">
                <table border="0" cellpadding="0" cellspacing="0">
                    <?php $monday = date("d.m.Y", strtotime("Monday this week")); ?>
                    <?php for($day = 1; $day <= 7; $day++): ?>
                        <tr>
                            <?php $cur_day_time = strtotime($monday) + 86400*($day-1); ?>
                            <?php $day_name = DateHelper::getWeekDayName($day-1);?>

                            <td><?php echo DayViewHelper::shortDay(date($cur_day_time));?></td>
                            <td><?php echo $laboratory->{'start_time_'.$day_name}; ?> - <?php echo $laboratory->{'end_time_'.$day_name}; ?></td>
                        </tr>
                    <?php endfor; ?>
                </table>
            </div>
            <div class="section get" style="display: none;">
                <table border="0" cellpadding="0" cellspacing="0">
                    <?php $monday = date("d.m.Y", strtotime("Monday this week")); ?>
                    <?php for($day = 1; $day <= 7; $day++): ?>
                        <tr>
                            <?php $cur_day_time = strtotime($monday) + 86400*($day-1); ?>
                            <?php $day_name = DateHelper::getWeekDayName($day-1);?>

                            <td><?php echo DayViewHelper::shortDay(date($cur_day_time));?></td>
                            <?php if ($results_delivery): ?>
                                <td><?php echo $results_delivery->{'start_time_'.$day_name}; ?> - <?php echo $results_delivery->{'end_time_'.$day_name}; ?></td>
                            <?php else: ?>
                                <td>-</td>
                            <?php endif; ?>
                        </tr>
                    <?php endfor; ?>
                </table>
            </div>
        </div>
    </div>