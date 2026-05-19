var ConfirmEmailController = function (email) {

    this.init = function () {

        /*отправка подтверждения регистрации на email*/
        $('#sendEmailConfirmation').click(function () {
            Ajax.Get('/account/ajaxSendEmailConfirmationMessage',
                {email:email},
                function (data) {
                    if (data.status == 0) {
                        showOk('Подтверждение выслано на ' + email);
                    } else {
                        showError('Ошибка при отправке email.');
                    }
                }
            );
        });
    };
};