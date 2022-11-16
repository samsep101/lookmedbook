var AddReviewBlockController = function (visit_id, visits_page_flag, unique_el_id) {

    var self = this;

    this.button = null;
    this.visit_id = visit_id;
    this.cabinet;
    this.waiting_time;
    this.relationship;
    this.value_for_money;
    this.diagnosis_is_clear;
    this.service_at_the_reception;
    this.is_doctor_advice;
    this.is_doctor_advice_click;
    this.is_clinic_advice;
    this.is_clinic_advice_click;
    this.doctor_review;
    this.clinic_review;
    this.private_review;
    this.doctor_id;
    this.clinic_id;
    this.author_name;

    this.visits_page_flag = visits_page_flag;
    this.unique_el_id = unique_el_id;

    this.init = function () {

        self.getPopup();

    };

    this.sendReview = function () {

        Ajax.Post('/review/ajaxAdd', {
                visit_id: self.visit_id,
                doctor_id: self.doctor_id,
                clinic_id: self.clinic_id,

                author_name: self.author_name,
                cabinet: self.cabinet,
                waiting_time: self.waiting_time,
                relationship: self.relationship,
                value_for_money: self.value_for_money,
                diagnosis_is_clear: self.diagnosis_is_clear,
                service_at_the_reception: self.service_at_the_reception,

                is_doctor_advice: self.is_doctor_advice,
                is_clinic_advice: self.is_clinic_advice,

                doctor_review: self.doctor_review,
                clinic_review: self.clinic_review,
                private_review: self.private_review
            },
            function (data) {
                if (data.status == 0) {
                    self.showPopup('Спасибо!<br> Ваш отзыв будет опубликован после проверки модератором!');
                    if (self.visit_id) {
                        if (self.visits_page_flag)
                            window.location = '/account/doctorsVisitsPast';
                        else
                            window.location = '/account/reviews';
                    } else {
                        $('.fancybox-close').click();
                    }
                }
            }
        );
    };

    this.checkNegativeReview = function () {
        if ($("#cabinet").html() == 1 && $("#waiting_time").html() == 1 && $("#relationship").html() == 1 && $("#value_for_money").html() == 1 && $("#diagnosis_is_clear").html() == 1 && $("#service_at_the_reception").html() == 1) {
            $('#complaint').css('display', 'block');
            $('.btn-4').css('display', 'block');
        }
    };

    this.readValues = function (popup) {
        switch (popup) {

            case 'complaint':
                self.cabinet = $("#cabinet").html() ? $("#cabinet").html() : '';
                self.waiting_time = $("#waiting_time").html() ? $("#waiting_time").html() : '';
                self.relationship = $("#relationship").html() ? $("#relationship").html() : '';
                self.value_for_money = $("#value_for_money").html() ? $("#value_for_money").html() : '';
                self.diagnosis_is_clear = $("#diagnosis_is_clear").html() ? $("#diagnosis_is_clear").html() : '';
                self.service_at_the_reception = $("#service_at_the_reception").html() ? $("#service_at_the_reception").html() : '';

                self.is_doctor_advice = '';
                self.is_clinic_advice = '';
                self.doctor_review = '';
                self.clinic_review = '';
                break;

            case 'popup1':
                self.author_name = $('#author_name').val();
                self.cabinet = $("#cabinet").html() ? $("#cabinet").html() : '';
                self.waiting_time = $("#waiting_time").html() ? $("#waiting_time").html() : '';
                self.relationship = $("#relationship").html() ? $("#relationship").html() : '';
                self.value_for_money = $("#value_for_money").html() ? $("#value_for_money").html() : '';
                self.diagnosis_is_clear = $("#diagnosis_is_clear").html() ? $("#diagnosis_is_clear").html() : '';
                self.service_at_the_reception = $("#service_at_the_reception").html() ? $("#service_at_the_reception").html() : '';
                // 4478 Упрощаем процесс создания отзыва
                $('#step1-submit').attr('href', self.doctor_id ? '#rev-popup3' : '#rev-popup4');
                break;

            case 'popup2':

                if (!self.is_doctor_advice_click || !self.is_clinic_advice_click) {
                    $('.error-msg-text').remove();
                    if (!self.is_doctor_advice_click) self.setError($('.not-advice-doctor'), 'Выберите что-нибудь!');
                    if (!self.is_clinic_advice_click) self.setError($('.not-advice-clinic'), 'Выберите что-нибудь!');
                } else {
                    self.is_doctor_advice = $(".yes-advice-doctor.finger-up").hasClass('selected') ? 1 : 0;
                    self.is_clinic_advice = $(".yes-advice-clinic.finger-up").hasClass('selected') ? 1 : 0;
                    $('#step2-link').click();
                }
                break;

            case 'popup2_skip':
                self.is_doctor_advice = '';
                self.is_clinic_advice = '';
                break;

            case 'popup3':
                self.doctor_review = $("#doctor_review").val();
                if (!self.doctor_review) {
                    $('.error-msg-text').remove();
                    self.setError($('#doctor_review'), 'Вы не написали отзыв!');
                    return;
                } else {
                    // 4478 Упрощаем процесс создания отзыва
                    // $('#step3-link').click();
                }
                break;

            case 'popup3_skip':
                self.doctor_review = '';
                break;

            case 'popup4':
                self.clinic_review = $("#clinic_review").val();
                if (!self.clinic_review) {
                    $('.error-msg-text').remove();
                    self.setError($('#clinic_review'), 'Вы не написали отзыв!');
                    return;
                } else {
                    // 4478 Упрощаем процесс создания отзыва
                    // $('#step4-link').click();
                }
                break;

            case 'popup4_skip':
                self.clinic_review = '';
                break;

            case 'popup5':
                self.private_review = $("#private_review").val();
                break;
        }
    };

    this.getPopup = function () {
        var data = {
            type: 'add_review',
            unique_el_id: self.unique_el_id
        };

        self.button = $('#visit-remind-block-' + self.unique_el_id + ' .review-link');

        Ajax.Get('/ajax/getPopup', data, function (data) {
            if (data.status == 0) {
                $('body').append(data.result);

                $('.review-link').fancybox({padding: 2});

                self.button.click();

                $('.yes-advice-doctor').click(function () {
                    //$('#advice-doctor').val(1);
                    self.is_doctor_advice_click = true;
                    //$(this).parent().css('display', 'none');
                });

                $('.not-advice-doctor').click(function () {
                    //$('#advice-doctor').val('');
                    self.is_doctor_advice_click = true;
                    //$(this).parent().css('display', 'none');
                });

                $('.yes-advice-clinic').click(function () {
                    //$('#advice-clinic').val(1);
                    self.is_clinic_advice_click = true;
                    //$(this).parent().css('display', 'none');
                });

                $('.not-advice-clinic').click(function () {
                    //$('#advice-clinic').val('');
                    self.is_clinic_advice_click = true;
                    //$(this).parent().css('display', 'none');
                });

                $('.rating').click(function () {
                    self.checkNegativeReview();
                });

                $('#step1-submit').click(function () {
                    self.readValues('popup1');
                });

                $('#complaint').click(function () {
                    self.readValues('complaint');
                });

                $('#step2-submit').click(function () {
                    self.readValues('popup2');
                });

                $('#step2-skip').click(function () {
                    self.readValues('popup2_skip');
                });

                $('#step3-submit').click(function () {
                    self.readValues('popup3');
                    // 4478 Упрощаем процесс создания отзыва
                    self.sendReview();
                    return false;
                });

                $('#step3-skip').click(function () {
                    self.readValues('popup3_skip');
                });

                $('#step4-submit').click(function () {
                    self.readValues('popup4');
                    // 4478 Упрощаем процесс создания отзыва
                    self.sendReview();
                    return false;
                });

                $('#step4-skip').click(function () {
                    self.readValues('popup4_skip');
                });

                $('#step5-submit').click(function () {
                    self.readValues('popup5');
                    self.sendReview();
                });
            }
        });
    };

    this.setError = function(el, message) {
        var error = $('<div class="error-msg-text">' + message + '</div>');
        var pos =  el.position();

        if (el.attr('id') == 'clinic_review' || el.attr('id') == 'doctor_review'){
            error.css({
                top: 100,
                'margin-right': 70,
                position: 'absolute',
                display: 'block'
            });
        } else {
            error.css({
                top: -110,
                'margin-left': 170,
                position: 'relative',
                display: 'block'
            });
        }

        error.css('display', 'block').delay(2000).fadeOut(1000);
        el.after(error);
    };

    this.showPopup = function(text)
    {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    };
};
