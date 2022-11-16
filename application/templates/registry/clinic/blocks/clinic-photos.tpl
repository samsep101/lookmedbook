<div class="connected-carousels">
    <div class="stage"> <span class="map-corn"></span>
        <div class="carousel carousel-stage">
            <ul>
                <li><?php echo ClinicAvatarViewHelper::view($clinic, 658, 279); ?></li>

                <?php if ($clinic->images): ?>
                <?php foreach($clinic->images as $image): ?>
                    <li>
                        <img src="<?php echo $image->crop(658, 279)->path; ?>" alt="<?php echo $clinic->name; ?>">
                    </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <?php if ($clinic->image): ?>
    <div class="navigation">
        <a href="javascript:void(0)" class="prev prev-navigation"></a>
        <a href="javascript:void(0)" class="next next-navigation"></a>
        <div class="carousel carousel-navigation">
            <ul>
                <li><img src="<?php echo $clinic->image->crop(101, 56)->path; ?>" alt="<?php echo $clinic->name; ?>" /></li>
                <?php if ($clinic->images): ?>
                <?php foreach($clinic->images as $image): ?>
                        <li><img src="<?php echo $image->crop(101, 56)->path; ?>" alt="<?php echo $clinic->name; ?>"></li>
                <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>
</div>