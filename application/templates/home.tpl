<?php
	/**
	 * @var View $this
	 * @var CityModel $city
	 * @var DistrictModel[] $districts
	 * @var SpecialtyModel $specialty
	 * @var bool $home_page
	 * @var bool $daytime
	 */
?>
<!DOCTYPE HTML>
<html>
<head>
    <?php foreach ($this->getStyles(\app\library\resources\Style::TYPE_LAZY) as $style) { ?>
        <link rel="preload" as="style" href="<?= $style->getUrl() ?>" type="text/css" class="lazy-css">
    <?php } ?>
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
    <?php $this->block('blocks/head'); ?>
    <?php if (isset($home_page)):?>
        <link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/home_style.css?<?php echo RELEASE__NUMBER?>" type="text/css" media="screen, projection" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" href="/media/css/home_page/ie/ie-7.css?<?php echo RELEASE__NUMBER?>" type="text/css" media="screen, projection" />
        <![endif]-->
        <!--[if lt IE 9]>
        <link rel="stylesheet" href="/media/css/home_page/ie/ie.css?<?php echo RELEASE__NUMBER?>" type="text/css" media="screen, projection" />
        <![endif]-->
    <?php endif?>

    <meta name='yandex-verification' content='76535cc7dd5d586f' />
    <meta name=viewport content="width=device-width, initial-scale=1">
  <?php if (debug) : ?>
	<link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/media.css?ver=<?php echo RELEASE__NUMBER?>" type="text/css"/>
  <link rel="stylesheet" href="/media/responsive/css/main2.css?ver=<?php echo RELEASE__NUMBER?>" type="text/css"/>
  <link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/responsive-header.css?ver=<?php echo RELEASE__NUMBER?>" type="text/css"/>
  <link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/responsive-body.css?ver=<?php echo RELEASE__NUMBER?>" type="text/css"/>
  <link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/slick.css?ver=<?php echo RELEASE__NUMBER?>" type="text/css"/>
  <link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/slick-theme.css?ver=<?php echo RELEASE__NUMBER?>" type="text/css"/>
  <link rel="stylesheet" href="/media/responsive/css/responsive-nav.css" type="text/css"/>
  <script type="text/javascript" src="/media/js/responsive-switch.js" defer></script>
  <script type="text/javascript" src="/media/responsive/js/slick.min.js" defer></script>
  <script type="text/javascript" src="/media/responsive/js/hide-elements.js?ver=<?php echo RELEASE__NUMBER; ?>" defer></script>
  <script type="text/javascript" src="/media/responsive/js/custom.js?ver=<?php echo RELEASE__NUMBER?>" defer></script>
  <script type="text/javascript" src="/media/responsive/js/responsive-nav.min.js?ver=<?php echo RELEASE__NUMBER?>" defer></script>
  <?php else : ?>
    <link rel="stylesheet" href="/media/css/min/home.css?<?= RELEASE__NUMBER ?>" type="text/css"/>
    <script type="text/javascript" src="/media/js/min/home.js?<?= RELEASE__NUMBER ?>" defer></script>
  <?php endif ?>
    <script src="https://docdoc.ru/widget/js" type="text/javascript" async></script>
    <style>
        .dd-button{
            width: 100% !important;
            font-size: 15px !important;
        }
    </style>
    <script async type="text/javascript" src="//sjsmartcontent.org/static/plugin-site/js/sjplugin.js" site="6fmj"></script>
    <?php if (!empty($isHiddenFromRobots) ) { ?>
        <meta name="robots" content="noindex, nofollow" />
    <?php } ?>

</head>
<body>

<?php
if (debug > 1) {
    benchmarks()->display(debug > 2);
}
?>


<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PDVS826"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<div class="header__menu">
    <a href="/" title="главная">
        <img src="/media/images/home_page/look/main_logo.png" class="mobile-logo hidden-lg" alt="Мобильное лого"/>
    </a>
    <i id="toggle-nav" class="glyphicon glyphicon-menu-hamburger"></i>
</div>
<script type="text/template" id="citymaps-balloon-template">
    <div class="citymaps-balloon-wrapper">
        <div class="citymaps-balloon-container">
            <div class="citymaps-balloon-body">
                $[result]
            </div>
        </div>
    </div>
</script>
<script type="text/template" id="citymaps-balloon-group-template">
    <div class="doc-popup-sm map-card-block flo"  style="left:-64px; top:23px;z-index:5000;visibility: hidden;">
        <span class="corn-top"></span>
        <div class="citymaps-balloon-container">
            <ins class="ins1"></ins>
            <ins class="ins2"></ins>
            <div class="citymaps-balloon-body">
                <ul class="citymaps-balloon-buttons">
                    <li><a class="citymaps-balloon-close" style="width:7px; height:7px;display:block;"></a></li>
                </ul>
                <div class="citymaps-balloon-content">
                    <h3 style="font-weight: normal">$[title]</h3>
                    <div style="height:168px; overflow: auto;">
                        <table cellpadding="3">
                            <tbody>$[rowContent]</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
<?php if (!isset($home_page)): ?>
    <div class="wrap">
        <?php $this->block(!empty($header) && $header === 'ab' ? 'blocks/ab/responsive-header' : 'blocks/responsive-header'); ?>
        <?php if (isset($example_page)): ?>
            <br><br><br><br>
        <?php endif; ?>
<?php else: ?>
    <div id="wrapper" class="wrap">
        <?php $this->block('blocks/responsive-main-header'); ?>

<?php endif; ?>
    <div class="content
    	<?php echo (isset($is_red) ? 'red' : '');  ?>
    	<?php echo ((isset($is_green) && $is_green) ? 'green' : '');  ?>
    	<?php echo (isset($is_simple) ? 'simple-popup-registration' : '');  ?>
    	<?php echo ((isset($disease_green_btn) && $disease_green_btn) ? 'disease-green-btn' : '');	?>
    	">
        <?php $this->content(); ?>
    </div>
