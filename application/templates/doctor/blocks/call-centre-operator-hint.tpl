<?php
    /**
     * @var View $this
     * @var AccountModel $current_account
     * @var DoctorModel $doctor
     */
?>
<script type="text/javascript">
    $(document).ready(function(){
        var hint_controller = new CallCentreOperatorDoctorHintsController();
        hint_controller.doctor_id = <?php echo $doctor->getId(); ?>;
        hint_controller.init();
    });
</script>
<?php if ($current_account && $current_account->is_call_centre_operator): ?>
    <div class="hint-doctor hint-doctor-<?php echo $doctor->getId(); ?>">
        <?php foreach($doctor->clinics as $clinic): ?>
            <?php $this->clinic = $clinic; ?>
            <?php $this->block('clinic/blocks/call-centre-operator-hint'); ?>
        <?php endforeach; ?>
    </div>
    <p class="clear_fix"></p>
<?php endif; ?>