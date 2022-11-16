var LandingLoginPageController = function (page_url, specialty_text, recording, action_for_counters, label_for_counters) {
    var self = this;
    this.page_url = page_url;
    this.specialty_text = specialty_text;
    this.recording = recording;
    this.action_for_counters = action_for_counters;
    this.label_for_counters = label_for_counters;

    this.block_title = null;
    this.doctor_icon_text = null;
    this.block_over_textbox = null;

    this.popup = null;

    this.success_login_callback = null;

    this.init = function () {

        if (LandingLoginPageController.block_show_popup_flag)
            return;

        LandingLoginPageController.block_show_popup_flag = true;

        if (self.page_url == undefined)
            self.page_url = '/account';

        if(self.specialty_text == undefined)
            self.specialty_text = 'врачи';

        if (self.block_title == null)
            self.block_title = 'Лучшие врачи Москвы';

        if (self.doctor_icon_text == null)
            self.doctor_icon_text = 'В нашей базе лучшие врачи Москвы';

        var options = {
            landing: true,
            type: 'landing_login'
        };

        if (self.action_for_counters == undefined)
            self.action_for_counters = 'landing-login-reg';

        Ajax.Get('/ajax/getPopup', options, function (data) {
            if (data.status == 0) {
                self.popup = new Popup();
                self.popup.show(data.result.html, '550px', undefined, true);
                $('.fancybox-overlay').css('background-image', 'url("/media/images/fancybox_overlay_light.png")');

                self.popup.setCloseCallback(function() {
                    LandingLoginPageController.block_show_popup_flag = false;
                });

                if (self.block_title == 'для записи к врачу' || self.block_title == 'для добавления в закладки'){
                    $('.head-block h2').html('Войдите ' + self.block_title);
                } else {
                    $('.head-block h2').html(self.block_title);
                }
                self.popup.getElement('.vis-1 p').html(self.doctor_icon_text);


                if (self.popup.getElement('.vis-1 p').text().length > 50) {
                    self.popup.getElement('.head-block').css('height',245);
                    self.popup.getElement('.head-block').css('background','url("/media/images/landing_popup_bg.jpg") no-repeat scroll 0 -44px transparent');
                }


                self.popup.getElement('#submit_landing_login').click(function () {
                    self.tryLogin();
                });

                self.popup.getElement('#fb_login').click(function(){
                    setCookie('fb_redirect_url',self.page_url,"Mon, 01-Jan-2040 00:00:00 GMT", "/");
                });

                self.changeSocialLink();


                self.popup.getElement('#landing-registration-link').click(function () {
                    self.popup.hide();
                    var landing_registration_page_controller = new LandingRegistrationPageController(self.page_url, self.specialty_text, self.recording);
                    landing_registration_page_controller.action_for_counters = self.action_for_counters;
                    landing_registration_page_controller.label_for_counters = self.label_for_counters;
                    landing_registration_page_controller.success_registration_callback = self.success_login_callback;
                    landing_registration_page_controller.block_title = self.block_title;
                    landing_registration_page_controller.block_over_textbox = self.block_over_textbox;

                    landing_registration_page_controller.init();
                });

                self.popup.getElement('#forgot-pass-link').click(function () {
                    self.popup.hide();
                    var password_recovery_controller = new PasswordRecoveryController();
                    password_recovery_controller.init();
                });

                document.onkeyup = function (e) {
                    e = e || window.event;
                    if (e.keyCode === 13) {
                        self.popup.getElement('#submit_landing_login').click();
                    }
                    // Отменяем действие браузера
                    return false;
                };
            }
        });

    };

    this.tryLogin = function () {
        var email = self.popup.getElement('input[name="landing_login_email"]').val();
        var password = self.popup.getElement('input[name="landing_login_password"]').val();

        var data = {
            email: email,
            password: password
        };

        Ajax.Post('/account/ajaxLogin', data, function (data) {
            if (data.status == 0) {
                if (self.success_login_callback) {
                    SessionInfo.email = email;
                    self.success_login_callback(data);
                } else {
                    if (self.page_url != undefined) {
                        if (self.recording)
                            window.location = self.page_url+'?recording=1';
                        else
                            window.location = self.page_url;
                    }
                    else
                        window.location = '/account';
                }
                self.popup.close();
                SessionInfo.is_authed = true;
            }
            else if (data.status == 23) {
                self.showError(self.popup.getElement('#submit_landing_login'), 'Вы ещё не получили приглашение');
            }
            else {
                $('.error-msg-auth').remove();
                showLoginOrPhoneValidationMessage(self.popup.getElement('input[name="landing_login_email"]'), self.popup.getElement('input[name="landing_login_password"]'));
            }
        });
    };

    this.showError = function (el, message) {
        $('.error-msg-auth').remove();
        var error = $('<div class="error-msg-auth"><label class="error" for="landing_login_form">' + message + '</label></div>');
        var pos = el.position();

        error.css({
            top: '-' + el.height(),
            'margin-left': 130,
            right: el.parent().width() - pos.left,
            position: 'relative',
            display: 'block',
            width: '250px'
        });

        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    };

    this.changeSocialLink = function(){
        var site_url = $('.socials input[name="site_url"]').val();

        var vk_cliend_id = $('.socials input[name="vk_client_id"]').val();
        var vk_dest_url = '?destination='+self.page_url;
        var vk_redirect_url = site_url+'/account/vk_login'+vk_dest_url;
        var vk_link = 'https://oauth.vk.com/authorize?client_id='+vk_cliend_id+'&scope=photos,offline&redirect_uri='+vk_redirect_url+'&response_type=code';
        self.popup.getElement('#vk_login').attr('href', vk_link);

        var fb_cliend_id = $('.socials input[name="fb_client_id"]').val();
        //var fb_dest_url = '?destination='+self.page_url;
        var fb_redirect_url = site_url+'/account/fb_login';//+fb_dest_url;
        var fb_link = 'https://www.facebook.com/dialog/oauth?client_id='+fb_cliend_id+'&scope=email,user_birthday,user_location&redirect_uri='+fb_redirect_url+'&response_type=code';
        self.popup.getElement('#fb_login').attr('href', fb_link);

        var mailru_cliend_id = $('.socials input[name="mailru_client_id"]').val();
        var mailru_dest_url = '?destination='+self.page_url;
        var mailru_redirect_url = site_url+'/account/mailru_login'+mailru_dest_url;
        var mailru_link = 'https://connect.mail.ru/oauth/authorize?client_id='+mailru_cliend_id+'&response_type=code&redirect_uri='+mailru_redirect_url;
        self.popup.getElement('#mailru_login').attr('href', mailru_link);

        var ok_cliend_id = $('.socials input[name="ok_client_id"]').val();
        var ok_dest_url = '?destination='+self.page_url;
        var ok_redirect_url = site_url+'/account/ok_login'+ok_dest_url;
        var ok_link = 'https://www.odnoklassniki.ru/oauth/authorize?client_id='+ok_cliend_id+'&response_type=code&redirect_uri='+ok_redirect_url;
        self.popup.getElement('#ok_login').attr('href', ok_link);
    }
}

LandingLoginPageController.block_show_popup_flag = false;
