var RegistrationFormController = function (action_for_counters) {
    var self = this;
    self.email = '';
    self.password = '';
    self.action_for_counters = action_for_counters;

    self.popup = null;

    this.init = function () {

        if (self.popup){
            self.popup.show();
        } else {
            if (RegistrationFormController.block_show_popup_flag)
                return;


            RegistrationFormController.block_show_popup_flag = true;

            Ajax.Get('/popup/registration', {}, function(html){
                setCounters('reg-begin', action_for_counters, 'home', 'guest');

                self.popup = new Popup();
                self.popup.show(html);

                if (navigator.appName.indexOf('Explorer') + 1){
                    $('input[placeholder]').placeholder();
                }

                self.popup.setCloseCallback(function(){
                   RegistrationFormController.block_show_popup_flag = false;
                });


                self.popup.getElement('#repeat_registration_password, #password').keyup(function(e) {
                    e = e || window.event;
                    if(e.keyCode == 13){
                        $('input.submit_registration').click();
                    }
                });

                self.popup.getElement('input.submit_registration').validation({
                    validate : [
                        self.popup.getElement('input[name="email"]').validate(validation_rules['email']),
                        self.popup.getElement('#password').validate(validation_rules['password']),
                        self.popup.getElement('#repeat_registration_password').validate(validation_rules['password2'])
                    ],
                    callback : self.tryRegister
                });

                self.popup.getElement('#login-popup-link').click(function(){
                    self.popup.hide();
                    login_form_controller = new LoginFormController();
                    login_form_controller.init();
                });

                self.popup.getElement('.privacy-txt').click(function(){
                    self.popup.hide();
                    var license_popup = new LandingLicensePageController();
                    license_popup.sent_back_callback = function(){
                        self.popup.show();
                    };
                    license_popup.init();
                });

            }, false);
        }


    };

    this.tryRegister = function(){

        self.email =  self.popup.getElement('input[name="email"]').val();
        self.password = self.popup.getElement('input[name="password"]').val();

        var options = {
            email: self.email,
            password: self.password
        };

        Ajax.Post('/account/ajaxRegistration', options, function(data){
            if (data.status == 0)
            {
                setCounters('reg-complete', self.action_for_counters, 'home', self.email);
                document.location = "/account";
            }
            else {

                $('.error-msg-email').remove();
                self.setError(self.popup.getElement('input.submit_registration'), 'Ошибка при регистрации', 'error');
            }
        });
    };

    this.setError = function(el, message, type) {
        var top = -120;
        var left = 190;

        if (type = 'email'){
            var top = -280;
            var left = 170;
        };

        var error = $('<div class="error-msg-email">' + message + '</div>');
        var pos =  el.position();

        error.css({
            top: top,
            'margin-left': left,
            right: 10,
            position: 'relative',
            display: 'block'
        });
        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    };
}

RegistrationFormController.block_show_popup_flag = false;