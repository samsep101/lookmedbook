var PersonalRoomMyClinicSearchFormController = function (account_id) {

    var self = this;

    this.account_id = account_id;
    this.type_of_clinic = null;
    this.purpoise_of_visit = null;
    this.page = 1;

    this.init = function () {

        $('#view_more_visited_clinics i').addClass('icon-loader');
        this.sendRequest();

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

        $(document).on('click', '#view_more_visited_clinics', function () {
            $('#view_more_visited_clinics i').addClass('icon-loader');
            self.sendRequest();
        });

        favorite_clinics_form_controller = new FavoriteClinicsFormController(self.account_id);
        favorite_clinics_form_controller.init();
    };

    this.sendRequest = function () {
        var options = {
            type_of_clinic: self.type_of_clinic,
            purpoise_of_visit : self.purpoise_of_visit,
            account_id: self.account_id,
            page: self.page

        };
        Ajax.Get('/account/ajaxGetClinicsListByPastVisit', options, function (data) {

                if (data.status == 0) {
                    if (self.page == 1)
                    {
                        $('#visited_clinic_container').html(data.result.html);
                        $('#view_more_visited_clinics i').removeClass('icon-loader');
                    } else {
                        $('#visited_clinic_container').append(data.result.html);
                        $('#view_more_visited_clinics i').removeClass('icon-loader');
                    }

                    if (data.result.more_button == 0) {
                        $('#more_visited_clinics').css('display', 'none');
                    } else {
                        $('#more_visited_clinics').css('display', 'block');
                        $('#view_more_visited_clinics i').removeClass('icon-loader');
                    }
                    self.page++;
                } else if (data.status == 2){
                    $('#visited_clinic_container').html('Тут будут клиники, которые вы посетили');
                }

            }

        );
    };
};