<?php
    /**
     * @var DoctorModel $doctor
     */
?>


<script type="text/javascript">
    $(document).ready(function(){
        var doctor_controller = new DoctorPageController(<?php echo $doctor->getId(); ?>, null, null,null,null);
        $('.btn-appoint').click(function(){
           doctor_controller.block_title = 'для записи к врачу';
           doctor_controller.block_over_textbox = 'Введите почту и продолжайте запись!'
        });
        $('.btn-bookmark').click(function(){
            doctor_controller.block_title = 'для добавления в закладки';
            doctor_controller.block_over_textbox = 'Получите доступ ко всем возможностям <?php echo SITE_NAME; ?>!';
        });
        doctor_controller.init();
    });
</script>

<div class="inner flo">
        <div class="doctor-landing">
            <div class="doc-info-col">
                <div class="connected-carousels">
                    <div class="stage">
                        <div class="carousel carousel-stage">
                            <?php echo DoctorAvatarViewHelper::viewOnPage($doctor, 178, 270); ?>
                        </div>
                    </div><!-- end class "stage" -->
                    <?php echo DoctorAvatarViewHelper::viewOnPageForCarousel($doctor, 38, 29); ?>
                </div><!-- end class "connected-carousels" -->
                <div class="descr">
                    <p class="name">
                        <a href="javascript:void(0)">
                            <span class="post">
                                <?php echo $doctor->specialties_names; ?>
                            </span>
                            <?php echo $doctor->full_name; ?>
                        </a>
                    </p>

                    <div class="rating">
                        <?php echo RateViewHelper::view($doctor->rate); ?>
                        <?php if ($doctor->reviews_count!=0):?>
                            <a href="#reviews" class="comments-count"><?php echo StringHelper::getCorrectSuffixForReview($doctor->reviews_count);?></a>
                        <?php endif?>
                        <div class="btns">
                            <div id="btn-block"></div>
                            <?php $example_page = (isset($example_page)) ? true : false; ?>
                            <?php $this->block('doctor/blocks/card_buttons'); ?>
                        </div>
                    </div>
                </div><!-- end class "descr" -->
            </div><!-- end class "doc-info-col" -->

            <div class="main-cont doctor-page-cont">
                <div class="info-box">
                    <!--<h4>Время работы врача:</h4>-->
                    <div class="location-box">
                        <ul class="tabs flo">
                            <?php
                            $number = 1;
                            $clinic_numbers = array();
                            ?>
                            <?php foreach($doctor->clinics as $clinic):?>
                            <?php
                            if ($number == 1)
                                $clinic_numbers[$clinic->getId()] = '';
                            else
                                $clinic_numbers[$clinic->getId()] = $number-1;
                            ?>
                            <li data-id="<?php echo $clinic->getId();?>" class="loc-<?php echo $number ?> active">
                                    <span>
                                        <?php echo $number; ?>
                                    </span>
                            </li>
                            <?php $number++; ?>
                            <?php endforeach; ?>
                        </ul>

                        <div class="box">
                            <?php $section_number = 1; ?>
                            <?php foreach($doctor->clinics as $clinic):?>
                            <div class="section section-<?php echo $section_number; ?> visible flo">
                                <div class="avatar">
                                    <?php echo ClinicAvatarViewHelper::viewOnCard($clinic, 74, 31); ?>
                                </div>
                                <p class="name-center">
                                    <strong><a><?php echo $clinic->name; ?></a></strong>
                                </p>
                                <div class="location">
                                    <!-- <div class="trigger">
                                             <?php echo $section_number; ?>
                                         </div>-->

                                    <p class="name-inf">
                                        <?php if ($clinic->metro_station): ?>
                                        <?php if ($clinic->metro_station->metro_branch): ?>
                                            <?php echo MetroBranchIconViewHelper::getImage($clinic->metro_station->metro_branch)?>
                                            <?php endif; ?>
                                        <?php echo $clinic->metro_station->name; ?> <br  />
                                        <?php endif; ?>
                                        <?php echo StringHelper::trim($clinic->address); ?>
                                    </p>
                                </div>
                                <!--
                                <div class="price-inf">
                                    <?php
                                    $first_visit_price = $doctor->getFirstVisitPriceByClinicId($clinic->getId());
                                    if ($first_visit_price == '0') $first_visit_price = ' Бесплатно';
                                    elseif ($first_visit_price) $first_visit_price = ': '.$first_visit_price.' руб.';
                                    else $first_visit_price = '';

                                    $second_visit_price = $doctor->getSecondVisitPriceByClinicId($clinic->getId());
                                    if ($second_visit_price == '0') $second_visit_price = ' Бесплатно';
                                    elseif ($second_visit_price) $second_visit_price = ': '.$second_visit_price.' руб.';
                                    else $second_visit_price = '';
                                    ?>
                                    <p>Первый визит<strong><?php echo $first_visit_price ?></strong></p>
                                    <p>Повторный визит<strong><?php echo $second_visit_price ?></strong></p>

                                </div>
