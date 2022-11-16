<div class="connected-carousels iframe">
    <div class="stage">
        <div class="carousel carousel-stage">
            <?php if($this->doctor->image_id):?>
            <ul>
                <li><img src="<?php echo $doctor->image->resize(658,279)->path; ?>" alt="<?php echo $doctor->full_name; ?>" /></li>
                <?php if ($doctor->images): ?>
                <?php foreach($doctor->images as $image):?>
                    <li><img src="<?php echo $image->resize(658,279)->path; ?>" alt="<?php echo $doctor->full_name; ?>"></li>
                    <?php endforeach;?>
                <?php endif; ?>
            </ul>
            <?php else: ?>
            <ul>
                <li><img src="/media/images/no-photo.gif" alt="<?php echo $doctor->full_name; ?>" /></li>
            </ul>
            <?php endif; ?>
        </div>
        <!--<a href="javascript:void(0);" class="prev prev-stage"><span></span></a>
        <a href="javascript:void(0);" class="next next-stage"><span></span></a>-->
    </div>

    <div class="navigation">
        <a href="javascript:void(0);" class="prev prev-navigation"></a>
        <a href="javascript:void(0);" class="next next-navigation"></a>
        <div class="carousel carousel-navigation">
            <?php if($this->doctor->image_id):?>
            <ul>
                <li><img src="<?php echo $doctor->image->crop(101,56)->path; ?>" alt="<?php echo $doctor->full_name; ?>" /></a></li>
                <?php foreach($doctor->images as $image):?>
                <li><img src="<?php echo $image->crop(101,56)->path; ?>"alt="<?php echo $doctor->full_name; ?>"></li>
                <?php endforeach;?>
            </ul>
            <?php endif; ?>
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