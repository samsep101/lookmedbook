<?php
/**
 * @var View $this
 * @var ClinicModel $clinic
 */
?>
<div class="connected-carousels">
    <div class="stage">
        <div class="carousel carousel-stage clinic-carousel">
            <ul>
                <?php if ($clinic->images):
                    $isFirst = true;
                    ?>
                    <?php foreach ($clinic->images as $image):
                    $url = $image && $image->cropWithWatermark(660, 360) ? $image->cropWithWatermark(
                        660,
                        360
                    )->path : "/media/images/no_photo_clinic.gif";
                    ?>
                        <li>
                            <?php if ($isFirst) : ?>
                                <img src="<?= $url ?>" alt="<?= $clinic->name; ?>">
                            <?php else : ?>
                                <img loading='lazy' src="<?= $url ?>" alt="<?= $clinic->name; ?>" class="lazy-carousel">
                            <?php endif; ?>
                        </li>
                    <?php
                    $isFirst = false;
                endforeach; ?>
                <?php else: ?>
                    <li>
                        <img src="/media/images/no_photo_clinic.gif" alt="<?= $clinic->name; ?>">
                    </li>
                <?php endif; ?>
            </ul>
            <a href="javascript:void(0);" class="prev prev-stage"><span></span></a>
            <a href="javascript:void(0);" class="next next-stage"><span></span></a>
        </div>
    </div>
</div>
