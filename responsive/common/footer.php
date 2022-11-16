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

    <script type="text/javascript" src="/media/responsive/js/main.js"></script>

    <link rel="stylesheet" href="/media/css/chosen.css?0.1" type="text/css"/>
    <link rel="stylesheet" href="/media/css/jquery.jscrollpane.css?0.1" type="text/css"/>
    <link rel="stylesheet" href="/media/libraries/bootstrap-select/css/bootstrap-select.min.css" type="text/css"/>

    <script type="text/javascript" src="/media/js/init.js"></script>
    <script type="text/javascript" src="/media/responsive/js/compiled.min.js"></script>

    <!--[if lte IE 9]>
    <script src="/media/js/jquery.placeholder.min.js?0.1"></script>
    <![endif]-->

    <!--[if lt IE 10]>
    <script type="text/javascript" src="/media/js/flashcanvas.js?0.1"></script>
    <![endif]-->

    <script src="https://api-maps.yandex.ru/2.0/?load=package.full,package.clusters,package.overlays&lang=ru-RU" type="text/javascript"></script>
    <script type="text/javascript" src="/media/js/image_preview.js?0.1"></script>

    <script src="https://docdoc.ru/widget/js" type="text/javascript"></script>

    <script>
        $(document).ready(function () {
            window.city_controller = new CityController(2, 55.7558, 37.6176);
        });
    </script>

    <script type="text/javascript">
        SessionInfo.domain = "lookmedbook.citrus.one";
        SessionInfo.csrf = 'r5SB86hDK4hrDh7SHN6AHBBAZrb8FYRYQziKrya3';
    </script>

</body>
</html>