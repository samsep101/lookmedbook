<?php $this->container = '#university-form'; ?>

<script type="text/javascript">
    $(document).ready(function(){
        var form_controller = new DoctorEducationFormController();
        form_controller.setContainer('<?php echo $this->container; ?>');
        form_controller.init('<?php echo $model_name; ?>', <?php echo $entry_id; ?>);

        <?php if (!RegistryAccessHelper::hasAccessToEdit($model->moderate_status_id)): ?>
            form_controller.lock();
        <?php endif; ?>
    });
</script>

<?php $this->block('registry/blocks/moderate_status'); ?>

<div id="university-form">

    <div class="cab-page-3 pink-education sova">
        <div class="nav-3">
            <ul class="education-headers">
                <li class="ui-state-active"> <a class="first-tab-header">Высшее медицинское образование</a></li>
                <li><a class="second-tab-header">Среднее специальное</a></li>
            </ul>
        </div>
        <div class="fields-block-inner">
            <div class="education-inner">
                <div class="first-tab">
                    <div class="education-parameter institution-list-1 high-university long-parameter">
                        <label>ВУЗ</label>
                        <?php if ($universities):?>
                            <select name="form[high_education_university_id]">
                                <option value=""></option>
                                <?php foreach ($universities as $university):?>
                                    <option <?php if ($model->high_education_university_id == $university->getId()) {?>selected<?php }?> value="<?php echo $university->getId(); ?>"><?php echo $university->name; ?></option>
                                <?php endforeach?>
                            </select>
                        <?php endif?>
                    </div>
                    <div class="education-parameter button">
                        <input type="button" class="add-education" data-type="1" value="Добавить">
                    </div>
                    <div class="education-parameter short-parameter">
                        <label>Год окончания</label>
                        <!--<input type="text" value="<?php echo $model->high_education_end_year; ?>" name="form[high_education_end_year]">-->
                        <?php echo $view_processor->getView('high_education_end_year'); ?>
                    </div>
                    <div class="education-parameter short-parameter">
                        <label>Диплом по специальности</label>
                        <?php echo $view_processor->getView('high_education_specialty_id'); ?>
                    </div>
                </div>

                <div class="second-tab">
                    <div class="education-parameter institution-list-2 long-parameter">
                        <label>Учебное заведение</label>
                        <?php if ($secondary_universities):?>
                            <select name="form[secondary_education_university_id]">
                                <option value=""></option>
                                <?php foreach ($secondary_universities as $secondary_university):?>
                                    <option <?php if ($model->secondary_education_university_id == $secondary_university->getId()) {?>selected<?php }?> value="<?php echo $secondary_university->getId(); ?>"><?php echo $secondary_university->name; ?></option>
                                <?php endforeach?>
                            </select>
                        <?php endif?>
                    </div>
                    <div class="education-parameter button">
                        <input type="button" class="add-education" data-type="2" value="Добавить">
                    </div>
                    <div class="education-parameter short-parameter">
                        <label>Год окончания</label>
                        <?php echo $view_processor->getView('secondary_education_end_year'); ?>
                    </div>
                    <div class="education-parameter short-parameter">
                        <label>Диплом по специальности</label>
                        <?php echo $view_processor->getView('secondary_education_specialty_id'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cab-page-3 copying grey-education sova">
        <div class="nav-3">
            <ul class="education-headers">
                <li class="ui-state-active"> <a class="first-tab-header">Интернатура</a></li>
                <li><a class="second-tab-header">Ординатура</a></li>
            </ul>
        </div>
        <div class="fields-block-inner">
            <div class="education-inner" data-holder-for="doctor_education">
                <div class="first-tab">
                    <?php if ($doctor_educations): ?>
                        <?php foreach($doctor_educations as $doctor_education): ?>
                            <?php if ($doctor_education->doctor_education_type_id == DoctorEducationModel::INTERNSHIP): ?>
                                <div class="internship-single-block" data-name="doctor-education">
                                    <input style="display:none" name="doctor_education_type_id" value="<?php echo DoctorEducationModel::INTERNSHIP; ?>">
                                    <div class="education-parameter short-parameter">
                                        <label>Специализация</label>
                                        <?php if ($specialties):?>
                                            <select name="specialty_id">
                                                <option value=""></option>
                                                <?php foreach ($specialties as $specialty):?>
                                                    <option <?php if ($doctor_education->specialty_id == $specialty->getId()) {?>selected<?php }?> value="<?php echo $specialty->getId(); ?>"><?php echo $specialty->name; ?></option>
                                                <?php endforeach?>
                                            </select>
                                        <?php endif?>
                                    </div>
                                    <div class="education-parameter short-parameter">
                                        <label>Год окончания</label>
                                        <input type="text" name="end_year" value="<?php echo $doctor_education->end_year; ?>">
                                    </div>
                                    <div class="education-parameter institution-list-1 long-parameter">
                                        <label>ВУЗ</label>
                                        <?php if ($universities):?>
                                            <select name="university_id">
                                                <option value=""></option>
                                                <?php foreach ($universities as $university):?>
                                                    <option <?php if ($doctor_education->university_id == $university->getId()) {?>selected<?php }?> value="<?php echo $university->getId(); ?>"><?php echo $university->name; ?></option>
                                                <?php endforeach?>
                                            </select>
                                        <?php endif?>
                                    </div>
                                    <div class="education-parameter button">
                                        <input type="button" class="add-education" data-type="1" value="Добавить новый ВУЗ">
                                    </div>
                                    <div class="education-parameter copy-button">
                                        <input type="button" class="copy-education" value="Добавить">
                                    </div>
                                    <div class="education-parameter delete-button">
                                        <input type="button" class="delete-education" value="Удалить">
                                    </div>
                                </div>
                            <?php endif?>
                        <?php endforeach?>
                    <?php else:?>
                        <div class="internship-single-block" data-name="doctor-education">
                            <input style="display:none" name="doctor_education_type_id" value="<?php echo DoctorEducationModel::INTERNSHIP; ?>">
                            <div class="education-parameter short-parameter">
                                <label>Специализация</label>
                                <?php if ($specialties):?>
                                    <select name="specialty_id">
                                        <option value=""></option>
                                        <?php foreach ($specialties as $specialty):?>
                                            <option value="<?php echo $specialty->getId(); ?>"><?php echo $specialty->name; ?></option>
                                        <?php endforeach?>
                                    </select>
                                <?php endif?>
                            </div>
                            <div class="education-parameter short-parameter">
                                <label>Год окончания</label>
                                <input type="text" name="end_year">
                            </div>
                            <div class="education-parameter institution-list-1 long-parameter">
                                <label>ВУЗ</label>
                                <?php if ($universities):?>
                                    <select name="university_id">
                                        <option value=""></option>
                                        <?php foreach ($universities as $university):?>
                                            <option value="<?php echo $university->getId(); ?>"><?php echo $university->name; ?></option>
                                        <?php endforeach?>
                                    </select>
                                <?php endif?>
                            </div>
                            <div class="education-parameter button">
                                <input type="button" class="add-education" data-type="1" value="Добавить новый ВУЗ">
                            </div>
                            <div class="education-parameter copy-button">
                                <input type="button" class="copy-education" value="Добавить">
                            </div>
                            <div class="education-parameter delete-button">
                                <input type="button" class="delete-education" value="Удалить">
                            </div>
                        </div>
                    <?php endif?>
                </div>

                <div class="second-tab">
                    <?php if ($doctor_educations): ?>
                        <?php foreach($doctor_educations as $doctor_education): ?>
                            <?php if ($doctor_education->doctor_education_type_id == DoctorEducationModel::TRAINEESHIP): ?>
                                <div class="traineeship-single-block" data-name="doctor-education">
                                    <input style="display:none" name="doctor_education_type_id" value="<?php echo DoctorEducationModel::TRAINEESHIP; ?>">
                                    <div class="education-parameter short-parameter">
                                        <label>Специализация</label>
                                        <?php if ($specialties):?>
                                            <select name="specialty_id">
                                                <option value=""></option>
                                                <?php foreach ($specialties as $specialty):?>
                                                    <option <?php if ($doctor_education->specialty_id == $specialty->getId()) {?>selected<?php }?> value="<?php echo $specialty->getId(); ?>"><?php echo $specialty->name; ?></option>
                                                <?php endforeach?>
                                            </select>
                                        <?php endif?>
                                    </div>
                                    <div class="education-parameter short-parameter">
                                        <label>Год окончания</label>
                                        <input type="text" name="end_year" value="<?php echo $doctor_education->end_year; ?>">
                                    </div>
                                    <div class="education-parameter institution-list-1 long-parameter">
                                        <label>ВУЗ</label>
                                        <?php if ($universities):?>
                                            <select name="university_id">
                                                <option value=""></option>
                                                <?php foreach ($universities as $university):?>
                                                    <option <?php if ($doctor_education->university_id == $university->getId()) {?>selected<?php }?> value="<?php echo $university->getId(); ?>"><?php echo $university->name; ?></option>
                                                <?php endforeach?>
                                            </select>
                                        <?php endif?>
                                    </div>
                                    <div class="education-parameter button">
                                        <input type="button" class="add-education" data-type="1" value="Добавить новый ВУЗ">
                                    </div>
                                    <div class="education-parameter copy-button">
                                        <input type="button" class="copy-education" value="Добавить">
                                    </div>
                                    <div class="education-parameter delete-button">
                                        <input type="button" class="delete-education" value="Удалить">
                                    </div>
                                </div>
                            <?php endif?>
                        <?php endforeach?>
                    <?php else:?>
                        <div class="traineeship-single-block" data-name="doctor-education">
                            <div class="education-parameter short-parameter">
                                <label>Специализация</label>
                                <?php if ($specialties):?>
                                    <select name="specialty_id">
                                        <option value=""></option>
                                        <?php foreach ($specialties as $specialty):?>
                                            <option value="<?php echo $specialty->getId(); ?>"><?php echo $specialty->name; ?></option>
                                        <?php endforeach?>
                                    </select>
                                <?php endif?>
                            </div>
                            <div class="education-parameter short-parameter">
                                <label>Год окончания</label>
                                <input type="text" name="end_year">
                            </div>
                            <div class="education-parameter institution-list-1 long-parameter">
                                <label>ВУЗ</label>
                                <?php if ($universities):?>
                                    <select name="university_id">
                                        <option value=""></option>
                                        <?php foreach ($universities as $university):?>
                                            <option value="<?php echo $university->getId(); ?>"><?php echo $university->name; ?></option>
                                        <?php endforeach?>
                                    </select>
                                <?php endif?>
                            </div>
                            <div class="education-parameter button">
                                <input type="button" class="add-education" data-type="1" value="Добавить новый ВУЗ">
                            </div>
                            <div class="education-parameter copy-button">
                                <input type="button" class="copy-education" value="Добавить">
                            </div>
                            <div class="education-parameter delete-button">
                                <input type="button" class="delete-education" value="Удалить">
                            </div>
                        </div>
                    <?php endif?>
                </div>
            </div>
        </div>
    </div>

    <div class="cab-page-3 copying grey-education sova">
        <div class="nav-3">
            <ul class="education-headers">
                <li class="ui-state-active"> <a class="first-tab-header">Сертификаты</a></li>
            </ul>
        </div>
        <div class="fields-block-inner">
            <div class="education-inner">
                <div class="first-tab" data-holder-for="doctor_certificate">
                    <?php if ($doctor_certificates): ?>
                        <?php foreach($doctor_certificates as $doctor_certificate): ?>
                            <div class="certificate-single-block flo" data-name="doctor-certificate">
                                <div class="education-parameter short-parameter">
                                    <label>Специализация</label>
                                    <?php if ($specialties):?>
                                        <select name="specialty_id">
                                            <option value=""></option>
                                            <?php foreach ($specialties as $specialty):?>
                                                <option <?php if ($doctor_certificate->specialty_id == $specialty->getId()) {?>selected<?php }?> value="<?php echo $specialty->getId(); ?>"><?php echo $specialty->name; ?></option>
                                            <?php endforeach?>
                                        </select>
                                    <?php endif?>
                                </div>
                                <div class="education-parameter shortest-parameter">
                                    <label>Дата выдачи</label>
                                    <input type="text" name="date" value="<?php if ($doctor_certificate->date) echo DateViewHelper::date($doctor_certificate->date,'dd-mm-yyyy')?>">
                                </div>
                                <div class="education-parameter shortest-parameter">
                                    <label>Срок действия, лет</label>
                                    <input type="text" name="duration" value="<?php echo $doctor_certificate->duration; ?>">
                                </div>
                                <div class="education-parameter institution-list-2 long-parameter">
                                    <label>Учебное заведение</label>
                                    <?php if ($secondary_universities):?>
                                        <select name="university_id">
                                            <option value=""></option>
                                            <?php foreach ($secondary_universities as $secondary_university):?>
                                                <option <?php if ($doctor_certificate->university_id == $secondary_university->getId()) {?>selected<?php }?> value="<?php echo $secondary_university->getId(); ?>"><?php echo $secondary_university->name; ?></option>
                                            <?php endforeach?>
                                        </select>
                                    <?php endif?>
                                </div>
                                <div class="education-parameter button">
                                    <input type="button" class="add-education" data-type="2" value="Добавить учебное заведение">
                                </div>
                                <div class="education-parameter copy-button">
                                    <input type="button" class="copy-education" value="Добавить">
                                </div>
                                <div class="education-parameter delete-button">
                                    <input type="button" class="delete-education" value="Удалить">
                                </div>
                            </div>
                        <?php endforeach?>
                    <?php else:?>
                        <div class="certificate-single-block flo" data-name="doctor-certificate">
                            <div class="education-parameter short-parameter">
                                <label>Специализация</label>
                                <?php if ($specialties):?>
                                    <select name="specialty_id">
                                        <option value=""></option>
                                        <?php foreach ($specialties as $specialty):?>
                                            <option value="<?php echo $specialty->getId(); ?>"><?php echo $specialty->name; ?></option>
                                        <?php endforeach?>
                                    </select>
                                <?php endif?>
                            </div>
                            <div class="education-parameter shortest-parameter">
                                <label>Дата выдачи</label>
                                <input type="text" name="date">
                            </div>
                            <div class="education-parameter shortest-parameter">
                                <label>Срок действия, лет</label>
                                <input type="text" name="duration">
                            </div>
                            <div class="education-parameter institution-list-2 long-parameter">
                                <label>Учебное заведение</label>
                                <?php if ($secondary_universities):?>
                                    <select name="university_id">
                                        <option value=""></option>
                                        <?php foreach ($secondary_universities as $secondary_university):?>
                                            <option value="<?php echo $secondary_university->getId(); ?>"><?php echo $secondary_university->name; ?></option>
                                        <?php endforeach?>
                                    </select>
                                <?php endif?>
                            </div>
                            <div class="education-parameter button">
                                <input type="button" class="add-education" data-type="2" value="Добавить учебное заведение">
                            </div>
                            <div class="education-parameter copy-button">
                                <input type="button" class="copy-education" value="Добавить">
                            </div>
                            <div class="education-parameter delete-button">
                                <input type="button" class="delete-education" value="Удалить">
                            </div>
                        </div>
                    <?php endif?>
                </div>
            </div>
        </div>
    </div>

    <?php $this->block('registry/blocks/form-buttons'); ?>

</div>