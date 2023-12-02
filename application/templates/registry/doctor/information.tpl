<?php
	/**
	 * @var View $this
	 * @var ModerateDoctorInformationModel $model
	 * @var int $clinic_id
	 * @var int $entry_id
	 * @var string $model_name
	 * @var FormViewProcessor $view_processor
	 * @var string $this->container
	 * @var bool $only_children
	 * @var bool $only_adult
	 */
?>
<?php $this->container = '#information-doctor-form'; ?>
<?php if ($model->sex_id) $sex = $model->sex_id; else $sex = 0; ?>

<script type="text/javascript">
    $(document).ready(function(){
        window.validation_span = true;
        var form_controller = new DoctorInformationFormController("<?php echo $this->container;?>", <?php echo $sex; ?>);
        form_controller.setContainer('#information-doctor-form');
        form_controller.clinic_id = <?php echo (int)$clinic_id; ?>;
        form_controller.init('<?php echo $model_name; ?>', <?php echo $entry_id; ?>);

        <?php if (!RegistryAccessHelper::hasAccessToEdit($model->moderate_status_id)): ?>
            form_controller.lock();
        <?php endif; ?>
    });
</script>

    <?php $this->block('registry/blocks/moderate_status'); ?>

    <div id="information-doctor-form">

        <?php if (isset($clinic_id)): ?>
            <input type="hidden" name="clinic_id" value="<?php echo $clinic_id; ?>" />
        <?php endif; ?>

        <div class="fields-block  flo sova">
            <?php if (RegistryAccessHelper::checkManagerAuth() && $_SERVER['REQUEST_URI'] == '/registry/doctor/add?clinic_id='.$clinic_id): ?>
                <p>Добавление врача к клинике</p>
            <?php else:?>
                <p>О враче</p>
            <?php endif;?>
            <div class="fields-block-inner pink-inner information">
                <?php if (RegistryAccessHelper::checkManagerAuth() && $_SERVER['REQUEST_URI'] == '/registry/doctor/add?clinic_id='.$clinic_id): ?>
                    <div class="row-header">
                        <label>Выберите врача из списка:</label>
                    </div>
                    <div class="row-record">
                        <label>Фильтр врачей</label>
                        <div class="row-record-data">
                            <input class="doctor-filter">
                        </div>
                    </div>
                    <div class="row-record">
                        <label>Врачи</label>
                        <div class="row-record-data">
                            <select style="width: 558px" name="form[full_name]" class="doctor-list-data">
                            </select>
                        </div>
                        <input type="hidden" name="form[clinic_id]" value="<?php echo $clinic_id; ?>">
                    </div>
                    <div class="row-header">
                        <label>Введите данные нового врача:</label>
                    </div>
                <?php endif; ?>

                <div class="row-record">
                    <label>Фамилия</label>
                    <div class="row-record-data">
                        <?php echo $view_processor->getView('last_name'); ?>
                    </div>
                    <?php if (!isset($add_flag)): ?>
                    <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::DOCTOR_INFORMATION_LAST_NAME, $model->revision_number); ?>
                    <?php endif; ?>
                </div>

                <div class="row-record">
                    <label>Имя</label>
                    <div class="row-record-data">
                        <?php echo $view_processor->getView('first_name'); ?>
                    </div>
                    <?php if (!isset($add_flag)): ?>
                    <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::DOCTOR_INFORMATION_FIRST_NAME, $model->revision_number); ?>
                    <?php endif; ?>
                </div>

                <div class="row-record">
                    <label>Отчество</label>
                    <div class="row-record-data">
                        <?php echo $view_processor->getView('second_name'); ?>
                    </div>
                    <?php if (!isset($add_flag)): ?>
                    <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::DOCTOR_INFORMATION_SECOND_NAME, $model->revision_number); ?>
                    <?php endif; ?>
                </div>

                <?php if (RegistryAccessHelper::checkManagerAuth()): ?>
                    <div class="row-record">
                        <label>Рейтинг</label>
                        <div class="row-record-data">
                            <?php echo $view_processor->getView('rate'); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row-record">
                    <label>Стаж работы</label>
                    <div class="row-record-data">
                        <?php echo $view_processor->getView('work_experience'); ?>
                    </div>
                </div>

                <div class="row-record doctor-gender">
                    <label>Пол</label>
                    <?php echo $view_processor->getView('sex_id'); ?>
                </div>
                <div class="doctor-type">
                    <span class="doctor-type-name">Врач для</span>

                    <ul class="horizontal block" >
                        <li class="adult <?php echo (isset($only_adult) && $only_adult) ? 'blocked' : ''; ?>"><?php echo $view_processor->getView('is_adult'); ?></li>
                        <li class="li-right pregnant"><?php echo $view_processor->getView('is_pregnant'); ?></li>
                        <li class="li-right pregnant"><?php echo $view_processor->getView('is_leave_the_house'); ?></li>

                    </ul>
                    <ul class="horizontal block" >
                        <li class="child <?php echo (isset($only_children) && $only_children) ? 'blocked' : ''; ?>"><?php echo $view_processor->getView('is_children'); ?></li>
                        <li class="li-right handicapped"><?php echo $view_processor->getView('is_handicapped'); ?></li>
                        <li class="li-right not_work"><?php echo $view_processor->getView('not_work'); ?></li>
                        <li class="li-right is_active"><?php echo $view_processor->getView('is_active'); ?></li>
                    </ul>
                </div>
                <?php if (!isset($add_flag)): ?>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::DOCTOR_INFORMATION_GENDER, $model->revision_number); ?>
                <?php endif; ?>

                <div class="row-record">
                </div>

                <input type="hidden" name="form[to_validate]" value="<?php if (isset($menu_active) && $menu_active == 'add_doctor') echo 1; else echo 0;?>">
            </div>
        </div>
        <div class="fields-block flo sova">
           <p class="black" style="clear: both; padding-top: 10px;">Информация о враче (не изменяется при импорте)</p>
            <div style="width: 620px; float: left; padding-left: 10px;"><?php echo $view_processor->getView('about_internal'); ?></div>
            <?php if (!isset($add_flag)): ?>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::DOCTOR_INFORMATION_ABOUT_INTERNAL, $model->revision_number); ?>
            <?php endif; ?>

            <p class="black">Опыт / Компетенции</p>
            <div class="fields-block-inner white-inner without-right-padding">
                <p class="after-p">Напишите в первых строчках наиболее интересную информацию о враче. Далее раскройте более подробно.
                    Это поможет Вам получить больше записей к врачу.</p>
                <!--<div class="registry-error" style="display: none">Опишите врача</div>-->
                <div style="width: 620px; float: left;"><?php echo $view_processor->getView('about'); ?></div>
                <ul class="refinement">
                    <li>! Структура описания:</li>
                    <li>1. Укажите информацию о местоположении</li>
                    <li>2. Укажите информацию о специализации</li>
                    <li> ... </li>
                </ul>
            </div>
            <?php if (!isset($add_flag)): ?>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::DOCTOR_INFORMATION_ABOUT, $model->revision_number); ?>
            <?php endif; ?>

            <p class="black" style="clear: both; padding-top: 10px;">Образование</p>
            <div style="width: 620px; float: left; padding-left: 10px;"><?php echo $view_processor->getView('education'); ?></div>
            <?php if (!isset($add_flag)): ?>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::DOCTOR_EDUCATION, $model->revision_number); ?>
            <?php endif; ?>
            <p class="black" style="clear: both; padding-top: 10px;">Курсы повышения квалификации</p>
            <div style="width: 620px; float: left; padding-left: 10px;"><?php echo $view_processor->getView('course'); ?></div>
            <?php if (!isset($add_flag)): ?>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::DOCTOR_COURSE, $model->revision_number); ?>
            <?php endif; ?>
            <p class="black" style="clear: both; padding-top: 10px;">Сертификаты</p>
            <div style="width: 620px; float: left; padding-left: 10px"><?php echo $view_processor->getView('certificate'); ?></div>
            <?php if (!isset($add_flag)): ?>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::DOCTOR_CERTIFICATE, $model->revision_number); ?>
            <?php endif; ?>
            <p class="black" style="clear: both; padding-top: 10px;">Ученые степени</p>
            <div style="width: 620px; float: left; padding-left: 10px"><?php echo $view_processor->getView('academic_title'); ?></div>
            <?php if (!isset($add_flag)): ?>
                <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::DOCTOR_ACADEMIC_TITLE, $model->revision_number); ?>
            <?php endif; ?>
        </div>

        <?php if(isset($only_adult) && $only_adult && $doctor->is_adult != '1'): ?>
            <div class="doctor-specialties-error adult">
                Ошибка! У врача есть специальности "Только для взрослых", но это не
                отмечено в профиле. Поставьте отметку "Врач для взрослых"
            </div>
        <?php endif; ?>

        <?php if(isset($only_children) && $only_children && $doctor->is_children != '1'): ?>
            <div class="doctor-specialties-error children">
                Ошибка! У врача есть специальности "Только для детей", но это не
                отмечено в профиле. Поставьте отметку "Врач для детей"
            </div>
        <?php endif; ?>

        <?php $this->block('registry/blocks/form-buttons'); ?>
    </div>
