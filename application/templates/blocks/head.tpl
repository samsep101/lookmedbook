<?php
	/**
	 * @var View $this
	 * @var AccountModel $current_account
	 * @var bool $show_canonical_link
	 * @var string $canonical_link
	 * @var int $doctor_search_page
	 * @var CityModel $city
     * @var string $csrf
	 */

?>
    <?php
    if (!isset($canonical_link))
        $canonical_link = $_SERVER['REQUEST_URI'];


    if (strpos($canonical_link, '?')){
        $canonical_link = preg_replace('/^([^?]+)(\?.*?)?(#.*)?$/', '$1$3', $canonical_link);
    }

    if (isset($canonical_link)): ?>
        <?php if ($canonical_link != 'none' && !isset($site_url_not_using)) { ?>
            <link rel="canonical" href="<?php echo SITE_URL.$canonical_link; ?>" />
        <?php } elseif(isset($site_url_not_using) && $site_url_not_using) { ?>
            <link rel="canonical" href="<?php echo $canonical_link; ?>" />
        <?php } ?>
    <?php elseif(($city) && $city->name):  ?>
        <link rel="canonical" href="<?php echo SITE_URL.$_SERVER['REQUEST_URI']; ?>" />
    <?php endif; ?>
<?php if (debug) : ?>
  <link  rel="preload" as="style" href="/media/css/jquery.fancybox.css?<?php echo RELEASE__NUMBER?>" type="text/css" class="lazy-css"/>
    <link rel="preload" as="style" href="/media/css/fonts.css?<?php echo RELEASE__NUMBER ?>" type="text/css" class="lazy-css"/>
  <link  rel="preload" as="style" type="text/css" href="/media/js/jquery-rating/styles/jquery.rating.css?<?php echo RELEASE__NUMBER?>" class="lazy-css"/>
<?php else: ?>
  <link rel="preload" as="style" href="/media/css/min/lazy.css?<?php echo RELEASE__NUMBER;?>" type="text/css" class="lazy-css"/>
<?php endif ?>
<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" media="screen,projection" href="/media/css/for_ie.css?<?php echo RELEASE__NUMBER?>"/>
<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js?<?php echo RELEASE__NUMBER?>"></script>
<![endif]-->
<!--[if lte IE 9]>
<link rel="stylesheet" type="text/css" media="screen,projection" href="/media/css/for_ie9.css?<?php echo RELEASE__NUMBER?>"/>
<![endif]-->
<!--[if lte IE 8]>
<link rel="stylesheet" type="text/css" media="screen,projection" href="/media/css/for_ie8.css?<?php echo RELEASE__NUMBER?>"/>
<![endif]-->
<!--[if IE 11]>
<link rel="stylesheet" type="text/css" media="screen,projection" href="/media/css/for_ie11.css?<?php echo RELEASE__NUMBER?>"/>
<![endif]-->
<?php
$isLite = $this->getLayoutParam('isTopLite', false);
$this->block($isLite ? 'blocks/head/top-lite' : 'blocks/head/top');
?>

<script type="text/javascript" src="/js/validation?<?= RELEASE__NUMBER ?>" defer></script>
<script type="text/javascript" src="/media/js/ajaxContent.js?<?php echo RELEASE__NUMBER?>" defer></script>

<!--[if lte IE 9]>
<script src="/media/js/jquery.placeholder.min.js?<?php echo RELEASE__NUMBER?>"></script>
<![endif]-->

<?php $this->block($isLite ? 'blocks/head/main-lite' : 'blocks/head/main'); ?>
<?php $this->block('blocks/js-library'); ?>

<!--[if lt IE 10]>
<script type="text/javascript" src="/media/js/flashcanvas.js?<?php echo RELEASE__NUMBER?>"></script>
<![endif]-->

<!-- dev -->

<?php if (isset($load_map) && $load_map): ?>
    <script src="https://api-maps.yandex.ru/2.0/?load=package.full,package.clusters,package.overlays&lang=ru-RU"
            type="text/javascript"></script>
<?php endif; ?>

<script>
	<?php if($city): ?>
    $(document).ready(function () {
        window.city_controller = new CityController(<?php echo $city->getId();?>,<?php echo (float)$city->lat;?>,<?php echo (float)$city->lng;?>);
    });
	<?php endif; ?>
</script>

<script type="text/javascript">
    <?php if (Acc::isAuthed()): ?>
    SessionInfo.is_authed = true;
    SessionInfo.email = '<?php echo $current_account->email; ?>';
    <?php endif; ?>
    SessionInfo.domain = "<?php echo LinkHelper::getDomain(); ?>";
    SessionInfo.csrf = <?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>;
</script>


<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PDVS826');</script>
<!-- End Google Tag Manager -->

<script>
    window.gRecaptchaSiteKey = "<?= GRECAPTCHA_SITEKEY ?>";
</script>

<?php foreach ($this->getStyles(\app\library\resources\Style::TYPE_SIMPLE) as $style) { ?>
    <link rel="stylesheet" href="<?= $style->getUrl() ?>" type="text/css">
<?php } ?>