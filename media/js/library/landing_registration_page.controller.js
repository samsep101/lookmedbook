var LandingRegistrationPageController = function (url_page,specialty_text,recording) {

    var self = this;
    this.url_page = url_page;
    this.specialty_text = specialty_text;
    this.recording = recording;
    self.label_for_counters = null;
    self.action_for_counters = null;


    this.block_title = null;
    this.doctor_icon_text = null;
    this.block_over_textbox = null;

    this.popup = null;

    this.success_registration_callback = null;

    this.init = function () {
        if (LandingRegistrationPageController.block_show_popup_flag)
            return;

        LandingRegistrationPageController.block_show_popup_flag = true;
        if (self.url_page == undefined)
            self.url_page = '/account';

        if (self.url_page == undefined)
            self.url_page = '/account';

        if(self.specialty_text == undefined)
            self.specialty_text = 'врачи';

        if (self.block_title == null)
            self.block_title = 'Лучшие врачи Москвы';

        if (self.doctor_icon_text == null)
            self.doctor_icon_text = 'В нашей базе лучшие врачи Москвы';


        var options = {
            landing: true,
            type: 'landing_registration'
        };

        setCounters('reg-begin', self.action_for_counters, self.label_for_counters, 'guest');

        Ajax.Get('/ajax/getPopup', options, function (data) {
            if (data.status == 0) {

                self.popup = new Popup();
                self.popup.show(data.result.html, '550px', undefined, true);

                self.popup.getElement('.fancybox-wrap').addClass('fancybox-wrap-owl');

                $('.fancybox-overlay').css('background-image', 'url("/media/images/fancybox_overlay_light.png")');

                self.popup.setCloseCallback(function() {
                    LandingRegistrationPageController.block_show_popup_flag = false;
                });

                if (self.block_title == 'для записи к врачу' || self.block_title == 'для добавления в закладки'){
                    $('.head-block h2').html('Зарегистрируйтесь ' + self.block_title);
                } else {
                    $('.head-block h2').html(self.block_title);
                }
                $('.vis-1 p').html(self.doctor_icon_text);
                $('.cont.reg-popup h3').html(self.block_over_textbox);


                if (self.popup.getElement('.vis-1 p').text().length > 50) {
                    $('.head-block').css('height',245);
                    $('.head-block').css('background','url("/media/images/landing_popup_bg.jpg") no-repeat scroll 0 -44px transparent');
                }

                self.popup.getElement('#landing-popup-registration').css('display', 'block');
                if (navigator.appName.indexOf('Explorer') + 1 && parseInt($.browser.version, 10) <= 9){
                    $('input[placeholder]').placeholder();
                }

                self.popup.getElement('#landing-login-link').click(function () {
                    self.popup.hide();
                    login_form_controller = new LoginFormController();
                    login_form_controller.init();
                });

                self.popup.getElement('#landing-license').click(function(){
                    self.popup.hide();

                    landing_license = new LandingLicensePageController();
                    landing_license.sent_back_callback = function(){
                        self.popup.show();
                    };
                    landing_license.init();
                });

                self.popup.getElement('#submit_landing_registration').validation({
                    validate: [
                        self.popup.getElement('input[name="landing_registration_email"]').validate(validation_rules['email'])
                    ],
                    callback: function(){
                        self.tryLandingRegister();
                    }
                });

                document.onkeyup = function (e) {
                    e = e || window.event;
                    if (e.keyCode === 13) {
                        self.popup.getElement('#submit_landing_registration').click();
                    }
                    // Отменяем действие браузера
                    return false;
                };
            };
        });
    };

    this.tryLandingRegister = function () {

        self.email = $('#landing-popup-registration input[name="landing_registration_email"]').val();

        var options = {
            email: self.email,
            url: self.url_page
        };

        Ajax.Post('/account/ajaxSimpleRegistration', options, function (data) {
            if (data.status == 0) {
                if (self.success_registration_callback){
                    SessionInfo.email = self.email;
                    self.success_registration_callback(data);
                } else{

                    if (self.url_page != undefined){
                        if (self.recording)
                            window.location = self.url_page+'?recording=1';
                        else
                            window.location = self.url_page;
                    }
                    else
                        window.location = '/account';
                }
                setCounters('reg-complete', self.action_for_counters, self.label_for_counters, self.email);

                self.popup.hide();
                SessionInfo.is_authed = true;
                if (self.recording) window.location = self.url_page+'?recording=1';
                else window.location = self.url_page;
            } else {

                $('.error-msg-email').remove();
            }
        });
    };

    this.setError = function (el, message) {
        var error = $('<div class="error-msg-email">' + message + '</div>');
        var pos = el.position();

        error.css({
            top: -120,
            'margin-left': 210,
            right: 10,
            position: 'relative',
            display: 'block'
        });
        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    };
}

LandingRegistrationPageController.block_show_popup_flag = false;