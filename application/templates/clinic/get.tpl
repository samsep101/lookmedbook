<?php
/**
 * @var ClinicModel           $clinic
 * @var ClinicReviewModel[]   $clinic_reviews
 * @var ClinicReviewModel[]   $all_reviews
 * @var SpecialtyModel[]      $specialties
 * @var PurposeOfVisitModel[] $purposes
 */
?>

<?php
if (isset($_COOKIE['already_registred_account'])) {
    $already_registred_account = 1;
} else {
    $already_registred_account = 0;
}
$reviewId = 'clinic';

$mapZoomOptions = htmlentities(
    json_encode(
        [
            'position' => [
                'top' => 10,
            ]
        ]
    )
);
?>

<script type="text/javascript">
    $(document).ready(function () {
        var clinic_controller = new ClinicPageController('<?= $clinic->id?>', '<?= $clinic->latitude; ?>', '<?= $clinic->longitude; ?>', <?= (isset($landing_page) && !Acc::isAuthed(
            )) ? false : true; ?>, <?= $already_registred_account; ?>, "<?= $_SERVER['REQUEST_URI']; ?>");
        $('.btn-bookmark').click(function () {
            clinic_controller.block_title = 'для добавления в закладки';
            clinic_controller.block_over_textbox = 'Получите доступ ко всем возможностям <?= SITE_NAME; ?>!';
        });
        clinic_controller.city_id = <?= $clinic->city_id; ?>;

        <?php if(!empty($main_specialty)) { ?>
        clinic_controller.main_specialty = <?= $main_specialty->getId(); ?>;
        <?php } ?>

        clinic_controller.init();

        var reviewController;
        $('.review-link').click(function () {
            $(this).fancybox();
            if (!reviewController) {
                reviewController = new AddReviewBlockController(null, null, '<?= $reviewId ?>');
                reviewController.clinic_id = <?= (int)$clinic->id ?>;
                reviewController.init();
            }
        });
    });
