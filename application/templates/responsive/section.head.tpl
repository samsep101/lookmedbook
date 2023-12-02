<?php
    if (!isset($canonical_link)) {
        $canonical_link = SITE_URL . $_SERVER['REQUEST_URI'];
    }

    if (strpos($canonical_link, '?')) {
        $canonical_link = preg_replace('/^([^?]+)(\?.*?)?(#.*)?$/', '$1$3', $canonical_link);
    }
?>
<?php if (isset($canonical_link) && $canonical_link) : ?>
    <?php if ($canonical_link != 'none' && !isset($site_url_not_using)) : ?>
        <link rel="canonical" href="<?php echo SITE_URL . $canonical_link; ?>" />
    <?php elseif (isset($site_url_not_using) && $site_url_not_using) : ?>
        <link rel="canonical" href="<?php echo $canonical_link; ?>" />
    <?php endif; ?>
<?php elseif (($city) && $city->name): ?>
    <link rel="canonical" href="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>" />
<?php endif; ?>
<link rel="stylesheet" href="/media/responsive/styles.min.css?<?php echo RELEASE__NUMBER ?>"/>
<link rel="stylesheet" href="/media/css/jquery.jscrollpane.css" type="text/css"/>
<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" media="screen,projection" href="/media/css/for_ie.css?<?php echo RELEASE__NUMBER ?>"/>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js?"></script>
<![endif]-->
<!--[if lte IE 9]>
<link rel="stylesheet" type="text/css" media="screen,projection" href="/media/css/for_ie9.css?<?php echo RELEASE__NUMBER ?>"/>
<![endif]-->
<!--[if lte IE 8]>
<link rel="stylesheet" type="text/css" media="screen,projection" href="/media/css/for_ie8.css?<?php echo RELEASE__NUMBER ?>"/>
<![endif]-->
<!--[if IE 11]>
<link rel="stylesheet" type="text/css" media="screen,projection" href="/media/css/for_ie11.css?<?php echo RELEASE__NUMBER ?>"/>
<![endif]-->
<script type="text/javascript" src="/media/js/jquery-1.8.3.min.js"></script>
<script src="https://docdoc.ru/widget/js" type="text/javascript"></script>

<!-- Google Tag Manager -->
<script>(function (w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({'gtm.start':
                    new Date().getTime(), event: 'gtm.js'});
        var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-PDVS826');</script>
<!-- End Google Tag Manager -->