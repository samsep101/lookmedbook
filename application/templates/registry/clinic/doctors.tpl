<?php
	/**
	 * @var View $this
	 * @var int $clinic_id
	 * @var string $model_name
	 * @var int $entry_id
	 * @var SpecialtyModel[] $specialties
	 * @var DoctorModel[] $doctors
	 */
?>
<?php $param =  (RegistryAccessHelper::checkAuth()) ? '?clinic_id='.$clinic_id : ''; ?>
<?php $clinic_id = (RegistryAccessHelper::checkManagerAuth(false)) ? $clinic_id : null; ?>

<script type="text/javascript">
    $(document).ready(function(){
        /*
        var form_controller = new ClinicUserFormController();
        form_controller.setContainer('<?php echo $this->container; ?>');
        form_controller.init('<?php echo $model_name; ?>', '<?php echo $entry_id; ?>');

        window.form_controller = form_controller;
        */

        var doctor_list_controller = new DoctorListController(<?php echo $clinic_id; ?>);
        doctor_list_controller.init();
    });
</script>
<?php $this->block('registry/blocks/moderate_status'); ?>
<div class="moderated-form" id="doctors-form">
    <div class="fields-block flo">
        <p>Врачи клиники</p>
        <div class="doctor-list-form flo">
            <input type="text" class="fio-field" placeholder="ФИО врача">
            <input class="find-doctor" type="button" value="Найти">

            <label>Специализация</label>
            <select class="specialty-pick">
                <option value="">Все</option>
                <?php if ($specialties): ?>
					<?php foreach ($specialties as $specialty): ?>
						<option value="<?php echo $specialty->getId(); ?>"><?php echo $specialty->name; ?></option>
					<?php endforeach; ?>
                <?php endif; ?>
            </select>

            <a class="btn-appoint" href="/registry/doctor/add<?php echo $param; ?>">Добавить врача</a>
        </div>

        <?php if ($doctors): ?>
			<div class="doctor-list">
				<?php $this->block('registry/doctor/blocks/doctor_results'); ?>
			</div>
        <?php endif; ?>
    </div>
</div>