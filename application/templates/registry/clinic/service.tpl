<script type="text/javascript">
    $(document).ready(function(){
        window.validation_span = true;
        var form_controller = new ClinicServiceFormController(<?php echo (isset($entry_id)) ? $entry_id : 0; ?>);
        form_controller.setContainer('#service-form');
        form_controller.init('<?php echo $model_name; ?>', <?php echo $entry_id; ?>);
        form_controller.clinic_id = <?php echo $entry_id; ?>;
        <?php if (!RegistryAccessHelper::hasAccessToEdit($model->revision_info->moderate_status_id)): ?>
            form_controller.lock();
        <?php endif; ?>
    });
</script>

<div class="work-status">
    <?php if ($model->revision_info->moderate_status_id): ?>
        <?php echo ModerateStatusViewHelper::view($model->revision_info->moderate_status_id); ?>
    <?php endif; ?>
</div>

<div class="fields-block pick-a-pic flo">
    <p>Сервис</p>
    <div class="fields-block-inner white-inner">

        <div class="moderated-form" id="service-form">
            <p>Данная информация поможет подробнее рассказать о клинике, и приведет пациентов, которые нуждаются именно в Ваших услугах</p>

            <div class="fields-block-inner pink-inner">
                <div class="row-record" data-holder-for="feature_to_clinic">
                    <ul class="horizontal phones-list">
                        <?php $list_view_processor = new FormListViewProcessor('feature_to_clinic', $features); ?>
                        <?php $list_view_processor->setViewTemplate('registry/form_template/feature_form'); ?>
                        <?php echo $list_view_processor->getView(); ?>
                    </ul>
                </div>

                <div class="row-record">
                    <label>Способ оплаты</label>
                    <div class="row-record-data">
                        <ul class="checkbox-list horizontal">
                            <!--<li>
                                <?php echo $view_processor->getView('is_cache_pay'); ?>
                            </li>-->
                            <li>
                                <?php echo $view_processor->getView('is_card_pay'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_FEATURE, $revision_number); ?>

            <div class="new_features_to_clinic fields-block-inner">
                <label class="validate_rules">макс 70 символов</label>
                <input type="text" id="new_feature" placeholder="Укажите свой дополнительный сервис">
                <img class="plus" src="/media/images/registry/plus-button.png">
                <ul class="new_features_list">
                    <?php if (count($suggested_features)): ?>
                        <?php foreach ($suggested_features as $suggested_feature): ?>
                            <li><span class="feature"><?php echo $suggested_feature->feature_name; ?></span><span class="remove-feature">X</span></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <?php $this->block('registry/blocks/form-buttons'); ?>
        </div>
    </div>
</div>

