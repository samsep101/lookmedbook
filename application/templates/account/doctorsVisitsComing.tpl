<script type="text/javascript">
    $(document).ready(function () {
        var controller = new DocotorsVisitsComingController();
        controller.init();
    });
</script>

<div class="inner-3">
    <div class="cab-page flo">

        <?php $this->active_top_menu = 'visits'; ?>
        <?php $this->block('blocks/personal-room-top-menu'); ?>

        <ul class="sub-menu sub-menu-var">
            <li class="active"><a href="/account/doctorsVisitsComing">Предстоящие</a></li>
            <li><a href="/account/doctorsVisitsPast">Прошедшие</a></li>
        </ul>
        <div class="cab-cont flo">
            <h2>Предстоящие</h2>
            <div id="datepicker" class="calendar"></div>
            <div class="record-block">

            <?php if (count($visits)):?>
                <?php foreach ($visits as $visit):?>

                <div class="record-cart">
                    <div class="cont flo">
                        <h3>
                            <?php echo ($visit->visit_start_time) ? DateViewHelper::date($visit->visit_start_time, 'full') : DateViewHelper::date($visit->schedule->dt_end, 'full');?> года
                            <?php if ($visit->visit_start_time): ?>
                                на <?php echo DateViewHelper::date($visit->visit_start_time, 'time'); ?>
                            <?php endif; ?>
                            <?php if ($visit->status_id): ?>
                                (<?php echo VisitStatusViewHelper::view($visit->status_id); ?>)
                            <?php endif; ?>
                        </h3>

                        <?php $this->visit = $visit; ?>
                        <?php $this->block('doctor/card_tiny'); ?>
                            <div class="btns">
                                <span class="btn-5">
                                    <input type="submit" class="cancel_visit_button" data-id="<?php echo $visit->getid(); ?>" value="Отменить">
                                </span>
                            </div>
                        <div class="bott-info flo">
                            <div class="note-txt"><!--Как подготовиться к приему-->
                                <div class="req"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endforeach?>
            <?php else: ?>
                <div class="coming_visit">
                    Записей нет
                </div>
            <?php endif?>
            </div>

        </div>
    </div>
</div>