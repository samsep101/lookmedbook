var RecordToTheDoctorBlockController = function (doctor_id, button, visit_id ) {
    var self = this;

    this.button = button;
    this.doctor_id = doctor_id;
    this.visit_id = visit_id;
    this.is_simple = ($('.content').hasClass('simple-popup-registration')) ? 1 : 0;
    this.container = null;
    this.type = null;

    this.selected_schedule_id = null;
    this.purpose_of_visit_id = null;

    this.family_relation_status_id = null;

    this.step = 1;

    this.full_name = null;
    this.phone = null;
    this.comment = null;

    this.day_schedule_id = button.attr('data');

    this.changeable_schedule_id = null;

    this.popup = null;

    this.blocked_flag = false;


    this.mode = 'visit';

    this.action_for_counters = null;

    this.init = function () {
        if (RecordToTheDoctorBlockController.block_show_popup_flag)
            return;

        if ((window.location.pathname == '/doctor') && (navigator.appName.indexOf('Opera') + 1)) {
            window.location = '/doctor/get?id=' + self.doctor_id;
            return;
        }

        RecordToTheDoctorBlockController.block_show_popup_flag = true;

        if (!self.action_for_counters){
            self.action_for_counters = 'unknown';
        }

        self.container = '#record-to-the-doctor-popup-' + self.doctor_id;
        self.type = 'record_to_the_doctor';
        if (self.is_simple === 1)
        	self.type += '_simple';
        
        
        var data = {
            visit_id: self.visit_id,
            doctor_id:self.doctor_id,
            type:self.type,
            schedule_id: self.day_schedule_id
        };

        Ajax.Get('/ajax/getPopup', data, function (data) {
            if (data.status == 0) {
                var block_code = data.result.html;
                
                var popup_width = (self.is_simple == 1)?'440px':'639px';
                self.popup = new Popup();
                self.popup.show(block_code, popup_width);

                setCounters('booking-begin', self.action_for_counters, '', SessionInfo.email);

                self.popup.setCloseCallback(function(){
                   RecordToTheDoctorBlockController.block_show_popup_flag = false;
                });

                $('.fancybox-overlay').css('background-image', 'url("/media/images/fancybox_overlay_light.png")');
                $('.fancybox-overlay').css('z-index', 10000);

                if (self.day_schedule_id) {
                    $('.fancybox-inner').css('width',620);
                    $('.fancybox-wrap').css('width',620);
                }

                $('.scroll-pane').jScrollPane();

                $(document).ready(function(){
                    for (l in data.result.schedule) {

                        var schedule = data.result.schedule[l].specialties;
                        for (j in schedule) {
                            self.fillTimeBlock(schedule[j], l, j);
                        }
                    }

                    if (self.day_schedule_id && !self.selected_schedule_id) {
                        self.selectScheduleId($(self.container + ' select[name="schedule_time"]').val());
                    }

                    $(self.container + ' .location-box .tabs').each(function () {
                        $(self.container + ' .location-box .tabs li:first-child').addClass('active');
                        $(this).find('li').each(function (i) {
                            $(this).click(function () {
                                $('.day').removeClass('active');
                                var id = $(this).data('id');

                                $(this).addClass('active').siblings().removeClass('active')
                                    .parents('.location-box').find('.section').eq(i).fadeIn(150).siblings('.section').hide();
                                $(self.container + ' .time-clinic-' + id).addClass('active');
                                $(self.container + ' .js--clinic-info').addClass('hidden');
                                $(self.container + ' .js--clinic-info.js--clinic-info-' + id).removeClass('hidden');
                            });
                        });
                    });

                    if (visit_id)
                        self.initializeData();

                    if(window.doctor_form_controller)
                    {
                        self.purpose_of_visit_id = window.doctor_form_controller.purpose_of_visit_id;
                        $(self.container + ' select[name="purpose_of_visit_id"] option[value="'+window.doctor_form_controller.purpose_of_visit_id +'"]').attr('selected', 'selected');
                    }


                    $(self.container + ' .section.visible.flo').next('div.section.visible.flo').css('display', 'none');

                    if (self.action_for_counters != 'unknown')
                        self.action_for_counters = 'unknown';

                    $('.time-li').click(function () {
                        setCounters('booking-select-time', self.action_for_counters, '', SessionInfo.email);

                        if (!$(this).hasClass('clicked'))
                        {
                            $('.time-li').removeClass('clicked');
                            $(this).addClass('clicked');
                            self.selectScheduleId($(this).data('schedule_id'));
                        }
                    });

                    $(self.container + ' input.resume-btn').click(self.submitFirstStep);

                    $('.fancybox-close').click( function(event){
                        self.popup.close();
                    });

                    var validate_rules = [];
                    if (self.is_authed)
                    {
                        validate_rules = [
                            $(self.container + ' input[name="surname"]').validate(validation_rules['required']),
                            $(self.container + ' input[name="phone"]').validate(validation_rules['visit_phone'])
                            //$(self.container + ' select[name="purpose_of_visit_id"]').validate(validation_rules['required'])
                        ];
                    } else {
                        validate_rules = [
                            $(self.container + ' input[name="surname"]').validate(validation_rules['required']),
                            $(self.container + ' input[name="phone"]').validate(validation_rules['visit_phone']),
                            //$(self.container + ' input[name="email"]').validate(validation_rules['required_email'])
                            //$(self.container + ' select[name="purpose_of_visit_id"]').validate(validation_rules['visit_purpose'])
                        ];
                    }

                    $(self.container + ' input.send-button').validation({
                        validate : validate_rules,
                        callback : function(){
                        	self.sendData();
                        }
                    });

                    $(self.container + ' select[name="schedule_time"]').change(function () {
                        self.selectScheduleId($(this).val());
                    });

                    setChosenSelect();

                    self.attachCarousel();

                    $('input[name="phone"]').inputmask('+7-999-999-99-99');


                    $('.family-type-relative span').click(function(){
                        $('.relation .sel-box').show();
                        $(this).parent().parent().next().find('.chzn-container .chzn-drop').css('left','0').css('top','33px');
                    });

                    $('.family-type-main span').click(function(){
                        $('.relation .sel-box').hide();
                    });

                    self.verification();

                    $('textarea[name="comment"]').focusout(function(){
                        self.comment = $(self.container + ' textarea[name="comment"]').val();
                    });

                    if (window.grecaptcha) {
                        var _grep_id = Math.round(Math.random() * 1000000) + 'google_captcha';
                        $(self.container + ' .g-recaptcha-add_review').empty().attr('id', _grep_id);
                        window.grecaptcha.render(_grep_id, {
                            'sitekey': window.gRecaptchaSiteKey
                        });
                    }

                    /* placeholder safari */
                    if ($.browser.safari) {
                        $('textarea').each(function(){
                            var a=$(this).index();
                            var out = self.newLine(a);
                            $('textarea').eq(a).css('color','#999');
                            $('textarea').eq(a).val(out);
                        });

                        $('textarea').click(function(){
                            var a=$(this).index();
                            var b= self.newLine(a);
                            var c=$('textarea').eq(a).val();
                            if (b==c) { $(this).val(''); $('textarea').eq(a).css('color','#000'); }

                        });

                        $('textarea').blur(function(){
                            var a=$(this).index();
                            var b=self.newLine(a);
                            var c=$('textarea').eq(a).val();
                            if (c=='') { $(this).val(b); $('textarea').eq(a).css('color','#999'); }
                        });
                    }
                    /* placeholder safari end */
                });

            }
        });

        $('.btn-appoint').prop("disabled", false);

        $(document).on('mouseenter', ".booking .time-li", function() {
            var date = $(this).attr('data-date');

            var columns = $(".booking .day");

            var i = 0;
            columns.removeClass('selected');
            columns.children("div").removeClass('selected');
            for(i = 0; i < columns.length; i++) {
                if(columns.eq(i).attr('data-date') == date) {
                    columns.eq(i).addClass('selected');
                    columns.eq(i).children("div").addClass('selected');
                    break;
                }
            }
        });

        $(document).on('click', ".booking .time-li", function() {
            var date = $(this).attr('data-date');

            var columns = $(".booking .day");

            var i = 0;
            columns.removeClass('selected').removeClass('clicked');
            columns.children("div").removeClass('selected').removeClass('clicked');
            for(i = 0; i < columns.length; i++) {
                if(columns.eq(i).attr('data-date') == date) {
                    columns.eq(i).addClass('clicked');
                    columns.eq(i).children("div").addClass('clicked');
                    break;
                }
            }
        });

        $(document).on('mouseleave', ".booking .time", function() {
            var columns = $(".booking .day");
            columns.removeClass('selected');
            columns.children("div").removeClass('selected');
        });
    };

    /* placeholder safari */
    this.newLine = function newLine(kx) {
        var b=$('textarea').eq(kx).attr('placeholder');
        var c=b.length;
        var out='';
        var z=0;
        for (k=0;k<c;k++) {
            if (b.substr(k,1)=='\\') {
                out=out+b.substr(z,k-z)+"\n";
                k++;
                z=k;
            }
        }
        out=out+b.substr(z,k-z);
        return out;
    };

    this.verification = function(){
        $('input[name="surname"]').focus(function(){
            $(this).parent().removeClass('input-success');
            $(this).parent().addClass('input-error');
        });
        $('input[name="phone"]').focus(function(){
            $(this).parent().removeClass('input-success');
            $(this).parent().addClass('input-error');
        });
        $('input[name="email"]').focus(function(){
            $(this).parent().removeClass('input-success');
            $(this).parent().addClass('input-error');
        });
        $('input[name="password"]').focus(function(){
            $(this).parent().removeClass('input-success');
            $(this).parent().addClass('input-error');
        });
    };

    this.updateVisitPrice = function(){
        var option = $('select[name="purpose_of_visit_id"] option:selected');
        //var price = option.data('price');

        if (option[2]) {
            var price = option[2].dataset.price;
        }
        else if (option[1]) {
            var price = option[1].dataset.price;
        }
        else {
            var price = option[0].dataset.price;
        }

        if (price == '0'){
            $('.price').html('Бесплатно');
        } else if (price != 0 && price != ''){
            $('.price').html(' = ' + price + ' руб.');
        } else {
            $('.price').html('');
        }
    };

    this.submitFirstStep = function(){

        if (!self.selected_schedule_id){
            self.showError('Пожалуйста, выбери время');
            return;
        }

        setCounters('booking-next', self.action_for_counters, '', SessionInfo.email);
/*
        Ajax.Get('/ajax/getPurposeOfVisitListByScheduleId', {schedule_id: self.selected_schedule_id}, function (data) {
            if (data.result.html) {
                $('.record-purpose-list').html(data.result.html);
                setChosenSelect();
                $(self.container + ' select[name="purpose_of_visit_id"]').change(function(){
                    self.purpose_of_visit_id = $(this).val();
                    self.updateVisitPrice();
                });
            }
            if (navigator.appName.indexOf('Explorer') + 1 && parseInt($.browser.version, 10) <= 9){
                $('[placeholder]').placeholder();
            }

        });
*/
        self.step = 2;
        $('.steps').removeClass('current');
        $('.step-2').addClass('current');
        //$('.fancybox-inner').css('max-height',430);
        $('.fancybox-inner').css('overflow','visible');
        $('.fancybox-wrap').css('width',569);
        $('.fancybox-inner').css('width',569);
        $('.step-block-1').css('display', 'none');
        $('.step-block-2').css('display', 'block');
        $('.error-msg').css('display','none');
/*
        $(self.container + ' .god-mode').click(function(){
            self.purpose_of_visit_id = 283;
        });
        */
    };

    this.sendData = function(){
        if (self.blocked_flag)
            return;
/*
        if (!self.purpose_of_visit_id){
            self.showError('Пожалуйста, укажи цель визита');
            return;
        };
*/
        self.blocked_flag = true;

        self.full_name = $(self.container + ' input[name="surname"]').val();
        self.phone = $(self.container + ' input[name="phone"]').val();
        self.comment = $(self.container + ' textarea[name="comment"]').val();
        self.recaptchaResponse = $(self.container + ' [name="g-recaptcha-response"]').val();

        self.family_relation_status_id = $(self.container + ' select[name="family_relation_status"]').attr('value');

        var data = {
            schedule_id: self.selected_schedule_id,
            //purpose_of_visit_id: self.purpose_of_visit_id,
            family_relation_status_id : self.family_relation_status_id,
            full_name: self.full_name,
            phone: self.phone,
            doctor_id: self.doctor_id,
            visit_id: self.visit_id,
            comment: self.comment,
            'g-recaptcha-response': self.recaptchaResponse
        };

        Ajax.Post('/ajax/recordToTheVisit', data, function(data){
            if (data.status == 0)
            {
                self.popup.close();

                Ajax.Get('/ajax/getPopup', {type: 'success_record_to_the_doctor'}, function(success_data){
                    if (success_data.status == 0){
                        var html = success_data.result.html;

                        var success_popup = new Popup();
                        success_popup.show(html);

                        success_popup.setCloseCallback(function(){
                            self.blocked_flag = false;
                            window.location.reload();
                        });
                    }
                });

                if (self.mode == 'visit' && !SessionInfo.is_authed){
                    send('//ad.adriver.ru/cgi-bin/rle.cgi?sid=194132&sz=registr&bt=55&pz=0&rnd=![rnd]');
                }

                setCounters('booking-complete', self.action_for_counters, '', SessionInfo.email);
            }

            if (data.status == 74)
            {
                var error_popup = new PopupMessage();
                error_popup.show('Номер телефона используется на другом аккаунте.<br />' +
                                'Ты можешь: <br />' +
                                '1. Если это твой родственник, необходимо связать аккаунты в разделе Семья.<br />' +
                                '2. Обратиться в поддержку <a href="mailto:help@lookmedbook.com">help@lookmedbook.com</a>, описав полностью ситуацию и указав email аккаунта.');

                // Убираем поля ввода пароля
                $('.email-row, .password-row').remove();
                self.popup.getElement('input.send-button').data('validate', []);
                $(self.container + ' input.send-button').validation({
                    validate : [
                        $(self.container + ' input[name="surname"]').validate(validation_rules['full_name']),
                        $(self.container + ' input[name="phone"]').validate(validation_rules['visit_phone'])
                        //$(self.container + ' select[name="purpose_of_visit_id"]').validate(validation_rules['required'])
                    ]
                });

                self.mode = 'visit';
                SessionInfo.is_authed = true;
            }

            if (data.status == 111)
            {
                var error_popup = new PopupMessage();
                error_popup.show('Выбранная специальность не соответствует специальности врача в данной клинике');
                $('.fancybox-wrap').css('width',569);
                $('.fancybox-inner').css('width',569);
            }

            if (data.status == 555)
            {
                var confirm_controller = new ConfirmPhoneController();
                confirm_controller.phone_id = data.data.phone_id;
                confirm_controller.setSuccessCallback(self.sendData);
                confirm_controller.tmp_init();
            }

            self.blocked_flag = false;
        });
    };

    this.showError = function(str){
        $('.error-msg-1').html(str);
        $('.error-msg-1').css('display', 'block');
        $('.error-msg-1').delay(2000).fadeOut(500);
    };

    this.selectScheduleId = function (schedule_id) {
        var data = {
            schedule_id:schedule_id,
            doctor_id:self.doctor_id,
            selected_schedule_id: self.selected_schedule_id
        };
        var schedule_id = schedule_id;
        if (visit_id && !self.changeable_schedule_id) self.changeable_schedule_id = self.selected_schedule_id;

        $('.time-li').removeClass('selected');
        $('.schedule-'+schedule_id).addClass('selected');
        self.selected_schedule_id = schedule_id;
    };

    self.formTimeBlock = function (time_list) {
        var list = $('<div />');

        // $(self.container + ' .time-scroll ul').html(list.html());
        $(self.container + ' .time-scroll ul').html();
    };

    self.fillTimeBlock = function (schedule, clinic_counter, specialty_counter) {
        //$('ul.time li.unactive').css('display', 'none');

        for (i in schedule.schedule) {
            dt_start = self.parseDateTime(schedule.schedule[i].dt_start);
            dt_end = self.parseDateTime(schedule.schedule[i].dt_end);
            el = $('ul.day-' + dt_start.day + ' li.time-' + dt_start['class']);

            if (dt_end.time == '23:59') dt_end.time = '24:00';

            var li = $('<li></li>');
            li.addClass('time-' + dt_start.time);
            li.addClass('time-li');
            //li.addClass('time');
            li.data('schedule_id');
            li.html(dt_start.time);
            li.append('<br /> - <br />')
            li.append(dt_end.time);
            li.data('schedule_id', schedule.schedule[i].schedule_id);
            li.addClass('schedule-' + schedule.schedule[i].schedule_id);
            li.addClass('active');
            li.removeClass('unactive');
            li.attr('data-date', dt_start.day);
            //li.attr('data-date', )

            $('ul.specialty-' + specialty_counter +'.clinic-' + clinic_counter + '.day-' + dt_start.day + ' li.unactive').before(li);
        }

    }

    self.parseDateTime = function (dt) {
        dt = dt.replace('-', '/');
        dt = dt.replace('-', '/');
        dt_obj = new Date(dt);

        month = dt_obj.getMonth() + 1;
        if (month < 10)
            month = '0' + month;
        day = dt_obj.getDate();
        if (day < 10)
            day = '0' + day;

        if(dt_obj.getYear() < 1000)
        {
            year = 1900 + dt_obj.getYear();
        } else {
            year =  dt_obj.getYear();
        }

        day = year + '-' + month + '-' + day;

        hours = dt_obj.getHours();
        if (hours < 10)
            hours = '0' + hours;
        minutes = dt_obj.getMinutes();
        if (minutes < 10)
            minutes = '0' + minutes;

        return {
            day:day,
            time:hours + ':' + minutes,
            'class': hours + '-' + minutes
        };
    };

    this.attachCarousel = function () {
        $(function () {
          $(".schedule-extended .shedule-var").each(function(e){
            $(this).parent('.schedule-extended').addClass('schedule-extended-'+e);
            $(this).nextAll('a').addClass('nav-'+e);
            $('.step-block-1 .location-box .tabs li').on('click', function(){
              $('.schedule-extended-' + e + ' .shedule-var').carouFredSel({
                synchronise:['.schedule-extended-' + e + ' + .time-scroll .jspPane', false, true],
                auto: false,
                prev: '.step-block-1 .prev-nav.nav-'+ e,
                next: '.step-block-1 .next-nav.nav-'+ e,
                scroll:{items:7},
                circular: false,
                infinite:false
              });

              $('.schedule-extended-' + e + ' + .time-scroll .jspPane').carouFredSel({
                auto:false,
                scroll:{items:7},
                circular:false,
                infinite:false,
                height:118
              });
            });
            $('.step-block-1 .location-box .tabs li:first-child').trigger('click');
          });
        });
    };

    this.initializeData = function() {
        Ajax.Get('/ajax/SetScheduleId', {visit_id: self.visit_id}, function (data) {
            if (data.result) {

                self.purpose_of_visit_id = data.result.purpose_of_visit_id;
                self.selected_schedule_id = data.result.schedule_id;
                self.full_name = data.result.full_name;
                self.phone = data.result.phone;

                var active_purpose = data.result.purpose_of_visit;
                $('.visit-target ul.chzn-results li').each(function(){
                    if ($(this).text() == active_purpose) {
                        $(this).addClass('result-selected');
                        $('.visit-target .chzn-single span').html(active_purpose);
                    }
                });

                var time_container = '.jspPane .day-'+data.result.day+' .time-'+data.result.time;
                $(time_container).removeClass('unactive');
                $(time_container).addClass('schedule-'+data.result.schedule_id);
                $(time_container).addClass('clicked');
                $(time_container).addClass('selected');
                $(time_container).html(data.result.dt_start);

                $('.step-block-2 .txt input[name=surname]').val(data.result.full_name);
                $('.step-block-2 .txt input[name=phone]').val(data.result.phone);

            }

        });
    };
};

RecordToTheDoctorBlockController.block_show_popup_flag = false;