/**
 * Класс для работы с графиком по дням недели
 * Использует плагин FullCalendar (http://arshaw.com/fullcalendar/)
 * @constructor
 */
var WeekScheduleController = function(){

    var self = this;

    this.container = null;

    this.fullcalendar_instance = null;

    this.data = {};


    this.flag = false;

    this.events = {};

    this.options = {
        header : {
            left:'',
            center:'',
            right:''
        },
        firstDay : 1,
        axisFormat : 'HH:mm',
        timeFormat : 'HH:mm{ - HH:mm}',
        allDaySlot : false,
        dayNamesShort : ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
        defaultView : 'agendaWeek',
        weekMode : 'liquid',
        selectable : true,
        selectHelper : true,
        slotMinutes : 60,
        height : '600',
        minTime : '00:00',
        maxTime : '24:00',
        columnFormat : {
            month: 'ddd',    // Mon
            week: 'ddd', // Mon 9/7
            day: 'dddd M/d'  // Monday 9/7
        } ,
        select:function (start, end, allDay, event) {

        },
        editable : true,
        draggable : false,
        eventResize: function(event){

        },
        eventDrop: function(event){

        },
        eventClick: function(){
        },
        events:[

        ]
    };

    this.setDayData = function(day_name, data)
    {
        var today = new Date();
        var week_number =  today.getWeekNumber();
        var date = DateHelper.getDateByDayNameAndWeekNumberAndYear(day_name, week_number, 1900 + today.getYear());
        date = date.toDateString();

        var end_time = (data.end_time == '00:00') ? '23:59' : data.end_time;
        if (!self.events[day_name])
        {
            var options = {
                'title' : '',
                start: new Date(date + ' ' + data.start_time),
                end: new Date(date + ' ' + end_time),
                allDay : false,
                'info' : data
            };

            // отображаем изменения на самом календаре
            self.fullcalendar_instance.fullCalendar('renderEvent',
                options,
                true
            );
        } else {
            var event = self.events[day_name];
            event.start =  new Date(date + ' ' + data.start_time);
            event.end =  new Date(date + ' ' + end_time);
            event.info = data;

            self.fullcalendar_instance.fullCalendar('updateEvent', event);
        }

        self.data[day_name] = data;
    };

    this.getData = function(){
        return self.data;
    };

    this.initFields = function(){
        for (var day_name in self.data)
        {
            self.setDayData(day_name, self.data[day_name]);
        }
    };

    this.init = function(){

        if (!self.flag)
            $(self.container).fullCalendar('destroy');

        self.flag = true;

        self.options.select = function (start, end, allDay, event) {
            var set_day_controller = new SetDayVisitTimeController();
            set_day_controller.schedule_controller = self;
            set_day_controller.setTimeRange(start, end);
            set_day_controller.calendar = self.fullcalendar_instance;
            set_day_controller.init();
        };


        self.options.eventResize =  function(event){
            // обновление данных при ресайзе события
            var day_number = event.start.getDay();
            var day_name = DateHelper.getEngNameByDayNumber(day_number);
            self.data[day_name].start_time = DateHelper.getTimeByDate(event.start);
            self.data[day_name].end_time = DateHelper.getTimeByDate(event.end);
        };

        self.options.eventDrop = function(event){
            // обновить при перетаскивании
            var day_number = event.start.getDay();
            var day_name = DateHelper.getEngNameByDayNumber(day_number);
            self.data[day_name].start_time = DateHelper.getTimeByDate(event.start);
            self.data[day_name].end_time = DateHelper.getTimeByDate(event.end);
        };

        self.options.eventRender = function(event, element) {
            // При рендеринге события добавляем ему крестик, который удаляет данное событие
            var delete_button = $('<span class="event-del">x</span>');
            delete_button.click(function(){
                $(self.container).fullCalendar('removeEvents', event['_id']);
                var day_name = DateHelper.getEngNameByDayNumber(event.start.getDay());
                self.data[day_name] = null;
            });

            if (event.info !== undefined)
            {
                var title = '';
                if (event.info.break && event.info.break.start_time)
                {
                    title += '<u>перерыв:</u> <br/>'
                        + event.info.break.start_time + ' : ' + event.info.break.end_time + '<br /><br />';
                }


                if (!$('.calendar-clinic').length){
                    if (event.info.visit_type_id == 1) {
                        title += 'принимает в клинике';
                    } else {
                        title += 'принимает на дому';
                    }

                }
                if ($('.calendar-clinic').length)  {
                    $('.fc-event-title').hide();
                }

                element.find('.fc-event-title').append(title);
            }

            element.append(delete_button);

            // при клике на элемент открываем попап с изменением времени записи
            element.find('.fc-event-inner').dblclick(function(){
                var set_day_time_controller = new SetDayVisitTimeController();
                set_day_time_controller.event = event;
                set_day_time_controller.info = self.getDayInfoByEvent(event);
                set_day_time_controller.schedule_controller = self;
                set_day_time_controller.date = event.start.toDateString();
                set_day_time_controller.day_of_week = event.start.getDay();
                set_day_time_controller.init();
            });
        };
        self.options.eventAfterAllRender = function(){
            var events = $(self.container).fullCalendar('clientEvents');

            self.events = [];
            for (var i in events)
            {
                var event = events[i];
                var day_name = self.getDayNameByEvent(event);

                self.events[day_name] = event;
            }
        };

        self.fullcalendar_instance = $(self.container).fullCalendar(self.options);
        self.initFields();
    };

    this.getDayNameByEvent = function(event)
    {
        var day = event.start.getDay();
        var day_name = DateHelper.getEngNameByDayNumber(day);

        return day_name;
    };

    this.getDayInfoByEvent = function(event)
    {
        var day_name = self.getDayNameByEvent(event);
        return (self.data[day_name]) ? self.data[day_name] : [];
    }
};