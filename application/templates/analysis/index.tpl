<?php
	/**
	 * @var CityModel $city
	 * @var int $city_id
	 * @var float $latitude
	 * @var float $longitude
	 */
?>
<script>
    $(document).ready(function(){
        var analysis_page_controller = new AnalysisPageController();
		analysis_page_controller.city_id = <?php echo $city->getId(); ?>;
		analysis_page_controller.city_alias = "<?php echo $city->alias; ?>";
        analysis_page_controller.init();
    });
</script>

<div class="flo">
    <div class="map-box map-box-analizes">
        <div id="map" class="map__canvas"></div>

        <div class="analizes-center">
            <div class="simple-text">
                <p class="h-txt">Анализы</p>
                <p class="text">
                    На карте представлены лаборатории Вашего города с указанием времени работы и приема биоматериала для анализа
                </p>
            </div>

            <div class="search-box flo" style="left: 670px;">
                <input class="txt" type="text" id="address-input" placeholder="Искать по адресу или станции метро" />
                <input class="btn-search" id="address-submit" type="submit" value="Найти"/>
            </div>
            <div class="criteria">
                <p class="h-txt">Выбрать критерии:</p>
                <ul class="choose-list">
                    <li class="urgent_tests">
                        <div class="chekBox"><span></span>Срочно<input type="hidden" value="0">
                        </div>
                    </li>
                    <li class="day_and_night">
                        <div class="chekBox"><span></span>24 часа<input type="hidden" value="0">
                        </div>
                    </li>
                    <li class="work_seven_days">
                        <div class="chekBox"><span></span>Без выходных<input type="hidden" value="0">
                        </div>
                    </li>
                    <li class="easy_entry">
                        <div class="chekBox"><span></span>Вход для колясок<input type="hidden" value="0">
                        </div>
                    </li>
                    <li class="card_pay">
                        <div class="chekBox"><span></span>Оплата по карте<input type="hidden" value="0">
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>