-->
                                <?php $doctor_clinic_specialties = $doctor->getSpecialtiesByClinicId($clinic->getId());?>
                                <?php $existing_schedule = $doctor->checkExistingDoctorScheduleByClinicIdAndClinicSpecialties($clinic->getId(), $doctor_clinic_specialties);?>

                                <div class="schedule-extended schedule-clinic-<?= $clinic->getId() ?>">
                                    <div class="schedule-extended__title">График приема врача:</div>
                                    <ul class="schedule flo">
                                        <?php $monday = date("d.m.Y", strtotime("last Monday")); ?>
                                        <?php for ($day = 1; $day <= 14; $day++): ?>
                                        <?php $cur_day_time = strtotime($monday) + 86400*($day-1); ?> <!-- текущий день недели -->
                                        <li class="day active schedule-tim">
                                            <i><?php echo DayViewHelper::shortDay(date($cur_day_time));?></i>
                                            <p><?php echo date('d-m', $cur_day_time); ?></p>
                                            <?php if ($existing_schedule):?>
                                            <?php foreach ($doctor_clinic_specialties as $specialty): ?>
                                            <?php $doctor_schedule = ModelManagerFactory::getByName('doctor_schedule')->getOneCurrentByDoctorIdAndClinicIdAndSpecialtyId($doctor->getId(), $clinic->getId(), $specialty->getId()); ?>
                                            <?php if ($doctor_schedule): ?>
                                                <?php $work_time = $doctor_schedule->getDoctorWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                <?php else:?>
                                                <?php $work_time = $clinic->getClinicWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                <?php endif;?>

                                            <p class="post"><?php echo ChildSpecialtyHelper::changeFormat($specialty, $doctor); ?></p>
                                            <?php if ($work_time): ?>
                                                <a><?php echo $work_time;?></a>
                                                <?php else: ?>
                                                <a class="hide"></a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <?php else:?>
                                                <?php $work_time = $clinic->getClinicWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>

                                                <p class="post"><?php echo $doctor->getSpecialtiesNamesStringByClinicSpecialties($doctor_clinic_specialties); ?></p>
                                                <?php if ($work_time): ?>
                                                    <a><?php echo $work_time;?></a>
                                                <?php else: ?>
                                                    <a class="hide"></a>
                                                <?php endif; ?>
                                            <?php endif?>
                                        </li>
                                        <?php endfor; ?>
                                    </ul>
                                    <a class="prev-nav" href="javascript:void(0)"></a>
                                    <a class="next-nav" href="javascript:void(0)"></a>
                                </div>
                            </div>
                            <?php $section_number++;?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div><!-- end class "main-cont" -->
        </div> <!-- end class "doctor-landing" -->
    </div><!-- end class "inner" -->

    <div class="full-width">
        <div class="inner flo">
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
            <div class="st-col">
                <?php if ($doctor->education): ?>
                    <div class="info-col col-education">
                        <i class="icon"></i>
                        <h3>Образование</h3>
                        <p><?php echo $doctor->education; ?></p>
                    </div>
                <?php endif; ?>
                <?php if ($doctor->academic_title): ?>
                <div class="info-col col-cert"><i class="icon"></i>
                    <h3>Ученые степени</h3>
                    <p><?php echo $doctor->academic_title; ?></p>
                </div>
                <?php endif; ?>

                <?php if($doctor->certificate):?>
                    <div class="info-col col-cert">
                        <i class="icon"></i>
                        <h3>Сертификаты</h3>
                        <p><?php echo $doctor->certificate; ?></p>
                    </div>
                <?php endif?>

                <?php if($doctor->course):?>
                    <div class="info-col col-associations"><i class="icon"></i>
                        <h3>Курсы повышения квалификации</h3>
                        <p><?php echo $doctor->course; ?></p>
                    </div>
                <?php endif?>

            </div>
        </div>
    </div>

    <div class="inner-2">
        <?php if(count($reviews)):?>
            <div id="reviews">
                <div class="heading-line">
                    <h2><span>ОТЗЫВЫ О ВРАЧЕ</span></h2>
                </div>
                <div class="item-row flo">
                    <?php $counter = 1; ?>
                    <?php foreach($reviews as $review): ?>
                        <div id="doctor-review-<?php echo $review->visit->rating->getId(); ?>" class="review-box flo <?php if ($counter % 2 == 0) echo 'fright';?>">
                            <span class="chk-pic"></span>

                            <div class="aside">
                                <p class="name"><?= $review->getAccountNameOrFallback('full_name') ?></p>

                                <div class="rating-item">
                                    <p>Сервис в регистратуре</p>
                                    <?php echo RateViewHelper::viewDoctorReviewRate($review->service_at_the_reception); ?>
                                </div>
                                <div class="rating-item">
                                    <p>Время ожидания</p>
                                    <?php echo RateViewHelper::viewDoctorReviewRate($review->waiting_time); ?>
                                </div>
                                <div class="rating-item">
                                    <p>Атмосфера</p>
                                    <?php echo RateViewHelper::viewDoctorReviewRate($review->relationship); ?>
                                </div>
                                <div class="rating-item">
                                    <p>Соответствие цене</p>
                                    <?php echo RateViewHelper::viewDoctorReviewRate($review->value_for_money); ?>
                                </div>
                                <div class="rating-item">
                                    <p>Диагноз и дальнейшее лечение ясны</p>
                                    <?php echo RateViewHelper::viewDoctorReviewRate($review->diagnosis_is_clear); ?>
                                </div>
                            </div>
                            <div class="review-cont">
                                <?php if ($doctor->advice_rate): ?>
                                <div class="advice">
                                    <?php echo RateViewHelper::viewAdvise($doctor->advice_rate); ?>
                                    <span>Посоветуют друзьям</span>
                                </div>
                                <?php endif; ?>
                                <p><?php echo $review->text;?></p>
                            </div>
                        </div>
                    <?php $counter++; ?>
                    <?php endforeach;?>
                    <div id="review-container"></div>
                </div>
                <?php if ($all_reviews && $all_reviews > 4) : ?>
                    <div id="view_more_button">
                        <a href="javascript:void(0)" class="view-more"><i></i>Показать ещё 10 отзывов</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<script>
    $(function() {
        $('[data-jcarousel]').each(function() {
            var el = $(this);
            el.jcarousel(el.data());
        });

        $('[data-jcarousel-control]').each(function() {
            var el = $(this);
            el.jcarouselControl(el.data());
        });

        $(".schedule-extended ul").each(function (e) {
            $(this).parent('.schedule-extended').addClass('schedule-extended-'+e);
            $(this).nextAll('a').addClass('nav-' + e);
            $('.location-box .tabs li').on('click', function(){
                $('.schedule-extended-' + e + ' ul').carouFredSel({
                    auto: false,
                    prev:'.prev-nav.nav-'+ e,
                    next:'.next-nav.nav-'+ e,
                    scroll:{items:1},
                    circular: false,
                    infinite:false
                });
            });
            $('.location-box .tabs li:first-child').trigger('click');
        });

    });

    $(".connected-carousels .carousel-stage li a").fancybox({
        maxWidth	: 660,
        maxHeight	: 370,
        fitToView	: false,
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
    });
</script>
