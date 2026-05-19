<?php
    // @TODO Проверить, используется ли. Если не используется - выпилить вместе с зависимостями
    $discountVisibility1 = '';
    $discountVisibility2 = '';
    if(!empty($_SESSION['isDiscountVisible'])){
        if($_SESSION['isDiscountVisible'] === '0') {
            $discountVisibility1 = 'style="display: none;"';
        } else {
            $discountVisibility2 = 'style="display: none;"';
        }
    } else {
        $discountVisibility2 = 'style="display: none;"';
    }

    $treatmentInSwitzVisibleOpen = $treatmentInSwitzClass = $treatmentInSwitzVisibleClose = '';
    if (isset($_SESSION['isTreatmentInSwitzVisible']) && $_SESSION['isTreatmentInSwitzVisible'] === "0") {
        $treatmentInSwitzVisibleClose = 'style="display: none;"';
        $treatmentInSwitzClass = 'small-banner-visible';
    }
    if (!isset($_SESSION['isTreatmentInSwitzVisible']) || $_SESSION['isTreatmentInSwitzVisible'] === "1") {
        $treatmentInSwitzVisibleOpen = 'style="display: none;"';
    }

?>
<!DOCTYPE HTML>
<html>
    <head>

        <?php $this->block('responsive/includes/google-analitics')?>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo (isset($page_title)) ? $page_title : SITE_NAME; ?></title>
        <meta name="description" content="<?php echo (isset($page_description)) ? $page_description : '' . SITE_NAME . ' - поиск врача и запись на прием, информация обо всех известных заболеваниях.'; ?>">
        <link rel="icon" href="/media/images/home_page/<?php echo CSS_DIR; ?>/favicon.png" type="image/png">
        <meta name='yandex-verification' content='76535cc7dd5d586f' />
        <meta name=viewport content="width=device-width, initial-scale=1">

        <?php $this->block('responsive/section.head'); ?>

        <?php if (isset($home_page)): ?>
            <link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/home_style.css?<?php echo RELEASE_NUMBER ?>" type="text/css" media="screen, projection" />
            <!--[if lt IE 8]>
            <link rel="stylesheet" href="/media/css/home_page/ie/ie-7.css?<?php echo RELEASE_NUMBER ?>" type="text/css" media="screen, projection" />
            <![endif]-->
            <!--[if lt IE 9]>
            <link rel="stylesheet" href="/media/css/home_page/ie/ie.css?<?php echo RELEASE_NUMBER ?>" type="text/css" media="screen, projection" />
            <![endif]-->
        <?php endif ?>

    </head>


    <body class="bg-paper responsive">

        <?php if($city) : ?>
            <div class="hidden __citydata" data-id="<?=$city->getId()?>" data-lat="<?=(float) $city->lat?>" data-lng="<?=(float) $city->lng;?>"></div>
        <?php endif; ?>

        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PDVS826" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->

        <?php $this->block('responsive/section.header'); ?>

        <div class="content">
            <?php $this->content(); ?>
        </div>

        <?php if (!isset($_SESSION['sentDiscountRequest']) OR !$_SESSION['sentDiscountRequest']) { ?>
            <div class="discount">
                <div class="discount-open">
                    <span class="btn-open" <?php echo $discountVisibility2; ?>>
                        <i class="glyphicon glyphicon-chevron-left"></i>
                    </span>
                    <span class="percent">&#37;</span>
                </div>
                <div class="discount-close" <?php echo $discountVisibility1; ?>><i class="glyphicon glyphicon-remove"></i></div>
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


        <?php if ($this->show_horizontal_banner) : ?>
            <?php $this->block('blocks/horizontal-banner'); ?>
        <?php endif; ?>

        <?php $this->block('responsive/section.footer'); ?>

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

        <?php $this->block('responsive/includes/record_form_container'); ?>
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
