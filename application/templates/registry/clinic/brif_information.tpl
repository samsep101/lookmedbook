
<?php $this->container = '#brif_information-form'; ?>

<script type="text/javascript">
    $(document).ready(function(){

        var form_controller = new ClinicUserFormController();
        form_controller.setContainer('<?php echo $this->container; ?>');
        form_controller.init('<?php echo $model_name; ?>', '<?php echo $entry_id; ?>');

        window.form_controller = form_controller;
    });
</script>
<?php $this->block('registry/blocks/moderate_status'); ?>
<div class="moderated-form" id="brif_information-form">
        <div class="fields-block sova flo">
            <p>Контактные данные о предоставившем информацию по брифу</p>
            <div class="fields-block-inner pink-inner" style="width: 880px">
                <div class="row-record">
                    <label>Ф.И.О</label>
                    <div class="row-record-data">
                        <?php echo $view_processor->getView('fio'); ?>
                    </div>
                </div>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_BRIF_INFORMATION_FIO, $model->revision_number); ?>
                <div class="row-record">
                    <label>Телефон</label>
                    <div class="row-record-data">
                        <?php echo $view_processor->getView('phone'); ?>
                    </div>
                </div>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_BRIF_INFORMATION_PHONE, $model->revision_number); ?>

                <div class="row-record">
                    <label>e-mail</label>
                    <div class="row-record-data">
                        <?php echo $view_processor->getView('email'); ?>
                    </div>
                </div>
            <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_BRIF_INFORMATION_EMAIL, $model->revision_number); ?>

                <?php $this->block('registry/blocks/form-buttons'); ?>
            </div>
        </div>
</div>