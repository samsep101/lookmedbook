<script type="text/javascript">
    $(document).ready(function(){
        var clinic_controller = new ClinicPageController('<?php echo $clinic->id?>', '<?php echo $clinic->latitude; ?>', '<?php echo $clinic->longitude; ?>',null, null,"<?php echo $_SERVER['REQUEST_URI']; ?>");
        $('.btn-bookmark').click(function(){
            clinic_controller.block_title = 'для добавления в закладки';
            clinic_controller.block_over_textbox = 'Получите доступ ко всем возможностям <?php echo SITE_NAME; ?>!';
        });
        clinic_controller.init();
    });
</script>
    <div class="inner flo">
        <div class="clinic-landing">
            <div class="main-box">
                <div class="head-info flo">
                    <div class="rating">

                        <?php echo RateViewHelper::view($clinic->rate); ?>
                        <?php if (count($clinic->reviews)): ?>
                            <div class="comments-count">
                                <a href="#reviews"><?php echo StringHelper::getCorrectSuffixForReview(count($clinic->reviews));?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h1><?php echo $clinic->name; ?></h1>
                    <p>
                        <?php if ($clinic->metro_station): ?>
                            <?php if ($clinic->metro_station->metro_branch): ?>
                                <?php echo MetroBranchIconViewHelper::getImage($clinic->metro_station->metro_branch)?>
                            <?php endif; ?>
                            <?php echo $clinic->metro_station->name; ?> <br  />
                        <?php endif; ?>
                        <?php echo $clinic->address; ?>
                    </p>
                </div>
                <div class="vis-block">
                    <div class="nav"> <!--<span class="inf">Первый визит: <strong>бесплатно</strong></span>-->
                        <ul>
                            <li class="tab-foto"><a href="#tabs-1"><i></i>Фото</a></li>
                            <li class="tab-map"><a href="#tabs-2" class="map-link"><i></i>Карта</a></li>
                        </ul>
                    </div>
                    <div class="content">
                        <div id="tabs-1" class="section">
                            <?php $this->block('clinic/blocks/photo_carousel'); ?>
                        </div>
                        <div id="tabs-2" class="section">
                            <div class="map-block" id="map-block" style="width: 658px; height: 360px">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="side-box">
                <div class="btns">
                    <a href="#our-doctors" class="clinic-side-box-button btn-find-doctor-2">Найти врача</a>
                    <?php if (isset($example_page)): ?>
                        <span class="clinic-side-box-button btn-bookmark btn-bookmark-big" onclick="return false">
                            <i class="icon-add"></i>
                            <span class="txt">Добавить в закладки</span>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="time_clinic">
                    <?php if($clinic->is_day_and_night):?>
                        <p class="h-txt">Часы работы:</p>
                        <p class="time-line time-line-center">круглосуточная</p>
                    <?php else:?>
                        <?php echo ScheduleViewHelper::view($clinic); ?>
                    <?php endif; ?>
                    <p class="our_time">
                        <?= HelpPhoneNumberViewHelper::renderPhoneBlock(<<<HTML
                        <span class="h-txt">Запись на прием:</span><br/>
                        {%phone%}
HTML
);
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="full-width">
        <div class="inner flo">

            <div class="info-col col-about">
                <i class="icon"></i>
                <div class="about-cont">
                    <h3>О клинике</h3>
                    <?php echo $clinic->about; ?>
                </div>
                <a class="more-link">Узнать больше</a>
            </div>

            <?php if ($clinic->specializations): ?>
                <div class="info-col col-services">
                    <i class="icon"></i>
                    <h3>Виды услуг</h3>
                    <ul>
                        <?php foreach ($clinic->specializations as $specialization): ?>
                            <li class="service-link" data-specialty-id="<?php echo $specialization->getId(); ?>"><?php echo $specialization->name; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if (count($clinic->specializations)>14): ?>
                        <a class="more-link" href="javascript:void(0);">Узнать больше</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($clinic->features):?>
                <div class="info-col col-comfort">
                    <i class="icon"></i>
                    <h3>Удобства</h3>
                    <ul>
                        <?php foreach ($clinic->features as $feature) :?>
                            <li><?php echo $feature->name; ?></li>
                        <?php endforeach?>
                    </ul>
                    <?php if (count($clinic->features)>14): ?>
                        <a class="more-link">Узнать больше</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <div class="inner-2">
        <?php if ($clinic_reviews):?>
            <div id="reviews">
                <div class="heading-line">
                    <h2><span>ОТЗЫВЫ О КЛИНИКЕ</span></h2>
                </div>
                <div class="item-row flo">
                    <?php $counter = 1; ?>
                    <?php foreach($clinic_reviews as $review): ?>
                        <div id="clinic-review-<?php echo $review->getId(); ?>" class="review-box flo <?php if ($counter % 2 === 0) echo 'fright'; ?>">
                            <span class="chk-pic"></span>
                            <div class="aside">
                                <p class="name"><?php echo $review->visit->account->full_name; ?></p>
                                <div class="rating-item">
                                    <p>Сервис в регистратуре</p>
                                    <?php echo RateViewHelper::view($review->service_at_the_reception); ?>
                                </div>
                                <div class="rating-item">
                                    <p>Время ожидания</p>
                                    <?php echo RateViewHelper::view($review->waiting_time); ?>
                                </div>
                                <div class="rating-item">
                                    <p>Атмосфера</p>
                                    <?php echo RateViewHelper::view($review->relationship); ?>
                                </div>
                                <div class="rating-item">
                                    <p>Соответствие цене</p>
                                    <?php echo RateViewHelper::view($review->value_for_money); ?>
                                </div>
                                <div class="rating-item">
                                    <p>Диагноз и дальнейшее лечение ясны</p>
                                    <?php echo RateViewHelper::view($review->diagnosis_is_clear); ?>
                                </div>
                            </div>

                            <div class="review-cont">
                                <?php if ($clinic->advice_rate): ?>
                                    <div class="advice">
                                        <?php echo RateViewHelper::viewAdvise($clinic->advice_rate); ?>
                                        <span>Посоветуют друзьям</span>
                                    </div>
                                <?php endif; ?>
                                <p><?php echo $review->text; ?></p>
                            </div>
                        </div>

                        <?php $counter++; ?>
                    <?php endforeach; ?>

                    <div id="review-container"></div>
                </div>

                <?php if ($all_reviews && $all_reviews > 4) : ?>
                    <div id="view_more_reviews">
                        <a href="javascript:void(0)" class="view-more" id="more_reviews"><i></i>Показать ещё 10 отзывов</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="divider-shadow"></div>

        <div id="our-doctors">
            <div class="heading-line">
                <h2><span>НАШИ ВРАЧИ</span></h2>
            </div>
            <div class="select-area flo" id="doctor_search_form">
                <div class="sel-box">
                    <select data-placeholder="Специальность врача" name="specialty_id" class="chzn-select" style="width:308px;">
                        <?php $this->specialties = $specialties; ?>
                        <?php $this->block('blocks/specialties_options'); ?>
                    </select>
                </div>
                <div class="sel-box" id="purpose_of_visit_block">
                    <select data-placeholder="Цель визита" name="purpose_of_visit_id" class="chzn-select" style="width:308px;">
                        <option value=""></option>

                    </select>
                </div>
                <div class="sel-box">
                    <select data-placeholder="Время визита" name="time_of_visit" class="chzn-select" style="width:308px;">
                        <option></option>
                        <option value="any">в любое время</option>
                        <option value="weekend">в выходные дни</option>
                        <option value="evening">вечером</option>
                        <option value="leave_house">выезд на дом</option>
                        <option value="morning">утром</option>
                    </select>
                </div>
            </div>

            <div class="item-row flo">
                <div id="doctor-container">
                </div>
            </div>

            <div id="view_more_doctors">
                <a class="view-more" id="more_doctors" href="javascript:void(0)"><i></i>Показать ещё</a>
            </div>

        </div>
    </div>



<script>
    $(function() {
        <?php if (count($clinic->images)>5): ?>
            $('.connected-carousels .next-navigation').removeClass('inactive');
        <?php endif; ?>

        $( ".vis-block" ).tabs();
        $(".schedule-extended ul").each(function(e){
            $(this).nextAll('a').addClass('nav-'+e);
            $(".schedule-extended ul").eq(e).carouFredSel({
                auto: false,
                prev: {
                    button:".prev.nav-"+e
                },
                next: {
                    button:".next.nav-"+e
                },
                scroll:{items:1},
                circular: false,
                infinite:false
            });
        });
    });

    <?php if (!$clinic->images): ?>
        $(document).ready(function (){
            $('.tab-foto').css('display', 'none');
            $('.nav .tab-map').addClass('ui-state-active');
            $('#tabs-1').hide();
            $('#tabs-2').show();
        });
    <?php endif; ?>
</script>
<script type="text/javascript">
    var is_test = 1;
</script>
