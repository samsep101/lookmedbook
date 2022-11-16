var PersonalRoomTrustController = function (in_process_sn) {
    var self = this;

    self.confirm_code = '';
    self.phone;
    self.in_process_sn = in_process_sn;

    this.init = function () {

        if (self.in_process_sn == 1) {
            attachFancybox($('#success-popup-link'));
            self.showPopup('Аккаунт уже используется в системе');
        }

        /*отправка подтверждения регистрации на email*/
        $('#confirm_email').click(function () {
            var email = $(this).data('email');
            $('#confirm_email').attr('disabled','disabled');
            if (email) {
                Ajax.Post('/account/ajaxSendEmailConfirmationMessage',
                    {email: email},
                    function (data) {
                        var popup = new Popup();
                        if (data.status == 0) {
                            document.getElementById('confirm_email').disabled=false;
                            popup.show('<div style="padding: 50px; font-size: 25px">Письмо с подтверждением выслано на Ваш email!</div>');
                        } else {
                            document.getElementById('confirm_email').disabled=false;
                            popup.show('<div style="padding: 50px; font-size: 25px">Ошибка при отправке письма. <br> Попробуйте позже.</div>');
                        }
                    }
                );
            }
        });

        $(".confirm-phone-link").click(function(){
            if ($(this).data('clicked') == 1)
                return;

            $(this).data('clicked', 1);

            var confirm_phone_form_controller = new ConfirmPhoneController();
            confirm_phone_form_controller.phone_id = $(this).data('phone-id');
            confirm_phone_form_controller.tmp_init();

            $(this).data('clicked', 0);
        });
    };


    this.confimPhone = function () {

        Ajax.Post('/account/ajaxConfirmPhoneCode',
            {confirm_code: self.confirm_code, phone: self.phone},
            function (data) {
                if (data.status == 0) {
                    $('.fancybox-close').click();
                    $('.not_confrim_block').addClass('item-approved');
                } else {
                    $('.intro').text('Неверный код!');
                }
            }
        );
    };

    this.showPopup = function (text) {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }

}