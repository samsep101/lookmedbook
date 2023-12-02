<script type="text/javascript">
    $(document).ready(function(){
        var form_controller = new ModerateFormController();
        form_controller.setContainer('<?php echo $this->container; ?>');
        form_controller.init('<?php echo $model_name; ?>', <?php echo $entry_id; ?>);

        <?php if (!RegistryAccessHelper::hasAccessToEdit($model->moderate_status_id)): ?>
            form_controller.lock();
        <?php endif; ?>
    });
</script>