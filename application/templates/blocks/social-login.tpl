<div class="socials">
    <div class="heading">
        <h3><span>Или войди с помощью</span></h3>
    </div>
    <ul>

        <li class="socials-vkontakte">
            <a id="vk_login" href="https://oauth.vk.com/authorize?client_id=<?php echo SettingsManager::get('vk_client_id'); ?>&scope=photos,offline&redirect_uri=<?php echo SITE_URL; ?>/account/vk_login&response_type=code"></a>
            <input type="hidden" name="vk_client_id" value="<?php echo SettingsManager::get('vk_client_id'); ?>">
        </li>

        <li class="socials-odnoklassniki">
            <a id="ok_login" href="https://www.odnoklassniki.ru/oauth/authorize?client_id=<?php echo SettingsManager::get('ok_client_id'); ?>&response_type=code&redirect_uri=<?php echo SITE_URL; ?>/account/ok_login"></a>
            <input type="hidden" name="ok_client_id" value="<?php echo SettingsManager::get('ok_client_id'); ?>">
        </li>

        <li class="socials-mailru">
            <a id="mailru_login" href="https://connect.mail.ru/oauth/authorize?client_id=<?php echo SettingsManager::get('mailru_client_id'); ?>&response_type=code&redirect_uri=<?php echo SITE_URL; ?>/account/mailru_login"></a>
            <input type="hidden" name="mailru_client_id" value="<?php echo SettingsManager::get('mailru_client_id'); ?>">
        </li>

        <li class="socials-facebook">
            <a id="fb_login" href="https://www.facebook.com/dialog/oauth?client_id=<?php echo SettingsManager::get('facebook_client_id'); ?>&scope=email,user_birthday,publish_stream,user_likes,friends_likes,user_subscriptions,friends_subscriptions,user_status,friends_status,user_location&redirect_uri=<?php echo SITE_URL; ?>/account/fb_login&response_type=code"></a>
            <input type="hidden" name="fb_client_id" value="<?php echo SettingsManager::get('facebook_client_id'); ?>">
        </li>

        <input type="hidden" name="site_url" value="<?php echo SITE_URL; ?>">

    </ul>
</div>