</script>
<div itemscope itemtype="http://schema.org/Organization">
<div class="inner flo">
    <?php if (SiteUriHelper::refererFromClinicPage()): ?>
        <a class="back-to-search-link" href="<?= $_SERVER['HTTP_REFERER']; ?>">&larr; Назад к результатам
            поиска</a>
    <?php endif; ?>
    <ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">
        <li itemprop="itemListElement" itemscope
            itemtype="http://schema.org/ListItem">
            &nbsp;
            &nbsp;
            <a itemprop="item" href="/" class="breadcrumb__link">
                <span itemprop="name">Главная</span></a> -&nbsp;
            <meta itemprop="position" content="1"/>
        </li>
        <li itemprop="itemListElement" itemscope
            itemtype="http://schema.org/ListItem">
            <a itemprop="item" href="/clinic" class="breadcrumb__link">
                <span itemprop="name">Клиники</span></a> -&nbsp;
            <meta itemprop="position" content="2"/>
        </li>
        <li>
                    <span>
                    <span><?= htmlentities($clinic->name) ?></span></span>
        </li>
    </ol>

    <div id="clinic-card-<?= $clinic->id ?>"
         class="clinic-landing"
         data-phone="<?= $clinic->top_phone ?>"
         data-info="<?= htmlentities($this->renderInString('clinic/blocks/record_popup_info')) ?>"
    >
        <meta itemprop="url" content="<?= ClinicPageLinkViewHelper::getLink($clinic); ?>">
        <div class="main-box">
            <div class="head-info flo">
                <div class="rating">
                    <?= RateViewHelper::view($clinic->rate, 0, $clinic->is_best); ?>

                    <?php if ($clinic->is_best) { ?>
                        <div class="is_best_recomm">Рекомендуем</div>
                    <?php } ?>

                    <?php if (count($clinic->reviews)): ?>
                        <div class="comments-count" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                            <meta itemprop="name" content="<?= $clinic->name; ?>"/>
                            <meta itemprop="ratingValue" content="<?= htmlspecialchars($clinic->getRealRate()) ?>">
                            <meta itemprop="reviewCount" content="<?= count($clinic->reviews) ?>">
                            <a href="#reviews">
									<span>
										<?= StringHelper::getCorrectSuffixForReview(count($clinic->reviews)); ?>
									</span>
                            </a>
                        </div>
                    <?php endif; ?>

                </div>
                <h1 itemprop="name"><?= $clinic->name; ?></h1>
                <p>
                    <?php if ($clinic->metro_stations): ?>
                        <?php foreach ($clinic->metro_stations as $metro_station) { ?>
                            <?php if ($metro_station->metro_branch): ?>
                                <?= MetroBranchIconViewHelper::getImage($metro_station->metro_branch); ?>
                            <?php endif; ?>
                            <?= $metro_station->name; ?><br>
                        <?php } ?>
                    <?php endif; ?>
                    <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
							<span itemprop="streetAddress"><?= $clinic->address; ?></span>
                    </span>
                </p>
            </div>
            <div class="vis-block">
                <?php $this->block('clinic/blocks/photo_carousel'); ?>
            </div>
        </div>
        <div class="side-box">
            <div class="clinic-map-container text-center">
                <div class="map-block" id="map-block" style="width: 440px; height: 260px" data-zoom-options="<?= $mapZoomOptions ?>">
                </div>
                <a class="map-block-btn btn" href="javascript:">Показать карту</a>
            </div>

            <div class="clinic-contacts">
                <div class="clinic-contacts__row">
                    <div class="clinic-contacts__icon glyphicon glyphicon-map-marker"></div>
                    <div class="clinic-contacts__block">
                        <?= $clinic->address; ?>
                        <?php if ($clinic->metro_stations): ?>
                            <?php foreach ($clinic->metro_stations as $metro_station) { ?>
                                <div class="clinic-contacts__metro">
                                    <?php if ($metro_station->metro_branch): ?>
                                        <?= MetroBranchIconViewHelper::getImage($metro_station->metro_branch); ?>
                                    <?php endif; ?>
                                    <?= $metro_station->name; ?>
                                </div>
                            <?php } ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="clinic-contacts__row">
                    <div class="clinic-contacts__icon glyphicon glyphicon-time"></div>
                    <div class="clinic-contacts__block">
                        <?php if ($clinic->is_day_and_night): ?>
                            круглосуточно
                        <?php else: ?>
                            <?= ScheduleViewHelper::view($clinic, false); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="clinic-contacts__row">
                    <div class="clinic-contacts__icon glyphicon glyphicon-earphone"></div>
                    <div class="clinic-contacts__block">
                            <?php
                            if ($clinic->top_phone || in_array($clinic->city_id, Application::config('global.available_city_ids'))) { ?>
                                <span class="clinic-contacts__phone" itemprop="telephone">
                                    <?php
                                    if ($clinic->top_phone) {
                                        $phone = $clinic->top_phone;
                                    } else {
                                        $phone = HelpPhoneNumberViewHelper::getPhoneNumber($city);
                                    }
                                    echo $phone;
                                    ?>
						        </span>
                            <?php } else { ?>
                                уточните на сайте клиники
                            <?php } ?>
                    </div>
                </div>
                <div class="clinic-contacts__row">
                    <div class="clinic-contacts__block">
                        <?php $this->block('clinic/blocks/call-centre-operator-hint'); ?>

                        <?php if (!empty($current_account) && $current_account->is_call_centre_operator && $clinic->not_work) { ?>
                            <div>НЕ РАБОТАЕМ</div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="btns" id="visit-remind-block-<?= $reviewId ?>">
                <?php if (!$clinic->visit_disallow): ?>
                    <?= $clinic->getRecordButton(3) ?>
                <?php endif; ?>

                <a class="clinic-side-box-button btn-bookmark btn-bookmark-big click_btn_bookmark hidden">
                    <script>
                        <?php if ($clinic->my_clinic): ?>
                        $('.btn-bookmark.btn-bookmark-big').addClass('btn-bookmark-added');
                        $('.btn-bookmark.btn-bookmark-big').html('<i class="icon-add"></i><span class="txt txt-added">В закладках</span>');
                        <?php else: ?>
                        $('.btn-bookmark.btn-bookmark-big').removeClass('btn-bookmark-added');
                        $('.btn-bookmark.btn-bookmark-big').html('<i class="icon-add"></i><span class="txt">Добавить в закладки</span>');
                        <?php endif; ?>
                    </script>
                </a>
                <a class="btn clinic-side-box-button review-link hidden" href="#add-review-popup-<?= $reviewId ?>">Оставить
                    отзыв</a>
            </div>
        </div>
    </div>
</div>

<div class="full-width">
    <div id="clinic-description" style="position:absolute; top: -30px;"></div>
    <div class="inner flo">

        <div class="info-col col-about">
            <i class="icon"></i>
            <div class="about-cont">
                <?php if (!empty($clinic->name)) { ?>
                    <h3>О клинике: <?= $clinic->name; ?></h3>
                <?php } else { ?>
                    <h3>О клинике</h3>
                <?php } ?>
                <div id="about-clinic-content">
                    <?= $clinic->about; ?>
                </div>
            </div>
            <a class="more-link">Узнать больше</a>
        </div>

        <?php if ($clinic->specializations): ?>
            <div class="info-col col-services">
                <i class="icon"></i>
                <h3>Виды услуг</h3>
                <ul>
                    <?php foreach ($clinic->specializations as $specialization): ?>
                        <?php if ($specialization->getMainSpecialty($clinic->getId())): ?>
                            <li class="service-link"
                                data-specialty-id="<?= $specialization->main_specialty->getId(); ?>">
                                <a class="like_service_ul" href="#our-doctors"><?= $specialization->name; ?></a>
                            </li>
                        <?php else: ?>
                            <li class="service-link"><?= $specialization->name; ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <?php if (count($clinic->specializations) > 14): ?>
                    <a class="more-link" href="javascript:void(0);">Узнать больше</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($clinic->features): ?>
            <div class="info-col col-comfort">
                <i class="icon"></i>
                <h3>Удобства</h3>
                <ul>
                    <?php foreach ($clinic->features as $feature) : ?>
                        <li><?= $feature->name; ?></li>
                    <?php endforeach ?>
                </ul>
                <?php if (count($clinic->features) > 14): ?>
                    <a class="more-link">Узнать больше</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($serviceTree)) { ?>
    <div class="inner-2">
        <div class="heading-line">
            <h2><span>Цены на услуги</span></h2>
        </div>
        <?php $this->block('clinic/blocks/service_tree') ?>
    </div>
<?php } ?>

