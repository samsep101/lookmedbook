var PersonalRoomMyDoctorSearchFormController = function (account_id) {

    var self = this;

    this.account_id = account_id;
    this.clinic_id = null;
    this.specialty_id = null;
    this.purpose_of_visit_id = null;
    this.time_of_visit = null;
    this.page = 1;

    this.init = function () {
        /***** Search Form *****/
        $('#doctor_search_form select[name="clinic_id"]').change(function () {
            self.clinic_id = $(this).val();
            self.page = 1;

            self.sendRequest();
        });

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

        /*****   Past Visited Doctors *****/
        $('#more_visited_doctors i').addClass('icon-loader');
        this.sendRequest();

        $(document).on('click', '#more_visited_doctors', function () {
            $('#more_visited_doctors i').addClass('icon-loader');
            self.sendRequest();
        });

        favorite_doctors = new FavoriteDoctorsFormController(self.account_id);
        favorite_doctors.init();

    };

    this.loadPurposeOfVisitBlock = function (value) {
        Ajax.Get('/ajax/getPurposesOfVisitBySpecialtyId', {specialty_id: self.specialty_id}, function (data) {
            if (data.status == 0) {
                $('#purpose_of_visit_block').html(data.result);

                $('#purpose_of_visit_block select[name="purpose_of_visit_id"]').css('width', '407px');

                $(".chzn-select").chosen();
                $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            }
        });
    };

    this.sendRequest = function () {
        var data = {
            specialty_id: self.specialty_id,
            clinic_id: self.clinic_id,
            purpose_of_visit_id: self.purpose_of_visit_id,
            time_of_visit: self.time_of_visit,
            page: self.page
        };

        Ajax.Get('/account/ajaxGetDoctorsListByPastVisit', data, function (data) {
            if (data.status == 2) {
                $('#visited_doctors_container').html('<div class="no_result">Тут будут врачи, которых вы посетили</div>');
                $('#view_more_doctors').css('display', 'none');
            }

            if (data.status == 0) {
                if (self.page == 1)
                {
                    $('#visited_doctors_container').html(data.result.html);
                    $('#more_visited_doctors i').removeClass('icon-loader');
                } else {
                    $('#visited_doctors_container').append(data.result.html);
                }

                if (data.result.more_button == 0) {
                    $('#visited-doctors-block .view-more-block').css('display', 'none');
                } else {
                    $('#visited-doctors-block .view-more-block').css('display', 'block');
                    $('#more_visited_doctors i').removeClass('icon-loader');
                }
                self.page++;
            }
        });
    };
}