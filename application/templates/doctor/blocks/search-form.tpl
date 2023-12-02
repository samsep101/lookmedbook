<div id="doctor-search-form">
    <div class="search-form">
        <div class="box flo">
            <?php if (!isset($show_title) || $show_title): ?>
                <h2 id="box_h1">Найти врача</h2>
            <?php endif; ?>

            <div class="colapse"> </div>
            <div class="in_colapse">
                <div class="sel-box doctor-box">
                    <select id="specialties_to_search_doctor" data-placeholder="Специальность врача" class="chzn-select" name="specialty_id" style="width:390px;">
                        <?php $this->show_all_option = TRUE; ?>
                        <?php $this->block('blocks/specialties_options'); ?>
                    </select>
                </div>
                <!--<div class="sel-box" id="purpose_of_visit_block">
                    <select id="purpose_of_visit_to_search" data-placeholder="Цель визита" class="chzn-select" style="width:390px;">
                        <option value=""></option>
                    </select>
                </div>-->
                <div class="choose-section flo <?php if (isset($home_page) && $home_page == 1){ ?>choose-section-free<?php } ?>">
                    <div class="col colleft">
                        <!--<div class="head-label first-label">
                            <div class="radioBox visit-type visit-type-clinic act"><span></span> Я могу прийти к врачу:
                                <input type="hidden" value="1">
                            </div>
                        </div>
                        <ul class="choose-list">
                            <li>
                                <div class="chekBox-allTime time-all act"><span></span>В любое время<input type="hidden" value="1">
                                </div>
                            </li>
                            <li>
                                <div class="chekBox time-morning"><span></span>Утром<input type="hidden">
                                </div>
                            </li>
                            <li>
                                <div class="chekBox time-evening"><span></span>Вечером<input type="hidden">
                                </div>
                            </li>
                            <li>
                                <div class="chekBox time-weekend"><span></span>На выходных<input type="hidden">
                                </div>
                            </li>
                        </ul>-->
                        <?php if (isset($home_page) && $home_page == 1): ?>
                            <div class="head-label second-label">
                                <div class="radioBox visit-type visit-type-home"><span></span>Врач на дом<input type="hidden">
                                </div>
                            </div>
                        <?php endif; ?>
                        <ul class="choose-list main-form-refactor-checkbox like-head-label">
                            <li>
                                <div class="chekBox visit-type-home"><span></span>Врач на дом<input type="hidden">
                                </div>
                            </li>
                            <li>
                                <div class="chekBox time-weekend"><span></span>Прием в выходные дни<input type="hidden">
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="col colright">
                        <label class="head-label">Врач для:</label>
                        <ul class="choose-list">
                            <li>
                                <div class="radioBox doctor-type doctor-type-adult act"><span></span> Взрослых
                                    <input type="hidden" value="1">
                                </div>
                            </li>
                            <li>
                                <div class="radioBox doctor-type doctor-type-children"><span></span> Детей
                                    <input type="hidden">
                                </div>
                            </li>
                            <!--<li class="last-child">
                                <div class="radioBox doctor-type doctor-type-pregnant"><span></span> Беременных
                                    <input type="hidden">
                                </div>
                            </li>-->
                        </ul>
                    </div>
                </div>

                <div class="find-txt" id="doctor-find-txt"> Искать по
                    <a href="javascript:void(0)">имени</a> или <a href="javascript:void(0)">полу</a>.
                </div>

                <div class="choose-section-2 flo doctor_search_options" style="display: none">
                    <div class="fields">
                        <div class="search-by-name flo">
                            <input type="text" class="txt" name="doctor_name" placeholder="Введите имя врача"/>
                            <input type="submit" class="search-btn" value=""/>
                            <a class="show_input" href="javascript:void(0)">Искать врача по имени</a>
                        </div>
                    </div>
                    <div class="gender"><span class="legend">Пол врача</span>
                        <input type="hidden"/>
                        <span class="man sex-1"></span>
                        <input type="hidden"/>
                        <span class="woman sex-2"></span></div>
                </div>
                <div class="btn-box flo">
                    <input class="btn-1 btn-doctor" type="submit" value="Найти врача" data-category-for-counters="find-doctor" data-action-for-counters="find-doctor"/>
                </div>
            </div>
        </div>
    </div>
</div>