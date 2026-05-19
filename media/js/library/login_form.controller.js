var LoginFormController = function () {
    var self = this;
    self.registration_form_controller = null;
    self.password_recovery_controller = null;
    this.registration_controller = null;

    self.action_for_counters = null;

    self.popup = null;

    this.init = function () {

        if(self.popup)
        {
            self.popup.show();
        } else {
            if (LoginFormController.block_show_popup_flag)
                return;

            LoginFormController.block_show_popup_flag = true;

            Ajax.Get('/popup/login', {}, function(html){
                self.popup = new Popup();
                self.popup.show(html);

                if (navigator.appName.indexOf('Explorer') + 1 && parseInt($.browser.version, 10) <= 9){
                    $('input[placeholder]').placeholder();
                }

                self.popup.setCloseCallback(function(){
                    LoginFormController.block_show_popup_flag = false;
                });

                document.onkeypress = function (e) {
                    e = e || window.event;
                    if (e.keyCode === 13) {
                        $('#authorization-popup input.submit').click();
                        return false;
                    }
                };

                /*self.popup.getElement('input[name="password"]').keyup(function(e) {
                    e = e || window.event;
                    if(e.keyCode == 13){
                        $('#authorization-popup input.submit').click();
                    }
                });*/

                self.popup.getElement('input.submit').click(function () {
                    self.logIn();
                });

                self.popup.getElement('#registration-popup-link').click(function(){
                    var action_for_counters = $(this).data('action-for-counters');
                    self.action_for_counters = action_for_counters;
                    self.popup.hide();
                    self.registration_form_controller = new RegistrationFormController(action_for_counters);
                    self.registration_form_controller.init();
                });

                self.popup.getElement('#forgot-popup-link').click(function(){
                    self.popup.hide();
                    var password_recovery_popup = new PasswordRecoveryController();
                    password_recovery_popup.init();
                });
            }, false);
        }
    };

    this.logIn = function () {

        var email = self.popup.getElement('input[name="email"]').val();
        var password = self.popup.getElement('input[name="password"]').val();
        var destination = getParameterByName('destination', '/');

        var data = {
            email: email,
            password: password
        };

        Ajax.Post('/account/ajaxLogin', data, function (data) {
            if (data.status == 0) {
                if(data.result.temp_auth) {
                    self.popup.hide();
                    var new_password_popup = new SetNewPasswordController(data.result.email, data.result.is_confirm_email);
                    new_password_popup.init();
                } else {
                window.location = destination;
            }
            }
            else if (data.status == 23) {
                self.showError(self.popup.getElement('input.submit'), 'Вы ещё не получили получили приглашение');
            }
            else {
                $('.error-msg-auth').remove();
                showLoginOrPhoneValidationMessage(self.popup.getElement('input[name="email"]'), self.popup.getElement('input[name="password"]'));
            }
        });

    };

    this.showError = function (el, message) {
        $('.error-msg-auth').remove();
        var error = $('<div class="error-msg-auth"><label class="error" for="login_form">' + message + '</label></div>');
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
}

LoginFormController.block_show_popup_flag = false;
