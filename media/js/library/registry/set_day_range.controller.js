var SetDayRangeController = function(){
    var self = this;
    this.popup = null;

    this.possible_days_range = {
        min_date : null,
        max_date : null
    };

    this.date_from = null;
    this.date_to = null;

    this.callback = null;

    this.init = function(){

        if (SetDayRangeController.block_flag)
            return;

        SetDayRangeController.block_flag = true;


        Ajax.Get('/registry/ajax/getDayRangeSchedulePopup', null, function (data) {
            if (data.status == 0) {
                self.popup = new Popup();
                self.popup.show(data.result.html, '350px');

                self.popup.close_callback = function(){
                    SetDayRangeController.block_flag = false;
                };

                self.popup.getElement('#inputDate-1').DatePicker({
                    format:'d-m-Y',
                    date: self.popup.getElement('#inputDate-1').val(),
                    current: self.popup.getElement('#inputDate-1').val(),
                    starts:1,
                    position:'r',
                    onBeforeShow:function () {
                        self.popup.getElement('#inputDate').DatePickerSetDate(self.popup.getElement('#inputDate-1').val(), true);
                    },
                    onChange:function (formated, dates) {
                        self.popup.getElement('#inputDate-1').val(formated);
                        self.popup.getElement('#inputDate-1').DatePickerHide();
                    }
                });

                self.popup.getElement('#inputDate-2').DatePicker({
                    format:'d-m-Y',
                    date:self.popup.getElement('#inputDate-2').val(),
                    current:self.popup.getElement('#inputDate-2').val(),
                    starts:1,
                    position:'r',
                    onBeforeShow:function () {
                        self.popup.getElement('#inputDate').DatePickerSetDate($('#inputDate-2').val(), true);
                    },
                    onChange:function (formated, dates) {
                        self.popup.getElement('#inputDate-2').val(formated);
                        self.popup.getElement('#inputDate-2').DatePickerHide();
                    }
                });


                self.popup.getElement('.time-pick-confirm').validation({
                    validate : [
                        self.popup.getElement('#inputDate-1').validate(validation_rules['required_date']),
                        {
                            validator : function(){
                                var date1 = self.popup.getElement('#inputDate-1').val();
                                var date2 = self.popup.getElement('#inputDate-2').val();

                                if (date2 && DateHelper.compareDates(date1, date2) >= 0)
                                {
                                    return {
                                        status : false,
                                        'error_element' : self.popup.getElement('#inputDate-2'),
                                        message : 'Дата окончания не может быть меньше даты начала'
                                    }
                                } else {
                                    if (self.possible_days_range.min_date != null)
                                    {
                                        var range_flag = true;

                                        if (DateHelper.compareDates(date1, self.possible_days_range.min_date) < 0){
                                            range_flag = false;
                                        }

                                        if (self.possible_days_range.max_date){
                                            if (DateHelper.compareDates(date1, self.possible_days_range.max_date) > 0){
                                                range_flag = false;
                                            }

                                            if (DateHelper.compareDates(date2, self.possible_days_range.max_date) > 0){
                                                range_flag = false;
                                            }
                                        }

                                        if (!range_flag){

                                            var error_title = '';
                                            if (!self.possible_days_range.max_date)
                                                error_title = 'Дата начала периода не может быть меньше ' + self.possible_days_range.min_date;
                                            else
                                                error_title = 'Указаный период должен попадать в диапазон ' +
                                                    self.possible_days_range.min_date + ' - ' + self.possible_days_range.max_date;

                                            return {
                                                status : false,
                                                'error_element' : self.popup.getElement('#inputDate-2'),
                                                message : error_title
                                            }
                                        }
                                    }


                                    return {
                                        status : true
                                    }
                                }
                            }
                        }
                    ],
                    callback : self.sendData
                });

                self.popup.getElement('input[type="checkbox"]').click(function(){
                    if($(this).attr('checked'))
                    {
                        self.popup.getElement('#inputDate-2').attr('disabled', true);
                        self.popup.getElement('#inputDate-2').val('');
                    } else {
                        self.popup.getElement('#inputDate-2').attr('disabled', false);
                    }
                });

                self.initFields();
            }
        });

        self.sendData = function(){
            self.date_from = self.popup.getElement('#inputDate-1').val();
            self.date_to = self.popup.getElement('#inputDate-2').val();

            self.popup.close();
            if (self.callback)
                self.callback();
        }
    };

    this.initFields = function(){
        if (self.date_from)
            self.popup.getElement('#inputDate-1').val(self.date_from);

        if (self.date_to)
            self.popup.getElement('#inputDate-2').val(self.date_to);

        if (!self.date_to){
            self.popup.getElement('input[type="checkbox"]').click();
            self.popup.getElement('#inputDate-2').attr('disabled', true);
        }
    };
};

SetDayRangeController.block_flag = false;