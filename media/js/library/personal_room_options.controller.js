var PersonalRoomOptionsController = function () {
    var controller = this;

    this.phone_id = 0;
    this.note_type = '';

    this.init = function () {


        if ($('.phone-settings-checkbox-main').hasClass('act'))
            $('.options-mobile').fadeIn();
        else
            $('.options-mobile').fadeOut();

        $(document).on('click','#close_settings_note', function(){
            controller.note_type = 'settings_note';
            controller.closeNote();
        });

        $(document).on('click', '#save-options-button', function () {
            if ($('#mail-checkbox-1').val() == 1) controller.mail_checkbox_1 = 1;
            else controller.mail_checkbox_1 = 0;
            if ($('#mail-checkbox-2').val() == 1) controller.mail_checkbox_2 = 1;
            else controller.mail_checkbox_2 = 0;
            if ($('#mail-checkbox-3').val() == 1) controller.mail_checkbox_3 = 1;
            else controller.mail_checkbox_3 = 0;
            if ($('#sms-checkbox-1').val() == 1) controller.sms_checkbox_1 = 1;
            else controller.sms_checkbox_1 = 0;
            if ($('#sms-checkbox-2').val() == 1) controller.sms_checkbox_2 = 1;
            else controller.sms_checkbox_2 = 0;
            if ($('#sms-checkbox-3').val() == 1) controller.sms_checkbox_3 = 1;
            else controller.sms_checkbox_3 = 0;
            if ($('#sms-checkbox-main').val() == 1) {
                controller.sms_checkbox_main = 1;
            }
            else {
                controller.sms_checkbox_main = 0;
            }
            controller.phone_id = $('.options-phones').val();

            Ajax.Post('/account/ajaxEditPersonalNotificationSettings', {mail_checkbox_1:controller.mail_checkbox_1, mail_checkbox_2:controller.mail_checkbox_2, mail_checkbox_3:controller.mail_checkbox_3, sms_checkbox_1:controller.sms_checkbox_1, sms_checkbox_2:controller.sms_checkbox_2, sms_checkbox_3:controller.sms_checkbox_3, sms_checkbox_main:controller.sms_checkbox_main, phone_id:controller.phone_id}, function (data) {
                if (data.result == true) {
                    controller.showPopup('Изменения сохранены!');
                }
            });
        });

        $(document).on('change', '.options-phones', function () {
            controller.phone_id = $('.options-phones').val();
        });

        $(document).on('click', '.phone-settings-checkbox-main', function () {
            if ($('#sms-checkbox-main').val() != 1) {
                $('.phone-settings-checkbox').removeClass('act');
                $('#sms-checkbox-1').val(0);
                $('#sms-checkbox-2').val(0);
                $('#sms-checkbox-3').val(0);
            }
            else {
                $('.phone-settings-checkbox').addClass('act');
                $('#sms-checkbox-1').val(1);
                $('#sms-checkbox-2').val(1);
                $('#sms-checkbox-3').val(1);
            }
        });
    };

    this.closeNote = function () {
        Ajax.Get('/ajax/closeNote', {note_type: controller.note_type}, function (data) {
            if (data.status == 0)
            {
                $('#close_settings_note').parent().parent().fadeOut();
            }
        });
    };

    this.showPopup = function(text)
    {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }

}