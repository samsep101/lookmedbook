var SetDayVisitTimeByDaysController = function(){
    var self = this;

    this.validator_object = $('<span></span>');

    this.info = {
        is_active : 0,
        start_time : null,
        end_time : null,
        break : {
            start_time : null,
            end_time : null
        },
        visit_type_id : 1,
        days : {
            monday : true,
            tuesday : true,
            wednesday : true,
            thursday : true,
            friday : true,
            saturday : false,
            sunday : false
        }
    };

    this.even_numbers = null;
    this.odd_numbers = null;

    this.container = null;
    this.container_object = null;

    this.getData = function(){
        self.readData();
        return self.info;
    };

    this.readData = function(){
        self.info.start_time = self.container_object.find('input[name="start_time"]').val();
        self.info.end_time = self.container_object.find('input[name="end_time"]').val();

        self.info.break = {
            start_time : null,
            end_time : null
        };

        self.info.days = {};

        self.info.break.start_time = self.container_object.find('input[name="break.start_time"]').val();
        self.info.break.end_time = self.container_object.find('input[name="break.end_time"]').val();

        self.info.visit_type_id = self.container_object.find('.radio-week-control .first-label .radioBox').hasClass('act') ? 1 : 2;

        if (self.info.break.start_time == '__:__')
            self.info.break.start_time = '';

        if (self.info.break.end_time == '__:__')
            self.info.break.end_time = '';

        self.container_object.find('input[name="is_selected"]').each(function(){
            var day_name = $(this).data('day-name');
            var value = $(this).parent().hasClass('act') ? 1 : 0;

            self.info.days[day_name] = value;
        });

    };

    this.init = function(){
        $('body').append(self.validator_object);
        self.container_object = $(self.container);

        self.container_object.find(".numbers-row-record > .chekBox").click(function (e) {
            if (!$(this).hasClass('act')) {
                $(this).addClass('act');
                $(this).find('input').val(1);
                self.container_object.addClass('active-numbers');
                $(this).parent().parent().parent().find('.lock-timing').remove();

                self.info.is_active = 1;
            }
            else {
                $(this).removeClass('act');
                $(this).find('input').val(0);
                self.container_object.removeClass('active-numbers');
                self.lockTiming(this);

                self.info.is_active = 0;
            }
            return false;
        });

        if (self.even_numbers || self.odd_numbers){
            $(this).addClass('act');
            $(this).find('input').val(1);
            self.container_object.addClass('active-numbers');
            $(this).parent().parent().parent().find('.lock-timing').remove();

            self.info.is_active = 1;
        } else {
            $(this).removeClass('act');
            $(this).find('input').val(0);
            self.container_object.removeClass('active-numbers');
            self.lockTiming(this);

            self.info.is_active = 0;
        }

        self.setValidation();
        self.initFields();
    };

    this.initFields = function(){
        self.container_object.find('input[name="start_time"]').val(self.info.start_time);
        self.container_object.find('input[name="end_time"]').val(self.info.end_time);
        if (self.info.break !== undefined)
        {
            self.container_object.find('input[name="break.start_time"]').val(self.info.break.start_time);
            self.container_object.find('input[name="break.end_time"]').val(self.info.break.end_time);
        }

        if (self.info.visit_type_id == 2)
        {
            //self.container_object.find('.radio-week-control .second-label span').click();

            self.container_object.find('.radio-week-control .first-label .radioBox').removeClass('act');
            self.container_object.find('.radio-week-control .first-label .radioBox input').val(0);

            self.container_object.find('.radio-week-control .second-label .radioBox').addClass('act');
            self.container_object.find('.radio-week-control .second-label .radioBox input').val(1);
        }

        for (var i in self.info.days)
        {
            var selector = '.days-checkboxes .' + i;
            if (self.info.days[i] == 1)
            {
                self.container_object.find(selector).addClass('act');
                self.container_object.find(selector + ' input').val(1);
            } else {
                self.container_object.find(selector).removeClass('act');
                self.container_object.find(selector + ' input').val(0);
            }
        }

        if (!self.info.is_active)
        {
            self.container_object.find('.numbers-row-record span').click();
        }
    };

    this.setValidation = function() {
        self.validator_object.validation({
            validate : [
                self.container_object.find('input[name="start_time"]').validate(validation_rules['required_time']),
                self.container_object.find('input[name="end_time"]').validate(validation_rules['required_time']),
                self.container_object.find('input[name="break.start_time"]').validate(validation_rules['time']),
                self.container_object.find('input[name="break.end_time"]').validate(validation_rules['time']),
            ],
            callback : function(){

            },
            error_callback : function() {

            }
        });
    };


    this.lockTiming = function (object) {
        lock_timing = $('<div class="lock-timing"></div>');

        lock_timing.css({
            position:'absolute',
            left:0,
            top:25,
            width:600,
            height:110
        });
        $(object).parent().parent().parent().append(lock_timing);
    };
};