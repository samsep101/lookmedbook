var DocotorsVisitsComingController = function () {

    this.day_counter = 0;
    this.doctor_id;
    this.schedule_id;
    this.visit_id;

    this.record_controller = null;

    var controller = this;

    this.init = function () {

        $('.btn-4').click(function(){
            //if (controller.record_controller == null){
                controller.doctor_id = $(this).data('doctor_id');
                controller.visit_id = $(this).data('visit_id');
                controller.record_controller = new RecordToTheDoctorBlockController(controller.doctor_id, $(this),controller.visit_id);
                controller.record_controller.init();
            //}
        });

        $( "#datepicker" ).datepicker({
            inline: true,
            showOtherMonths: true,
            firstDay: 1,
            dayNamesMin: [ "", "", "", "", "", "", "" ] ,
            monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ]
        });

        controller.showCalendarDates();

        $(document).on('click', 'a.ui-corner-all', function () {
            controller.showCalendarDates();
        });


        $(document).on('click', '.cancel_visit_button', function () {
            var visit_id = $(this).data('id');
            if (visit_id)
                controller.cancelVisitToDoctor(visit_id, $(this));
        });

        $(document).on('click', '#change_visit', function () {
            controller.doctor_id = $(this).data('doctor_id');
            controller.visit_id = $(this).data('visit_id');

            if (controller.doctor_id) {
                controller.getScheduleTimes();
                $('#change-time').css('display', 'block');
                $('.manage_but').attr('disabled', 'disabled');
                $('.manage_but').attr('disabled', 'disabled');
            }
        });

        $(document).on('click', '#cancel_change', function () {
            $('#change-time').css('display', 'none');
            $('.manage_but').removeAttr('disabled');
            $('.manage_but').removeAttr('disabled');
        });

        $(document).on('click', '#doctor-day-next', function () {
            controller.day_counter++;
            controller.getScheduleTimes();

        });

        $(document).on('click', '#doctor-day-previous', function () {
            if (controller.day_counter > 0) {
                controller.day_counter--;
                controller.getScheduleTimes();
            }
            else {
                showError('Расписание прошедших дней недоступно!');
            }
        });

        $(document).on('click', '.time', function () {
            controller.schedule_id = $(this).attr("id");
            controller.schedule_id = $(this).attr("id");
            if (controller.schedule_id && controller.visit_id)
                controller.updateVisitTime(controller.schedule_id, controller.visit_id);
        });
    }

    this.getScheduleTimes = function () {
        Ajax.Get('/doctor/ajaxGetScheduleTimesByDay', {day_counter: controller.day_counter, doctor_id: controller.doctor_id}, function (data) {
            if (data.result.doctor_times)
                $('#doctor-times').html(data.result.doctor_times);
            else
                $('#doctor-times').empty();
        });
    };

    this.updateVisitTime = function (schedule_id,visit_id) {
        Ajax.Post('/doctor/ajaxUpdateVisitTime', {
                schedule_id: schedule_id,
                visit_id : visit_id
            },
            function (data) {
                if (data.result) {
                    showOk('Вы записаны на прием!');
                    $('#change-time').css('display', 'none');
                    $('.manage_but').removeAttr('disabled');
                    $('.manage_but').removeAttr('disabled');
                    window.location = '/account/doctorsVisitsComing';
                }
                else {
                    showError('Ошибка');
                }
            }
        );
    };

    this.cancelVisitToDoctor = function (visit_id,el) {
        Ajax.Post('/account/ajaxCancelVisitToDoctor', {
                visit_id: visit_id
            },
            function (data) {
                if (data.status == 0) {
                    //showOk('Ваш визит будет отменен.');
                    controller.showPopup('Ваш визит будет отменен.');
                    el.parent().parent().parent().parent().remove();
                    if ($('.record-cart').length == 0){
                        $('.record-block').html('<div class="coming_visit">Записей нет</div>');
                    };
                    //window.location = '/account/doctorsVisitsComing';
                } else {
                    showError('Ошибка');
                }
            }
        );
    }

    this.showPopup = function(text)
    {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }

    this.showCalendarDates = function() {

        $('a.ui-state-highlight').removeClass('ui-state-active');
        Ajax.Get('/ajax/getMonthVisitDays', {}, function (data) {
            if (data.result.visit_records)
            {
                var visits = data.result.visit_records;
                $('.ui-datepicker-calendar td').each(function(){
                    for (var i=0;i<visits.length;i++) {
                        if (($(this).attr('data-year') == visits[i].year) && ($(this).attr('data-month') == visits[i].month) && ($(this).text() == visits[i].day)){
                            $(this).children('a').addClass('ui-state-active');
                        }
                    }
                });
            }
        });
        controller.addCalendarLocker();
    }

    this.addCalendarLocker = function()
    {
        lock_calendar = $('<div class="lock-calendar"></div>');

        lock_calendar.css({
            position: 'absolute',
            left: 0,
            top: 100,
            width: 275,
            height: 235
        });

        $('.ui-datepicker-inline').append(lock_calendar);
    }
}