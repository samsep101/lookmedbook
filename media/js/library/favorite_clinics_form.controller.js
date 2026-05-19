var FavoriteClinicsFormController = function (account_id) {
    var self = this;

    this.account_id = account_id;
    this.type_of_clinic = null;
    this.purpoise_of_visit = null;
    this.page = 1;

    this.init = function () {

        $('#type_of_clinic').change(function () {
            self.type_of_clinic = $(this).val();
            self.purpoise_of_visit = $('#purpose_of_visit').val();
            self.page = 1;
            self.sendRequest();
        });

        $('#purpose_of_visit').change(function () {
            self.purpoise_of_visit = $(this).val();
            self.type_of_clinic = $('#type_of_clinic').val();
            self.page = 1;
            self.sendRequest();
        });

        $('#view_more_my_clinics i').addClass('icon-loader');
        self.sendRequest();

        $(document).on('click', '#view_more_my_clinics', function () {
            setCounters('next-10', 'unknown', '', SessionInfo.email);
            $('#view_more_my_clinics i').addClass('icon-loader');
            self.sendRequest();
        });

        $(document).on('click', '.close', function () {
            var clinic_id = $(this).data('id');
            if (clinic_id) {
                self.removeFromFavorite(clinic_id);
            };
        });
    };

    this.sendRequest = function (reload_flag) {

        if (reload_flag == undefined)
            reload_flag = 0;

        var data = {
            page: self.page,
            account_id: self.account_id,
            type_of_clinic: self.type_of_clinic,
            purpoise_of_visit : self.purpoise_of_visit,
            reload: reload_flag
        };

        Ajax.Get('/account/ajaxGetMyClinics', data, function (data) {

            if (data.status == 0) {
                $('#view_more_my_clinics i').removeClass('icon-loader');

                if (self.page == 1 || reload_flag)
                    $('#my_clinics_container').html(data.result.html);
                else
                    $('#my_clinics_container').append(data.result.html);

                if (data.result.more_button == 0) {
                    $('#more_my_clinics').css('display', 'none');
                } else {
                    $('#more_my_clinics').css('display', 'block');
                    $('#view_more_my_clinics i').removeClass('icon-loader');
                }

                self.page++;
            } else if (data.status == 2){
                $('#my_clinics_container').html('По указанным критериям не найдено клиник');
            }
        });
    };

    this.reload = function () {
        self.sendRequest(1);
    };

    this.removeFromFavorite = function (clinic_id) {
        options = {
            clinic_id: clinic_id
        };

        Ajax.Post('/ajax/removeClinicFromFavorite', options, function (data) {
            if (data.status == 0) {
                self.reload();
            };
        });
    };
};