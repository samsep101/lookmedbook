<script>
    $(document).ready(function(){
        var menu_controller = new RegionsMenuController();
        menu_controller.init();
    });
</script>

<?php
/**
 * @var View $this
 * @var UserModel[] $freelancers;
 * @var int $status
 * @var string $menu_active
 */
?>

<div class="cab-page-2">
    <?php if (in_array(Acl::userRole(), array(RoleModel::ACCOUNT_SUPER_MANAGER, RoleModel::ACCOUNT_MANAGER))): ?>
        <div class="nav-2">
    <?php else:?>
        <div class="nav-2 grey">
    <?php endif?>
        <ul>
            <?php if (in_array(Acl::userRole(), array(RoleModel::ACCOUNT_SUPER_MANAGER, RoleModel::ACCOUNT_MANAGER))): ?>
                <li class="<?php echo (isset($menu_active) && $menu_active == 'region_check') ? 'ui-state-active' : ''; ?>">
                    <a class="" href="/registry/manage/region_check">Проверка клиник</a>
                </li>
                <li class="<?php echo (isset($status) && ($status == 0)) ? 'ui-state-active' : ''; ?>">
                    <a class="region-tabs" data-id="0">Все - <span class="counter regions"><?php echo $counters['regions'];?></span></a>
                </li>
                <li class="<?php echo (isset($status) && $status == ClinicStatusModel::PUBLISHED) ? 'ui-state-active' : ''; ?>">
                    <a class="region-tabs" data-id="<?php echo ClinicStatusModel::PUBLISHED; ?>"><span class="region-img region-public"></span> Опубликованные - <span class="counter region_published"><?php echo $counters['region_published'];?></span></a>
                </li>
                <li class="<?php echo (isset($status) && $status == ClinicStatusModel::RAW) ? 'ui-state-active' : ''; ?>">
                    <a class="region-tabs" data-id="<?php echo ClinicStatusModel::RAW; ?>"><span class="region-img region-new"></span> Необработанные - <span class="counter region_raw"><?php echo $counters['region_raw'];?></span></a>
                </li>
                <li class="<?php echo (isset($status) && $status == ClinicStatusModel::PROBLEM) ? 'ui-state-active' : ''; ?>">
                    <a class="region-tabs" data-id="<?php echo ClinicStatusModel::PROBLEM; ?>"><span class="region-img region-problem"></span> Проблемные - <span class="counter region_problem"><?php echo $counters['region_problem'];?></span></a>
                </li>
            <?php endif?>

            <?php if (Acl::userRole() == RoleModel::FREELANCE_MANAGER): ?>
                <li class="<?php echo (isset($status) && $status == ClinicStatusModel::RAW) ? 'ui-state-active' : ''; ?>">
                    <a class="region-tabs" data-id="<?php echo ClinicStatusModel::RAW; ?>">Необработанные - <span class="counter region_raw"><?php echo $counters['region_raw'];?></span></a>
                </li>
                <li class="<?php echo (isset($status) && $status == ClinicStatusModel::PUBLISHED) ? 'ui-state-active' : ''; ?>">
                    <a class="region-tabs" data-id="<?php echo ClinicStatusModel::PUBLISHED; ?>">Опубликованные - <span class="counter region_published"><?php echo $counters['region_published'];?></span></a>
                </li>
                <li class="<?php echo (isset($status) && $status == ClinicStatusModel::PROBLEM) ? 'ui-state-active' : ''; ?>">
                    <a class="region-tabs" data-id="<?php echo ClinicStatusModel::PROBLEM; ?>">Проблемные - <span class="counter region_problem"><?php echo $counters['region_problem'];?></span></a>
                </li>
                <li class="<?php echo (isset($status) && ($status == 0)) ? 'ui-state-active' : ''; ?>">
                    <a class="region-tabs" data-id="0">Все - <span class="counter regions"><?php echo $counters['regions'];?></span></a>
                </li>
            <?php endif; ?>
        </ul>
    <div class="under-region-tabs-fields">
        <?php $this->block('registry/manage/blocks/simple_city_filter'); ?>

        <?php if (in_array(Acl::userRole(), array(RoleModel::ACCOUNT_SUPER_MANAGER, RoleModel::ACCOUNT_MANAGER))
        && $menu_active != 'region_check'): ?>
            <?php $this->block('registry/blocks/freelancers_list'); ?>
        <?php endif; ?>
    </div>
    </div>
</div>