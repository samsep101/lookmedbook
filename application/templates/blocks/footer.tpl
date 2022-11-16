<script type="text/javascript">
    $(document).ready(function () {
        var controller = new FooterBlockController();
        controller.init();
    });
</script>
    <footer class="footer">
        <div class="inner">
            <div class="footer-inner-top">
                <section class="inner-top-info"> Нужна помощь?
                    <span class="help-phone"><?= FOOTER_PHONE ?></span> или
                    <a data-link="mailto:<?= SettingsManager::get('help_email');?>" class="info-mail jsLinkHidingIndexing"><?= SettingsManager::get('help_email');?></a>
                    <a class="help-link jsLinkHidingIndexing" data-link="/contacts">Контакты</a>
                </section>
                <ul class="inner-top-socials">
                    <li class="socials-vkontakte"><a class="jsLinkHidingIndexing" data-link="//vk.com/lookmedbook" target="_blank"></a></li>
                    <li class="socials-odnoklassniki"><a class="jsLinkHidingIndexing" data-link="//odnoklassniki.ru/group/52035885072448" target="_blank"></a></li>
                    <li class="socials-facebook"><a class="jsLinkHidingIndexing" data-link="//www.facebook.com/LookMedBook" target="_blank"></a></li>
                </ul>
            </div>
            <div class="footer-inner-bottom">
                <section class="inner-bottom-copyright">&copy; &laquo;<?php echo SITE_DOMAIN; ?>&raquo;, <?php echo date('Y'); ?></section>
                <a class="reg-link jsLinkHidingIndexing" href="/user-agreement">Пользовательское соглашение</a>
                <a class="reg-link jsLinkHidingIndexing" href="/privacy-policy">Согласие на обработку персональных данных</a>
                <ul class="inner-bottom-navigation">
                    <li><a class="help-link jsLinkHidingIndexing" data-link="/help">Помощь</a></li>
                    <li><a class="about-link jsLinkHidingIndexing" data-link="/about">О проекте</a></li>
                    <li><a class="about-link jsLinkHidingIndexing" data-link="/sitemap">Карта сайта</a></li>
                </ul>
            </div>
            <div class="footer-warning">
                <noindex>
                    <div class="footer-warning__age"><div>18+</div></div>
                    Информация, представленная на сайте, не может быть использована для постановки диагноза, назначения лечения и не заменяет прием врача.
                </noindex>
            </div>
        </div>
    </footer>