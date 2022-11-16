<?php
    /**
     * @var View $this
     * @var int $already_registred_account
     * @var AccountModel $current_account
     * @var string $menu_active
	 * @var CityModel|null $city
     */

    if (isset($_COOKIE['already_registred_account']))
        $already_registred_account = 1;
    else
        $already_registred_account = 0;
?>

<script>
    $(document).ready(function(){
        var header_controller = new HeaderController();
        header_controller.init();
    });
</script>

<header class="header">
    <div class="inner flo">
        <a class="logo" href="<?php if($city->alias) { echo '/';} else echo SITE_URL.'/'; ?>" title="<?php echo PAGE_TITLE . $city->prepositional_name; ?> – <?php echo SITE_NAME; ?>">
            <img class="main-logo" src="/media/images/blank.png" alt="<?php echo PAGE_TITLE . $city->prepositional_name; ?> – <?php echo SITE_NAME; ?>"/></a>
        <?php if (!isset($example_page)): ?>
            <nav>
				<?php if (!$city || $city->hasDoctors() && $city) { ?>
                	<a class="<?php echo (isset($menu_active) && $menu_active == 'doctor') ? 'active' : ''; ?> doctor-link" href="<?php if($city && $city->isUsed()) { echo '/doctor';} else echo SITE_URL.'/doctor'; ?>">Врачи</a>
                <?php } ?>

				<?php if (!$city || $city->hasClinics() ) { ?>
                	<a class="<?php echo (isset($menu_active) && $menu_active == 'clinic') ? 'active' : ''; ?> clinic-link" href="<?php if($city && $city->isUsed()) { echo '/clinic';} else echo SITE_URL.'/clinic'; ?>">Клиники</a>
				<?php } ?>

                    <a class="<?php echo (isset($menu_active) && $menu_active == 'disease') ? 'active' : ''; ?> disease-link" href="<?php if($city->getId() == 2) { ?>/disease<?php } else { ?><?php echo SITE_URL; ?>/disease<?php } ?>">Заболевания</a>


                <?php /* if($city->getId() == 2) { ?>
                    <a class="<?php echo (isset($menu_active) && $menu_active == 'disease') ? 'active' : ''; ?> disease-link" href="<?php if($city && $city->isUsed()) { echo '/disease';} else echo SITE_URL.'/disease'; ?>">Заболевания</a>
                <?php } */ ?>
				<?php if (!$city || $city->hasLaboratories()) { ?>
                	<a class="<?php echo (isset($menu_active) && $menu_active == 'analysis') ? 'active' : ''; ?> analysis-link" href="<?php if($city && $city->isUsed()) { echo '/analysis';} else echo SITE_URL.'/analysis'; ?>">Анализы</a>
				<?php } ?>



            </nav>
            <script>
                $(document).ready(function () {
                    var disease_search_controller = new DiseaseQuickSearchFormController(<?php echo (!Acc::isAuthed()) ? 1 : 0; ?>, <?php echo $already_registred_account; ?>, "<?php echo (isset($label_for_counters)) ? $label_for_counters : ''; ?>");
                    disease_search_controller.setInputElement($('#disease-quick-search .quick-search-input'));
                    disease_search_controller.setDrowDownContainer($('#disease-quick-search .drop-menu'));
                    disease_search_controller.setSubmitElement($('#disease-quick-search .quick-search-submit'));
                    disease_search_controller.init();
                });
            </script>
            <form action="/search/results" method="GET">
                <div class="quick-search" id="disease-quick-search">
                    <div class="fields flo">
                        <input type="text" class="quick-search-input" autocomplete="off" name="query" placeholder="Найти врача, клинику"/>
                        <input type="submit" class="quick-search-submit" data-action-for-counters="top" value=""/>
                    </div>
                    <ul class="drop-menu">

                    </ul>
                </div>
            </form>
            <script>
                $(document).ready(function(){
                    //var notification_controller = new NotificationController();
                    //notification_controller.init();
                });
            </script>
            <?php if (Acc::isAuthed() && isset($current_account)): ?>
                <div class="header-user">
                    <a href="<?php if($city && $city->isUsed()) { echo '/account/message';} else echo SITE_URL.'/account/message'; ?>" class="header-usernotification"> <span style="display: none;" class="notification" id="usernotification"></span> </a>
                    <div class="header-userinfo"><a href="<?php if($city && $city->isUsed()) { echo '/account/about';} else echo SITE_URL.'/account/about'; ?>" class="header-userprofile">
                            <?php
                                if ($current_account && ($current_account->first_name || $current_account->last_name || $current_account->middle_name))
                                    echo $current_account->last_name.' '.$current_account->first_name.' '.$current_account->middle_name;
                                else if ($current_account->email)
                                    echo $current_account->email;
                                else{?>&nbsp<?php }
                            ?>
                        </a>
                        <ul class="header-usermenu">
                            <li><a href="<?php if($city && $city->isUsed()) { echo '/account/about';} else echo SITE_URL.'/account/about'; ?>">Профиль</a></li>
                            <li><a href="<?php if($city && $city->isUsed()) { echo '/help';} else echo SITE_URL.'/help'; ?>">Помощь</a></li>
                            <li><a href="<?php if($city && $city->isUsed()) { echo '/account/logout';} else echo SITE_URL.'/account/logout'; ?>">Выйти</a></li>
                        </ul>
                    </div>
                    <?php if($current_account && $current_account->is_call_centre_operator): ?>
                        <script type="text/javascript">
                            $(document).ready(function(){
                                var appeal_controller = new CallCentreAppealController();
                                appeal_controller.setButton($('.create-appeal'));
                                appeal_controller.init();
                            });
                        </script>
                        <a class="create-appeal btn-1" href="javascript:void(0);">Создать<br/> обращение</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if(!Acc::isAuthed()): ?>

                <div id="authorization-block-on-disease-page" class="no-auth-buttons">
                    <a class="btn-enter reg-linking" href="javascript:void(0);">Войти</a>
                    <a class="btn-reg reg-linking" data-action-for-counters="top-reg" href="javascript:void(0)">Зарегистрироваться</a>
                </div>
            <?php endif; ?>
            <?php if(Acc::isTemp()): ?>
                <script type="text/javascript">
                    $(document).ready(function(){
                        var set_new_password_controller = new SetNewPasswordController(null, false);
                        set_new_password_controller.init();
                    });
                </script>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</header>