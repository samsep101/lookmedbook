<?php
/**
 * @var CityModel | DistrictModel | RegionModel | MetroStationModel $address_object
 * @var View $this
 * @var SpecialtyModel|null $specialty
 * @var SpecialtyModel[] $specialties
 */
?>
<?php
    if (isset($is_seo_page) && $is_seo_page) {
        $cache_id = 'seo' . get_class($address_object) . $address_object->getId() . 'specilty_main';

        if ($specialty) {
            $cache_id = 'seo' . get_class($address_object) . $address_object->getId() . 'specialty' . $specialty->getId();
        }
        if ($specialties_groups) {


            if (!$cache->start($cache_id, 'seo_block')) {
                $adaThere = 0;
                if ($specialty) {
                    if (!isset($addressDataArray))
                        $addressDataArray = SeoSpecialtyBlockViewHelper::getViewByAddressObjectInArray($specialty, $address_object);
                    $adaThere = !empty($addressDataArray);
                }


?>

<div class="special-block inner-2">
    <?php
    if ($specialty && empty($setDefaultSpecialty)) {
        if (empty($search_page_description)) {
            if ($specialty->specialty_page_descr) { ?>
                <div class="seo-specialty-description">
                    <?= $specialty->specialty_page_descr; ?>
                </div>
    <?php }
        } else { ?>
    <div class="seo-geo-description">
        <?= $search_page_description; ?>
    </div>
    <?php }
    } ?>
</div>
<?php if ($adaThere && !empty($addressDataArray['districtsBlock'])) { ?>
    <div class="special-block links-block inner-2 specialties-block-modernize districtsBlock geoBlocks">
        <?= $addressDataArray['districtsBlock']; ?>
    </div>
<?php } ?>

<?php if (isset($isPopularShow)  && !$isPopularShow) { ?>
<div class="specialties-groups not-main-specialties<?php if (!empty($page_type)) echo ' doctor-specialties'; ?>">
    <h2>Популярные специальности <?= SeoTextViewHelper::getAddressObjectName($address_object); ?>: </h2>
    <?= $this->block('blocks/specialties-groups-content'); ?>
</div>
<?php } ?>
<?php if ($adaThere && !empty($addressDataArray['otherAddressData'])) { ?>
<div class="special-block links-block inner-2 specialties-block-modernize otherAddressData geoBlocks">
    <?php
                foreach ($addressDataArray['otherAddressData'] AS $oadKey => $oadValue) {
    echo $oadValue;
    }
    ?>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php
            }
        }
    }
