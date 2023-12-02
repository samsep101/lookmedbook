<?php
/**
 * @var MetroStationModel[] $metroStations
 * @var SpecializationModel $specialization
 * @var array $regionsWithDistricts
 * @var CityModel $city
 */
?>

<div class="inner">
    <div class="search-block flo">
    </div>
    <div class="ilness-list flo">
        <ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">
            <li itemprop="itemListElement" itemscope
                itemtype="http://schema.org/ListItem">
                    <span itemprop="item">
                        <span itemprop="name"><h1>Карта сайта</h1></span></span>
                <meta itemprop="position" content="1" />
            </li>
        </ol>
        <div class="sitemap">
            <ul class="list">
                <li><a href="<?= SeoLinkViewHelper::getSpecialtyPageLink($specialization, $city, 'clinic') ?>"><?= StringHelper::upperCaseFirstSymbol($specialization->name) ?></a></li>
                <?php if ($regionsWithDistricts) : ?>
                    <li><strong>По округам</strong>
                        <ul class="list">
                            <?php foreach ($regionsWithDistricts as $regionsWithDistrict) : ?>

                                <li>
                                    <a href="<?= SeoLinkViewHelper::getSpecialtyPageLink($specialization,
                                        $regionsWithDistrict['district'], 'clinic') ?>">
                                        <?= StringHelper::upperCaseFirstSymbol($regionsWithDistrict['district']->name) ?>
                                    </a>
                                    <ul>
                                        <?php foreach ($regionsWithDistrict['regions'] as $region) : ?>
                                            <li>
                                                <a href="<?= SeoLinkViewHelper::getSpecialtyPageLink($specialization,
                                                    $region, 'clinic') ?>">
                                                    <?= StringHelper::upperCaseFirstSymbol($region->name) ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if ($metroStations) : ?>
                    <li>
                        <strong>На станции метро</strong>
                        <ul>
                            <?php foreach ($metroStations as $metroStation) : ?>
                                <li>
                                    <a href="<?= SeoLinkViewHelper::getSpecialtyPageLink($specialization, $metroStation, 'clinic'); ?>">
                                        <?= StringHelper::upperCaseFirstSymbol($metroStation->name) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
