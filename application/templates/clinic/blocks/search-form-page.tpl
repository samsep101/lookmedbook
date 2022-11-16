<?php
/**
 * @var bool $hasMetro
 */
SeoHideHelper::begin()
?>
<div class="inner-2">
    <div id="clinic-search-form" class="search-form-page">
        <div class="row">
            <h2 class="text-center">Найти клинику</h2>
        </div>
        <div class="row">
            <div class="col-md-5">
                <select id="specialties_to_search_clinic"
                        name="specialty_id"
                        class="chzn-select"
                        data-placeholder="Специализация">
                    <?php
                    $this->specialization = empty($specialization) ? '' : $specialization;
                    $this->show_all_option = true;
                    $this->block('blocks/specialization_options');
                    ?>
                </select>
            </div>
            <?php if (!empty($hasMetro)) { ?>
                <div class="col-md-5">
                    <div class="search-box">
                        <input id="address-input"
                               type="text"
                               class="form-control search-box-address-input"
                               placeholder="Искать по станции метро"/>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-2 hidden-xs">
                <input class="btn-1 btn-form-elem form-control clinic_search_options"
                       type="submit"
                       value="Найти"
                       data-category-for-counters="find-clinic"
                       data-action-for-counters="find-clinic"/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul class="list-inline doctor-search-filters">
                    <li class="choose-list">
                        <span class="chekBox clinic-type clinic-type-children">
                            <span></span><input type="hidden">Детская клиника
                        </span>
                    </li>
                    <li class="chekBox is-card-pay"><span></span><input type="hidden">Оплата картой</li>
                    <li class="chekBox twenty-four-hours"><span></span><input type="hidden">Круглосуточная</li>
                    <li class="chekBox have-ramp"><span></span><input type="hidden">Есть пандус</li>
                </ul>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="map-box off-screen">
                  <div id="search-show-on-map" class="btn-show-map">Выбрать на карте</div>
                    <div id="map"></div>
                    <span class="resize">Увеличить</span>
                </div>
            </div>
        </div>
        <div class="row hidden visible-xs mt-5">
            <div class="col-xs-12">
                <input class="btn-1 btn-form-elem form-control clinic_search_options"
                       type="submit"
                       value="Найти"
                       data-category-for-counters="find-clinic"
                       data-action-for-counters="find-clinic"/>
            </div>
        </div>
    </div>
</div>
<?php SeoHideHelper::end() ?>
