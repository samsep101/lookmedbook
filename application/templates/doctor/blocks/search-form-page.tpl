<?php SeoHideHelper::begin() ?>
<div class="inner-2">
    <div id="doctor-search-form" class="search-form-page">
        <div class="row">
            <h2 class="text-center">Найти врача</h2>
        </div>
        <div class="row">
            <div class="col-md-5">
                <select id="specialties_to_search_doctor"
                        name="specialty_id"
                        class="chzn-select"
                        data-placeholder="Специальность врача">
                    <?php $this->show_all_option = false ?>
                    <?php $this->block('blocks/specialties_options') ?>
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
            <div class="col-md-2">
                <input class="btn-1 btn-form-elem form-control btn-doctor"
                       type="submit"
                       value="Найти"
                       data-category-for-counters="find-doctor"
                       data-action-for-counters="find-doctor"/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul class="list-inline doctor-search-filters">
                    <li class="choose-list">
                        <span class="chekBox doctor-type doctor-type-children">
                            <span></span><input type="hidden">Детский врач
                        </span>
                    </li>
                    <li class="chekBox visit-type-home"><span></span><input type="hidden">Выезд на дом</li>
                    <li class="chekBox time-weekend"><span></span><input type="hidden">Прием в выходные дни</li>
                    <li>
                        <div class="gender">
                            <span class="chekBox"><span class="man sex-1"></span><input type="hidden">Пол: М</span>
                            <span class="chekBox"><span class="woman sex-2"></span><input type="hidden">Пол: Ж</span>
                        </div>
                    </li>
                    <li>
                        <span class="lmb">
                            <span class="pad_tb">
                                <a class="a_dashed show_inp search-doctor-by-name" href="javascript:void(0)">Искать врача по имени</a>
                                <input class="search_txt form-control"
                                       type="text"
                                       placeholder="Введите имя врача"
                                       name="doctor_name">
                            </span>
                        </span>
                    </li>
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
    </div>
</div>
<?php SeoHideHelper::end() ?>
