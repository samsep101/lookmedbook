<?php
/**
 * @var CityModel $city
 * @var array $service_category
 * @var array|null $service
 */
$already_registred_account = (int)isset($_COOKIE['already_registred_account']);
?>

<script type="text/javascript">
  $(document).ready(function () {
    window.controller = new ClinicSearchPageController(
        <?php echo (isset($landing_page)) ? 'true' : 'false'; ?>,
        <?php echo $already_registred_account; ?>,
        "<?php echo $_SERVER['REQUEST_URI']; ?>",
        '/uslugi/ajaxSearchClinics'
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
    window.controller.service_categories = <?= $search_category ?>;
    window.controller.listing = false;

    controller.init();

    window.clinic_form_controller = controller.form_controller;
  });
</script>

<div class="row">
  <div class="col-xs-12">
    <div class="b-search-box form-control">
      <div class="b-search-box__address">
        <input class="b-search-box__address-input" type="text" id="address-input" placeholder="Искать по станции метро" />
      </div>
      <input class="b-search-box__button" id="address-submit" type="submit" value="Найти"/>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-xs-12">
      <div class="map-box off-screen refactor service-category-map clinic-map <?php echo (isset($is_red) && $is_red) ? 'red' : ''; ?>">
          <div id="search-show-on-map" class="btn-show-map">Выбрать на карте</div>
          <div id="map" class="map__canvas" data-map="small"></div>
          <span class="resize">Увеличить</span>
      </div>
  </div>
</div>
