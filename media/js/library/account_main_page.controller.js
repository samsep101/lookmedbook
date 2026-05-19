var AccountMainPageController = function () {
    this.doctor_search_form_controller = null;
    this.clinic_search_form_controller = null;
    var controller = this;

    this.init = function () {
        var self = this;
        self.doctor_search_form_controller = new DoctorSearchFormController();
        self.doctor_search_form_controller.setBlockMode();
        self.doctor_search_form_controller.init();

        self.clinic_search_form_controller = new ClinicSearchFormController();
        self.clinic_search_form_controller.setBlockMode();
        self.clinic_search_form_controller.init();

        self.attachEvents();

        $('#find_doctor_tab').click(function(){
            $('.rating-block-doctors').show();
            $('.rating-block-clinics').hide();
        });

        $('#find_clinic_tab').click(function(){
            $('.rating-block-clinics').show();
            $('.rating-block-doctors').hide();
        });

        $(document).on('click', '.cancel-visit', function () {
            var visit_id = $(this).data('id');
            if (visit_id)
                self.cancelVisitToDoctor(visit_id, $(this));
        });

        $('.find-doctor-btn').click(function(){
            setCounters('find-doctor', 'main-my-doctors', '', SessionInfo.email);
        });

        $('.find-clinic-btn').click(function(){
            setCounters('find-clinic', 'main-my-clinics', '', SessionInfo.email);
        });
    };

    this.attachEvents = function () {
        $(".info-block .close").click(function () {
            var id = $(this).data('id');
            setCookie('block-' + id, "1", "Mon, 01-Jan-2040 00:00:00 GMT", "/");
            $(this).parent('.info-block').hide();
        });
    };

    this.cancelVisitToDoctor = function (visit_id, el) {
        Ajax.Post('/account/ajaxCancelVisitToDoctor', {
                visit_id: visit_id
            },
            function (data) {
                if (data.status == 0) {
                    controller.showPopup('Ваш визит будет отменен.');
                    if ($('.reg-info').length == 1)
                        el.parent().parent().parent().remove();
                    else
                        el.parent().remove();
                    //window.location = '/account';
                } else {
                    showError('Ошибка');
                }
            }
        );
    };

    this.showPopup = function(text)
    {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }
}