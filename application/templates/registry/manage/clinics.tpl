<script type="text/javascript">
    $(document).ready(function(){
        var form_controller = new ManageController();
        form_controller.init();
    });
</script>

<?php echo $this->block('registry/manage/blocks/city_filter');?>

<div class="fields-block manager-account-block">

    <div class="fields-block-inner manager-account-search search-results white-inner">
        <div class="row-record">
            <p>Клиники</p>
            <div class="search-box flo">
                <input data-id="clinic" class="txt" type="text" value="<?php if ($query){echo strip_tags($query);}?>" placeholder="Остион" style="width: 350px;">
                <input data-id="clinic" class="btn-search" type="submit">
                <ul class="drop-menu"> </ul>
            </div>

            <div class="filter-clinic-list">
                <?php if ($clinics):?>
                    <div class="clinic-list">
                        <?php $this->block('registry/manage/blocks/clinic_results'); ?>
                    </div>
                <?php else:?>
                    <p class="no-results">По вашему запросу клиник не найдено</p>
                <?php endif?>
            </div>
        </div>
    </div>

</div>