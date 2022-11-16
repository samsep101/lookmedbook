var FavoriteDoctorsFormController = function (account_id) {
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

            self.sendRequestMyDoctors();
        });

        $('#doctor_search_form select[name="specialty_id"]').change(function () {
            self.specialty_id = $(this).val();
            self.page = 1;

            self.sendRequestMyDoctors();
        });

        $('#doctor_search_form select[name="time_of_visit"]').change(function () {
            self.time_of_visit = $(this).val();
            self.page = 1;
            self.sendRequestMyDoctors();
        });

        $(document).on('change', '#doctor_search_form select[name="purpose_of_visit_id"]', function () {
            self.purpose_of_visit_id = $(this).val();
            self.page = 1;

            self.sendRequestMyDoctors();
        });

        /***** my Doctors *****/
        $('#more_my_doctors i').addClass('icon-loader');
        self.sendRequestMyDoctors();

        $(document).on('click', '#more_my_doctors', function () {
            setCounters('next-10', 'unknown', '', SessionInfo.email);
            $('#more_my_doctors i').addClass('icon-loader');
            self.page++;
            self.sendRequestMyDoctors();
        });

        $(document).on('click', '.close', function () {
            var doctor_id = $(this).data('id');
            if (doctor_id) {
                self.removeFromFavorite(doctor_id);
            };
        });
    };

    this.sendRequestMyDoctors = function (reload_flag) {

        if (reload_flag == undefined)
            reload_flag = 0;

        var data = {
            specialty_id: self.specialty_id,
            clinic_id: self.clinic_id,
            purpose_of_visit_id: self.purpose_of_visit_id,
            time_of_visit: self.time_of_visit,
            page: self.page,
            account_id: self.account_id,
            reload: reload_flag
        };

        Ajax.Get('/account/ajaxGetMyDoctors', data, function (data) {

            if (data.status == 2)
            {
                $('#my_doctors_container').html('<div id="no_result">Вы еще не добавили в закладки ни одного врача</div>');
                $('#bookmarks-block .view-more-block').css('display', 'none');
            }

            if (data.status == 0) {

                $('#more_my_doctors i').removeClass('icon-loader');

                if (self.page == 1 || reload_flag)
                    $('#my_doctors_container').html(data.result.html);
                else
                    $('#my_doctors_container').append(data.result.html);

                if (data.result.more_button == 0) {
                    $('#bookmarks-block .view-more-block').css('display', 'none');
                } else {
                    $('#bookmarks-block .view-more-block').css('display', 'block');
                }
            }
        });
    };

    this.reload = function () {
        self.sendRequestMyDoctors(1);
    };

    this.removeFromFavorite = function (doctor_id) {
        options = {
            doctor_id: doctor_id
        };

        Ajax.Post('/ajax/removeDoctorFromFavorite', options, function (data) {
            if (data.status == 0) {
                self.reload();
            };
        });
    };
};