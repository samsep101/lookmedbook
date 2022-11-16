<?php
    /**
     * @var ModerateSpecializationToClinicModel[] $specializations
     * @var  ModerateSpecialtyToClinicModel $model
     * @var  ModerateClinicPricelistModel $pricelist_files
     * @var int $entry_id
     * @var ClinicModel $clinic
     */
?>
<?php if ($specializations): ?>
<script type="text/javascript">
    $(document).ready(function(){
        window.validation_span = true;
        var form_controller = new ClinicServicesFormController();
        form_controller.setContainer('#services-form');
        form_controller.init(null, <?php echo $entry_id; ?>);
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


<div class="moderated-form" id="services-form">

    <div class="fields-block flo">

        <p>Услуги клиники</p>
        <div class="prices-block flo">
            <div class="add-prices-block">
                <form style="display: block" id="upload_image_form">
                    <div id="queue"></div>
                    <input id="file_upload" name="file_upload" type="file" multiple="true" class="btn-appoint-license">
                </form>

                <div class="pricelist" data-holder-for="clinic_pricelist">
                    <ul>
                        <?php $list_view_processor = new FormListViewProcessor('clinic_pricelist', $pricelist_files); ?>
                        <?php $list_view_processor->setViewTemplate('registry/form_template/clinic_pricelist'); ?>
                        <?php echo $list_view_processor->getView(); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="actual-dt clinic-actual-dt">
            <span class="text">
                <?php if($clinic->dt_price_actual): ?>
                    Цены актуальны на <?php echo DateHelper::format($clinic->dt_price_actual, 'full'); ?>
                <?php else: ?>
                    Дата актульности цен не указана
                <?php endif; ?>

            </span>
            <input class="btn-appoint btn-appoint-small set-dt-actual" type="button" value="Обновить" />
            <script type="text/javascript">
                $(document).ready(function(){
                    var actual_controller = new ClinicPriceActualController();
                    actual_controller.clinic_id = <?php echo $clinic->getId(); ?>;
                    actual_controller.init();
                });
            </script>
        </div>
        <div class="hint">
            0 - означает, что услуга бесплатна
        </div>
            <div class="specialties-block">
                <?php $specialty_register = array(); ?>

                <?php foreach($specializations as $specialization): ?>
                    <?php if (!$specialization->is_selected)
                            continue; ?>
                    <div class="first-level-specialty-block flo specialization-block specialization-block-<?php echo $specialization->getId(); ?>">
                        <p class="first-level-specialty-name"><?php echo StringHelper::startProposalWord($specialization->name); ?></p>
                        <div class="second-level-specialties">
                            <?php if ($specialization->childs): ?>
                                <?php $number = 0; ?>
                                    <?php foreach($specialization->childs as $child_specialty): ?>
                                        <?php if ($number % 2 == 0): ?>
                                            <div class="second-level-double flo">
                                        <?php endif; ?>

                                        <div class="second-level-specialty">
                                            <div data-holder-for="specialty_to_clinic">
                                                <div data-name="specialty_to_clinic">
                                                    <input type="hidden" name="specialty_id" value="<?php echo $child_specialty->id;
                                                    ?>" />
                                                    <?php $class = $child_specialty->is_selected ? 'act' : '' ; ?>
                                                    <div class="chekBox <?php echo $class; ?> second-level-specialty-name">
                                                        <span data-specialty-id="<?php echo $child_specialty->getId(); ?>"></span>
                                                        <?php echo StringHelper::startProposalWord($child_specialty->name); ?>
                                                        <?php if (!isset($specialty_register[$child_specialty->getId()])): ?>
                                                            <input type="hidden" name="is_selected" value="<?php echo $child_specialty->is_selected; ?>">
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="third-level-specialty third-level-specialty-<?php echo $child_specialty->id; ?>">
                                                <div data-holder-for="purpose_of_visit_to_clinic">
                                                    <?php if ($child_specialty->purposes_of_visit): ?>
                                                        <?php foreach($child_specialty->purposes_of_visit as $purpose_of_visit): ?>
                                                            <div data-name="purpose_of_visit_to_clinic">
                                                                <?php if (!isset($specialty_register[$child_specialty->getId()])): ?>
                                                                <input type="hidden" name="specialty_id" value="<?php echo $child_specialty->id;?>" />
                                                                <?php endif; ?>
                                                                <input type="hidden" name="purpose_of_visit_id" value="<?php echo $purpose_of_visit->id; ?>" />
                                                                <?php
                                                                    $class = ($child_specialty->is_selected && ($purpose_of_visit->is_selected || $purpose_of_visit->is_main)) ? 'act' : '';
                                                                    if ($purpose_of_visit->is_main)
                                                                        $class .= ' bold main';
                                                                ?>



                                                                <div class="chekBox <?php echo $class; ?>"
                                                                    <?php if ($purpose_of_visit->is_main): ?>
                                                                    data-disabled="true"
                                                                    <?php endif; ?>
                                                                ">
                                                                    <span></span>
                                                                    <?php echo $purpose_of_visit->name; ?>
                                                                    <?php if (!isset($specialty_register[$child_specialty->getId()])): ?>
                                                                        <input type="hidden"  name="is_selected" value="<?php echo ($purpose_of_visit->is_selected || $purpose_of_visit->is_main ) ? 1 : 0 ; ?>">
                                                                    <?php endif; ?>
                                                                </div>

                                                                <input style="float: right" type="text" size="10" name="visit_price" value="<?php echo $purpose_of_visit->visit_price; ?>"/>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (($number % 2 == 1) && $number): ?>
                                            </div>
                                        <?php endif;?>
                                        <?php $number++; ?>
                                        <?php $specialty_register[$child_specialty->getId()] = true; ?>
                                    <?php endforeach; ?>
                                <?php if ($number % 2 == 1): ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_SERVICES_SPECIALTIES, $revision_number); ?>
            </div>
    </div>

<?php $this->block('registry/blocks/form-buttons'); ?>

</div>
<?php else: ?>
    Список специализаций клиники не указан.
<?php endif ;?>
