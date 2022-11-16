<?php
/**
 * @var View $this
 * @var string $query
 * @var ClinicModel[] $clinics
 * @var string $menu_active
 * @var string[] $edit_dates
 * @var int $city_id
 */
?>

<script type="text/javascript">
    $(document).ready(function(){
        var form_controller = new ManageController();
        form_controller.init();

        var controller = new RegionCheckController();
        controller.city_id = "<?php echo (isset($city_id) && $city_id) ? $city_id : null; ?>";
        controller.edit_dates = <?php echo json_encode($edit_dates); ?>;
        controller.init();
    });
</script>



<div class="fields-block manager-account-block">

    <div class="fields-block-inner manager-account-search search-results region white-inner">
        <div class="row-record">
            <p class="h-region-left">Регионы</p>


            <?php $this->block('registry/blocks/regions_menu'); ?>
            
            <div class="clinic-list">

            </div>
        </div>
    </div>

</div>