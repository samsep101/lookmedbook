var PersonalRoomAboutController = function (current_account_id) {

    var self = this;

    self.nick = '';
    self.first_name = '';
    self.last_name = '';
    self.middle_name = '';
    self.gender = null;
    self.birthday_date = null;
    self.phone_numbers = [];
    self.email = '';
    self.note_type = '';
    self.city = '';
    self.current_account_id = current_account_id;

    self.show_popup = false;
    self.link_to_page = null;

    self.changed_flag = false;
    self.disabl = false;

    this.init = function () {

        self.getCurrentInfo();

        $(document).on('keyup','.city-search-input',function () {
            self.updateDropDownList();
        });

        $(document).on('click','.city-search-input',function () {
            if (!self.city) $(this).val('');
        });

        $(document).on('focusout','.city-search-input',function () {
            if (!self.city) $('.city-search-input').val('');
        });

        $(document).on('click','.search-city-block .drop-menu li',function () {
            $('.city-search-input').val($(this).text());
            self.city_name = $(this).text();
            Ajax.Get('/ajax/getCityId', {city_name : self.city_name}, function (data) {
                if (data.status == 0) {
                    self.city = data.result.city_id;
                    $('.city-search-input').attr('data-id',self.city);
                }
            });
            $('.search-block .drop-menu').slideUp();
            $('.search-block .drop-menu').html('');
        });

        $('body a:not(.chzn-single)').click(function () {

            if (this.href != 'javascript:void(0)') {

                self.checkPhones();

                if (self.show_popup == false) {
                    if (self.isChangeinfo()) {
                        self.show_popup = true;
                    } else {
                        self.show_popup = false;
                    }
                }

                if (self.show_popup) {
                    var modal_window = new ModalWindow();
                    modal_window.show('Cохранить изменения?');

                    var a = $(this);
                    modal_window.setYesAction(function () {
                        self.link_to_page = a.attr('href');
                        $('#save_info_button').click();
                    });

                    var a = $(this);
                    modal_window.setNoAction(function () {
                        window.location = a.attr('href');
                    });
                    return false;
                }
            }
        });

        $('#save_info_button').validation({
            validate: [
                $('.about-form input[name="email"]').validate(validation_rules['email']),
                $('.about-form input[name="birthday_date"]').validate(validation_rules['birthday_date'])
            ],
            callback: self.saveInfo,
            error_callback: function(){
                self.link_to_page = null;
            }
        });

        $('#close_about_note').click(function () {
            self.note_type = 'about_note';
            self.closeNote();
        });

        $('.row-phone input[type="text"]').inputmask('+7-999-999-99-99');

        // установка значения даты рождения
        $(document).on('change', '.about-form select[name="birthday_day"],.about-form select[name="birthday_month"],.about-form select[name="birthday_year"]', function () {
            var day = $('.about-form select[name="birthday_day"]').val();
            var month = $('.about-form select[name="birthday_month"]').val();
            var year = $('.about-form select[name="birthday_year"]').val();

            if (!day || !month || !year) {
                $('.about-form input[name="birthday_date"]').val('');
                return;
            }

            if (month < 10)
                month = '0' + parseInt(month);

            if (day < 10)
                day = '0' + parseInt(day);

            $('.about-form input[name="birthday_date"]').val(year + '-' + month + '-' + day);
        });

        // установка пола
        $(document).on('click', '#male_gender', function () {
            if ($('.about-form input[name="sex_id"]') != 1)
            {
                self.changed_flag = true;
                $('.about-form input[name="sex_id"]').val(1);
                $(this).addClass('selected');
                $('#female_gender').removeClass('selected');
            }
        });

        $(document).on('click', '#female_gender', function () {
            if ($('.about-form input[name="sex_id"]') != 2)
            {
                self.changed_flag = true;
                $('.about-form input[name="sex_id"]').val(2);
                $(this).addClass('selected');
                $('#male_gender').removeClass('selected');
            }
        });

        //add phone
        $('.add-phone').click(function () {
            var add_phone = '<div class="row flo"><label class="lab">Номер телефона</label><div class="data-box"><div class="txt"><input type="text" class="mask new_phone" placeholder="+7-___-___-__-__"></div><span class="note-txt">Передадим в клинику, только после записи на приём</span></div><img class="conf-pic" src="/media/images/conf-pic.png" alt=""></div>';
            $(".row-phone").append(add_phone);
            $('.new_phone').inputmask('+7-999-999-99-99');
            $('.new_phone').trigger('mouseover');
        });


        $(document).on('change', '.about-form input, .about-form select', function(){
            self.changed_flag = true;
        });

        $('.new_phone, .elreary_exist_phone').inputmask('+7-999-999-99-99');
        Inputmask.extendDefinitions({
            'n': {
                "validator": "[А-Яа-яA-Za-z]",
                "cardinality": 1,
                'prevalidator': null
            }
        });
        if (navigator.appName.indexOf('Explorer') < 0 || (navigator.appName.indexOf('Explorer') + 1 && parseInt($.browser.version, 10) > 9)) {
            $('.about-form input[name="last_name"]').inputmask({ "mask": 'n', "repeat": 255, "greedy": false });
            $('.about-form input[name="user_first_name"]').inputmask({ "mask": 'n', "repeat": 255, "greedy": false });
            $('.about-form input[name="middle_name"]').inputmask({ "mask": 'n', "repeat": 255, "greedy": false });
        }


        $('.birthday_data .chzn-search').remove();
    };

    this.showAndHide = function (el) {
        $('.row_other .data-box div' + el).show();
        $('.row_other .data-box div:not("' + el + '")').hide();
    };

    this.readValues = function () {

        self.nick = ($('.about-form input[name="nick"]').val() == $('.about-form input[name="nick"]').attr('placeholder')) ? '' : $('.about-form input[name="nick"]').val();

        self.first_name = ($('.about-form input[name="user_first_name"]').val() == $('.about-form input[name="user_first_name"]').attr('placeholder')) ? '' : $('.about-form input[name="user_first_name"]').val();
        self.last_name = ($('.about-form input[name="last_name"]').val() == $('.about-form input[name="last_name"]').attr('placeholder')) ? '' : $('.about-form input[name="last_name"]').val();
        self.middle_name = ($('.about-form input[name="middle_name"]').val() == $('.about-form input[name="middle_name"]').attr('placeholder')) ? '' : $('.about-form input[name="middle_name"]').val();

        self.gender = $('.about-form input[name="sex_id"]').val();
        self.birthday_date = $('.about-form input[name="birthday_date"]').val();
        self.email = ($('.about-form input[name="email"]').val() == $('.about-form input[name="email"]').attr('placeholder')) ? '' : $('.about-form input[name="email"]').val();
        self.city = ($('input[name="city_query"]').attr('data-id') == undefined) ? '' : $('input[name="city_query"]').attr('data-id');
    };

    this.saveInfo = function () {
        valid = true;

        $('.new_phone').each(function () {
            var add_phone = $(this).val().replace(/\+/, '').replace(/-/g, '');

            if ((add_phone != "") && !self.isValidPhone(add_phone)) {
                valid = false;
                $('.error_span').remove();
                showErrorLabel('В поле должен быть введён корректный телефон! Пример: +7-495-111-11-11', $('.new_phone'));
            }

            if ((add_phone != "") && !self.checkUniquePhone(add_phone)) {
                valid = false;
                $('.error_span').remove();
                showErrorLabel('Данный номер уже есть в базе!', $('.new_phone'));
            }
        });

        $('.elreary_exist_phone').each(function () {
            var new_phone = $(this).val().replace(/\+/, '').replace(/-/g, '');
            var old_phone_id = $(this).data('old_phone_id');
            var old_phone = $(this).data('phone');

            if (old_phone != new_phone) {

                if ((new_phone != "") && !self.checkUniquePhoneForChange(new_phone)) {
                    valid = false;
                    $('.error_span').remove();
                    showErrorLabel('Данный номер уже есть в базе!', $('.elreary_exist_phone'));
                } else {
                    if (self.isValidPhone($(this).val())){
                        self.changeExistPhone(old_phone_id, new_phone, $(this));
                        $(this).data('phone', new_phone);
                    } else {
                        valid = false;
                        $('.error_span').remove();
                        showErrorLabel('В поле должен быть введён корректный телефон! Пример: +7-495-111-11-11', $('.elreary_exist_phone'));
                    }
                };
            };
        });

        if (!valid) return;

        $('#save_info_button').prop("disabled", true);

        $('.new_phone').each(function () {
            if ($(this).val())
                self.phone_numbers.push($(this).val());
        });

        self.readValues();
        var options = {
            nick: self.nick,
            first_name: self.first_name,
            last_name: self.last_name,
            middle_name: self.middle_name,
            sex_id: self.gender,
            birthday_date: self.birthday_date,
            phone_numbers: self.phone_numbers,
            email: self.email,
            city: self.city
        };

        Ajax.Post('/account/ajaxSaveAbout', options, function (data) {
            if (data.status == 0) {
                self.changed_flag = false;
                $('.new_phone').each(function () {
                    if ($(this).val() == '') {
                        $(this).parent().parent().parent().remove();
                    }
                });
                $('.new_phone').attr('disabled', 'disabled');
                $('.new_phone').removeClass('new_phone');

                self.phone_numbers = [];
                if (self.link_to_page)
                {
                    window.location = self.link_to_page;
                } else {
                    var modal_window = new ModalWindow();
                    modal_window.showDefaultPopup('Изменения сохранены!');
                    self.getCurrentInfo();
                }

            }
            $('#save_info_button').prop("disabled", false);
        });
    };

    this.isValidPhone = function (phone) {
        return /^\+?7\-?[0-9]{3}\-?[0-9]{3}\-?[0-9]{2}\-?[0-9]{2}$/.test(phone);
    }

    this.checkUniquePhone = function (phone) {

        var result = true;
        phone = phone.replace(/[^0-9]/, '');
        Ajax.Get(
            '/ajax/checkUnique',
            {
                fields: 'account_phone.phone',
                value: phone,
                check_account_id: false
            },
            function (data) {
                if (data.result == false) {
                    result = false;
                }
            }
        );

        return result;
    };

    this.setError = function (el, message) {

        var error = $('<div class="error-msg-phone">' + message + '</div>');
        var pos = el.position();

        error.css({
            top: 0,
            display: 'block'
        });

        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    }

    this.closeNote = function () {
        Ajax.Get('/ajax/closeNote', {note_type: self.note_type}, function (data) {
            if (data.status == 0) {
                $('#close_about_note').parent().parent().fadeOut();
            }
        });
    };

    this.showPopup = function (text) {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }

    this.showAskToSavePopup = function (text) {
        $('#ask-to-save-link').click();
    }

    this.changeExistPhone = function (old_phone_id, new_phone, el) {
        var options = {
            old_phone_id: old_phone_id,
            new_phone: new_phone
        };
        Ajax.Post('/ajax/changeExistPhone', options, function (data) {
            if (data.result.deleted) {
                el.parent().parent().parent().remove();
            }
            if (data.status != 0) {
                showValidationError(el, 'Ошибка при изменения номера!');
            }
        });
    }

    this.checkUniquePhoneForChange = function (phone) {

        var result = true;
        phone = phone.replace(/\+/, '').replace(/-/g, '');
        Ajax.Get(
            '/ajax/checkUniquePhone',
            {
                phone: phone
            },
            function (data) {
                if (data.result == false) {
                    result = false;
                }
            }
        );

        return result;
    };

    this.isChangeinfo = function () {
        return self.changed_flag;
    };

    this.getCurrentInfo = function () {
        options = {
            account_id: self.current_account_id
        };
        Ajax.Get('/ajax/getAccountInfo', options, function (data) {
            if (data.status == 0) {
                self.current_nick = (data.result.nick) ? data.result.nick : '';
                self.current_first_name = (data.result.first_name) ? data.result.first_name : '';
                self.current_last_name = (data.result.last_name) ? data.result.last_name : '';
                self.current_middle_name = (data.result.middle_name) ? data.result.middle_name : '';
                self.current_gender = (data.result.sex_id) ? data.result.sex_id : '';
                self.current_birthday = (data.result.birthday) ? data.result.birthday : '';
                self.current_city = (data.result.city_id) ? data.result.city_id : '';
                self.current_email = (data.result.email) ? data.result.email : '';

                self.checkPhones();
            };
        });
    };

    this.checkPhones = function () {
        $('.elreary_exist_phone').each(function () {

            if ($(this).data('phone') != $(this).val().replace(/\+/, '').replace(/-/g, '')) {
                self.show_popup = true;
            } else {
                self.show_popup = false;
            };

        });
    };

    this.updateDropDownList = function () {

        var text = $('.city-search-input').val();

        if (text.length > 1) {
            Ajax.Get('/ajax/getCities', {query:text, aboute_page:1}, function (data) {
                if (data.status == 0) {
                    $('.search-city-block .drop-menu').html(data.result);
                    $('.search-city-block .drop-menu').slideDown();
                }

                if (data.status == 2) {
                    $('.search-city-block .drop-menu').slideUp();
                    $('.search-city-block .drop-menu').html('');
                }
            });
        } else {
            $('.search-city-block .drop-menu').slideUp();
            $('.search-city-block .drop-menu').html('');
        }
    };
}
