<?php
/**
 * @var CityModel | DistrictModel | RegionModel | MetroStationModel $address_object
 * @var View                                                        $this
 * @var SpecializationModel|null                                    $specialization
 * @var SpecializationModel[]                                       $specializations
 * @var callable                                                    $getSpecializationsGroups
 * @var IHtmlCache                                                  $cache
 */

$search_page_description = SeoTextViewHelper::getClinicPageDescription($specialization, $address_object);
$adaThere = 0;
if ($specialization) {
    $addressDataArray = SeoSpecializationBlockViewHelper::getViewByAddressObjectInArray($specialization,
        $address_object);
    $adaThere = !empty($addressDataArray);
} ?>

<div class="special-block inner-2">
    <?php if ($specialization) { ?>
        <?php if (empty($search_page_description)) {
            if ($specialization->specialty_page_descr) { ?>
                <div class="seo-specialty-description">
                    <?= $specialization->specialty_page_descr; ?>
                </div>
            <?php }
        } else { ?>
            <div class="seo-geo-description">
                <?= $search_page_description ?>
            </div>
        <?php }
    } ?>
</div>

<?php if ($adaThere && !empty($addressDataArray['districtsBlock'])) { ?>
    <div class="special-block links-block inner-2 specialties-block-modernize districtsBlock">
        <?= $addressDataArray['districtsBlock']; ?>
    </div>
<?php }

if (empty($specialization)) {
    $cacheKey = 'seo_block_clinic_' . get_class($address_object) . $address_object->getId();
    if (!$cache->start($cacheKey, 'seo_block')) {
        $allSpecs = $getSpecializationsGroups($city_id);
        $specsInColumn = ceil(count($allSpecs) / 4.0);
        ?>
        <div class="specialties-groups b-specialization">
            <h2>Специализации <?= SeoTextViewHelper::getAddressObjectName($address_object) ?></h2>
            <div class="specialty-group">
                <?php
                $i = -1;
                foreach ($allSpecs as $spec) {
                    ++$i;
                    if ($i > 0 && ($i % $specsInColumn == 0)) {
                        echo "</div><div class=\"specialty-group\">";
                    }
                    ?>
                    <div class="b-specialization__item">
                        <a class="b-specialization__item-link"
                           href="<?= SeoLinkViewHelper::getSpecialtyPageLink($spec, $address_object, 'clinic'); ?>">
                            <span class="b-specialization__item-count"><?= htmlentities($spec['count']) ?></span>
                            <span class="b-specialization__item-name"><?= htmlentities(StringHelper::upperCaseFirstSymbol($spec['name'])) ?></span>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php $cache->end();
    }
}

if ($adaThere && !empty($addressDataArray['otherAddressData'])) { ?>
    <div class="special-block links-block inner-2 specialties-block-modernize otherAddressData">
        <?php
        foreach ($addressDataArray['otherAddressData'] AS $oadKey => $oadValue) {
            echo $oadValue;
        }
        ?>
    </div>
<?php } ?>
<div class="clearfix"></div>
