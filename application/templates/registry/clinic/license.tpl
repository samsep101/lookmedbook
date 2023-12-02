<script type="text/javascript" xmlns="http://www.w3.org/1999/html">
    $(document).ready(function(){
        window.validation_span = true;
        var form_controller = new ClinicLicenseFormController('<?php echo session_name();?>','<?php echo session_id(); ?>');
        form_controller.setContainer('#clinic-license-form');
        form_controller.init('<?php echo $model_name; ?>', <?php echo $entry_id; ?>);
        form_controller.clinic_id = <?php echo $entry_id; ?>;

        <?php if (!RegistryAccessHelper::hasAccessToEdit($model->moderate_status_id)): ?>
            form_controller.lock();
            $('#file_upload').css('z-index', '0');
        <?php endif; ?>
    });
</script>

<?php $this->block('registry/blocks/moderate_status'); ?>

<div class="moderated-form" id="clinic-license-form">
    <div class="fields-block flo">
        <p>Лицензия клиники</p>
        <div class="fields-block-inner grey-inner">
            <div class="row-record">
                <label>№ лицензии</label>
                <div class="row-record-data">
                    <?php echo $view_processor->getView('license_number'); ?><br />
                    <span class="ex_registry">Пример: 77-01-004841</span>
                </div>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_FULL_NAME, $model->revision_number); ?>
            </div>
            <div class="row-record">
                <label></label>
                <div class="education-parameter short-parameter">
                    <label>Дата выдачи</label>
                    <?php echo $view_processor->getView('license_issue_date'); ?>
                </div>
                <div class="education-parameter short-parameter">
                    <label>Дата окончания</label>
                    <?php echo $view_processor->getView('license_validity_date'); ?>
                </div>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_LICENSE_DATE, $model->revision_number); ?>
            </div>
        </div>
    </div>
    <div class="fields-block flo">
        <p>Копия лицензии</p>
        <div class="fields-block-inner white-inner">
            <div class="row-record add_license_block">

                <form style="display: block" id="upload_image_form">
                    <div id="queue"></div>
                    <ul class="refinement">
                        <li>! Вам необходимо добавить все отсканированные копии лицензии</li>
                    </ul>
                    <input id="file_upload" name="file_upload" type="file" multiple="true" class="btn-appoint-license">

                </form>

                <!--<input class="btn-appoint-license" type="submit" name="add_license_copy" value="Добавить копию лицензии">-->


                <div class="license_images" data-holder-for="clinic_license_image">
                    <ul>
                        <?php $list_view_processor = new FormListViewProcessor('clinic_license_image', $license_images); ?>
                        <?php $list_view_processor->setViewTemplate('registry/form_template/license-image'); ?>
                        <?php echo $list_view_processor->getView(); ?>
                    </ul>

                </div>
            </div>
            <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_LICENSE_IMAGE, $model->revision_number); ?>
        </div>
    </div>
    <?php if ($specializations): ?>
        <div class="fields-block flo">
            <p>Специализация клиники</p>
            <div class="fields-block-inner white-inner">
                <div class="specialties-block" data-holder-for="specialization_to_clinic">
                    <?php foreach ($specializations as $specialization): ?>
                        <div class="clinic_specialty">
                            <label>
                                <?php
                                    if ($specialization->is_selected){
                                        $class = 'act';
                                        $value = 1;
                                    } else {
                                        $class = '';
                                        $value = 0;
                                    }
                                ?>
                                <div data-name="specialization_to_clinic" class="specialization_to_clinic">
                                    <input type="hidden" name="specialization_id" value="<?php echo $specialization->getId() ?>" />
                                    <div class="chekBox <?php echo $class; ?>">
                                        <span></span>
                                        <?php echo $specialization->name; ?>
                                        <input type="hidden" name="is_selected" value="<?php echo $value; ?>" />
                                    </div>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_LICENSE_SPECIALTIES, $model->revision_number); ?>
    <?php $this->block('registry/blocks/form-buttons'); ?>

</div>