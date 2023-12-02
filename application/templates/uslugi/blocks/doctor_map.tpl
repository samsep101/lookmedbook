<?php
/**
 * @var View $this
 * @var float $latitude
 * @var float $longitude
 * @var StreetModel|null $street
 * @var DistrictModel|null $district
 * @var RegionModel|null $region
 * @var CityModel|DistrictModel|RegionModel|StreetModel $address_object
 * @var MetroStationModel $metro_station
 * @var string $bounds
 * @var string $map_file
 * @var CityModel $city
 * @var array $service_category
 * @var array|null $service
 */

$already_registred_account = (int)isset($_COOKIE['already_registred_account']);
?>


<script type="text/javascript">
  $(document).ready(function () {

    window.controller = new DoctorSearchPageController(
        <?php echo (isset($landing_page) && !Acc::isAuthed()) ? false : true; ?>,
        <?php echo $already_registred_account; ?>,
        "<?php echo $_SERVER['REQUEST_URI']; ?>",
        '/uslugi/ajaxSearchDoctors'
    );
      <?php if (!empty($district)): ?>
    window.controller.district_id = <?php echo $district->getId(); ?>;
      <?php endif; ?>
      <?php if (!empty($region)): ?>
    window.controller.region_id = <?php echo $region->getId(); ?>;
      <?php endif; ?>
      <?php if (!empty($street)): ?>
    window.controller.street_id = <?php echo $street->getId(); ?>;
      <?php endif; ?>
      <?php if (!empty($metro_station)): ?>
    window.controller.metro_station_id = <?php echo $metro_station->getId();?>;
      <?php endif; ?>
      <?php if (!empty($this->doctor_type)): ?>
    window.controller.doctor_type = '<?php echo $this->doctor_type; ?>';
      <?php endif; ?>
      <?php if (!empty($this->visit_type)): ?>
    window.controller.visit_type = '<?php echo $this->visit_type; ?>';
      <?php endif; ?>
      <?php if (!empty($this->discount)): ?>
    window.controller.discount = '<?php echo $this->discount; ?>';
      <?php endif; ?>


    window.controller.city_id = <?php echo $city->getId(); ?>;
    window.controller.service_categories = <?= $search_category ?>;
    window.controller.listing = false;
    window.controller.specialty_id = '<?= $service['specialty_id'] ?>';
    window.controller.init();

    window.doctor_form_controller = controller.form_controller;
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
