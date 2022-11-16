<?php if (RegistryAccessHelper::checkManagerAuth()): ?>
    <script type="text/javascript">
        $(document).ready(function(){
            var select_controller = new RegistrySelectDoctorController();
            <?php if (isset($select_clinic_url)): ?>
                select_controller.url = '<?php echo $select_clinic_url; ?>';
            <?php endif; ?>
            select_controller.init();
        });
    </script>
    <div class="select-clinic-block">
        Клиника:
        <select name="doctor_clinic_id">
            <?php foreach($doctor->clinics_for_all as $doctor_clinic): ?>
                <?php $selected = ($doctor_clinic->getId() == $clinic->getId()) ? 'selected="selected"' : ''; ?>
                <option value="<?php echo $doctor_clinic->getId(); ?>" <?php echo $selected; ?>><?php echo $doctor_clinic->name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php endif; ?>