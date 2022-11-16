<div class="address-and-time-area">
    <?php if ($clinic->total_doctors > 0): ?>
        <?php if (!empty($current_account) && $current_account->is_call_centre_operator && $clinic->not_work) : ?>
            <div class="not-work-message">НЕ РАБОТАЕМ</div>
        <?php else : ?>
            <div class="aata-time">
                <?=ScheduleViewHelper::schedule_in_table($clinic); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="aata-address">
        <div class="aata-street">
            <img src="/media/images/small_placemark_for_street.png"><?=$clinic->address; ?>
        </div>
        <div class="aata-metro">
            <?php if ($clinic->metro_stations) :
                    foreach ($clinic->metro_stations as $metro_station) :
                        echo '<span class="metro"><span>&#9899;</span>'.$metro_station->name.'</span>';
                endforeach;
            endif; ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
