<script type="text/javascript">
    $(document).ready(function(){
        window.validation_span = true;
        var form_controller = new RequisitesFormController();
        form_controller.setContainer('#requisites-form');
        form_controller.init('<?php echo $model_name; ?>', <?php echo $entry_id; ?>);

        <?php if (!RegistryAccessHelper::hasAccessToEdit($model->moderate_status_id)): ?>
            form_controller.lock();
        <?php endif; ?>
    });
</script>
<?php $this->block('registry/blocks/moderate_status'); ?>

<div class="moderated-form" id="requisites-form">

    <div class="fields-block flo">
        <p>Реквизиты</p>
        <div class="fields-block-inner white-inner">

            <div class="row-record">
                <label>Наименование банка</label>
                <div class="row-record-data long-input">
                    <?php echo $view_processor->getView('name_of_bank'); ?>
                </div>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_REQISITES_BANK_NAME, $model->revision_number); ?>
            </div>
            <div class="row-record">
                <label>БИК банка</label>
                <div class="row-record-data">
                    <?php echo $view_processor->getView('bank_bik'); ?>
                </div>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_REQISITES_BANK_BIK, $model->revision_number); ?>
            </div>
            <div class="row-record">
                <label>ИНН</label>
                <div class="row-record-data">
                    <?php echo $view_processor->getView('bank_inn'); ?>
                </div>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_REQISITES_BANK_INN, $model->revision_number); ?>
            </div>
            <div class="row-record">
                <label>КПП</label>
                <div class="row-record-data">
                    <?php echo $view_processor->getView('bank_kpp'); ?>
                </div>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_REQISITES_BANK_KPP, $model->revision_number); ?>
            </div>
            <div class="row-record">
                <label>Расчетный счет</label>
                <div class="row-record-data">
                    <?php echo $view_processor->getView('current_account'); ?>
                </div>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_REQISITES_BANK_RS, $model->revision_number); ?>
            </div>
            <div class="row-record">
                <label>Корреспондентский счет</label>
                <div class="row-record-data">
                    <?php echo $view_processor->getView('correspondent_account'); ?>
                </div>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_REQISITES_BANK_KS, $model->revision_number); ?>
            </div>
            <div class="row-record">
                <label>ОГРН</label>
                <div class="row-record-data">
                    <?php echo $view_processor->getView('ogrn'); ?>
                </div>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_REQISITES_BANK_OGRN, $model->revision_number); ?>
            </div>
            <div class="row-record">
                <label>Юридический адрес</label>
                <div class="row-record-data">
                    <?php echo $view_processor->getView('legal_address'); ?>
                </div>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_REQISITES_BANK_UR_ADDRESS, $model->revision_number); ?>
            </div>
            <div class="row-record check-address">
                <label>
                    <div class="chekBox act">
                        <span></span>
                        Совпадает
                        <input type="hidden" value="1">
                    </div>
                </label>
            </div>
            <div class="row-record check-address">
                <label>Фактический адрес</label>
                <div class="row-record-data">
                    <?php echo $view_processor->getView('fact_address'); ?>
                </div>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_REQISITES_BANK_FACT_ADDRESS, $model->revision_number); ?>
            </div>
        </div>
    </div>
    <?php $this->block('registry/blocks/form-buttons'); ?>

</div>