<div class="inner-2">

    <div id="our-doctors">
        <div class="heading-line">
            <h2><span>ВРАЧИ КЛИНИКИ</span></h2>
            <h2><span>Выберите специалиста и запишитесь на прием:</span></h2>
        </div>
        <div class="select-area flo" id="doctor_search_form">
            <div class="sel-box">
                <select data-placeholder="Специальность врача" name="specialty_id" class="chzn-select"
                        style="width:308px;">
                    <?php $this->specialties = $specialties; ?>
                    <?php $this->block('blocks/specialties_options'); ?>
                </select>
            </div>
            <div class="sel-box" id="purpose_of_visit_block">
                <?php $this->purposes = $purposes; ?>
                <?php $this->select_style = 'width: 308px'; ?>
                <?php $this->block('ajax/purposes_select'); ?>
            </div>
            <div class="sel-box">
                <select data-placeholder="Время визита" name="time_of_visit" class="chzn-select"
                        style="width:308px;">
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

    <div class="divider-shadow" id="divider-shadow"></div>

    <?php if ($clinic_reviews): ?>
        <div id="reviews">
            <div class="heading-line">
                <h2>
						<span>
							ОТЗЫВЫ О КЛИНИКЕ:
							<br/>
							<?php if (!empty($clinic->name)) { ?>
                                <?= mb_strtoupper($clinic->name, 'utf-8'); ?>
                            <?php } ?>
						</span>
                </h2>
            </div>
            <div class="item-row flo">

                <?php
                $this->reviews = $clinic_reviews;
                $this->isClinic = 1;
                $this->block('/blocks/reviews-list');
                ?>

            </div>

            <?php if ($all_reviews && $all_reviews > 4) { ?>
                <div id="view_more_reviews">
                    <a href="javascript:void(0)" class="view-more" id="more_reviews"><i></i>Показать ещё 10 отзывов</a>
                </div>
            <?php } ?>
        </div>
    <?php endif; ?>
    <?php if ($actions): ?>
        <?php $this->block('clinic/blocks/actions'); ?>
    <?php endif; ?>
</div>
</div>

<script>
    $(function () {
        <?php if (count($clinic->images) > 5): ?>
        $('.connected-carousels .next-navigation').removeClass('inactive');
        <?php endif; ?>
    });
</script>

<?php $this->block('blocks/adv/content_page_tiezerlady'); ?>
