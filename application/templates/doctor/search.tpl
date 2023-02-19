<?php
/**
 * @var View $this
 * @var int $city_id
 * @var float $latitude
 * @var float $longitude
 * @var StreetModel|null $street
 * @var DistrictModel|null $district
 * @var RegionModel|null $region
 * @var CityModel|DistrictModel|RegionModel|StreetModel $address_object
 * @var MetroStationModel $metro_station
 * @var int $doctorTotalCount
 * @var bool $nextPageFlag
 * @var string $doctorsSearchErrorBlock
 * @var SpecialtyModel|null $specialty
 */

if (isset($_COOKIE['already_registred_account']))
    $already_registred_account = 1;
else
    $already_registred_account = 0;
?>


<script type="text/javascript">
  $(document).on('content-ready', function () {
        window.controller = new DoctorSearchPageController(
            <?php echo (isset($landing_page) && !Acc::isAuthed()) ? false : true; ?>,
            <?php echo $already_registred_account; ?>,
            '/doctor',
            '/doctor/ajaxSearch'
        );
        <?php if ($specialty): ?>
            window.controller.specialty_id = <?php echo $specialty->getId(); ?>;
            window.controller.specialty_alias = '<?php echo $specialty->alias; ?>';
        <?php endif; ?>
        <?php if ($district): ?>
            window.controller.district_id = <?php echo $district->getId(); ?>;
        <?php endif; ?>
        <?php if ($region): ?>
            window.controller.region_id = <?php echo $region->getId(); ?>;
        <?php endif; ?>
        <?php if ($street): ?>
            window.controller.street_id = <?php echo $street->getId(); ?>;
        <?php endif; ?>
        <?php if ($metro_station): ?>
            window.controller.metro_station_id = <?php echo $metro_station->getId();?>;
        <?php endif; ?>
        <?php if ($this->doctor_type): ?>
                window.controller.doctor_type = '<?php echo $this->doctor_type; ?>';
        <?php endif; ?>
        <?php if ($this->visit_type): ?>
                window.controller.visit_type = '<?php echo $this->visit_type; ?>';
        <?php endif; ?>
        <?php if ($this->discount): ?>
                window.controller.discount = '<?php echo $this->discount; ?>';
        <?php endif; ?>


        window.controller.city_id = '<?= $city_id; ?>';
        window.controller.init();

        window.doctor_form_controller = controller.form_controller;
    });
</script>



<?php
    $this->doctors_page = 1;
    $this->is_seo_page = $is_seo_page;
?>

<?= $this->block('doctor/blocks/search-form-page') ?>

<div class="search-count-block <?php echo (isset($is_red) && $is_red) ? 'red' : ''; ?>">
  <div class="js--ajax-breadcrumbs">
    <?= $this->block('doctor/blocks/breadcrumbs') ?>
  </div>
  <div class="special-block">
      <h1 class="js--main-h1">
        <?php
        if ($specialty && $specialty->__get('h1') != ''){
            echo str_replace('в Москве', SeoTextViewHelper::getAddressObjectName($address_object), $specialty->__get('h1'));
          }else {
            echo SeoTextViewHelper::getSpecialtyH1($specialty, $address_object);
          }
        ?>
      </h1>


  </div>


    <p class="count">
            Мы нашли для Вас <span class="count-digit"><?= $doctorTotalCount ?></span> <span class="count-doctor"><?= SpecialtyHelper::getDoctorWordForm($doctorTotalCount) ?></span>
            <span class="count-specialty"><?= isset($specialty->id) ? SpecialtyHelper::getNameByCount($specialty, $doctorTotalCount) : '' ?></span>
    </p>
    <div class="divider-shadow" id="divider-shadow"></div>
</div>


<?= HelpPhoneNumberViewHelper::renderPhoneBlock(<<<HTML
<div align="center" class="adv_text_bf_search_result">
    Мы сравним для вас цены и найдем лучшее предложение.<br> Звоните {%phoneLink%}
</div>
HTML
);
?>
<div class="inner-2" style="padding-top: 0;">
    <div id="our-doctors">
        <?php if ($doctorsSearchErrorBlock) {
            SeoHideHelper::begin();
            echo $doctorsSearchErrorBlock;
            SeoHideHelper::end();
        }
        $this->block('doctor/card_big_list');

        if ($nextPageFlag) {
            echo "<a class=\"load-next-page view-more\"><i></i>Показать ещё 10 врачей</a>";
        } ?>
    </div>
</div>

<?php if ($doctorTotalCount == 0 && false) { ?>
<div class="inner-2" style="padding-top: 0;">
    <p style="font-size: 14px">
        К сожалению, все предложения в категории <?= isset($specialty->id) ? SpecialtyHelper::getNameByCount($specialty, $doctorTotalCount).' '. SeoTextViewHelper::getAddressObjectName($address_object) : '' ?> закончились, но мы можем вам предложить специалистов из районов поблизости.
    </p>
    <div id="our-doctors">
        <?php if ($doctorsSearchErrorBlock) {
                SeoHideHelper::begin();
                echo $doctorsSearchErrorBlock;
                SeoHideHelper::end();
            }
            $this->block('doctor/card_big_list_alt');

        if ($nextPageFlag) {
        echo "<a class=\"load-next-page view-more\"><i></i>Показать ещё 10 врачей</a>";
        } ?>
    </div>
</div>
<?php } ?>


<div class="inner-2">
    <?php
if ($specialty_id) {
    echo "<div class='seo_text-container'>".str_replace('<ul>', '<ul class="list list-description">', SeoTextViewHelper::getTextBySpecialtyId($specialty_id))."</div>";
}
?>
</div>


<div class="doctor-special-links">

    <?php $this->block('doctor/blocks/seo_block'); ?>
</div>
<?php $this->block('doctor/blocks/micro-markup'); ?>
<style>.seo_text-container {margin: 2rem 0;} .seo_text-container p {padding: 1.5rem 0;}</style>