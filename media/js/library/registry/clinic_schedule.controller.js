var ClinicScheduleController = function () {
    var self = this;

    this.nameday = null;

    this.week_calendar = null;

    this.blocked = 0;

    this.clinic_id = null;

    this.visit_minTime = 0;
    this.visit_maxTime = 24;

    this.possible_days_range = null;

    this.data = {
        schedule_type_id: 1,
        week:{

        }
    };

    this.blocked_flag = false;

    this.initCalendars = function() {
        self.week_calendar = new WeekScheduleController();
        self.week_calendar.container = '#first-week-calendar';
        self.week_calendar.options.slotMinutes = 60;
        self.week_calendar.options.minTime = self.visit_minTime;
        self.week_calendar.options.maxTime = self.visit_maxTime;

        if (self.blocked)
        {
            ElementsHelper.blockElement($('.view-schedule-block'));
        }

        self.week_calendar.data = self.data.week;
        self.week_calendar.init();

        if (self.blocked)
        {
            self.week_calendar.options.editable = false;
        }

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

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        self.initCalendars();

        $('input[name="save"]').click(function(){
            self.is_active = 0;
            self.sendData();
        });

        $('input[name="publish"]').click(function(){
            self.is_active = 1;
            self.sendData();
        });

        $('input[name="cancel"]').click(function(){
            location.reload();
        });

        jQuery("#tabs-schedule").tabs();

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
            set_day_range_controller.possible_days_range = self.possible_days_range;
            set_day_range_controller.callback = function(){
                self.setDateRangeField();
            };
            set_day_range_controller.init();
        });

        self.initFields();
        self.setDateRangeField();
    };

    this.sendData = function(){

        if (self.blocked_flag)
            return;

        self.blocked_flag = true;

        self.readData();

        var data = {};

        data.clinic_id = self.clinic_id;
        data.schedule_info = self.data;
        data.is_active = self.is_active;

        Ajax.Post('/registry/clinic/ajaxSaveSchedule', data, function(data){
            if (data.status == 0)
            {
                var message_popup = new PopupMessage();
                message_popup.close_callback = function(){
                    window.location = '/registry/clinic/time?clinic_id=' + data.result.clinic_id;
                };
                message_popup.show('Данные успешно сохранены!');
            }
        });
    };

    this.readData = function(){
        self.data.week = self.week_calendar.getData();

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


