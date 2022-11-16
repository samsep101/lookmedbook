<script type="text/javascript">
    $(document).ready(function(){
        var form_controller = new ManageController();
        form_controller.init();
    });
</script>

<?php echo $this->block('registry/manage/blocks/city_filter');?>

<?php if (Acl::userRole() != RoleModel::FREELANCE_MANAGER): ?>
    <div class="fields-block manager-account-block">

        <div class="fields-block-inner manager-account pink-inner">
            <div class="row-record">
                <p>Обновления страниц и полей</p>
                <?php if (count($updates)): ?>
                    <p class="smaller-p">Требует проверки</p>
                    <ul class="updates-list">
                        <?php foreach($updates as $update): ?>
                            <li><?php echo ModeratePageLinkViewHelper::getView($update); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a class="show-all" href="/registry/manage/updates">Показать все</a>
                <?php else: ?>
                    нет данных на модерации
                <?php endif; ?>
            </div>
        </div>

    </div>
<?php endif; ?>

<div class="fields-block manager-account-block">
    <div class="fields-block-inner manager-account-search white-inner">
        <div class="row-record">
            <?php if (Acl::userRole() == RoleModel::FREELANCE_MANAGER): ?>
                <a href="/registry/manage/regions?status_id=<?php echo ClinicStatusModel::RAW; ?>" style="float:right;" class="float-right">Необработанные</a>
            <?php endif; ?>
            <?php if (in_array(Acl::userRole(), array(RoleModel::ACCOUNT_SUPER_MANAGER, RoleModel::ACCOUNT_MANAGER))): ?>
                <a href="/registry/manage/region_check" style="float:right;" class="float-right">Проверка клиник</a>
            <?php endif; ?>
            <p>Регионы</p>
            <div class="search-box flo">
                <input data-id="regions" class="txt placeholder" type="text" placeholder="Остеон" style="width: 300px;">
                <input data-id="regions" class="btn-search" type="">
                <ul class="drop-menu"> </ul>
            </div>
            <?php if ($region_clinics):?>
                <ul class="updates-list">
                    <?php foreach ($region_clinics as $clinic):?>
                        <li>
                            <?php echo ModeratePageLinkViewHelper::getClinicOrDoctorLinkView($clinic, 'clinic'); ?>
                            <?php echo RegionStatusViewHelper::getStatusImage($clinic->clinic_status_id); ?>
                        </li>
                    <?php endforeach?>
                </ul>
                <a class="show-all" href="/registry/manage/regions">Показать все</a>
            <?php else:?>
                <div style="clear: both; margin-top: 10px;">
                    нет данных
                </div>
            <?php endif?>
        </div>
    </div>
</div>

<div class="fields-block manager-account-block">
    <div class="fields-block-inner manager-account-search white-inner">
        <div class="row-record">
            <p>Клиники

                <?php if (in_array(Acl::userRole(), array(RoleModel::ACCOUNT_SUPER_MANAGER, RoleModel::ACCOUNT_MANAGER))): ?>
                <a href="/registry/clinic/add" style="text-decoration: none">
                    <input class="manage-add" type="button" value="Добавить клинику">
                </a>
                <?php endif; ?>
            </p>
            <div class="search-box flo">
                <input data-id="clinic" class="txt placeholder" type="text" placeholder="Остеон" style="width: 300px;">
                <input data-id="clinic" class="btn-search" type="">
                <ul class="drop-menu"> </ul>
            </div>
            <div class="filter-clinic-list">
                <?php if ($clinics):?>
                    <ul class="updates-list">
                        <?php foreach ($clinics as $clinic):?>
                        <li><?php echo ModeratePageLinkViewHelper::getClinicOrDoctorLinkView($clinic, 'clinic'); ?></li>
                        <?php endforeach?>
                    </ul>
                    <a class="show-all" href="/registry/manage/clinics">Показать все</a>
                    <?php else:?>
                    <div style="clear: both; margin-top: 10px;">
                        нет данных
                    </div>
                <?php endif?>
            </div>
        </div>
    </div>
</div>

<script> if ($('.manager-account.pink-inner').length > 0) {document.write('<p class="clear"></p>');}</script>

<div class="fields-block manager-account-block">

    <div class="fields-block-inner manager-account-search white-inner">
        <div class="row-record">
            <p>Врачи</p>
            <div class="search-box flo">
                <input data-id="doctor" class="txt placeholder" type="text" placeholder="Петров" style="width: 300px;">
                <input data-id="doctor" class="btn-search" type="">
                <ul class="drop-menu"> </ul>
            </div>
            <div class="filter-doctor-list">
                <?php if ($doctors):?>
                    <ul class="updates-list">
                        <?php foreach ($doctors as $doctor):?>
                            <li><?php echo ModeratePageLinkViewHelper::getClinicOrDoctorLinkView($doctor, 'doctor'); ?></li>
                        <?php endforeach?>
                    </ul>
                    <a class="show-all" href="/registry/manage/doctors">Показать все</a>
                <?php else:?>
                    <div style="clear: both; margin-top: 10px;">нет данных</div>
                <?php endif?>
            </div>
            <!--<input class="manage-add" type="button" value="Добавить врача">-->
        </div>
    </div>

</div>
<!--
<div class="fields-block manager-account-block">

    <div class="fields-block-inner manager-account vyz pink-inner">
        <div class="row-record">
            <p class="pink-p">ВУЗы</p>
            <select>
                <option value="">ВУЗ 1</option>
                <option value="">ВУЗ 2</option>
                <option value="">ВУЗ 3</option>
            </select>
            <p>Новый ВУЗ</p>
            <input class="" type="text" >

            <div class="vyz-buttons">
                <input class="manage-add" type="button" value="Удалить">
                <input class="manage-add" type="button" value="Отменить">
                <input class="manage-add" type="button" value="Добавить">
            </div>
        </div>
    </div>

</div>

-->