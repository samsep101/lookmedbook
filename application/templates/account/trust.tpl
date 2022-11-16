<?php $is_conected = (isset($_GET['is_conected'])) ? 1 : null; ?>

<script type="text/javascript">
    $(document).ready(function () {
        var controller = new PersonalRoomTrustController(<?php echo $is_conected; ?>);
        controller.init();
    });
</script>

<div class="inner-3">
    <div class="cab-page flo">

        <?php $this->active_top_menu = 'profile'; ?>
        <?php $this->block('blocks/personal-room-top-menu'); ?>

        <?php $this->active_left_menu = 'trust'; ?>
        <?php $this->block('blocks/personal-room-left-menu'); ?>

        <div class="cab-cont">
            <div class="owl-block">
                <div class="note"> <span class="corn"></span>
                    <div class="note-cont">
                        <p class="center-align"><img src="/media/images/note-pic1.png" alt=""></p>
                        <p>Подтвержденный аккаунт получает больше возможностей</p>
                    </div>
                </div>
            </div>
            <h2>Доверие</h2>
            <div class="conf-block">

                <?php if ($current_account->phones): ?>
                    <?php foreach ($current_account->phones as $account_phone): ?>
                        <div class="item flo item-approved">
                            <div class="descr">
                                <span class="title">Номер телефона <span class="status"></span></span>
                                <p><?php echo PhoneFormatViewHelper::view($account_phone->phone); ?></p>
                                <p>Мы передадим номер телефона в клинику только после Вашей записи на приём</p>
                            </div>
                            <a class="reg-link" href="#forgotpass-popup">
                                <input class="btn-3" type="submit" value="Подтвердить">
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($current_account->not_confirmed_phones): ?>
                    <?php foreach ($current_account->not_confirmed_phones as $account_phone): ?>

                        <div id="not_confirmed_block<?php echo $account_phone->getId();?>" class="item flo">
                            <div class="descr">
                                <span class="title">Номер телефона <span class="status"></span></span>
                                <p><?php echo PhoneFormatViewHelper::view($account_phone->phone); ?></p>
                                <p>Мы передадим номер телефона в клинику только после Вашей записи на приём</p>
                            </div><br>
                            <a class="confirm-phone-link" data-phone-id="<?php echo $account_phone->getId(); ?>" href="#confirm-phone-popup<?php echo $account_phone->getId(); ?>">
                                <input class="btn-3" type="submit" value="Подтвердить" >
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if ($this->current_account->is_confirm_email): ?>
                    <div class="item item-approved flo">
                        <div class="descr"> <span class="title">Электронная почта <span class="status"></span></span>
                            <p>Мы передадим Ваш адрес в клинику только после Вашей записи на приём</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="item flo">
                        <div class="descr">
                            <span class="title">Электронная почта <span class="status"></span></span>
                            <p>Мы передадим Ваш адрес в клинику только после Вашей записи на приём</p>
                        </div>
                        <?php if ($this->current_account->email): ?>
                            <input class="btn-3" id="confirm_email" data-email="<?php echo $this->current_account->email; ?>" type="submit" value="Подтвердить">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="conf-socials flo">
                <h3>Аккаунты соц. сетей</h3>
                <p>Связь с аккаунтами в социальных сетях повысит доверие <br>
                    к Вам и позволит проще регистрироваться на сайте</p>

                <!--Vkontakte-->
                <?php if ($vk_account): ?>
                    <div class="soc-item vk flo item-approved">
                        <span class="icon"></span>
                        <a href="javascript:void(0)">
                            <input class="soc-btn" type="submit" value="Связать с Вконтакте">
                        </a>
                        <div class="clear"></div>
                        <span class="status"></span>
                    </div>
                <?php else: ?>
                    <div class="soc-item vk flo">
                        <span class="icon"></span>
                        <a href="https://oauth.vk.com/authorize?client_id=<?php echo SettingsManager::get('vk_client_id'); ?>&scope=friends,photos,groups,offline&redirect_uri=<?php echo SITE_URL; ?>/account/joinVkAccount&response_type=code">
                            <input class="soc-btn" type="submit" value="Связать с Вконтакте">
                        </a>
                        <div class="clear"></div>
                        <span class="status"></span>
                    </div>
                <?php endif; ?>
                <!-- End Vkontakte -->

                <!--Facebook-->
                <?php if ($fb_account): ?>
                    <div class="soc-item fb flo item-approved">
                        <span class="icon"></span>
                        <a href="javascript:void(0)">
                            <input class="soc-btn" type="submit" value="Связать с Facebook">
                        </a>
                        <div class="clear"></div>
                        <span class="status"></span>
                    </div>
                <?php else: ?>
                    <div class="soc-item fb flo">
                        <span class="icon"></span>
                        <a href="https://www.facebook.com/dialog/oauth?client_id=<?php echo SettingsManager::get('facebook_client_id'); ?>&scope=email,user_birthday,publish_stream,read_friendlists,user_groups,user_likes,user_about_me,user_status,friends_status,user_education_history,friends_subscriptions,user_subscriptions,friends_likes,user_relationship_details,user_religion_politics,user_relationships,user_website,user_activities,user_interests&redirect_uri=<?php echo SITE_URL; ?>/account/joinFbAccount&response_type=code">
                            <input class="soc-btn" type="submit" value="Связать с Facebook">
                        </a>
                        <div class="clear"></div>
                        <span class="status"></span>
                    </div>
                <?php endif; ?>
                <!--End Facebook-->

                <!--Odnoklassniki-->
                <?php if ($ok_account): ?>
                    <div class="soc-item odn flo item-approved">
                        <span class="icon"></span>
                        <a href="javascript:void(0)">
                            <input class="soc-btn" type="submit" value="Связать с Одноклассниками">
                        </a>
                        <div class="clear"></div>
                        <span class="status"></span>
                    </div>
                <?php else: ?>
                    <div class="soc-item odn flo">
                        <span class="icon"></span>
                        <a href="https://www.odnoklassniki.ru/oauth/authorize?scope=VALUABLE ACCESS&client_id=<?php echo SettingsManager::get('ok_client_id'); ?>&response_type=code&redirect_uri=<?php echo SITE_URL; ?>/account/joinOkAccount">
                            <input class="soc-btn" type="submit" value="Связать с Одноклассниками">
                        </a>
                        <div class="clear"></div>
                        <span class="status"></span>
                    </div>
                <?php endif; ?>
                <!--End Odnoklassniki-->

                <!--Mail.ru-->
                <?php if ($mailru_account): ?>
                    <div class="soc-item mlr flo item-approved">
                        <span class="icon"></span>
                        <a href="javascript:void(0)">
                            <input class="soc-btn" type="submit" value="Связать с Mail.ru">
                        </a>
                        <div class="clear"></div>
                        <span class="status"></span>
                    </div>
                <?php else: ?>
                    <div class="soc-item mlr flo">
                        <span class="icon"></span>
                        <a href="https://connect.mail.ru/oauth/authorize?client_id=<?php echo SettingsManager::get('mailru_client_id'); ?>&response_type=code&redirect_uri=<?php echo SITE_URL; ?>/account/joinMailruAccount">
                            <input class="soc-btn" type="submit" value="Связать с Mail.ru">
                        </a>
                        <div class="clear"></div>
                        <span class="status"></span>
                    </div>
                <?php endif; ?>
                <!--End Mail.ru-->
            </div>
        </div>
    </div>
</div>


