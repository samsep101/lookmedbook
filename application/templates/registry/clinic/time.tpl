<?php $this->block('registry/blocks/moderate_status'); ?>
<script>
    $(document).ready(function(){
        var controller = new ClinicScheduleController();
        controller.clinic_id = <?php echo $clinic->getId(); ?>;
        controller.data = <?php echo json_encode($schedule->structured['schedule_info'], JSON_FORCE_OBJECT); ?>;
        controller.init();
    });
</script>

<div class="moderated-form" id="information-form">
    <div class="fields-block sova flo">
        <p>Время работы клиники</p>
        <div class="fields-block-inner grey-inner" style="width: 815px">
            <div id="first-week-calendar" class="calendar-work calendar-clinic head-none"></div>
        </div>
    </div>

    <?php $this->block('registry/blocks/form-buttons'); ?>
</div>