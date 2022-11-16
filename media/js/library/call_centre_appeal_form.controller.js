var CallCentreAppealController = function () {
    var self = this;
    self.form_controller = null;

    self.button = null;

    this.setButton = function (button) {
        self.button = button;
    };

    this.init = function () {
        $(self.button).click(function () {
            self.form_controller = new CallCentreAppealFormController;
            self.form_controller.init();
        });
    };
};

var CallCentreAppealFormController = function () {

    var self = this;

    this.view = new CallCentreAppealFormView(this);

    this.data = {};

    this.success_request_callback = null;

    this.init = function () {
        this.view.show();
    };

    this.sendRequest = function (data) {
        Ajax.Post('/ajax/createAppeal', data, function (data) {
            var message_popup = new PopupMessage();

            if (data.status == 0) {
                self.view.hide();
                message_popup.show('Обращение сохранено');

                if (data.visit_id > 0)
                    window.location = '/admin/visit/edit/?id=' + data.visit_id;

                if (self.success_request_callback)
                    self.success_request_callback();
            } else {
                message_popup.show('В данный момент не удалось сохранить вашу заявку. Попробуйте еще раз.');
                if (self.success_request_callback)
                    self.success_request_callback();
            }
        });
    };

    this.getAccountInfoByPhoneNumber = function (phone_number) {
        var data = {
            phone_number: phone_number
        };
        Ajax.Get('/ajax/getAccountInfoByPhoneNumber', data, function (data) {
            if (data.status == 0) {
                self.view.fillAccountInfo(data.result);
            }
        });
    };

};

var CallCentreAppealFormView = function (controller) {
    var self = this;
    this.controller = controller;

    this.html = null;

    this.data = {
        first_name: null,
        middle_name: null,
        last_name: null,
        title: null,
        visit_source_id: null,
        specialty_id: null,
        appeal_type_id: null,
        is_with_visit: null,
        phone_number: null
    };

    this.block_buttons_click = false;

    this.current_appeal_type = 1;

    this.formElementActions = function() {
        var selectAppealTypeID = $('.popup-handling select[name=appeal_type_id]');
        self.current_appeal_type = selectAppealTypeID.val();
        selectAppealTypeID.on('change', function() {
            self.current_appeal_type = parseInt($(this).val());
            self.formElementValidation();
        })
    };

    this.formElementValidation = function() {
        var validate = [];

        if(self.current_appeal_type == 1) {
            validate = [
                $('input[name="first_name"]').validate(validation_rules['appeal_first_name']),
                $('input[name="phone_number"]').validate(validation_rules['appeal_phone_number']),
                $("input[name='phone_number']").inputmask("+7-999-999-99-99"),
                $('select[name="specialty_id"]:visible').validate(validation_rules['appeal_specialty_id']),
                $('input[name="title"]').validate(validation_rules['appeal_title'])
            ];
        } else {
            validate = [
                $('input[name="first_name"]').validate(validation_rules['appeal_first_name']),
                $('input[name="phone_number"]').validate(validation_rules['appeal_phone_number']),
                $("input[name='phone_number']").inputmask("+7-999-999-99-99"),
                $('input[name="title"]').validate(validation_rules['appeal_title'])
            ];
        }

        $('.popup-handling').find('input[name="with_visit"]').validation({
            validate: validate,
            callback: function () {
                self.data.is_with_visit = 1;
                self.submit();
            }
        });

        $('.popup-handling').find('input[name="without_visit"]').validation({
            validate: validate,
            callback: function () {
                self.data.is_with_visit = 0;
                self.submit();
            }
        });
    };

    this.show = function () {

        self.controller.success_request_callback = function () {
            self.block_buttons_click = false;
        };

        Ajax.Get('/popup/appeal', {}, function (data) {
            if (data.status == 0) {
                var html = data.result;
                html = $(html);
                $('.create-appeal').after(html);
                if (navigator.appName.indexOf('Explorer') + 1 && parseInt($.browser.version, 10) <= 9){
                    $('[placeholder]').placeholder();
                }
                html.find('input[name="cancel"]').click(function (event) {
                    self.hide();
                });


                html.find('input[name="phone_number"]').keyup(function () {
                    var phone_number = $(this).val().replace(/[^0-9]/g, '');
                    if (phone_number.length == 11) {
                        self.controller.getAccountInfoByPhoneNumber(phone_number);
                    }
                });

                self.html = html;


                self.formElementValidation();
                self.formElementActions();
            }
        });
    };

    this.fillAccountInfo = function (account_info) {
        self.html.find('input[name="first_name"]').val(account_info.first_name);
        self.html.find('input[name="middle_name"]').val(account_info.middle_name);
        self.html.find('input[name="last_name"]').val(account_info.last_name);
    };

    this.hide = function () {
        if (self.html) {
            self.html.remove();
            $('.error_span').remove();
        }
        self.html = null;

    };

    this.submit = function () {
        if (self.block_buttons_click)
            return;

        self.block_buttons_click = true;

        self.readData();
        self.controller.sendRequest(self.data);
    };

    this.readData = function (is_with_visit) {

        self.data.first_name = self.html.find('input[name="first_name"]').val();
        self.data.middle_name = self.html.find('input[name="middle_name"]').val();
        self.data.last_name = self.html.find('input[name="last_name"]').val();
        self.data.phone_number = self.html.find('input[name="phone_number"]').val();
        self.data.title = self.html.find('textarea[name="title"]').val();
        self.data.visit_source_id = self.html.find('select[name="visit_source_id"]').val();
        self.data.appeal_type_id = self.html.find('select[name="appeal_type_id"]').val();
        self.data.specialty_id = self.html.find('select[name="specialty_id"]').val();
        self.data.target_call_id = self.html.find('select[name="target_call_id"]').val();
    };
};