<div class="container-fluid">
    <div class="row">
        <noindex>
            <footer class="footer">
                <div class="container">
                    <div class="footer-inner-top ff-bold">
                        <section class="inner-top-info"> Нужна помощь?
                            <?= HelpPhoneNumberViewHelper::renderPhoneBlock('<span class="help-phone">{%phone%}</span> или'); ?>
                            <a class="info-mail jsLinkHidingIndexing" href="mailto:help@lookmedbook.ru">help@lookmedbook.ru</a>
                        </section>
                        <ul class="inner-top-socials">
                            <li class="socials-vkontakte"><a class="jsLinkHidingIndexing" target="_blank" href="https://vk.com/lookmedbook"></a></li>
                            <li class="socials-odnoklassniki"><a class="jsLinkHidingIndexing" target="_blank" href="https://odnoklassniki.ru/group/52035885072448"></a></li>
                            <li class="socials-facebook"><a class="jsLinkHidingIndexing" target="_blank" href="https://www.facebook.com/LookMedBook"></a></li>
                        </ul>
                    </div>
                    <div class="footer-inner-bottom">
                        <section class="inner-bottom-copyright ff-bold">© «lookmedbook.ru», 2017</section>
                        <a class="reg-link jsLinkHidingIndexing" href="/user-agreement">Пользовательское соглашение</a>
                        <?php if(!isset($city) OR $city->hasLaboratories()) :
                            $url = (isset($city) AND $city->isUsed()) ? '/analysis' : SITE_URL . '/analysis';
                            $class = (!empty($menu_active) AND $menu_active == 'analysis') ? 'active' : '';
                        ?>
                            <a class="<?=$class?> analysis-link" href="<?=$url?>">Анализы</a>
                        <?php endif; ?>
                        <ul class="inner-bottom-navigation ff-bold">
                            <li><a class="help-link jsLinkHidingIndexing" href="/help">Помощь</a></li>
                            <li><a class="about-link jsLinkHidingIndexing" href="/about">О проекте</a></li>
                            <li><a class="about-link jsLinkHidingIndexing" href="/sitemap">Карта сайта</a></li>
                        </ul>
                    </div>
                </div>
            </footer>
        </noindex>
    </div>
</div>

<?php if($this->clearscripts) : ?>

    <script type="text/javascript" src="/media/responsive/js/onready.js?<?=RELEASE_NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/inputmask/jquery.inputmask.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/init.js?<?=RELEASE_NUMBER?>"></script>
    <script type="text/javascript" src="/media/responsive/js/plugins.js?<?=RELEASE_NUMBER?>"></script>

<?php else : ?>

    <script async type="text/javascript" src="//sjsmartcontent.ru/static/plugin-site/js/sjplugin.js" site="6fmj"></script>
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
    <script type="text/javascript" src="/media/js/image_preview.js?<?php echo RELEASE__NUMBER ?>"></script>

    <script type="text/javascript" src="/media/js/jquery-ui-1.10.2.custom.min.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/jquery.maskedinput.min.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/jquery.carouFredSel-6.2.0-packed.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/chosen.jquery.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/jquery.maskedinput-1.3.min.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/bootstrap-affix.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/waypoints.min.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/jquery.fancybox.pack.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/jquery.jscrollpane.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/jquery.jcarousel.min.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/jcarousel.connected-carousels.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/js/validation"></script>
    <script type="text/javascript" src="/media/js/jquery.raty.min.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/cookies.js?<?php echo RELEASE__NUMBER?>"></script>
    <script type="text/javascript" src="/media/js/ajaxContent.js?<?php echo RELEASE__NUMBER?>"></script>

    <!--[if lte IE 9]>
        <script src="/media/js/jquery.placeholder.min.js?<?php echo RELEASE__NUMBER?>"></script>
    <![endif]-->

    <?php if (debug): ?>
        <script type="text/javascript" src="/media/js/actions.js?<?php echo RELEASE__NUMBER?>"></script>
        <script type="text/javascript" src="/media/js/popup.js?<?php echo RELEASE__NUMBER?>"></script>
        <script type="text/javascript" src="/media/js/modal_window.js?<?php echo RELEASE__NUMBER?>"></script>
        <script type="text/javascript" src="/media/js/popup_message.js?<?php echo RELEASE__NUMBER?>"></script>
        <script type="text/javascript" src="/media/js/init.js?<?php echo RELEASE__NUMBER?>"></script>
        <script type="text/javascript" src="/media/js/history.js?<?php echo RELEASE__NUMBER?>"></script>
        <script type="text/javascript" src="/media/js/citymap.js?<?php echo RELEASE__NUMBER?>"></script>
        <script type="text/javascript" src="/media/js/simple-timer.js?<?php echo RELEASE__NUMBER?>"></script>
        <script type="text/javascript" src="/media/js/jquery.form.js?<?php echo RELEASE__NUMBER?>"></script>
        <script type="text/javascript" src="/media/js/jquery.form.validation.js?<?php echo RELEASE__NUMBER?>"></script>
    <?php else: ?>
        <script type="text/javascript" src="/media/js/min/core.js?<?php echo RELEASE__NUMBER?>"></script>
    <?php endif; ?>
    <!--[if lt IE 10]>
        <script type="text/javascript" src="/media/js/flashcanvas.js?<?php echo RELEASE__NUMBER?>"></script>
    <![endif]-->

    <script type="text/javascript" src="/media/js/inputmask/jquery.inputmask.js?<?php echo RELEASE__NUMBER?>"></script>

    <?php $this->block('blocks/js-library'); ?>

    <script type="text/javascript">
    <?php if (Acc::isAuthed()): ?>
        SessionInfo.is_authed = true;
        SessionInfo.email = '<?php echo $current_account->email; ?>';
    <?php endif; ?>
        SessionInfo.domain = "<?php echo LinkHelper::getDomain(); ?>";
        SessionInfo.csrf = <?php echo isset($csrf) ? '\'' . $csrf . '\'' : 'null'; ?>;
    </script>

<?php endif; ?>



<?php if (isset($load_map) && $load_map): ?>
    <script src="https://api-maps.yandex.ru/2.0/?load=package.full,package.clusters,package.overlays&lang=ru-RU" type="text/javascript"></script>
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
<?php endif; ?>

<link rel="stylesheet" href="/media/css/jquery.fancybox.css" type="text/css"/>
<link rel="stylesheet" href="/media/css/jquery-ui.min.css" type="text/css"/>
<script type="text/javascript" src="/media/js/jquery-rating/js/jquery.rating-2.0.js?<?php echo RELEASE__NUMBER?>"></script>
<link rel="stylesheet" type="text/css" href="/media/js/jquery-rating/styles/jquery.rating.css?<?php echo RELEASE__NUMBER?>"/>
<script src="/media/js/jquery.event.move.js?<?php echo RELEASE__NUMBER?>"></script>
