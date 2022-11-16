<?php
	/**
	 * @var View $this
	 * @var int $count_cities
     * @var CityModel[] $main_cities
	 * @var CityModel[] $cities
	 * @var AccountModel $current_account
	 * @var CityModel $selected_city
	 * @var string $page
	 */
?>
<?php $number = time().rand(1,100); ?>
<script>
    $(function() {
        $(document).ready(function(){
            var city_choice_controller = new CityChoiceController();
			city_choice_controller.page = '<?php echo $page; ?>';
            city_choice_controller.setContainer('#city-block-<?php echo $number; ?>');
            city_choice_controller.init();
        });
    });
</script>
<div id="city-block-<?php echo $number; ?>" class="city-block">
    <noindex>
        <h3>Ваш город:
            <?php if ($selected_city):?>
                <a href="javascript:void(0)" data-id="<?php echo $selected_city->getId(); ?>"
                data-city_alias="<?php echo $selected_city->alias; ?>"><?php echo $selected_city->name; ?></a>
            <?php else: ?>
                <a href="javascript:void(0)" data-id="<?php echo $city->getId(); ?>"
               data-city_alias="<?php echo $city->alias; ?>"><?php echo $city->name; ?></a>
            <?php endif; ?>
        </h3>
        <p class="beforeh">Выберите город, в котором вы собираетесь найти врача</p>

        <div class="search-block flo" style="margin:0">
            <input style="width:412px;margin: 0;" type="text" class="txt city-search-input" autocomplete="off" name="city_query" placeholder="Введите название города" />
            <input type="button" class="btn-1 city-search-submit" id="save-city-button" value="Выбрать" />
            <ul class="drop-menu" style="clear:both">
            </ul>
        </div>

        <p class="or">или выберите город из списка:</p>

        <?php if ($main_cities): ?>
            <ul class="not-empty-cities flo">
                <?php foreach ($main_cities as $main_city):?>
                <?php //php if (!$selected_city || ($selected_city->getId() != $city->getId())):?>
                <li>
                    <div class="for_icons">
                        <?php if($main_city->is_has_laboratories): ?>
                            <span class="icon_analyzes"></span>
                        <?php endif; ?>
                        <?php if($main_city->is_has_clinics): ?>
                            <span class="icon_clinic"></span>
                        <?php endif; ?>
                    </div>
                    <a data-id="<?php echo $main_city->getId(); ?>"
                   data-city_alias="<?php echo $main_city->alias; ?>"><?php echo $main_city->name; ?></a></li>
                <?php //endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php /*
        <div class="info_icons">
            В городе есть: <span class="icon_clinic"></span> - клиники <span class="icon_analyzes"></span> - лаборатории (анализы)
        </div>
        */ ?>
        <a class="show_all" href="javascript:void(0)">Все города</a>
        <div class="all_city">
            <ul class="other-cities">
                <?php $count = 0; ?>
                <?php foreach($cities as $city): ?>
                    <?php if($count == 0): ?>
                        <li>
                    <?php endif; ?>
                        <div class="city">
                            <div class="for_icons">
                                <?php if($city->is_has_laboratories): ?>
                                    <span class="icon_analyzes"></span>
                                <?php endif; ?>
                                <?php if($city->is_has_clinics): ?>
                                    <span class="icon_clinic"></span>
                                <?php endif; ?>
                            </div>
                            <a data-id="<?php echo $city->getId(); ?>"
                               data-city_alias="<?php echo $city->alias; ?>"><?php echo $city->name; ?></a>
                        </div>
                    <?php $count++; ?>
                    <?php if($count == 13): ?>
                        </li>
                        <?php $count = 0; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <a class="prev-nav" href="javascript:void(0)"><span class="bg_nav"></span><span class="bg_arrow"></span></a>
            <a class="next-nav" href="javascript:void(0)"><span class="bg_nav"></span><span class="bg_arrow"></span></a>
        </div>
    </noindex>
</div>