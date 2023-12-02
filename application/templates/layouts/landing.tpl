<?php
	/**
	 * @var View $this
	 * @var CityModel $city
	 * @var DistrictModel[] $districts
	 * @var SpecialtyModel $specialty
	 * @var bool $home_page
	 * @var bool $daytime
	 * @var ProductBasket $product_basket
	 */
?>
    <!DOCTYPE HTML>
<html>
<head>
	<script>
    (function (i, s, o, g, r, a, m) {i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
    a = s.createElement(o),
    m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m)
    })
    (window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
    ga('create', '<?php echo AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId(empty($city)?'':$city->getId(), AnalyticCounterTypeModel::GOOGLE_COUNTER); ?>', '<?php echo strtolower(SITE_DOMAIN);?>');

    ga('send', 'pageview');
    </script>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo (isset($page_title)) ? $page_title : SITE_NAME; ?></title>
    <meta name="description" content="<?php echo (isset($page_description)) ? $page_description : ''.SITE_NAME.' - поиск врача и запись на прием, информация обо всех известных заболеваниях.'; ?>">
    <link rel="icon" href="/media/images/home_page/<?php echo CSS_DIR; ?>/favicon.png" type="image/png">

    <meta name='yandex-verification' content='76535cc7dd5d586f' />
    <meta name=viewport content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/media/landing/css/styles.css?ver=<?php echo microtime(true);?>" type="text/css"/>
    <link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/responsive-header.css?ver=<?php echo microtime(true);?>" type="text/css"/>
    <link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/responsive-body.css?ver=<?php echo microtime(true);?>" type="text/css"/>
    <script type="text/javascript" src="/media/js/jquery-1.8.3.min.js"></script>
    <script async type="text/javascript" src="//sjsmartcontent.ru/static/plugin-site/js/sjplugin.js" site="6fmj"></script>
</head>
<body>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PDVS826"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="wrapper" class="wrap">
    <?php $this->block('blocks/landing-header'); ?>
    <div class="content
    	<?php echo (isset($is_red) ? 'red' : '');  ?>
    	<?php echo ((isset($is_green) && $is_green) ? 'green' : '');  ?>
    	<?php echo (isset($is_simple) ? 'simple-popup-registration' : '');  ?>
    	<?php echo ((isset($disease_green_btn) && $disease_green_btn) ? 'disease-green-btn' : '');	?>
    	">
        <?php $this->content(); ?>
    </div>
</div>

<?php $this->block('blocks/landing-footer'); ?>
<noindex>
    <?php $this->block('blocks/counters'); ?>
</noindex>
<!--[if IE]><script type="text/javascript" src="http://www.xiper.net/examples/js-plugins/html5-and-css3/explorer-canvas/excanvas.js"></script><![endif]-->

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PDVS826');</script>
<!-- End Google Tag Manager -->
<script type="text/javascript" src="/media/js/ajaxContent.js"></script>
</body>
</html>




