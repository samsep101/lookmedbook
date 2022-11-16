var IndexPageController = function () {

    var self = this;

    this.login_form_controller = null;
    this.registration_form_controller = null;

    this.name = '';
    this.phone = '';

    this.init = function () {
        if(navigator.userAgent.indexOf('Mac')!=-1 && $.browser.safari) {
            $('body').addClass('macos_safari');
        }
        if($.browser.chrome && parseInt($.browser.version, 10) == 32) {
            $('.about_dis .h-txt').addClass('underline');
            $('.about_dis .l_txt').addClass('underline');
        }
        window.city_controller.subscribe(function(city_info){
            if (city_info.city_alias && (city_info.city_alias != 'moskva'))
                window.location = '//'+city_info.city_alias + '.'+SessionInfo.domain;
            else if(city_info.city_alias == 'moskva')
                window.location = '//'+SessionInfo.domain+'/';
        });

        $(document).on('click', ".fancybox-item", function() {
            $(".a-dashed").removeAttr('data-disabled');
        });

        if (self.send_call_request)
            return;

        self.send_call_request = true;
        $('.form-call input[type="text"]').val('');
        $('input[name="phone_number"]').inputmask('+7-999-999-99-99');


        $('input[name="phone_number"]').validate(validation_rules['call_to_user_phone_number']);
        //$('input[name="first_name"]').validate(validation_rules['required']);


        self.doctor_search_form_controller = new DoctorSearchFormController();
        self.doctor_search_form_controller.url_page = "/doctor";
        self.doctor_search_form_controller.setBlockMode();
        self.doctor_search_form_controller.init();

        self.clinic_search_form_controller = new ClinicSearchFormController();
        self.clinic_search_form_controller.url_page = "/clinic";
        self.clinic_search_form_controller.setBlockMode();
        self.clinic_search_form_controller.init();

        var options = $('#specialties_to_search_clinic option');
        
        options.removeAttr('selected');
        options.eq(0).attr('selected', 'selected');

        $('#registration-link, #registration-link-2').click(function(){
            var action_for_counters = $(this).data('action-for-counters');

            if (!self.registration_form_controller)
                self.registration_form_controller = new RegistrationFormController(action_for_counters);

            self.registration_form_controller.init();
        });

        $('#authorization-link button').click(function(){
            if (!self.login_form_controller)
                self.login_form_controller = new LoginFormController();

            self.login_form_controller.init();

        });

        $('.home-disease-link').click(function(){
            setCounters('diseases', 'home', '', SessionInfo.email);
        });

        $('.home-doctor-link').click(function(){
            setCounters('doctors', 'home', '', SessionInfo.email);
        });

        $('.home-clinic-link').click(function(){
            setCounters('clinics', 'home', '', SessionInfo.email);
        });

        $('.order-call').toggle(function () {
            $('.form-call-step-1').css('display', 'block');
        },function () {
            $('.form-call-step-1').css('display', 'none');}
        );

        $('.content,.btn-enter').click( function(event){
            if( $(event.target).closest(".form-call-step-1").length )
                return;
            if ($('.form-call-step-1:visible').length) {
              $(".form-call-step-1").fadeOut(500);
              $('.error_span').each(function() {
                $(this).remove();
              });
              event.stopPropagation();
            }
        });


        $('.btn-call').click(function() {
            if (self.send_call_request) {
                self.send_call_request = false;
                self.name = ($('input[name="first_name"]').val() != $('input[name="first_name"]').attr('placeholder')) ? $('input[name="first_name"]').val() : '';
                self.phone = ($('input[name="first_name"]').val() != $('input[name="phone_number"]').attr('placeholder')) ? $('input[name="phone_number"]').val() : '';
                Ajax.Post('/ajax/addCallToUser', {
                        name: self.name,
                        phone: self.phone//,
                        //specialty:$("#specialties_to_search_doctor").val();
                    },
                    function (data) {
                        if (data.status == 0) {
                            $('.form-call-step-1').css('display', 'none');
                            $('.form-call input[type="text"]').val('');
                            $('.form-call-step-2').css('display', 'block');
                            setTimeout(function () {
                                self.send_call_request = true;
                                $('.form-call-step-2').fadeOut(500)},
                            3000);
                        } else {
                            showErrorLabel(data.data, $('.btn-call'), 1);
                            self.send_call_request = true;
                        }
                    }
                );
            }
        });
        
        $('.btn-call-s').click(function() {
            if (self.send_call_request) {
                self.send_call_request = false;
                self.name = ($('input[name="first_name"]').val() != $('input[name="first_name"]').attr('placeholder')) ? $('input[name="first_name"]').val() : '';
                self.phone = ($('input[name="first_name"]').val() != $('input[name="phone_number"]').attr('placeholder')) ? $('input[name="phone_number"]').val() : '';
                Ajax.Post('/ajax/addCallToUser', {
                        name: self.name+' ('+$("#specialties_to_search_doctor option:selected").text()+')',
                        phone: self.phone//,
                        //specialty:$("#specialties_to_search_doctor").val();
                    },
                    function (data) {
                        if (data.status == 0) {
                            $('.form-call-step-1-s').css('display', 'none');
                            $('.form-call input[type="text"]').val('');
                            $('.form-call-step-2-s').css('display', 'block');
                            setTimeout(function () {
                                self.send_call_request = true;
                                $('.form-call-step-2-s').fadeOut(500)},
                            3000);
                        } else {
                            showErrorLabel(data.data, $('.btn-call-s'), 1);
                            self.send_call_request = true;
                        }
                    }
                );
            }
        });
    };

    this.showPopup = function (text) {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }
}