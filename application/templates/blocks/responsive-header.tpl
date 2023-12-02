<?php
/**
 * @var View           $this
 * @var int            $already_registred_account
 * @var AccountModel   $current_account
 * @var string         $menu_active
 * @var CityModel|null $city
 */

if (isset($_COOKIE['already_registred_account'])) {
    $already_registred_account = 1;
} else {
    $already_registred_account = 0;
}
?>

<script>
    $(document).ready(function () {
        var header_controller = new HeaderController();
        header_controller.init();
    });
</script>

<nav>
    <ul class="responsive-header__nav_mobile">
        <?php if ($city->is_has_doctors) { ?>
            <li><a class="<?php echo (isset($menu_active) && $menu_active == 'doctor') ? 'active' : ''; ?> doctor-link"
                   href="/doctor">Врачи</a></li>
        <?php } ?>
        <?php if ($city->is_has_clinics) { ?>
            <li><a class="<?php echo (isset($menu_active) && $menu_active == 'clinic') ? 'active' : ''; ?> clinic-link" href="/clinic">Клиники</a>
            </li>
        <?php } ?>

        <li><a class="<?php echo (isset($menu_active) && $menu_active == 'disease') ? 'active' : ''; ?> disease-link"
               href="<?php if ($city->getId() == 2) { ?>/disease<?php } else { ?><?php echo strtolower(SITE_URL); ?>/disease<?php } ?>">Заболевания</a>
        </li>
        <li><a class="" href="/shop/catalog">Лекарства</a></li>
        <li><a class="" href="/action">Акции</a></li>
        <?php if (Application::config('section.services.available')) : ?>
            <li><a class="" href="/uslugi">Услуги</a></li>
        <?php endif; ?>
        <li>
            <a class="city popup_city"><span class="glyphicon glyphicon-map-marker"></span> <?= htmlentities($city->name) ?></a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <header class="row responsive-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-2 responsive-header__relative hidden-sm hidden-xs hidden-md">
                    <a href="/" class="responsive-header__logo_min responsive-header_desktop__left"></a>
                </div>

                <div class="col-lg-6 hidden-md responsive-header__left responsive-header_desktop">
                    <nav>
                        <ul class="responsive-header__nav_desktop">
                            <?php if ($city->is_has_doctors) { ?>
                                <li><a class="<?php echo (isset($menu_active) && $menu_active == 'doctor') ? 'active' : ''; ?> doctor-link"
                                       href="/doctor">Врачи</a></li>
                            <?php } ?>
                            <?php if ($city->is_has_clinics) { ?>
                                <li><a class="<?php echo (isset($menu_active) && $menu_active == 'clinic') ? 'active' : ''; ?> clinic-link"
                                       href="/clinic">Клиники</a></li>
                            <?php } ?>

                            <li><a class="<?php echo (isset($menu_active) && $menu_active == 'disease') ? 'active' : ''; ?> disease-link"
                                   href="<?php if ($city->getId() == 2) { ?>/disease<?php } else { ?><?php echo strtolower(
                                       SITE_URL
                                   ); ?>/disease<?php } ?>">Заболевания</a></li>
                            <li><a class="" href="/shop/catalog">Лекарства</a></li>
                            <li><a class="" href="/action">Акции</a></li>
                            <?php if (Application::config('section.services.available')) : ?>
                                <li><a class="" href="/uslugi">Услуги</a></li>
                            <?php endif; ?>
                            <li>
                                <div>
                                    <a class="city popup_city"><?= htmlentities($city->name) ?></a>
                                </div>
                            </li>
                        </ul>
                    </nav>
                </div>

                <div class="col-lg-5 col-md-12 responsive-header__right">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <script>
                                $(document).ready(function () {
                                    var disease_search_controller = new DiseaseQuickSearchFormController(<?php echo (!Acc::isAuthed(
                                    )) ? 1 : 0; ?>, <?php echo $already_registred_account; ?>, "<?php echo (isset($label_for_counters)) ? $label_for_counters : ''; ?>");
                                    disease_search_controller.setInputElement($('#disease-quick-search .quick-search-input'));
                                    disease_search_controller.setDrowDownContainer($('#disease-quick-search .drop-menu'));
                                    disease_search_controller.setSubmitElement($('#disease-quick-search .quick-search-submit'));
                                    disease_search_controller.init();
                                });
                            </script>
                            <form action="/search/results" method="GET">
                                <div class="quick-search nav__padding" id="disease-quick-search">
                                    <div class="fields flo">
                                        <input type="text" class="quick-search-input" autocomplete="off" name="query"
                                               placeholder="Найти врача, клинику"/>
                                        <input type="submit" class="quick-search-submit" data-action-for-counters="top" value=""/>
                                    </div>
                                    <ul class="drop-menu">

                                    </ul>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="header-right">
                                <?php $this->block('blocks/header-number') ?>
                                <?php if (Acc::isAuthed() && isset($current_account)): ?>
                                    <div class="header-user">
                                        <a href="<?php if ($city && $city->isUsed()) {
                                            echo '/account/message';
                                        } else {
                                            echo SITE_URL.'/account/message';
                                        } ?>" class="header-usernotification"> <span style="display: none;" class="notification"
                                                                                     id="usernotification"></span> </a>
                                        <div class="header-userinfo"><a href="<?php if ($city && $city->isUsed()) {
                                                echo '/account/about';
                                            } else {
                                                echo SITE_URL.'/account/about';
                                            } ?>" class="header-userprofile">
                                                <?php
                                                if ($current_account && ($current_account->first_name || $current_account->last_name || $current_account->middle_name)) {
                                                    echo $current_account->last_name.' '.$current_account->first_name.' '.$current_account->middle_name;
                                                } else {
                                                    if ($current_account->email) {
                                                        echo $current_account->email;
                                                    } else {
                                                        ?>&nbsp<?php }
                                                }
                                                ?>
                                            </a>
                                            <ul class="header-usermenu">
                                                <li><a href="<?php if ($city && $city->isUsed()) {
                                                        echo '/account/about';
                                                    } else {
                                                        echo SITE_URL.'/account/about';
                                                    } ?>">Профиль</a></li>
                                                <li><a href="<?php if ($city && $city->isUsed()) {
                                                        echo '/help';
                                                    } else {
                                                        echo SITE_URL.'/help';
                                                    } ?>">Помощь</a></li>
                                                <li><a href="<?php if ($city && $city->isUsed()) {
                                                        echo '/account/logout';
                                                    } else {
                                                        echo SITE_URL.'/account/logout';
                                                    } ?>">Выйти</a></li>
                                            </ul>
                                        </div>
                                        <?php if ($current_account && $current_account->is_call_centre_operator): ?>
                                            <script type="text/javascript">
                                                $(document).ready(function () {
                                                    var appeal_controller = new CallCentreAppealController();
                                                    appeal_controller.setButton($('.create-appeal'));
                                                    appeal_controller.init();
                                                });
                                            </script>
                                            <a class="create-appeal btn-1" href="javascript:void(0);">Создать<br/> обращение</a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!Acc::isAuthed()): ?>
                                    <div class="no-auth-buttons">
                                        <a class="btn-enter header__profile" href="javascript:void(0);">Личный кабинет</a>
                                    </div>
                                <?php endif; ?>
                                <?php if (Acc::isTemp()): ?>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            var set_new_password_controller = new SetNewPasswordController(null, false);
                                            set_new_password_controller.init();
                                        });
                                    </script>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </header>
</div>