</div>
</div>
<?php SeoHideHelper::begin() ?>
<?php $discountVisibility1 = (isset($_SESSION['isDiscountVisible']) && $_SESSION['isDiscountVisible'] === "0")?'style="display: none;"':''; ?>
<?php $discountVisibility2 = (!isset($_SESSION['isDiscountVisible']) || $_SESSION['isDiscountVisible'] === "1")?'style="display: none;"':''; ?>
<?php if (!isset($_SESSION['sentDiscountRequest']) || !$_SESSION['sentDiscountRequest']) {?>
<div class="discount">
	<div class="discount-open"><span class="btn-open" <?php echo $discountVisibility2; ?>>&lt;</span>%</div>
	<div class="discount-close" <?php echo $discountVisibility1; ?>>x</div>
	<div class="form-discount form-discount-step-1" <?php echo $discountVisibility1; ?>>
		<p class="txt" >Хотите получить<br/>индивидуальную<br/>скидку на прием к врачу?</p>
		<label>Оставьте телефон:</label>
		<input class="mask error" type="text" name="discount_phone_number" placeholder="+7-___-___-__-__">
		<input class="btn-discount" type="button" value="Получить скидку"/>
	</div>
	<div class="form-discount form-discount-step-2" style="display: none;">
		<p class="txt">Поздравляем! У нас уже<br/>готово индивидуальное<br/>предложение для вас.<br/>
		Подробности вы<br/>получите в ближайшее<br/>время по телефону.</p>
	</div>
</div>
<?php } ?>


<?php
    $treatmentInSwitzVisibleOpen = $treatmentInSwitzClass = $treatmentInSwitzVisibleClose = '';
    if(isset($_SESSION['isTreatmentInSwitzVisible']) && $_SESSION['isTreatmentInSwitzVisible'] === "0") {
        $treatmentInSwitzVisibleClose = 'style="display: none;"';
        $treatmentInSwitzClass = 'small-banner-visible';
    }
    if(!isset($_SESSION['isTreatmentInSwitzVisible']) || $_SESSION['isTreatmentInSwitzVisible'] === "1") {
        $treatmentInSwitzVisibleOpen = 'style="display: none;"';
    }
?>

<div class="switz-left-version banner-treatment-in-switz <?php echo $treatmentInSwitzClass; ?>">
    <a href="http://swiss.lookmedbook.ru/" class="banner-treatment-in-switz-link" <?php echo $treatmentInSwitzVisibleClose; ?>><div class="icon"></div>Лечение в Швейцарии <br/> Бесплатная консультация </a>
    <div class="close" <?php echo $treatmentInSwitzVisibleClose; ?>>&times;</div>
    <div class="banner-treatment-in-switz-open" <?php echo $treatmentInSwitzVisibleOpen; ?>>
        <div class="icon"></div>
    </div>
</div>
<?php SeoHideHelper::end() ?>
<?php /* $secondOpinionVisibility1 = (isset($_SESSION['isSecondOpinionVisible']) && $_SESSION['isSecondOpinionVisible'] === "0")?'style="display: none;"':''; ?>
<?php $secondOpinionVisibility2 = (!isset($_SESSION['isSecondOpinionVisible']) || $_SESSION['isSecondOpinionVisible'] === "1")?'style="display: none;"':''; ?>
<?php if (!isset($is_second_opinion)) {?>
	<div class="second-opinion-block">
		<a href="http://secondopinions.ru/lp7/" class="second-opinion-1" <?php echo $secondOpinionVisibility1; ?>><div class="icon"></div>Расшифровка снимков МРТ,<br/>КТ и др.<br/>Получи консультацию<br/>экспертов за 24 часа</a>
		<div class="close" <?php echo $secondOpinionVisibility1; ?>>x</div>
		<div class="second-opinion-2" <?php echo $secondOpinionVisibility2; ?>>
			<div class="icon"></div>
			<div class="open"><</div>
		</div>
	</div>
<?php } */?>
<?php if (!isset($example_page)) { ?>
    <?php if ($this->show_horizontal_banner) { ?>
        <?php $this->block('blocks/horizontal-banner'); ?>
    <?php } ?>

    <?php $this->block('blocks/footer'); ?>
<?php } ?>
<noindex>
    <?php $this->block('blocks/counters'); ?>
</noindex>
<!--[if IE]><script type="text/javascript" src="http://www.xiper.net/examples/js-plugins/html5-and-css3/explorer-canvas/excanvas.js"></script><![endif]-->

<!-- BEGIN JIVOSITE CODE {literal} -->
<!-- 2497
<script type='text/javascript'>
    (function(){ var widget_id = 'iiJvHkBseA';var d=document;var w=window;function l(){
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
/2497 -->
<!-- {/literal} END JIVOSITE CODE -->

<?php $this->block('blocks/record_form_container'); ?>
<?php $this->block('blocks/learn_form_container'); ?>
<!-- Mobile Advert Advertur.ru start -->
<div id="advertur_140974"></div><script>
  // Google pagespeedfix
  if (!/(Chrome-Lighthouse)|(Google Page Speed)/.test(navigator.userAgent)) {
    (function(w, d, n) {
      w[n] = w[n] || [];
      w[n].push({
        section_id: 140974,
        place: "advertur_140974",
        width: 0,
        height: 0
      });
    })(window, document, "advertur_sections");
  }
</script>
<script type="text/javascript" src="//ddnk.advertur.ru/v1/s/loader.js" async></script>
<!-- Mobile Advert Advertur.ru end -->
</body>
</html>




