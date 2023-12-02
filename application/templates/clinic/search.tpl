<?php
/**
 * @var View $this
 * @var CityModel $city
 * @var int $city_id
 * @var float $latitude
 * @var float $longitude
 * @var View $this
 * @var AccountModel $current_account
 * @var ClinicModel $clinic
 * @var int $clinicTotalCount
 * @var bool $nextPageFlag
 */

$specializationName = isset($specialization->name) ? $specialization->name : '';
 ?>
<?php
    if (isset($_COOKIE['already_registred_account']))
        $already_registred_account = 1;
    else
        $already_registred_account = 0;
?>
<script type="text/javascript">
    $(document).on('content-ready', function () {
        window.controller = new ClinicSearchPageController(
            <?php echo (isset($landing_page)) ? 'true' : 'false'; ?>,
            <?php echo $already_registred_account; ?>,
            "/clinic",
            '/clinic/ajaxSearch'
        );

        <?php if(!  empty($specialization)) { ?>
            window.controller.specialty_id = <?php echo $specialization->getId(); ?>;
            window.controller.specialty_alias = '<?php echo $specialization->alias; ?>';
        <?php } ?>
        <?php if (!empty($district) and is_object($district)): ?>
            window.controller.district_id = <?php echo $district->getId(); ?>;
        <?php endif; ?>
        <?php if (!empty($region) and is_object($region)): ?>
            window.controller.region_id = <?php echo $region->getId(); ?>;
        <?php endif; ?>
        <?php if (!empty($street) and is_object($street)): ?>
            window.controller.street_id = <?php echo $street->getId(); ?>;
        <?php endif; ?>
        <?php if (!empty($metro_station) and is_object($metro_station)): ?>
            window.controller.metro_station_id = <?php echo $metro_station->getId(); ?>;
        <?php endif; ?>


        window.controller.city_id = <?php echo $city->getId(); ?>;
        window.controller.city_alias = "<?php echo $city->alias; ?>";

        controller.init();

        window.clinic_form_controller = controller.form_controller;


        if (window.clinic_form_controller){
            const urlSearchParams = new URLSearchParams(window.location.search);
            const params = Object.fromEntries(urlSearchParams.entries());

            if (! (params.page === undefined) && parseInt(params.page) + 1 !== window.clinic_form_controller.page ){
                window.clinic_form_controller.page = parseInt(params.page) ;

            }
        }
    });
</script>
<?php $this->block('clinic/blocks/search-form-page'); ?>

<div class="search-count-block clinic-search <?php echo (isset($is_red) && $is_red) ? 'red' : ''; ?>">
  <div class="js--ajax-breadcrumbs">
    <?= $this->block('clinic/blocks/breadcrumbs') ?>
  </div>
  <div class="special-block">
      <h1 class="js--main-h1"><?= SeoTextViewHelper::getSpecializationH1($specialization, $address_object) ?></h1>
  </div>
    <p class="count">
        Мы нашли для Вас <span class="count-digit"><?= $clinicTotalCount ?></span> <span class="count-doctor"><?= SpecialtyHelper::getClinicWordForm($clinicTotalCount) ?></span>
        <span class="specialty-label" <?= !isset($specialization->name) ? 'style="display: none"' : '' ?>>по специализации <span class="count-specialty"><?= $specializationName ?></span></span>
    </p>
    <div class="divider-shadow" id="divider-shadow"></div>
</div>
<?php if (!empty(SITE_PHONE)) : ?>
    <div align="center" class="adv_text_bf_search_result">
        Мы сравним для вас цены и найдем лучшее предложение.<br> Звоните <?= HelpPhoneNumberViewHelper::getPhoneNumberLink() ?>
    </div>
<?php endif; ?>

<div class="inner-2 clinic-search-result <?php if($current_account  && $current_account->is_call_centre_operator) echo 'account_active'; ?>">
    <div id="our-doctors">
        <?php
        $this->block('clinic/card_small_list');
        if ($nextPageFlag) {
            echo "<a class=\"view-more\"><i></i>Показать ещё 10 клиник</a>";
        } ?>
    </div>
</div>


<?php if ($clinicTotalCount == 0) { ?>
<div class="inner-2" style="padding-top: 0;">
    <p style="font-size: 14px">
        К сожалению, все предложения в категории <?= isset($specialty->id) ? SpecialtyHelper::getNameByCount($specialty, $clinicTotalCount).' '. SeoTextViewHelper::getAddressObjectName($address_object) : '' ?> закончились, но мы можем вам предложить клиники из районов поблизости.
    </p>
    <div id="our-doctors" style="padding-top: 2rem ">
        <?php
            //$this->block('clinic/card_big_list-alt');
        ?>
    </div>
</div>

<?php } else {
    $total = $clinicTotalCount;
    $this->block('blocks/pagination');
} ?>

<div class="clinic-special-links">
    <?php $this->block('clinic/blocks/seo_block'); ?>
</div>
