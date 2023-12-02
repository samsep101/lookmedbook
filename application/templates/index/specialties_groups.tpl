<?php
    if($specialties_groups):
?>
    <div class="specialties-groups-wrapper">
        <div class="specialties-shadow-block"></div>
        <div class="specialties-groups">
            <div class="sg-title">Выберите специализацию и запишитесь на прием к лучшему врачу:</div>

            <?php echo $this->block('blocks/specialties-groups-content');?>

        </div>
        <div class="clearfix"></div>
        <a href="javascript:void(0)" class="up_page">наверх</a>
    </div>
<?php
    endif;
?>