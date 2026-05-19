<nav class="responsive-header__nav_mobile">
    <?php if ($city->is_has_doctors) { ?>
        <li><a class="<?php echo (isset($menu_active) && $menu_active == 'doctor') ? 'active' : ''; ?> doctor-link" href="/doctor">Врачи</a></li>
    <?php } ?>
    <?php if ($city->is_has_clinics) { ?>
        <li><a class="<?php echo (isset($menu_active) && $menu_active == 'clinic') ? 'active' : ''; ?> clinic-link" href="/clinic">Клиники</a></li>
    <?php } ?>

    <li><a class="<?php echo (isset($menu_active) && $menu_active == 'disease') ? 'active' : ''; ?> disease-link" href="<?php if ($city->getId() == 2) { ?>/disease<?php } else { ?><?php echo strtolower(SITE_URL); ?>/disease<?php } ?>">Заболевания</a></li>
    <li><a class="" href="/shop/catalog">Лекарства</a></li>
    <li><a class="" href="/action">Акции</a></li>
    <?php if (Application::config('section.services.available')) : ?>
        <li><a class="" href="/uslugi">Услуги</a></li>
    <?php endif; ?>
</nav>

<div class="container-fluid">
    <header class="row responsive-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 hidden-md responsive-header__right responsive-header_desktop">
                  <nav>
                    <ul class="responsive-header__nav_desktop">
                        <?php if ($city->is_has_doctors) { ?>
                            <li><a class="<?php echo (isset($menu_active) && $menu_active == 'doctor') ? 'active' : ''; ?> doctor-link" href="/doctor">Врачи</a></li>
                        <?php } ?>
                        <?php if ($city->is_has_clinics) { ?>
                            <li><a class="<?php echo (isset($menu_active) && $menu_active == 'clinic') ? 'active' : ''; ?> clinic-link" href="/clinic">Клиники</a></li>
                        <?php } ?>

                        <li><a class="<?php echo (isset($menu_active) && $menu_active == 'disease') ? 'active' : ''; ?> disease-link" href="<?php if ($city->getId() == 2) { ?>/disease<?php } else { ?><?php echo strtolower(SITE_URL); ?>/disease<?php } ?>">Заболевания</a></li>
                        <li><a class="" href="/shop/catalog">Лекарства</a></li>
                        <li><a class="" href="/action">Акции</a></li>
                        <?php if (Application::config('section.services.available')) : ?>
                            <li><a class="" href="/uslugi">Услуги</a></li>
                        <?php endif; ?>
                    </ul>
                  </nav>
                </div>

                <div class="col-lg-1 col-md-2 col-sm-2 col-xs-2 responsive-header__relative hidden-md hidden-sm hidden-xs">
                    <a href="/" class="responsive-header__logo responsive-header_desktop__center"></a>
                </div>

                <div class="col-lg-5 col-md-10 responsive-header__left">
                  <nav>
                    <ul class="responsive-header__nav nav__main">
                        <li class="responsive-header__city">
                            <a class="responsive-header__a_wodecoration" href="javascript:void(0);">
                                <span class="responsive-header__a_dotted popup_city"><?php echo $city->name; ?></span>
                            </a>
                        </li>

                        <li>
                            <span>
                                <? if (SITE_PHONE_CODE == '800'){echo '+7'; }?> (<?php echo SITE_PHONE_CODE; ?>)
                                <a href="tel:+7(<?php echo SITE_PHONE_CODE; ?>)<?php echo SITE_PHONE; ?>"><?php echo SITE_PHONE; ?></a>
                                с 09 до 21
                            </span>
                        </li>

                        <li>
                            <div class="form-call-wrapper responsive-header__a_wodecoration" href="javascript:void(0);">
                                <span class="order-call responsive-header__a_dotted">Заказать звонок</span>

                                <div class="form-call form-call-step-1">
                                    <p class="h-txt">Заказать звонок</p>
                                    <?php if (!$daytime): ?>
                                        <p class="time-txt">Мы работаем с 9 до 21. Ваша заявка будет обработана в начале рабочего дня.</p>
                                    <?php endif; ?>
                                    <div>
                                        <label>Тел:</label>
                                        <input class="mask error" type="text" name="phone_number" placeholder="+7-___-___-__-__">
                                        <div class="clearfix"></div>
                                        <label>Имя:</label>
                                        <input class="success" type="text" name="first_name" placeholder="Имя">
                                    </div>
                                    <input class="btn-call" type="button" value="Позвоните мне!"/>

                                </div>

                                <div class="form-call form-call-step-2">
                                    <p class="h-txt">Заказан звонок</p>
                                    <p class="txt">
                                        Мы свяжемся с Вами<br/>
                                        <?php if (!$daytime): ?>
                                            c 9 до 10 часов утра
                                        <?php else: ?>
                                            в течение 5 минут
                                        <?php endif; ?>
                                    </p>
                                    <p class="txt">
                                        Спасибо,<br/>
                                        что выбрали нас!
                                    </p>
                                </div>
                            </div>
                        </li>

                        <li>
                            <div id="authorization-block-on-disease-page" class="no-auth-buttons">
                                <a class="btn-enter reg-linking" href="javascript:void(0);">Войти</a>
                            </div>
                        </li>
                    </ul>
                  </nav>
                </div>
            </div>
        </div>
    </header>
</div>
