var LandingPageController = function () {

    var self = this;

    this.name = '';
    this.phone = '';
    this.comment = '';
    this.specialty_id = '';
    this.counter_number = '';
    this.counter_page_index = '';
    this.specialty_alias = '';

    this.init = function () {

        $('input[type="text"], textarea').focus(function(){
            setNewCounters(self.counter_number, self.counter_page_index+'/Page', $(this).data('action'), 'Focus', '');
        });

        $('input[type="text"], textarea').change(function(){
            setNewCounters(self.counter_number, self.counter_page_index+'/Page', $(this).data('action'), 'Change', '');
        });

        $('.order-link-center').click(function(){
            setNewCounters(self.counter_number, self.counter_page_index+'/Page', $(this).data('action'), 'Push', 'Center');
        });

        $('.order-link-bottom').click(function(){
            setNewCounters(self.counter_number, self.counter_page_index+'/Page', $(this).data('action'), 'Push', 'Bottom');
        });

        self.send_call_request = true;
        $('input[type="text"], textarea').val('');
        $('input[name="phone_number"]').inputmask('+7-999-999-99-99', {"clearMaskOnLostFocus": false, "showMaskOnHover": false});

        $('input[name="phone_number"]').validate(validation_rules['call_to_user_phone_number']);
        $('input[name="first_name"]').validate(validation_rules['required']);

        $('.order-form-submit').click(function() {
            setNewCounters(self.counter_number, self.counter_page_index+'/Page', $('.order-form-submit').data('action'), 'Push', '');

            if (self.send_call_request) {
                self.send_call_request = false;
                self.name = ($('input[name="first_name"]').val() != $('input[name="first_name"]').attr('placeholder')) ? $('input[name="first_name"]').val() : '';
                self.phone = ($('input[name="first_name"]').val() != $('input[name="phone_number"]').attr('placeholder')) ? $('input[name="phone_number"]').val() : '';
                self.comment = ($('textarea[name="comment"]').val() != $('textarea[name="comment"]').attr('placeholder')) ? $('textarea[name="comment"]').val() : '';
                self.specialty_id = $('input[name="specialty_id"]').val();
                Ajax.Post('/ajax/landingRecord', {
                        name: self.name,
                        phone: self.phone,
                        comment: self.comment,
                        specialty_id: self.specialty_id
                    },
                    function (data) {
                        if (data.status == 0) {
                            popup_code = '<div class="text-center">Спасибо, Ваша заявка принята!<br><br>Вам перезвонят в течение 15 минут.</div>';
                            var popup_message = new PopupMessage();
                            popup_message.show(popup_code);
                            $('input[type="text"], textarea').val('');

                            setNewCounters(self.counter_number, self.counter_page_index+'/Page', $('.order-form-submit').data('action'), 'Success', '');

                            setCounters('booking-complete', 'landing', self.specialty_alias, SessionInfo.email);

                            setTimeout(function () {
                                self.send_call_request = true;
                            }, 3000);
                        } else {
                            showLandingErrorLabel(data.data, $('.order-form-submit'), 1);
                            self.send_call_request = true;
                        }
                    }
                );
            }
        });
    };
}