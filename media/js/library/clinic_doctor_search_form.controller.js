var ClinicDoctorSearchFormController = function (clinic_id) {

    var self = this;

    this.clinic_id = clinic_id;
    this.specialty_id = null;
    this.purpose_of_visit_id = null;
    this.time_of_visit = null;
    this.page = 1;
    this.show_doctors_card_anyway = false;

    this.init = function () {

        $('#more_doctors i').addClass('icon-loader');
        if(!self.main_specialty) {
            this.sendRequest();
        }

        $(".chzn-select").trigger("liszt:updated");

        $('#doctor_search_form select[name="specialty_id"]').change(function () {
            self.specialty_id = $(this).val();
            self.page = 1;
            self.loadPurposeOfVisitBlock();
            self.sendRequest();
        });

        $('#doctor_search_form select[name="time_of_visit"]').change(function () {
            self.time_of_visit = $(this).val();
            self.page = 1;

            self.sendRequest();
        });

        $(document).on('change', '#doctor_search_form select[name="purpose_of_visit_id"]', function () {
            self.purpose_of_visit_id = $(this).val();
            self.page = 1;
            self.sendRequest();
        });


        $(document).on('click', '#more_doctors', function () {
            setCounters('next-10', 'unknown', '', SessionInfo.email)
            $('#more_doctors i').addClass('icon-loader');
            self.specialty_id = $('#doctor_search_form select[name="specialty_id"]').val();
            self.time_of_visit = $('#doctor_search_form select[name="time_of_visit"]').val();
            self.purpose_of_visit_id = $('#doctor_search_form select[name="purpose_of_visit_id"]').val();
            self.page++;
            self.sendRequest();
        });
    };

    this.loadPurposeOfVisitBlock = function () {
        var option = {
            specialty_id: self.specialty_id,
            clinic_id: self.clinic_id
        };

        Ajax.Get('/ajax/getPurposesOfVisitBySpecialtyIdAndClinicId', option, function (data) {
            if (data.status == 0) {
                $('#purpose_of_visit_block').html(data.result);
                $('#purpose_of_visit_block').css('width', '308px').children().css('width', '308px');

                $(".chzn-select").chosen();
                $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            }
        });
    };

    this.getExistsDoctorIds = function () {
        var doctor_cards = $('#doctor-container .info-card');
        var exclude_ids = [];
        doctor_cards.each(function(){
            exclude_ids.push($(this).attr('id').replace('doctor-big-card-', ''));
        });
        return exclude_ids;
    };

    this.sendRequest = function () {
        var data = {
            specialty_id: self.specialty_id,
            clinic_id: self.clinic_id,
            purpose_of_visit_id: self.purpose_of_visit_id,
            time_of_visit: self.time_of_visit,
            page: self.page
        };

        Ajax.Get('/clinic/ajaxGetDoctorsList', data, function (data) {

            if (data.status == 2) {
                if (!self.show_doctors_card_anyway){
                    $('#doctor-container').html('<div id="no_result">Врачи по указанным критериям поиска не найдены</div>');
                    $('#view_more_doctors').css('display', 'none');
                }
            } else if (data.status == 0) {
                var re = new RegExp(document.location.href+'"',"g");
                data.result.html = data.result.html.replace(re, 'javascript:void(0)"');

                if (self.page == 1) {
                    $('#doctor-container').html(data.result.html);
                    $('#more_doctors i').removeClass('icon-loader');
                } else {
                    $('#doctor-container').append(data.result.html);
                    $('#more_doctors i').removeClass('icon-loader');
                }

                //setCursorDefault();

                if (data.result.more_button == 0) {
                    $('#view_more_doctors').css('display', 'none');
                } else {
                    $('#view_more_doctors').css('display', 'block');
                }
                $('#no_result').css('display', 'none');

                if (window.is_test){
                    $('.doctor-big-card a').attr('href', 'javascript:void(0)');
                }
            }
            self.show_doctors_card_anyway = false;
        });
    };

    this.sendRequestByPaggin = function () {
        var data = {
            specialty_id: self.specialty_id,
            clinic_id: self.clinic_id,
            purpose_of_visit_id: self.purpose_of_visit_id,
            time_of_visit: self.time_of_visit,
            page: self.page,
            exclude_ids: self.getExistsDoctorIds()
        };

        Ajax.Get('/clinic/ajaxGetDoctorsList', data, function (data) {

            if (data.status == 2) {
                if (!self.show_doctors_card_anyway){
                    $('#no_result').html('Врачи по указанным критериям поиска не найдены');
                    $('#view_more_doctors').css('display', 'none');
                }

            } else if (data.status == 0) {
                $('#doctor-container').append(data.result.html);
                $('#more_doctors i').removeClass('icon-loader');
                $('#no_result').css('display', 'none');

                if (data.result.more_button == 0) {
                    $('#view_more_doctors').css('display', 'none');
                } else {
                    $('#view_more_doctors').css('display', 'block');
                    $('#more_doctors i').removeClass('icon-loader');
                }

                //self.attachCarousel();
            }
            self.show_doctors_card_anyway = false;
        });
    };

    this.setSpecialtyId = function (specialty_id) {
        $('#doctor_search_form').find('select[name="specialty_id"]').val(specialty_id).trigger("liszt:updated");
        self.specialty_id = specialty_id;
        self.show_doctors_card_anyway = true;
        self.sendRequest();
    };

}