<?php
    /**
     * @var ClinicModel $clinic
     */
?>
<script type="text/javascript">
$( window  ).load( function (){
    var mapController = '';
    $(document).ready(function(){
        mapController = new YandexMapController({});
        mapController.page = 'clinic';
        mapController.setDataUrl('/ajax/getClinicMapCard?big=1&id=');
        mapController.init();

        function loadClinicChilds()
        {
            var data = {
                datatype: 'clinic',
                primary_clinic_id:'<?=$clinic->id?>'
            };
            Ajax.Get('/ajax/getMapData', data, function (data) {
                mapController.setData(data.result);
            });
        }

        window.setTimeout(loadClinicChilds, 3000);
    });
});
</script>

<?php $this->block('blocks/top_number'); ?>
<div class="inner flo">
    <?php if (SiteUriHelper::refererFromClinicPage()): ?>
    <a class="back-to-search-link" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">&larr; Назад к результатам поиска</a>
    <?php endif; ?>
    <ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">
        <li itemprop="itemListElement" itemscope
            itemtype="http://schema.org/ListItem">
            &nbsp;
            &nbsp;
            <a itemprop="item" href="/">
                <span itemprop="name">Главная</span></a> -&nbsp;
            <meta itemprop="position" content="1" />
        </li>
        <li itemprop="itemListElement" itemscope
            itemtype="http://schema.org/ListItem">
            <a itemprop="item" href="/clinic">
                <span itemprop="name">Клиники</span></a> -&nbsp;
            <meta itemprop="position" content="2" />
        </li>
        <li itemprop="itemListElement" itemscope
            itemtype="http://schema.org/ListItem">
                    <span itemprop="item">
                    <span itemprop="name"><?=$clinic->name?></span></span>
            <meta itemprop="position" content="3" />
        </li>
    </ol>

    <div class="clinic-landing" itemscope itemtype="http://schema.org/Organization">
        <meta itemprop="url" content="<?php echo ClinicPageLinkViewHelper::getLink($clinic); ?>">
        <div class="main-box">
            <div class="head-info flo">
                <div class="rating" itemscope itemtype="http://schema.org/AggregateRating">
                    <meta itemprop="name" content="<?php echo $clinic->name; ?>"/>

                    <?php echo RateViewHelper::view($clinic->rate, 0, $clinic->is_best); ?>

                    <?php if($clinic->is_best) { ?>
                    <div class="is_best_recomm">Рекомендуем</div>
                    <?php } ?>

                    <?php if (count($clinic->reviews)): ?>
                    <div class="comments-count">
                        <a href="#reviews">
                                    <span itemprop="reviewCount">
                                        <?php echo StringHelper::getCorrectSuffixForReview(count($clinic->reviews));?>
                                    </span>
                        </a>
                    </div>
                    <?php endif; ?>

                </div>
                <h1 itemprop="name"><?php echo $clinic->name; ?></h1>
            </div>
            <div class="vis-block">
                <div class="nav">
                    <ul>
                        <li class="tab-map"><a href="#tabs-2" class="map-link"><i></i>Карта</a></li>
                    </ul>
                </div>
                <div class="content">
                    <div id="tabs-2" class="section">
                        <div class="map-block" id="map" style="width: 100%; height: 360px">
                        </div>
                    </div>
                </div>
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
                <?php if(!empty($clinic->name)) { ?>
                <h3>О клинике: <?php echo $clinic->name; ?></h3>
                <?php } else { ?>
                <h3>О клинике</h3>
                <?php } ?>
                <div id="about-clinic-content">
                    <?php echo $clinic->about; ?>
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
                <?php if($specialization->getMainSpecialty($clinic->getId())): ?>
                <li class="service-link" data-specialty-id="<?php echo $specialization->main_specialty->getId(); ?>">
                    <a class="like_service_ul" href="#our-doctors"><?php echo $specialization->name; ?></a>
                </li>
                <?php else: ?>
                <li class="service-link"><?php echo $specialization->name; ?></li>
                <?php endif; ?>
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
    <?php if (!empty($clinic_reviews)):?>
    <div id="reviews">
        <div class="heading-line">
            <h2>
                        <span>
                            ОТЗЫВЫ О КЛИНИКЕ:
                            <br />
                            <?php if(!empty($clinic->name)) { ?>
                            <?php echo mb_strtoupper($clinic->name, 'utf-8'); ?>
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

        <?php if (!empty($all_reviews) && $all_reviews > 4) { ?>
        <div id="view_more_reviews">
            <a href="javascript:void(0)" class="view-more" id="more_reviews"><i></i>Показать ещё 10 отзывов</a>
        </div>
        <?php } ?>

        <?php endif; ?>
        <div class="divider-shadow" id="divider-shadow"></div>
        <?php if (!empty($actions)): ?>
        <?php $this->block('clinic/blocks/actions'); ?>
        <?php endif; ?>
    </div>



    <script>
        $(function() {
            $( ".vis-block" ).tabs();
        });
    </script>
