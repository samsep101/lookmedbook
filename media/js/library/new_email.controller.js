var NewEmailController = function (destination) {

    var self = this;
    this.new_email = '';
    this.destination = destination;

    this.init = function () {

        var options = {
            landing: true,
            type: 'social_new_email'
        };

        Ajax.Get('/ajax/getPopup', options, function (data) {
            if (data.status == 0) {
                var landing_forgotpass_popup = data.result.html;

                showLandingForgotPassPopup(landing_forgotpass_popup);

                $('#landing-forgotpass-popup').css('display', 'block');

                document.onkeyup = function (e) {
                    e = e || window.event;
                    if (e.keyCode === 13) {
                        $('#send_confirm_email').click();
                    }
                    // Отменяем действие браузера
                    return false;
                };

                $('#send_confirm_email').validation({
                    validate : [
                        $('#new_email').validate(validation_rules['email'])
                    ],
                    callback : self.sendConfirmMail
                });
            }
        });
    };

    this.sendConfirmMail = function (email) {

        self.new_email = $('#new_email').val();

        Ajax.Post('/account/setEmail', {
                email:self.new_email
            },
            function (data) {
                if (data.status == 0) {
                    if (self.destination)
                        window.location = self.destination;
                    else {
                        window.location = '/account';
                    }

            }
            }
        );
    }
};