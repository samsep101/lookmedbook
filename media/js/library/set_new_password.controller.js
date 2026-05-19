var SetNewPasswordController = function (email, not_confirmed_email) {

    var self = this;
    self.email = email;
    self.not_confirmed_email = not_confirmed_email;

    this.init = function () {

        var options = {
            landing: true,
            type: 'set_new_password'
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

                $('#set_new_password').click(function(){
                    $('.error_span').remove();
                    self.setNewPassword();
                });

                $('#set_new_password').validation({
                    validate: [
                        $('#set_password').validate(validation_rules['password']),
                        $('#set_repeat_password').validate(validation_rules['password_repeat'])
                    ],
                    callback: self.setNewPassword
                });

                if (self.not_confirmed_email == 1){
                    var check_box = '<div class="chekBox row flo"><span></span>Подтвердить email<input id="check_confirm" type="hidden" value=""></div>';
                    $('.repeat_pass').after(check_box)
                }

                $('.chekBox').click(function(){

                    $(this).toggleClass('act');

                    if ($(this).hasClass('act'))
                        $('#check_confirm').val('1');
                    else
                        $('#check_confirm').val('');
                })
            }
        });
    };

    this.setNewPassword = function () {
        var options = '';

        if(email) {
            options = {
                email: self.email,
                password: $('#set_password').val(),
                is_confirm_email : $('#check_confirm').val()
            };

            Ajax.Post('/account/setNewPassword', options, function (data) {
                    if (data.status == 0) {
                        $('#forgotpass-popup .intro').html('Пароль успешно изменен!');
                        window.location = '/account';
                    } else {
                        $('.error-msg-rec-pass').remove();
                        self.setError($('#set_new_password'), 'Ошибка! попробуйте еще раз');
                    }
                }
            );
        } else {
            options = {
                password: $('#set_password').val(),
                password_repeat: $('#set_repeat_password').val()
            };

            if(options.password && options.password_repeat && options.password === options.password_repeat) {
                Ajax.Post('/account/setNewPasswordPhone', options, function (data) {
                        if (data.status == 0) {
                            window.location = '/account';
                        } else {
                            $('.error-msg-rec-pass').remove();
                            self.setError($('#set_new_password'), 'Ошибка! попробуйте еще раз');
                        }
                    }
                );
            }
        }
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