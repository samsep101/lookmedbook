<?php
	/**
	 * @var View $this
	 * @var DoctorModel $doctor
	 */
?>
<style>
    div.connected-carousels.iframe div.stage div.carousel ul li {
        width: 640px;
        text-align: center;
        vertical-align: middle;
    }
    .connected-carousels.iframe div.stage{

    }
</style>
<div class="connected-carousels iframe">
    <div class="stage">
        <div class="carousel carousel-stage">
            <ul>
                <?php if (count($doctor->images)): ?>
                    <?php foreach($doctor->images as $image):?>
                        <li><img src="<?php echo $image->resizeWithWatermark(658,279)->path?>" alt="<?php echo $doctor->full_name; ?>"></li>
                    <?php endforeach;?>
                <?php endif; ?>
            </ul>
        </div>
        <a href="javascript:void(0);" class="prev prev-stage"><span></span></a>
        <a href="javascript:void(0);" class="next next-stage"><span></span></a>
    </div>

    <div class="navigation">
        <a href="javascript:void(0);" class="prev prev-navigation"></a>
        <a href="javascript:void(0);" class="next next-navigation"></a>
        <div class="carousel carousel-navigation">
            <ul>
                <?php if (count($doctor->images)): ?>
                    <?php foreach($doctor->images as $image):?>
                        <li><img src="<?php echo $image->resizeWithWatermark(101,56)->path; ?>"alt="<?php echo $doctor->full_name; ?>"></li>
                    <?php endforeach;?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

</div>

<script>
    $(function() {
        <?php if (count($doctor->images)>5){?>
            $('.connected-carousels .next-navigation').removeClass('inactive');
        <?php }?>
    });
</script>