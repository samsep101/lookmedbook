<div class="container-fluid">
    <div class="row">
        <noindex>
            <footer class="footer">
                <div class="container fixed-w">
                    <div class="footer-inner-top ff-bold">
                        <section class="inner-top-info"> Нужна помощь?
                            <span class="help-phone"><?= LANDING_PHONE ?> с 09 до 18</span> или
                            <a class="info-mail jsLinkHidingIndexing" href="mailto:help@lookmedbook.ru">help@lookmedbook.ru</a>
                        </section>
                        <ul class="inner-top-socials">
                            <li class="socials-vkontakte"><a class="jsLinkHidingIndexing" target="_blank" href="http://vk.com/lookmedbook"></a></li>
                            <li class="socials-odnoklassniki"><a class="jsLinkHidingIndexing" target="_blank" href="http://odnoklassniki.ru/group/52035885072448"></a></li>
                            <li class="socials-facebook"><a class="jsLinkHidingIndexing" target="_blank" href="http://www.facebook.com/LookMedBook"></a></li>
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
                            <li><a class="about-link jsLinkHidingIndexing" href="/smap">Карта сайта</a></li>
                        </ul>
                    </div>
                </div>
            </footer>
        </noindex>
    </div>
</div>