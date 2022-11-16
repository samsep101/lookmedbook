/**
 * Контроллер формы управления расписанием доктора
 */
var DoctorScheduleController = function () {
    var self = this;

    this.nameday = null;

    this.first_week_calendar = null;
    this.second_week_calendar = null;

    this.schedule_id = null;

    this.blocked = 0;

    this.doctor_id = null;
    this.specialty_id = null;
    this.clinic_id = null;

    this.even_numbers_controller = null;
    this.odd_numbers_controller = null;

    this.visit_minTime = 0;
    this.visit_maxTime = 24;

    this.possible_days_range = null;

    this.even_numbers = null;
    this.odd_numbers = null;

    this.data = {
        date_from:null,
        date_to:null,
        visit_slot_time: 60,
        schedule_type_id: 1,
        first_week:{

        },
        second_week:{

        },
        even_numbers:{

        },
        odd_numbers:{

        }
    };

    this.blocked_flag = false;

    this.initCalendars = function() {
        self.first_week_calendar = new WeekScheduleController();
        self.first_week_calendar.container = '#first-week-calendar';
        self.first_week_calendar.options.slotMinutes = 60;
        self.first_week_calendar.options.minTime = self.visit_minTime;
        self.first_week_calendar.options.maxTime = self.visit_maxTime;

        if (self.blocked)
        {
            //ElementsHelper.blockElement($('.view-schedule-block'));

            $('#date-range').attr('disabled', 'disabled');
            $('.min-number').attr('disabled', 'disabled');

            lock_timing = $('<div class="div-lock"></div>');

            lock_timing.css({
                position:'absolute',
                left:270,
                top:483,
                width:600,
                height:416,
                'z-index': 9999999
            });
            $('#tabs-schedule-2').parent().append(lock_timing);
        }

        self.first_week_calendar.data = self.data.first_week;
        self.first_week_calendar.init();

        self.second_week_calendar = new WeekScheduleController();
        self.second_week_calendar.container = '#second-week-calendar';
        self.second_week_calendar.options.slotMinutes = 60;
        self.second_week_calendar.options.minTime = self.visit_minTime;

        if (self.blocked)
        {
            self.first_week_calendar.options.editable = false;
        }

        self.second_week_calendar.options.maxTime = self.visit_maxTime;
        self.second_week_calendar.data = self.data.second_week;
        self.second_week_calendar.init();
    };

    this.setDateRangeField = function() {

        var text = '';

        if (self.data.date_from)
        {
            text += 'c ' + self.data.date_from
            if (self.data.date_to)
                text +=  ' по ' + self.data.date_to;
        }

        $('#date-range').val(text);
    };

    this.initFields = function(){
        if ((self.data.schedule_type_id == 2) || (self.data.schedule_type_id == 3))
        {
            $('input.add-week').click();

            if(self.data.schedule_type_id == 3)
            {
                $('.radio-week-control .second-label span').click();
            }
        }

        if (self.data.schedule_type_id == 4)
        {
            $('ul.schedule-type li[data-name="numbers"] a').click();
        }
    };

    this.init = function () {

        $('<span></span>').validation({
            validate : [
                $('input[name="visit_slot_time"]').validate(validation_rules['digits'])
            ],
            callback: function(){

            }
        });

        $('input[name="visit_slot_time"]').val(self.data.visit_slot_time);

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        self.initCalendars();

        self.even_numbers_controller = new SetDayVisitTimeByDaysController();
        self.even_numbers_controller.container = '#even-numbers-container';
        self.even_numbers_controller.info = self.data.even_numbers;
        self.even_numbers_controller.even_numbers = self.even_numbers;
        self.even_numbers_controller.init();

        self.odd_numbers_controller = new SetDayVisitTimeByDaysController();
        self.odd_numbers_controller.container = '#odd-numbers-container';
        self.odd_numbers_controller.info= self.data.odd_numbers;
        self.odd_numbers_controller.odd_numbers = self.odd_numbers;
        self.odd_numbers_controller.init();

        $('input[name="save"]').click(function(){
            self.is_active = 0;
            self.sendData();
        });

        $('input[name="publish"]').click(function(){
            self.is_active = 1;
            self.sendData();
        });

        $('input[name="save"], input[name="publish"]').validation({
            validate : [
                {
                    validator : function(){
                        self.readData();

                        var status = true;

                        return {
                            status : status
                        }
                    }
                }
            ],
            callback: function(){

                self.sendData();
            }
        });


        jQuery("#tabs-schedule").tabs();

        $('.second-week').css('visibility', 'hidden').css('height', '0');

        $(document).on('click', '.radio-label-container .radioBox span', function () {
            $(this).parent().parent().parent().find(".act").removeClass("act");
            $(this).parent().addClass("act");
            $(this).parent().parent().parent().find('input[type=hidden]').val(0);
            $(this).parent().find('input[type=hidden]').val(1);
        });

        $(".schedule-view .schedule-table").click(function () {
            Ajax.Get('/registry/ajax/getTimingPopup', null, function (data) {
                if (data.status == 0) {
                    var popup = new Popup();
                    popup.show(data.result.html, '360px');
                }
            });
        });

        $(".schedule-edit .schedule-table").click(function () {
            Ajax.Get('/registry/ajax/getTimingEditPopup', null, function (data) {
                if (data.status == 0) {
                    var popup = new Popup();
                    popup.show(data.result.html, '400px');

                    $('.radio-edit .radioBox span').click(function () {
                        $('.schedule-edit-popup .numbers-field').css('display', 'none');
                        if ($(this).parent().hasClass('schedule-changing'))
                            $('#schedule-changing').css('display', 'block');
                        if ($(this).parent().hasClass('not-working'))
                            $('#not-working').css('display', 'block');
                    });
                }
            });
        });

        $(".day-range-schedule-pick").click(function () {
            var set_day_range_controller = new SetDayRangeController();
            set_day_range_controller.date_from = self.data.date_from;
            set_day_range_controller.date_to = self.data.date_to;
            set_day_range_controller.possible_days_range = self.possible_days_range;
            set_day_range_controller.callback = function(){
                self.data.date_from = set_day_range_controller.date_from;
                self.data.date_to = set_day_range_controller.date_to;
                self.setDateRangeField();
            };
            set_day_range_controller.init();
        });

        $(document).on('click', '.add-week', function () {
            $(this).css('display', 'none');
            $('.second-week .calendar-work .fc-button-next').click();
            $('.second-week').css('visibility', 'visible').css('height', 'auto');
        });

        $(document).on('click', '.remove-week', function () {
            $(this).parent().css('visibility', 'hidden').css('height', '0');
            $('.second-week').css('visibility', 'hidden').css('height', '0');
            $('.second-week .calendar-work .fc-button-prev').click();
            $('.add-week').css('display', 'block');
        });

        if (self.data.date_from == null)
            self.data.date_from = self.possible_days_range.min_date;

        self.initFields();
        self.setDateRangeField();
    };

    this.sendData = function(){

        if (self.blocked_flag)
            return;

        self.blocked_flag = true;

        self.readData();

        var data = {};

        data.specialty_id = self.specialty_id;
        data.clinic_id = self.clinic_id;
        data.doctor_id = self.doctor_id;
        data.schedule_id = self.schedule_id;
        data.schedule_info = self.data;
        data.is_active = self.is_active;

        Ajax.Post('/registry/doctor/ajaxSaveSchedule', data, function(data){
            if (data.status == 0)
            {
                var message_popup = new PopupMessage();
                message_popup.close_callback = function(){
                    window.location = '/registry/doctor/schedule_view?schedule_id=' + data.result.doctor_schedule_id;
                };
                message_popup.show('Данные успешно сохранены!');
            }
        });
    };

    this.readData = function(){
        self.data.first_week = self.first_week_calendar.getData();
        self.data.second_week = self.second_week_calendar.getData();
        self.data.even_numbers = self.even_numbers_controller.getData();
        self.data.odd_numbers = self.odd_numbers_controller.getData();

        self.data.visit_slot_time = $('input[name="visit_slot_time"]').val();

        if ($('ul.schedule-type li.ui-state-active').data('name') == 'days')
        {
            if (!$('.second-week').height())
            {
                self.data.schedule_type_id = 1;
            } else {
                if ($('.radio-week-control input').first().val() == 1)
                {
                    self.data.schedule_type_id = 2;
                } else {
                    self.data.schedule_type_id = 3;
                }
            }
        } else {
            self.data.schedule_type_id = 4;
        }
    };
};


