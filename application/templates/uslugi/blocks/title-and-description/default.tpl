<div class="container page-h1 ff-regular hidden-map-fullscreen">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1><?=$title?></h1>
        </div>
    </div>
</div>

<?php if ($description) : ?>
    <div>
        <div class="service-category__description about-ilness-content w100">
            <div class="main-cont panel-post __container">
                <?= $description; ?>
                <?php if ($fullDescription) { ?>
                    <div class="remain" style="display:none;">
                        <?= $fullDescription; ?>
                    </div>
                    <div><a class="__showmore" data-switch="Свернуть статью" href="javascript:void(0)">Читать далее...</a></div>
                <?php } ?>

            </div>
        </div>
    </div>
<?php endif; ?>