var SetDayVisitTimeController = function()
{
    var self = this;
    this.popup = null;
    this.schedule_controller = null;
    this.day_of_week = null;
    this.date = null;
    this.calendar = null;

    this.is_new_event = false;

    this.info = {
        start_time: null,
        end_time: null,
        visit_type_id: 1,
        break:{
            start_time: null,
            end_time: null
        }
    };

    this.setTimeRange = function(start, end)
    {
        self.date = start.toDateString();
        self.day_of_week = start.getDay();

        self.info.start_time = DateHelper.getTimeByDate(start);
        self.info.end_time = DateHelper.getTimeByDate(end);

        self.is_new_event = true;
    };

    this.init = function(){

        if (SetDayVisitTimeController.block_flag)
            return;

        var day_name = DateHelper.getEngNameByDayNumber(self.day_of_week);

        if (self.is_new_event && self.schedule_controller.data[day_name])
        {
            var popup_message = new PopupMessage();
            popup_message.show('График для данного дня уже создан');
            self.calendar.fullCalendar('rerenderEvents');
            return;
        }

        SetDayVisitTimeController.block_flag = true;

        Ajax.Get('/registry/ajax/getTimingEditPopup', null, function (data) {
            if (data.status == 0) {
                self.popup = new Popup();

                self.popup.close_callback = function(){
                    SetDayVisitTimeController.block_flag = false;
                };

                self.popup.show(data.result.html, '350px');

                self.popup.getElement('input[name="start_time"]').val(self.info.start_time);
                self.popup.getElement('input[name="end_time"]').val(self.info.end_time);
                self.popup.getElement('input[name="break.start_time"]').val(self.info.break.start_time);
                self.popup.getElement('input[name="break.end_time"]').val(self.info.break.end_time);

                if (self.info.visit_type_id == 1) {
                    self.popup.getElement('input[name="visit_type.clinic"]').parent().find('span').click();
                } else {
                    self.popup.getElement('input[name="visit_type.home"]').parent().find('span').click();
                }

                self.popup.getElement('input[name="close"]').click(function(){
                    self.popup.close();
                });

                self.setPopupValidation();

                var day_name = DateHelper.getRuNameByDayNumber(self.day_of_week);
                self.popup.getElement('.nameday').append(day_name);
                self.popup.getElement('.radio-menu').hide();
                if ($('.calendar-clinic').length) {
                    self.popup.getElement('.schedule-edit-popup h2').text('График работы клиники в');
                    self.popup.getElement('.radio-label-container').hide();
                }
            }
        });
    };

    this.setPopupValidation = function(){
        self.popup.getElement('input[name="save"]').validation({
            validate : [
                self.popup.getElement('input[name="start_time"]').validate(validation_rules['required_time']),
                self.popup.getElement('input[name="end_time"]').validate(validation_rules['required_time']),
                self.popup.getElement('input[name="break.start_time"]').validate(validation_rules['time']),
                self.popup.getElement('input[name="break.end_time"]').validate(validation_rules['time']),
                {
                    'validator' :  function() {
                        // Проверяем, чтобы время окончания приема врача следовало
                        // до времени начала приема
                        var start_time = self.popup.getElement('input[name="start_time"]').val();
                        var end_time = self.popup.getElement('input[name="end_time"]').val();

                        if ((end_time != '00:00') && (self.compareTime(start_time, end_time) == 1)){
                            return {
                                'status' : false,
                                'error_element' : self.popup.getElement('input[name="end_time"]'),
                                'message' : 'Время окончания должно быть больше времени начала'
                            };
                        } else {
                            return {
                                'status' : true
                            };
                        }
                    }
                },
                {
                    'validator' :  function() {
                        // Проверяем, чтобы время окончания перерыва врача следовало
                        // до времени начала приема
                        var start_time = self.popup.getElement('input[name="break.start_time"]').val();
                        var end_time = self.popup.getElement('input[name="break.end_time"]').val();

                        if (start_time == '__:__')
                            start_time = '';

                        if (end_time == '__:__')
                            end_time = '';

                        if ((start_time && !end_time) || (!start_time && end_time))
                        {
                            return {
                                'status' : false,
                                'error_element' : self.popup.getElement('input[name="break.end_time"]'),
                                'message' : 'Должны быть указаны оба значения'
                            };
                        }

                        if (start_time && end_time)
                        {
                            if (self.compareTime(start_time, end_time) == 1){
                                return {
                                    'status' : false,
                                    'error_element' : self.popup.getElement('input[name="break.end_time"]'),
                                    'message' : 'Время окончания должно быть больше времени начала'
                                };
                            } else {
                                return {
                                    'status' : true
                                };
                            }
                        } else {
                            return {
                                'status' : true
                            }
                        }
                    }
                },
                {
                    'validator' :  function() {
                        // Проверяем, чтобы время окончания приема врача следовало
                        // до времени начала приема
                        var start_time = self.popup.getElement('input[name="start_time"]').val();
                        var end_time = self.popup.getElement('input[name="end_time"]').val();

                        var break_start_time = self.popup.getElement('input[name="break.start_time"]').val();
                        var break_end_time = self.popup.getElement('input[name="break.end_time"]').val();

                        if (break_start_time == '__:__')
                            break_start_time = '';

                        if (break_end_time == '__:__')
                            break_end_time = '';

                        if (break_start_time && break_end_time
                            && ((self.compareTime(start_time, break_start_time) > 0) ||
                            (self.compareTime(end_time, break_end_time) < 0)
                            )
                            )
                        {
                            return {
                                'status' : false,
                                'error_element' : self.popup.getElement('input[name="break.end_time"]'),
                                'message' : 'Время перерыва не соответствует времени приема'
                            };

                        } else {
                            return {
                                'status' : true
                            }
                        }
                    }
                }
            ],
            callback: self.send
        });
    };

    this.send = function() {
        // считывает значения с попапа в self.info
        self.info.start_time = self.popup.getElement('input[name="start_time"]').val();
        self.info.end_time = self.popup.getElement('input[name="end_time"]').val();
        self.info.break.start_time = self.popup.getElement('input[name="break.start_time"]').val();
        self.info.break.end_time = self.popup.getElement('input[name="break.end_time"]').val();

        if (self.info.break.start_time == '__:__')
            self.info.break.start_time = '';

        if (self.info.break.end_time == '__:__')
            self.info.break.end_time = '';

        var visit_type_id = (self.popup.getElement('input[name="visit_type.clinic"]').val() == 1) ? 1 : 2;
        self.info.visit_type_id = visit_type_id;

        // передаем данные в ScheduleController
        var day_name = DateHelper.getEngNameByDayNumber(self.day_of_week);

        self.schedule_controller.setDayData(day_name, self.info);
        self.popup.close();
    };

    this.compareTime = function(start_time, end_time)
    {
        var start_hours = DateHelper.getHoursByTime(start_time);
        var end_hours = DateHelper.getHoursByTime(end_time);
        var start_minutes = DateHelper.getMinutesByTime(start_time);
        var end_minutes = DateHelper.getMinutesByTime(end_time);

        if ((end_hours < start_hours) ||
            ((end_hours == start_hours) && (end_minutes <= start_minutes)))
        {
            return 1;
        } else {
            return -1;
        }
    }
};


SetDayVisitTimeController.block_flag = false;
