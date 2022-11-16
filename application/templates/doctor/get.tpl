<?php
/**
 * @var DoctorModel $doctor
 * @var int $reviews_count
 * @var EqualDoctorModel[] $equal_doctors
 * @var DoctorReviewModel[] $reviews
 * @var DoctorReviewModel $review
 * @var DoctorReviewModel[] $all_reviews
 * @var int|null $minPrice
 * @var int|null $maxPrice
 */
?>

<?php
if (isset($_COOKIE['already_registred_account']))
    $already_registred_account = 1;
else
    $already_registred_account = 0;
$this->reviewId = 'doctor';
?>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var doctor_controller = new DoctorPageController(
            <?= $doctor->getId() ?>,
            <?= !(isset($landing_page) && !Acc::isAuthed()) ?>,
            <?= $already_registred_account ?>,
            "<?= $_SERVER['REQUEST_URI'] ?>",
            <?= isset($recording) ? $recording : 0 ?>,
            <?= (int) $doctor->my_doctor ?>
        );
        $('.btn-appoint').click(function () {
            doctor_controller.block_title = 'для записи к врачу';
            doctor_controller.block_over_textbox = 'Введите почту и продолжайте запись!'
        });
        $('.btn-bookmark').click(function () {
            doctor_controller.block_title = 'для добавления в закладки';
            doctor_controller.block_over_textbox = 'Получите доступ ко всем возможностям <?php echo SITE_NAME; ?>!';
        });
        doctor_controller.city_id = <?php echo $city_id; ?>;
        doctor_controller.init();

        var carousel_controller = new CarouselController(370);
        carousel_controller.init();

        var reviewController;
        $('.review-link').click(function () {
            $(this).fancybox();
            if (!reviewController) {
                reviewController = new AddReviewBlockController(null, null, '<?= $this->reviewId ?>');
                reviewController.doctor_id = <?= (int)$doctor->id ?>;
                reviewController.init();
            }
        });
    });
</script>

