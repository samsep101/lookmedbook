var PasswordRecoveryController = function () {

    var self = this;
    self.email = '';
    self.popup = null;
    self.send_block = false;

    this.init = function () {

        var html = '<div class="reg-popup">' +
                        '<img class="logo" src="/media/images/main_logo.png" alt="">' +
                        '<p class="intro">Напиши свой телефон или email. Мы вышлем инструкцию ' +
                        'для смены пароля.</p>' +
                        '<div class="form forgot-form">' +
                            '<div id="forgotpass-form">' +
                                '<div class="row flo">' +
                                    '<div class="txt">' +
                                        '<input type="email" value="" id="recovery_email" placeholder="Телефон или email" class="placeholder">' +
                                    '</div>' +
                                '</div>' +
                            '<div class="btns">' +
                                '<input type="button" value="Восстановить пароль" class="btn-1 submit_email" id="send_recovery_email">' +
                            '</div>' +
                        '</div>'+
                    '</div>';


        self.popup = new Popup();
        self.popup.show(html);
        if (navigator.appName.indexOf('Explorer') + 1 && parseInt($.browser.version, 10) <= 9){
            $('input[placeholder]').placeholder();
        }
        self.popup.popup_block.find('.submit_email').click(function(){
            self.sendRecoveryPasswordEmail();
        });

        var a = self.popup.popup_block.find('input[type="email"]');
        self.popup.popup_block.find('input[type="email"]').onkeyup = function (e) {
            e = e || window.event;
            if (e.keyCode === 13) {
                self.sendRecoveryPasswordEmail();
            }
            // Отменяем действие браузера
            return false;
        };
    };

    this.sendRecoveryPasswordEmail = function () {

        if(self.send_block)
            return;

        self.send_block = true;

        self.email = self.popup.popup_block.find('#recovery_email').val();

        Ajax.Post('/account/ajaxPasswordRecovery', {
                email: self.email
            },
            function (data) {
                if (data.status == 0) {
                    self.popup.close();

                    var popup_message = new PopupMessage();

                    if(self.email.match(/[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-\.]/)) {
                    popup_message.show('Письмо отправлено на ' + self.email + '.');
                    } else {
                        popup_message.show('Сообщение отправлено на ' + self.email + '.');
                    }

                } else if (data.status == 32) {
                    $('.error-msg-email').remove();
                    self.setError($('#forgotpass-form input.submit_email'), 'Данный email еще не подтвержден');
                }
                else if(!self.email.match(/[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-\.]/) && !self.email.match(/^(\+?7|8)[0-9]{10}$/)) {
                    $('.error-msg-email').remove();
                    self.setError(self.popup.popup_block.find('.submit_email'), 'В поле телефон или email должен быть введён корректный телефон или email-адрес');
                } else {
                    $('.error-msg-email').remove();
                    self.setError(self.popup.popup_block.find('.submit_email'), 'Пользователь с таким телефоном или E-mail не зарегистрирован');
                }

                self.send_block = false;
            }
        );
    };

    this.setError = function(el, message) {
        var error = $('<div class="error-msg-email">' + message + '</div>');
        var pos =  el.position();

        error.css({
            top: -120,
            'margin-left': -20,
            right: 10,
            position: 'relative',
            display: 'block'
        });

        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    }
};