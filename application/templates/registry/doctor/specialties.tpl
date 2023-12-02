<script type="text/javascript">
    $(document).ready(function(){
        window.validation_span = true;
        var form_controller = new DoctorSpecialtiesFormController(<?php echo (int)$entry_id; ?>,<?php echo (int)$clinic_id; ?>);
        form_controller.setContainer('#doctor-specialties-form');
        form_controller.entry_id  = <?php echo $entry_id; ?>;
        form_controller.doctor_id =  <?php echo $entry_id; ?>;
        form_controller.clinic_id = <?php echo (int)$clinic_id; ?>;
        form_controller.init(null, <?php echo $entry_id; ?>);

        <?php if (!RegistryAccessHelper::hasAccessToEdit($model->moderate_status_id)): ?>
            form_controller.lock();
        <?php endif; ?>
    });
</script>

<?php $this->block('registry/blocks/moderate_status'); ?>

<div class="fields-block sova flo"  id="doctor-specialties-form">
    <p>Специализации и цены</p>
    <?php $this->block('registry/doctor/blocks/select_clinic'); ?>
            <?php if ($selected_specialties): ?>
                <?php foreach($selected_specialties as $current_specialty): ?>
                    <div class="fields-block-inner light-blue-inner" style="margin-bottom: 40px;    ">
                        <div class="row-record">
                            <label>Специализация</label>
                            <div class="row-record-data" data-holder-for="doctor_specialty_to_clinic">
                                <div data-name="doctor_specialty_to_clinic">
                                    <select name="specialty_id">
                                        <option value=""></option>
                                        <?php if ($specialties): ?>
                                            <?php foreach($specialties as $specialty): ?>
                                                <?php $selected = ($specialty->id == $current_specialty->specialty_id) ? 'selected="selected"' : ''; ?>
                                                <option value="<?php echo $specialty->id; ?>" <?php echo $selected; ?>><?php echo $specialty->name; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>

                                    <input type="hidden" name="clinic_id" value="<?php echo $clinic_id; ?>" />
                                </div>
                            </div>
                            <span class="btn-4" style="float: right; margin-right: 10px;"><input class="yes-delete-doctor-button" type="submit" value="Удалить" data-specialty-id="<?php echo $current_specialty->specialty_id; ?>"></span>
                        </div>
                        <div class="purpose-of-visit-block" id="purposes-block"></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="fields-block-inner light-blue-inner" style="margin-bottom: 40px;    ">
                    <div class="row-record">
                        <label>Специализация</label>
                        <div class="row-record-data" data-holder-for="doctor_specialty_to_clinic">
                            <div data-name="doctor_specialty_to_clinic">
                                <select name="specialty_id">
                                    <option value=""></option>
                                    <?php if ($specialties): ?>
                                        <?php foreach($specialties as $specialty): ?>
                                            <option value="<?php echo $specialty->id; ?>"><?php echo $specialty->name; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>

                                <input type="hidden" name="clinic_id" value="<?php echo $clinic_id; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="purpose-of-visit-block" id="purposes-block"></div>
                </div>
            <?php endif; ?>

    <div style="display:none" id="new-specialty">
        <div class="fields-block-inner light-blue-inner" style="margin-bottom: 40px;    ">
            <div class="row-record">
                <label>Специализация</label>
                <div class="row-record-data" data-holder-for="doctor_specialty_to_clinic">
                    <div data-name="doctor_specialty_to_clinic">
                        <select name="specialty_id">
                            <option value=""></option>
                            <?php if ($specialties): ?>
                                <?php foreach($specialties as $specialty): ?>
                                    <option value="<?php echo $specialty->id; ?>"><?php echo $specialty->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>

                        <input type="hidden" name="clinic_id" value="<?php echo $clinic_id; ?>" />
                    </div>
                </div>
            </div>
            <div class="purpose-of-visit-block" id="purposes-block"></div>
        </div>
    </div>
    <div style="clear: both;">
        <input type="button" value="Добавить специализацию" onclick="
            var container = $($('#new-specialty').html());
            $(this).parent().before(container);
            var purpose_controller = new PurposesBlockController();
            purpose_controller.container_object = container;
            purpose_controller.clinic_id = <?php echo $clinic_id; ?>;
            purpose_controller.doctor_id = <?php echo $entry_id; ?>;
            purpose_controller.init();
        " />
    </div>
    <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::DOCTOR_SPECIALTIES, $model->revision_number); ?>
    <?php $this->block('registry/blocks/form-buttons'); ?>
</div>

