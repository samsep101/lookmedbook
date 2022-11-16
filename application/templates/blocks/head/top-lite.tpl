<link rel="preload" href="/media/responsive/fonts/glyphicons-halflings-regular.woff2" as="font" type="font/woff2" crossorigin="anonymous">
<?php if (debug): ?>
    <script type="text/javascript" src="/media/js/jquery-1.8.3.min.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/chosen.jquery.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/jquery.fancybox.pack.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/docdoc-async.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/popup.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/popup_message.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/init.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/history.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/jquery.form.validation.js?<?php echo RELEASE__NUMBER?>"></script>
<?php else: ?>
    <script type="text/javascript" src="/media/js/min/top-lite.js?<?php echo RELEASE__NUMBER?>"></script>
<?php endif; ?>
<?php if (debug) : ?>
    <link rel="stylesheet" href="/media/responsive/css/bootstrap.css?<?php echo RELEASE__NUMBER;?>" type="text/css"/>
    <link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/styles.css?<?php echo RELEASE__NUMBER;?>" type="text/css"/>
    <link rel="stylesheet" href="/media/css/chosen.css?<?php echo RELEASE__NUMBER?>" type="text/css"/>
    <link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/my.css?<?php echo  RELEASE__NUMBER?>"/>
<?php else: ?>
    <link rel="stylesheet" href="/media/css/min/top-lite.css?<?php echo RELEASE__NUMBER;?>" type="text/css"/>
<?php endif ?>