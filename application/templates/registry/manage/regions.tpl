<?php
/**
 * @var View $this
 * @var string $query
 * @var ClinicModel[] $clinics
 * @var string $menu_active
 */
?>

<script type="text/javascript">
    $(document).ready(function(){
        var form_controller = new ManageController();
        form_controller.init();

        var regions_controller = new RegionsController();
        regions_controller.init("<?php echo $page_url?>", "<?php echo $status?>");
    });
</script>

<div class="fields-block manager-account-block">

    <div class="fields-block-inner manager-account-search search-results region white-inner">
        <div class="row-record">
            <p class="h-region-left">Регионы</p>
            <div class="search-box flo">
                <input data-id="<?php echo $menu_active; ?>" class="txt" type="text" value="<?php if ($query){echo strip_tags($query);}?>" placeholder="Остион" style="width: 350px;">
                <input data-id="<?php echo $menu_active; ?>" class="btn-search" type="submit">
                <ul class="drop-menu"> </ul>
            </div>

            <?php $this->block('registry/blocks/regions_menu'); ?>

            <?php if ($clinics): ?>
                <div class="region-results">
                    <?php $this->block('registry/manage/blocks/region_results'); ?>
                </div>
            <?php else: ?>
                <p class="no-results">По вашему запросу клиник не найдено</p>
            <?php endif; ?>
        </div>
    </div>

</div>