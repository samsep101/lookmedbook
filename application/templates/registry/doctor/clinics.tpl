<?php
/**
 * @var View $this
 * @var DoctorModel $doctor
 */
?>

<script type="text/javascript">
    $(document).ready(function(){
        window.validation_span = true;
        var form_controller = new DoctorClinicsFormController();
        form_controller.doctor_id = <?php echo $doctor->getId(); ?>;
        form_controller.init();
    });
</script>

<?php $this->block('registry/blocks/moderate_status'); ?>

<div class="fields-block sova flo"  id="doctor-clinics-form">
    <p>Клиники</p>
    <?php if ($doctor->clinics_for_all): ?>
        <?php foreach($doctor->clinics_for_all as $clinic): ?>
            <?php $this->selected_clinic_id = $clinic->getId(); ?>
            <?php $this->block('registry/doctor/blocks/doctor_clinic'); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?php $this->block('registry/doctor/blocks/doctor_clinic'); ?>
    <?php endif; ?>

    <div id="new-clinic" class="hidden">
        <?php $this->selected_clinic_id = 0; ?>
        <?php $this->block('registry/doctor/blocks/doctor_clinic'); ?>
    </div>

    <div style="clear: both;">
        <input type="button" class="add-clinic" value="Добавить клинику"  />
    </div>

    <?php $this->block('registry/blocks/form-buttons'); ?>
</div>

