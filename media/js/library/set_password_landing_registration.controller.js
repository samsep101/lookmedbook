var SetPasswordLandingRegistrationController = function (page_url, email) {

    var self = this;
    this.page_url = page_url;
    this.email = email;

    this.init = function () {

        var options = {
            landing: true,
            type: 'set_new_password_on_landing'
        };

        Ajax.Get('/ajax/getPopup', options, function (data) {
            if (data.status == 0) {
                var landing_new_pass_popup = data.result.html;

                showLandingForgotPassPopup(landing_new_pass_popup);

                $('#new-pass-popup').css('display', 'block');

                document.onkeyup = function (e) {
                    e = e || window.event;
                    if (e.keyCode === 13) {
                        $('#set_new_password').click();
                    }
                    // Отменяем действие браузера
                    return false;
                };

                $('#set_new_password').validation({
                    validate: [
                        $('#set_password').validate(validation_rules['password']),
                        $('#set_repeat_password').validate(validation_rules['password_repeat'])
                    ],
                    callback: self.setNewPassword
                });
            };
        });

        /*attachFancybox($('#new_pass_popup_link'));
        $("#new_pass_popup_link").trigger('click');*/

    };

    this.setNewPassword = function () {

        var options = {
            email: self.email,
            password: $('#set_password').val()
        }
        Ajax.Post('/account/setLandingRegistrationPassword', options, function (data) {
                if (data.status == 0) {
                    $('#forgotpass-popup .intro').html('Пароль успешно изменен!')
                    window.location =  self.page_url;
                } else if (data.status == 32) {
                    window.location = '/?is_change_password=1';
                } else {
                    $('.error-msg-rec-pass').remove();
                    self.setError($('#set_new_password'), 'Ошибка! попробуйте еще раз');
                }
            }
        );
    };

    this.setError = function (el, message) {
        var error = $('<div class="error-msg-rec-pass">' + message + '</div>');
        var pos = el.position();

        error.css({
            top: -120,
            'margin-left': 160,
            right: 10,
            position: 'relative',
            display: 'block'
        });
        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    }
};