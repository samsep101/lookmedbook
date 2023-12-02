<div class="row">
    <div class="col-md-8">
        <div class="container page-h1 ff-regular hidden-map-fullscreen">
            <h1><?= $title ?></h1>
        </div>

        <?php if ($description) : ?>
            <div>
                <div class="service-category__description simple-page">
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
    </div>
    <div class="col-md-4 visible-md visible-lg">
        <div style="padding:8px; background: none; box-shadow: none;">
            <a class="btn-double-floor" href="/doctor">
                <span style="padding: 10px;" class="just-text">Записаться к врачу</span>
            </a>
        </div>
        <div class="info-box disease-banner" style="padding:8px; background: none; box-shadow: none;">
            <a href="/action"><img class="lazy" data-src="/media/banners/actions.jpg" width="300"></a>
        </div>

    </div>
</div>