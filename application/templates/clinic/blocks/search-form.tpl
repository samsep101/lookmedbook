<?php SeoHideHelper::begin() ?>
<div id="clinic-search-form">
    <div class="search-form">
        <div class="box flo">
            <div class="section find-clinic visible">
                <?php if ((isset($menu_active)) && ($menu_active == 'clinic')):?>
                    <h1 id="box_h1">Найти клинику</h1>
                <?php endif?>
                <div class="colapse"> </div>

                <div class="in_colapse">
                    <?php if(!empty($landing_page) && !empty($current_item)) { ?>
                        <input type="hidden" id="landing_item_id" name="landing_item_id" value="<?php echo $current_item->alias; ?>">
                    <?php } ?>
                    <div class="sel-box clinic-field-margin-bottom" id="specialty_block">

                        <select data-placeholder="Специализация" id="specialties_to_search_clinic" class="chzn-select" name="specialty_id" style="width:290px;">
                        <?php
                            $this->specialization = empty($specialization)?'':$specialization;
                            $this->show_all_option = TRUE;
                            $this->block('blocks/specialization_options');
                        ?>
                        </select>
                    </div>

                    <div class="for-whom-clinic">
                        <div class="field-label">
                            Клиника для:
                        </div>
                        <ul class="choose-list choose-list-item-line">
                            <li>
                                <div class="radioBox clinic-type clinic-type-adult act"><span></span> Взрослых
                                    <input type="hidden" value="1">
                                </div>
                            </li>
                            <li>
                                <div class="radioBox clinic-type clinic-type-children"><span></span> Детей
                                    <input type="hidden">
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="other-clinic-search-params">
                        <ul class="choose-list main-form-refactor-checkbox like-head-label">
                            <li>
                                <div class="search-param-icon icon-credit-card"></div>
                                <div class="chekBox is-card-pay"><span></span>Оплата картой<input type="hidden"></div>
                                <div class="dotted-area"></div>
                            </li>
                            <li>
                                <div class="search-param-icon icon-time"></div>
                                <div class="chekBox twenty-four-hours"><span></span>Круглосуточная<input type="hidden"></div>
                                <div class="dotted-area"></div>
                            </li>
                            <li>
                                <div class="search-param-icon icon-pandus"></div>
                                <div class="chekBox have-ramp"><span></span>Есть пандус<input type="hidden"></div>
                                <div class="dotted-area"></div>
                            </li>
                        </ul>
                    </div>

                    <div class="choose-section-2 clinic_search_options" style="display: none">
                        <div class="search-by-name flo">
                            <div class="lmb">
                                <p class="pad_tb">
                                    <a class="a_dashed show_inp" href="javascript:void(0)">Искать клинику по названию</a>
                                    <input type="text" class="search_txt" name="clinic_name" placeholder="Название клиники"/>
                                </p>

                                <div class="btn-box flo">
                                    <input class="btn_red clinic_search_options" type="submit" value="Найти клинику" data-category-for-counters="find-clinic" data-action-for-counters="find-clinic"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  $(".chzn-select").chosen();
</script>
<?php SeoHideHelper::end() ?>