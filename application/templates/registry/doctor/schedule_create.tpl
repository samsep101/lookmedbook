
<?php $this->block('registry/doctor/blocks/select_clinic'); ?>


	<script>
		<?php if ($specialty): ?>
		$(document).ready(function(){
			var controller = new DoctorScheduleController();
			controller.doctor_id = <?php echo $doctor->getId(); ?>;
			controller.clinic_id = <?php echo $clinic->getId(); ?>;
			controller.specialty_id = <?php echo $specialty->getId(); ?>;
			controller.possible_days_range = <?php echo json_encode($days_range); ?>;
			controller.init();
		});
		<?php endif; ?>
	</script>


	<div class="schedule-content">

		<div class="left-schedule-menu">
			<?php $this->block('registry/doctor/blocks/left_schedule_menu'); ?>
		</div>

		<div class="right-schedule-content grey-inner">
			<?php if ($doctor_specialties): ?>
				<?php if ($specialty): ?>
					<div class="cab-page-22 white-inner">
						<?php $this->block('registry/doctor/blocks/schedule_top_menu'); ?>
					</div>
					<div class="view-schedule-block schedule-view grey-inner">
						<?php $this->block('registry/doctor/blocks/schedule'); ?>


						<div class="buttons flo">
							<input class="btn-appoint long_but" type="submit" name="save" value="Сохранить" onclick="return false;">
							<input class="btn-1" type="submit" value="Опубликовать на <?php echo SITE_NAME; ?>" name="publish" onclick="return false;">
						</div>

					</div>
				<?php else: ?>
				Выберите специализацию на вкладке "График"
				<?php endif; ?>
			<?php else: ?>
				<div class="info-message">Для врача не указан список специальностей. Перейдите на вкладку "Специальности и цены"</div>
			<?php endif; ?>
		</div>

	</div>