<?php $this->doctor_page = 1; ?>
<?php $this->block('blocks/top_number'); ?>
<div itemscope itemtype="http://schema.org/Physician">
<div class="inner flo refactor-content-styles <?php echo (isset($is_red) && $is_red == 1) ? 'red' : ''; ?>">
    <link itemprop="telephone" content="<?= $doctor->clinic && $doctor->clinic->top_phone ? $doctor->clinic->top_phone : HelpPhoneNumberViewHelper::getPhoneNumber() ?>">
    <div class="doctor-landing doctor-card-<?php echo $doctor->getId(); ?>">
        <ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">
            <li itemprop="itemListElement" itemscope
                itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="/" class="breadcrumb__link">
                    <span itemprop="name">Главная</span></a> -&nbsp;
                <meta itemprop="position" content="1" />
            </li>
            <li itemprop="itemListElement" itemscope
                itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="/doctor" class="breadcrumb__link">
                    <span itemprop="name">Врачи</span></a> -&nbsp;
                <meta itemprop="position" content="2" />
            </li>
            <li>
                <span>
                    <span><?= htmlspecialchars($doctor->full_name) ?></span>
                </span>
            </li>
        </ol>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="doc-info-col">
                    <div class="row">
                        <div class="col-lg-5 col-md-6 col-sm-6">
                            <div class="connected-carousels">
                                <div class="stage">
                                    <div class="carousel carousel-stage">
                                        <?php echo DoctorAvatarViewHelper::viewOnPage($doctor, 178, 247); ?>
                                    </div>
                                </div><!-- end class "stage" -->
                                <?php echo DoctorAvatarViewHelper::viewOnPageForCarousel($doctor, 38, 29); ?>

                                <div class="clearfix"></div>
                                <?php if (!empty($current_account) && $current_account->is_call_centre_operator && $doctor->not_work) { ?>
                                    <div class="not-work-message">
                                        НЕ РАБОТАЕМ
                                    </div>
                                <?php } ?>
                            </div><!-- end class "connected-carousels" -->
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="descr">
                                <h1 class="name refactor-name-style" itemprop="name"><?= htmlspecialchars($doctor->full_name); ?></h1>
                                <div class="specialties">
                                        <span class="post" itemprop="potentialAction">
                                            <?php echo $doctor->specialties_names_links; ?>
                                        </span>
                                </div>
                                <?php if ($minPrice !== null && $maxPrice !== null) { ?>
                                    <meta itemprop="priceRange" content="<?= $minPrice ?> - <?=  $maxPrice ?> руб."/>
                                <?php } ?>

                                <div class="rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                                    <meta itemprop="name" content="<?php echo $doctor->full_name; ?>"/>
                                    <meta itemprop="ratingValue" content="<?= htmlspecialchars($doctor->rate) ?>">

                                    <?php echo RateViewHelper::view($doctor->rate, 0, $doctor->is_best); ?>
                                    <?php if ($doctor->is_best) { ?>
                                        <div class="is_best_recomm">Рекомендуем</div>
                                    <?php } ?>

                                    <?php if (!empty($reviews_count)): ?>
                                        <a href="#reviews" class="comments-count refactor-comments-count-styles">
                                            читать отзывы (<span itemprop="reviewCount"><?php echo $reviews_count; ?></span>)
                                        </a>
                                    <?php endif ?>

                                    <?php $work_experience = $doctor->work_experience; ?>
                                    <?php if ($work_experience) { ?>
                                        <div class="work-experience">
                                            Стаж: <span><?php echo $work_experience; ?></span>
                                        </div>
                                    <?php } ?>

                                    <div class="cost-initial-reception">
                                        <?php
                                        foreach ($purpose_prices AS $ppKey => $ppValue) {
                                            ?>
                                            <div class="cost-visit-clinic" data-clinik-id="<?php echo $ppKey; ?>">
                                                <?php if( !empty($ppValue['spec']) ): $active = "active"; foreach($ppValue['spec'] as $specKey => $specValue ):?>
                                                    <div class="cost-visit-specialization <?=$active?>" data-specialization="<?php echo $specKey; ?>">

                                                        <?php if ($specValue['first_price']['price']) { ?>
                                                            <?php echo $specValue['first_price']['name']; ?>: <span>от <?php echo $specValue['first_price']['price']; ?> руб.</span><br />
                                                        <?php } ?>
                                                        <?php if ($specValue['second_price']['price']) { ?>
                                                            <?php echo $specValue['second_price']['name']; ?>: <span>от <?php echo $specValue['second_price']['price']; ?> руб.</span><br />
                                                        <?php } ?>

                                                    </div>
                                                <?php $active ="";  endforeach; else: ?>
                                                    <?php if ($ppValue['first_price']['price']) { ?>
                                                        <?php echo $ppValue['first_price']['name']; ?>: <span>от <?php echo $ppValue['first_price']['price']; ?> руб.</span><br />
                                                    <?php } ?>
                                                    <?php if ($ppValue['second_price']['price']) { ?>
                                                        <?php echo $ppValue['second_price']['name']; ?>: <span>от <?php echo $ppValue['second_price']['price']; ?> руб.</span><br />
                                                    <?php } ?>
                                                <?php endif; ?>

                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <div class="btns">
                                        <div id="btn-block"></div>
                                        <?php $this->block('doctor/blocks/card_buttons'); ?>
                                    </div>
                                    <?php foreach ($doctor->clinics as $i => $clinic) { ?>
                                        <?php if ($clinic->top_phone) { ?>
                                            <div class="registration-phone clinic-top-phone"
                                                 data-clinic-id="<?= $clinic->id ?>"
                                                 style="display: <?= $i ? 'none' : 'block' ?>;">
                                                или по телефону: <?= $clinic->top_phone ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div><!-- end class "descr" -->
                        </div>
                    </div>
                </div><!-- end class "doc-info-col" -->

            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">

                <div class="main-cont doctor-page-cont">
                    <div class="info-box">
                        <!--<h4>Время работы врача:</h4>-->
                        <div class="location-box">
                            <ul class="tabs flo">
                                <?php
                                $number = 1;
                                $clinic_numbers = array();
                                ?>
                                <?php foreach ($doctor->clinics as $clinic): ?>
                                    <?php
                                    if ($number == 1)
                                        $clinic_numbers[$clinic->getId()] = '';
                                    else
                                        $clinic_numbers[$clinic->getId()] = $number - 1;
                                    ?>
                                    <li data-id="<?php echo $clinic->getId(); ?>" class="loc-<?php echo $number ?>">
                                        <span>
                                            <?php echo $number; ?>
                                        </span>
                                    </li>
                                    <?php $number++; ?>
                                <?php endforeach; ?>
                            </ul>

                            <div class="box">
                                <?php $section_number = 1; ?>
                                <?php foreach ($doctor->clinics as $clinic): ?>
                                    <div class="section refactor-section-styles section-<?php echo $section_number; ?> visible flo"<?php if (!$clinic->latitude || !$clinic->longitude) { ?> style="height: 45px;" <?php } ?>>
                                        <div class="avatar refactor-avatar-styles">
                                            <?php echo ClinicAvatarViewHelper::viewOnCard($clinic, 74, 31); ?>
                                        </div>
                                        <p class="name-center">
                                            <strong><a href="<?php echo ClinicPageLinkViewHelper::getLink($clinic); ?>"><?php echo $clinic->name; ?></a></strong>
                                        </p>
                                        <div class="location">
                                            <!-- <div class="trigger">
                                            <?php echo $section_number; ?>
                                             </div>-->
                                            <p class="name-inf" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">

                                                <?php if ($clinic->metro_station): ?>
                                                    <?php if ($clinic->metro_station->metro_branch): ?>
                                                        <?php echo MetroBranchIconViewHelper::getImage($clinic->metro_station->metro_branch) ?>
                                                    <?php endif; ?>
                                                    <?php echo $clinic->metro_station->name; ?> <br  />
                                                <?php endif; ?>
                                                <span itemprop="streetAddress">
                                                    <?php echo StringHelper::trim($clinic->address); ?>
                                                </span>
                                            </p>
                                        </div>
                                        <div class="clearfix"></div>
                                        <?php if ($clinic->latitude && $clinic->longitude) { ?>
                                            <script type="application/javascript">
                                                var myMap;

                                                ymaps.ready(function () {
                                                myMap = new ymaps.Map("map-block<?php echo $clinic->id; ?>", {
                                                center: [<?php echo $clinic->latitude; ?>, <?php echo $clinic->longitude; ?>],
                                                zoom: 15,
                                                controls: []
                                                });

                                                myMap.controls
                                                .add("zoomControl", {
                                                // Расположим кнопку пробок слева
                                                float: "left",
                                                position: {
                                                top: 15,
                                                left: 10
                                                }
                                                });

                                                myPlacemark1 = new ymaps.Placemark([<?php echo $clinic->latitude; ?>, <?php echo $clinic->longitude; ?>], {
                                                iconContent: '<?php echo $section_number; ?>'
                                                }, {
                                                preset: 'twirl#blueIcon'
                                                });

                                                myMap.geoObjects.add(myPlacemark1);
                                                });
                                            </script>
                                            <div class="map-block<?php echo $clinic->id; ?> map-block" id="map-block<?php echo $clinic->id; ?>" style="width: 435px; height: 90px; margin: 10px 0 0;">
                                            </div>
                                        <?php } ?>
                                        <!--
                                        <div class="price-inf">
                                        <?php
                                        $first_visit_price = $doctor->getFirstVisitPriceByClinicId($clinic->getId());
                                        if ($first_visit_price == '0')
                                            $first_visit_price = ' Бесплатно';
                                        elseif ($first_visit_price)
                                            $first_visit_price = ': ' . $first_visit_price . ' руб.';
                                        else
                                            $first_visit_price = '';

                                        $second_visit_price = $doctor->getSecondVisitPriceByClinicId($clinic->getId());
                                        if ($second_visit_price == '0')
                                            $second_visit_price = ' Бесплатно';
                                        elseif ($second_visit_price)
                                            $second_visit_price = ': ' . $second_visit_price . ' руб.';
                                        else
                                            $second_visit_price = '';
                                        ?>
                                            <p>Первый визит<strong><?php echo $first_visit_price ?></strong></p>
                                            <p>Повторный визит<strong><?php echo $second_visit_price ?></strong></p>

                                        </div>
                                        -->
                                        <?php $doctor_clinic_specialties = $doctor->getSpecialtiesByClinicId($clinic->getId()); ?>
                                        <?php $existing_schedule = $doctor->checkExistingDoctorScheduleByClinicIdAndClinicSpecialties($clinic->getId(), $doctor_clinic_specialties); ?>

                                        <div class="schedule-extended schedule-clinic-<?= $clinic->getId() ?>">
                                            <div class="schedule-extended__title">График приема врача:</div>
                                            <ul class="schedule flo">
                                                <?php $monday = date("d.m.Y", strtotime("Monday this week")); ?>
                                                <?php for ($day = 1; $day <= 14; $day++): ?>
                                                    <?php $cur_day_time = strtotime($monday) + 86400 * ($day - 1); ?>
                                                    <li class="day active schedule-tim">
                                                        <i><?php echo DayViewHelper::shortDay(date($cur_day_time)); ?></i>
                                                        <p><?php echo date('d-m', $cur_day_time); ?></p>
                                                        <?php if ($existing_schedule): ?>
                                                            <?php $show = true; ?>
                                                            <?php foreach ($doctor_clinic_specialties as $specialty): ?>
                                                                <?php $doctor_schedule = ModelManagerFactory::getByName('doctor_schedule')->getOneCurrentByDoctorIdAndClinicIdAndSpecialtyId($doctor->getId(), $clinic->getId(), $specialty->getId()); ?>
                                                                <?php if ($show): ?>
                                                                    <?php if ($doctor_schedule && $show): ?>
                                                                        <?php $work_time = $doctor_schedule->getDoctorWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                                    <?php else: ?>
                                                                        <?php $work_time = $clinic->getClinicWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                                    <?php endif; ?>
                                                                    <?php if ($work_time) $show = false; ?>
                                                                    <div class="gray-separator"></div>
                                                                    <?php if ($work_time): ?>
                                                                        <a href="javascript:void(0)" class="record-day-pick"><?php echo $work_time; ?></a>
                                                                    <?php else: ?>
                                                                        <a href="javascript:void(0)" class="record-day-pick hide"></a>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <?php $work_time = $clinic->getClinicWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>

                                                            <div class="gray-separator"></div>

                                                            <?php if ($work_time): ?>
                                                                <a href="javascript:void(0)" class="record-day-pick"><?php echo $work_time; ?></a>
                                                            <?php else: ?>
                                                                <a href="javascript:void(0)" class="record-day-pick hide"></a>
                                                            <?php endif; ?>
                                                        <?php endif ?>
                                                    </li>
                                                <?php endfor; ?>
                                            </ul>
                                            <a class="prev-nav" href="javascript:void(0)"></a>
                                            <a class="next-nav" href="javascript:void(0)"></a>
                                        </div>

                                    </div>
                                    <?php $section_number++; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php $this->block('doctor/blocks/call-centre-operator-hint'); ?>
                    </div>
                </div><!-- end class "main-cont" -->


            </div>
        </div>







    </div> <!-- end class "doctor-landing" -->
</div><!-- end class "inner" -->

<div class="full-width refactor-description-doctor-styles">
    <div class="inner flo">
        <div class="row">
            <div class="col-lg-7">
                <?php if ($doctor->about || $doctor->about_internal): ?>
                    <div class="info-col col-about-doctor">
                        <i class="icon"></i>
                        <div class="about-cont">
                            <?php if (!empty($doctor->full_name)) { ?>
                                <h3>О враче: <?php echo $doctor->full_name; ?></h3>
                            <?php } else { ?>
                                <h3>О враче</h3>
                            <?php } ?>
                            <div id="about-content">
                                <?php if ($doctor->about_internal) { ?>
                                    <div class="about-content-internal">
                                        <?= $doctor->about_internal ?>
                                    </div>
                                <?php } ?>
                                <?php if ($doctor->about) { ?>
                                    <div class="about-content-main">
                                        <?php echo $doctor->about; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <a class="more-link">Узнать больше</a>
                    </div>
                <?php endif; ?>

            </div>
            <div class="col-lg-5">
                <div class="st-col">

                    <?php if ($doctor->education): ?>
                        <div class="info-col col-education">
                            <i class="icon"></i>
                            <h3>Образование</h3>
                            <div id="education-content">
                                <?php echo $doctor->education; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($doctor->academic_title): ?>
                        <div class="info-col col-cert">
                            <i class="icon"></i>
                            <h3>Ученые степени</h3>
                            <div id="academic-title-content">
                                <?php echo $doctor->academic_title; ?>
                            </div>
                            <div class=""></div>
                        </div>
                    <?php endif; ?>

                    <?php if ($doctor->certificate): ?>
                        <div class="info-col col-cert">
                            <i class="icon"></i>
                            <h3>Сертификаты</h3>
                            <div id="certificate-content">
                                <?php echo $doctor->certificate; ?>
                            </div>
                        </div>
                    <?php endif ?>

                    <?php if ($doctor->course): ?>
                        <div class="info-col col-associations"><i class="icon"></i>
                            <h3>Курсы повышения квалификации</h3>
                            <div id="course-content">
                                <?php echo $doctor->course; ?>
                            </div>
                        </div>
                    <?php endif ?>

                </div>
            </div>
        </div>


    </div>
</div>

<div class="inner-2 refactor-doctor-page-style">
    <?php if (count($reviews)): ?>
        <div id="reviews">
            <div class="heading-line">
                <h2>
                    <span>
                        ОТЗЫВЫ О ВРАЧЕ:
                        <br />
                        <?php if (!empty($doctor->full_name)) { ?>
                            <?php echo mb_strtoupper($doctor->full_name, 'utf-8'); ?>
                        <?php } ?>
                    </span>

                </h2>
            </div>
            <div class="item-row flo">
                <?php $this->block('/blocks/reviews-list'); ?>

            </div>

            <?php if ($all_reviews && $all_reviews > 4) { ?>
                <div id="view_more_button" style="margin-bottom: 15px;">
                    <a href="javascript:void(0)" class="view-more"><i></i>Показать ещё 10 отзывов</a>
                </div>
            <?php } ?>
        </div>
    <?php endif; ?>

    <div class="heading-line doctor-page">
        <div class="button-container refactor-button-container_styles"<?php if (empty($doctor_to_slider) && empty($doctor_to_slider['doctors'])) { ?>style="margin-bottom: 10px;" <?php } ?>>
            <?php $this->doctor = $doctor; ?>
            <?php $this->block('/doctor/blocks/doctor_appointment'); ?>
            <div class="registration-phone">
                или по телефону: <?= !empty($topPhone) ? $topPhone : HelpPhoneNumberViewHelper::getPhoneNumber() ?>
            </div>
        </div>
    </div>

    <?php if (!empty($doctor_to_slider) && !empty($doctor_to_slider['doctors'])) { ?>
        <?php // /api/doctor/getDoctorSliders?token=ba858827a1e7f5e54c01fc5b979c6041&doctor_id=2113  ?>
        <div class="doctors-slider">
            <div class="dsTitle">
                <?php echo $doctor_to_slider['title']; ?>
            </div>

            <?php $this->single_doctor_page = 0; ?>
            <?php $this->doctors = $doctor_to_slider['doctors']; ?>
            <?php $this->block('/doctor/blocks/slider_cards_doctors'); ?>
        </div>
    <?php } ?>

    <div class="back-to-search-area">
        <?php
        $data_return = SiteUriHelper::returnToSearchForm();
        if (!empty($data_return['count']) && !empty($data_return['link'])) {
            ?>
            <a class="back-to-search doctor-bottom-page" href="<?php echo $data_return['link']; ?>"> Вернуться к результатам поиска <span>(<?php echo $data_return['count'] . ' ' . SpecialtyHelper::getDoctorWordForm($data_return['count']); ?>)</span></a>
        <?php } elseif (SiteUriHelper::previousPageIsClinicPage()) { ?>
            <a class="back-to-search doctor-bottom-page" href="<?php echo $_SERVER['HTTP_REFERER']; ?>"> Вернуться на страницу клиники </a>
        <?php } ?>
    </div>
    <?php if (isset($equal_doctors) && $equal_doctors): ?>
        <?php $this->equal_elements_type = 'doctor'; ?>
        <?php $this->doctor = $doctor; ?>
        <?php $this->equal = $equal_doctors; ?>
        <?php $this->block('blocks/equal_elements'); ?>
    <?php endif; ?>
</div>
</div>

<?php $this->block('blocks/adv/content_page_tiezerlady'); ?>
