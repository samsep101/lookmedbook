<?php
	/**
	 * @var ModerateClinicInformationModel $model
	 * @var string $model_name
	 * @var int $entry_id
	 * @var FormViewProcessor $view_processor
	 * @var View $this
	 * @var ModerateClinicPhoneModel[] $phones
     * @var CityModel $city
	 */
?>

<?php $this->container = '#information-form'; ?>

<script type="text/javascript">
    $(document).ready(function(){
        window.validation_span = true;
        var form_controller = new ClinicInformationFormController();
        form_controller.setContainer('<?php echo $this->container; ?>');

        <?php if ($model->city_id): ?>
        form_controller.setCityId(<?php echo $model->city_id; ?>);
        <?php endif; ?>

        form_controller.clinic_id = <?php echo $entry_id; ?>;
        form_controller.init('<?php echo $model_name; ?>',
                <?php echo $entry_id; ?>);

        <?php if (!RegistryAccessHelper::hasAccessToEdit($model->moderate_status_id)): ?>
            form_controller.lock();
        <?php endif; ?>

        window.form_controller = form_controller;
    });
</script>
<?php $this->block('registry/blocks/moderate_status'); ?>

<div class="moderated-form" id="information-form">
    <div class="fields-block sova flo">
        <p>О клинике</p>
        <div class="fields-block-inner pink-inner" style="width: 815px">
            <div class="row-record">
                <label>Название клиники</label>

                <div class="row-record-data">
                    <?php echo $view_processor->getView('name'); ?>
                </div>
                <div style="margin-left: 20px; display: block; float: left; margin-top: 10px;">
                    <?php echo $view_processor->getView('is_contract'); ?>
                </div>
                <div style="display: block; float: left; margin: 10px 0 0 44px;">
                    <?php echo $view_processor->getView('is_yandex_send'); ?>
                </div>
                <div style="display: block; float: right; margin: 10px 42px 0 20px;">
                    <?php echo $view_processor->getView('only_adult'); ?>
                </div>
                <div style="display: block; float: right; margin: 10px 0 0 0;">
                    <?php echo $view_processor->getView('only_children'); ?>
                </div>
                <div style="display: block; float: left; margin-top: 10px;">
                    <?php echo $view_processor->getView('not_work'); ?>
                </div>
                <?php if (Acl::userRole() != RoleModel::FREELANCE_MANAGER):?>
                <div style="margin-left: 20px; display: block; float: left; margin-top: 10px;">
                    <?php echo $view_processor->getView('is_active'); ?>
                </div>
                <div style="display: block; float: left; margin-top: 10px;">
                    <?php echo $view_processor->getView('redirect_list'); ?>
                </div>
                <?php endif;?>
             </div>

            <div class="row-record">
                <label>Полное наименование</label>
                <div class="row-record-data">
                    <?php echo $view_processor->getView('full_name'); ?>
                    <br />
                    <span class="ex_registry">Пример: ООО "Клиника"</span>
                </div>

            </div>




            <?php if (RegistryAccessHelper::checkManagerAuth()): ?>
                <div class="row-record">
                    <label>Рейтинг</label>
                    <div class="row-record-data">
                        <?php echo $view_processor->getView('rate'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>



    </div>

    <div class="fields-block sova flo infClinicTypes">
        <p>Типы клиник</p>
        <div class="fields-block-inner pink-inner"  style="width: 815px">
            <div class="row-record">
                <label>К какой категории можно отнести клинику?</label>
                <div class="row-record-data">
                    <?php echo $view_processor->getView('clinic_type_id', $clinic); ?>
                </div>
            </div>
            <div class="row-record">
                <label>Услуги</label>
                <div class="row-record-data">
                    <?php echo $view_processor->getView('clinic_service_id', $clinic); ?>
                </div>
            </div>
        </div>
    </div>


    <div class="fields-block sova flo">
        <p>Адрес</p>
        <div class="fields-block-inner pink-inner"  style="width: 815px">
            <div class="row-record">
                <div class="row-record-data">
                <ul class="horizontal">
                    <li><?php echo $view_processor->getView('address'); ?></li>
                </ul>
                    <div class="row-record">
                        <label>Почтовый индекс</label>
                        <input id="kladr-button" type="button" value="Получить индекс">
                        <div class="row-record-data">
                            <?php echo $view_processor->getView('postcode'); ?>
                        </div>
                    </div>
                    <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_ADDRESS, $model->revision_number); ?>
                   </div>
            </div>

            <?php $cache_id = 'clinic_metro_station_id_' .$clinic->city_id; ?>
            <?php $cache_id .= '_metro_station_ids'; ?>
            <?php if ($metro_stations): ?>
                <?php foreach($metro_stations as $station): ?>
                    <?php $cache_id .= ('_'.$station->metro_station_id); ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!$cache->start($cache_id,  'clinic_metro_block')): ?>
                <div class="row-record list-form">
                    <label style="width: 110px">Метро</label>
                    <div class="row-record-data metro-station-to-clinic" data-holder-for="metro_station_to_clinic">
                        <?php if ($metro_stations): ?>
                            <?php foreach($metro_stations as $station): ?>
                                <div class="metro-station-name" data-name="metro_station_to_clinic">
                                    <input type="hidden" name="metro_station_id" value="<?php echo $station->metro_station_id; ?>">
                                    <?php echo $station->metro_station->name_with_city_name; ?>
                                    <span class="remove-metro">удалить</span>
                    </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                </div>
                </div>
                <div class="row-record add">
                    <div class="row-record-data" id="metro-station-select-container"></div>
                    <a class="btn-bookmark metro-add">
                        <i class="icon-add"></i>
                        <span class="txt">Добавить метро</span>
                    </a>
                </div>
                <?php $cache->end(); ?>
            <?php endif; ?>

            <?php if (RegistryAccessHelper::checkManagerAuth()) :?>
                <div class="row-record">
                    <label style="width: 110px">Долгота</label>
                    <div class="row-record-data">
                        <?php echo $view_processor->getView('longitude'); ?>
                    </div>
                </div>
                <div class="row-record">
                    <label style="width: 110px">Широта</label>
                    <div class="row-record-data">
                        <?php echo $view_processor->getView('latitude'); ?>
                    </div>
                </div>
                <div class="row-record">
                    <label style="width: 350px"><a target="_blank" href="https://api.yandex.ru/maps/tools/getlonglat/">Определение координат</a></label>
                </div>
            <?php endif?>
        </div>
    </div>
    <div class="fields-block flo">
        <p>Контактная информация</p>
        <div class="fields-block-inner blue-inner" style="width: 815px">


            <?php $email_list_view_processor = new FormListViewProcessor('moderate_clinic_email', $emails); ?>
            <?php $email_list_view_processor->setViewTemplate('registry/form_template/clinic_email'); ?>
            <div class="hidden" id="email-form-template">
                <?php echo $email_list_view_processor->getTemplate(); ?>
            </div>
            <div class="row-record list-form">
                <label>Email</label>
                <div class="row-record-data" data-holder-for="clinic_email">
                    <?php echo $email_list_view_processor->getView(); ?>

                    <script type="text/javascript">
                        function addEmailInputCallback()
                        {
                            window.form_controller.addValidationRule($('input[name="email"]').validate
                                    (validation_rules['clinic_email']));
                        }
                    </script>
                        <img class="plus_img" src="/media/images/registry/plus-button.png" onclick="Elements.AddFormTemplate($
                        ('#email-form-template'), $(this), addEmailInputCallback)" />
                </div>
            </div>

            <div class="row-record list-form">
                <label>Телефон в области (Звони, мы поможем)</label>
                <div class="row-record-data">
                    <?php echo $view_processor->getView('top_phone'); ?>
                </div>
            </div>

            <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_EMAIL, $model->revision_number); ?>

            <?php $phone_list_view_processor = new FormListViewProcessor('moderate_clinic_phone', $phones); ?>
            <?php $phone_list_view_processor->setViewTemplate('registry/form_template/clinic_phone'); ?>
            <div class="hidden" id="phones-form-template">
                <?php echo $phone_list_view_processor->getTemplate(); ?>
            </div>
            <div class="row-record list-form">
                <label>Телефоны</label>
                <div class="row-record-data" data-holder-for="clinic_phone">
                    <?php echo $phone_list_view_processor->getView('phones'); ?>
                    <script type="text/javascript">
                        function addInputCallback()
                        {
                            $('input[name="phone_number"]').inputmask("+79999999999");
                            window.form_controller.addValidationRule($('input[name="phone_number"]').validate                                    (validation_rules['clinic_phone']));
                        }
                    </script>
                    <img class="plus_img" src="/media/images/registry/plus-button.png"
                         onclick="Elements.AddFormTemplate($('#phones-form-template'), $(this), addInputCallback)" />
                </div>
            </div>
            <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_PHONE, $model->revision_number); ?>

            <div class="row-record">
                <label>Сайт</label>
                <div class="row-record-data">
                    <span><?php echo $view_processor->getView('site'); ?><span>
                </div>
            </div>
            <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_SITE, $model->revision_number); ?>

            <div class="row-record">
                <label>ФИО генерального директора</label>
                <div class="row-record-data">
                    <span><?php echo $view_processor->getView('director_fio'); ?><span>
                </div>
            </div>
            <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_DIRECTOR_FULL_NAME, $model->revision_number); ?>
            <div class="row-record">
                <label>Номер договора</label>
                <div class="row-record-data">
                    <span><?php echo $view_processor->getView('contract_number'); ?><span>
                </div>
            </div>
			<?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_CONTRACT_NUMBER, $model->revision_number); ?>

            <div class="row-record">
                <label>Дата договора</label>
                <div class="row-record-data">
                    <span><?php echo $view_processor->getView('date_contract'); ?><span>
                </div>
            </div>
			<?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_DATE_CONTRACT, $model->revision_number); ?>

            <div class="row-record">
                <label>Юр. лицо клиники</label>
                <div class="row-record-data">
                    <span><?php echo $view_processor->getView('legal_entity'); ?><span>
                </div>
            </div>
			<?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_LEGAL_ENTITY, $model->revision_number); ?>
        </div>
    </div>

   <?php $this->block('registry/blocks/form-buttons'); ?>
</div>
