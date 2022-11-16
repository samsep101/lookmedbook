<script>
    <?php if (isset($clinic_id)): ?>
        ModerateFormController.redirect = '/registry/doctor/information?clinic_id=<?php echo $clinic_id; ?>&id=';
    <?php else: ?>
        ModerateFormController.redirect = '/registry/doctor/information?id=';
    <?php endif; ?>
    $(document).ready(function(){
        var add_form_controller = new AddDoctorFormController();
        add_form_controller.init();
    });
</script>

    <div class="up-info">
        <div class="back-for-doctors">
            <?php if (Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY)): ?>
                <a href="/registry/clinic/information?clinic_id=<?php echo $clinic_id; ?>"> ← Назад на страницу клиники</a>
            <?php elseif (RegistryAccessHelper::checkManagerAuth()): ?>
                <a href="/registry/clinic/information?clinic_id=<?php echo $clinic_id; ?>"> ← Назад на страницу клиники</a>
            <?php endif; ?>
        </div>
    </div>
<br>
<?php $this->block('registry/doctor/information'); ?>