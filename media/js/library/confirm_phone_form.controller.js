var ConfirmPhoneController = function () {
    var self = this;

    this.phone_id = null;
    this.phone_number = null;
    this.check_id = null;

    this.code = null;

    this.popup = null;

    this.success_callback = null;

    this.setPhoneId = function(phone_id)
    {
        self.phone_id = phone_id;
    };

    this.tmp_init = function()
    {
        self.sendConfirmCode();
        self.showPopup();
        $('.fancybox-close').click( function(event){
            self.popup.close();
       });
    };

    this.setSuccessCallback = function(callback){
        self.success_callback = callback;
    };

    this.showPopup = function(){
        var html_code = '<div id="confirm-phone-popup9" class="phone-confirm-popup popup" style="display: block;">';
        //var html_code = '';
        html_code += '<img class="logo" src="/media/images/main_logo.png" alt="">';
        html_code += '<p class="intro">Напиши код, который пришел по смс</p>';
        html_code += '<div class="form forgot-form">';
        html_code += '<div class="row flo">';
        html_code += '<div class="txt">';
        html_code += '<input type="text" id="confirm_code' + self.phone_id +'" name="confirm_code9" placeholder="код" data-rule-required="true" data-msg-required="Введите еод" data-msg-email="Некорректный код">';
        html_code += '</div>';
        html_code += '</div>';
        html_code += '<div class="btns">';
        html_code += '<input data-phone-id="9" name="submit-confirm-code" type="submit" value="Подтвердить" class="btn-1 submit-button">';
        html_code += '</div>';
        html_code += '</div>';
        html_code += '</div>';

        var html = $(html_code);

        html.find('input[name="confirm_code9"]').keyup(function (e){
            e = e || window.event;
            if(e.keyCode == 13){
                self.sendRequest();
            }
        });

        html.find('input[name="submit-confirm-code"]').click(function(){
            self.sendRequest();
        });
        var popup = new Popup();
        popup.show(html);

        self.popup = popup;
        if (navigator.appName.indexOf('Explorer') + 1 && parseInt($.browser.version, 10) <= 9){
            $('[placeholder]').placeholder();
        }
   };

    this.sendConfirmCode = function(){
        var data = {
            phone_id: self.phone_id,
            phone_number: self.phone_number
        };
        Ajax.Post('/account_phone/ajaxSendConfirmedCode', data, function (data) {
            if(data.status == 0)
            {
                self.check_id = data.result.check_id;
        }
        });
    };



    this.sendRequest = function () {
        var confirm_code = $('#confirm_code' + self.phone_id).val();

        self.code = confirm_code;

        var data = {
            check_id: self.check_id,
            confirm_code: confirm_code
        };

        Ajax.Post('/account_phone/ajaxConfirmPhoneCode', data, function (data) {
            if (data.status == 0) {
                $('#not_confirmed_block'+self.phone_id).addClass('item-approved');

                self.popup.close();

                if (self.success_callback)
                {
                    self.success_callback();
                }
            } else {
                showErrorLabel('Введен неверный код', $('#confirm_code' + self.phone_id));
            }
        });
    };
};