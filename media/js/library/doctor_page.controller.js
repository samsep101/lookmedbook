var DoctorPageController = function (
    id,
    landing,
    already_registred_account,
    url_page,
    recording,
    my_doctor
) {
    var self = this;

    this.doctor_id = id;
    this.schedule_id = 0;
    this.name = '';
    this.phone = '';
    this.family_relation_status_id = 0;
    this.page = 1;
    this.landing_page = landing;
    this.url_page = url_page;
    this.already_registred_account = already_registred_account;
    this.recording = recording;

    this.block_title = null;
    this.doctor_icon_text = null;
    this.specialty_text = null;
    this.block_over_texbox = null;

    this.record_block = null;
    this.city_id = null;
    this.my_doctor = my_doctor;

    this.init = function () {

//        if ($.cookie('open-popup') == 1){
//            $.cookie('open-popup', 0);
//            self.openRecordPopup();
//        }
        var bookmarkButton = $('.doctor-landing .doctor_bookmark');
        var button_text = '<i class="icon-add"></i><span class="txt">Добавить в закладки</span>';
        if (self.my_doctor) {
            button_text = '<i class="icon-add" style="background-position: 0 100%;"></i><span class="txt txt-added" style="display: inline; line-height: 26px;">В закладках</span>';
        }
        bookmarkButton.html(button_text);
        bookmarkButton.click(function () {
            Ajax.Post('/doctor/ajaxAddToMyDoctorList', {doctor_id: self.doctor_id}, function (data) {
                if (data.status == 0) {
                    var isItAddOrKick = data.result.my_doctor;
                    if (isItAddOrKick) {
                        bookmarkButton.addClass("btn-bookmark-added");
                        bookmarkButton.html('<i class="icon-add"></i> <span class="txt txt-added">В закладках</span>');
                    } else {
                        bookmarkButton.removeClass('btn-bookmark-added');
                        bookmarkButton.html('<i class="icon-add"></i> <span class="txt">Добавить в закладки</span>');
                    }
                }
            });
        });

        if (self.block_title == null)
            self.block_title = 'Лучшие врачи Москвы';

        if (self.doctor_icon_text == null)
            self.doctor_icon_text = 'В нашей базе лучшие врачи Москвы';

        if (self.recording) {
            self.record_controller = new RecordToTheDoctorBlockController(self.doctor_id, $('.btn-appoint'));
            self.record_controller.init();
        }

        $('.record-day-pick').click(function () {
            self.record_controller = new RecordToTheDoctorBlockController(self.doctor_id, $(this));
            self.record_controller.action_for_counters = 'day';
            self.record_controller.init();
        });

        $('.location-box .tabs').each(function () {
            $(this).find('li').each(function (i) {
                $('.location-box .tabs li:first-child').addClass('active');
                $(this).click(function () {
                    var self = $(this),
                        id = self.data('id'),
                        price_areas = $('.cost-initial-reception .cost-visit-clinic'),
                        phone_areas = $('.clinic-top-phone');

                    $('.day').removeClass('active');

                    price_areas.hide().filter('[data-clinik-id=' + id + ']').show();
                    phone_areas.hide().filter('[data-clinic-id=' + id + ']').show();

                    self.addClass('active').siblings().removeClass('active')
                        .parents('.location-box').find('.section').eq(i).fadeIn(150).siblings('.section').hide();
                    $('.time-clinic-' + id).addClass('active');
                });
            });
        });

        $('.section.visible.flo').next('div.section.visible.flo').css('display', 'none');

        $(document).on('click', '.view-more', function () {
            setCounters('next-10', 'unknown', '', SessionInfo.email);
            $('.view-more i').addClass('icon-loader');
            self.page++;
            self.getReviews();
        });

        //$('.connected-carousels .next-navigation').removeClass('inactive');
    };

    this.openRecordPopup = function(){
        self.record_controller = new RecordToTheDoctorBlockController(self.doctor_id, $('.btn-appoint'));
        self.record_controller.init();
    };

    this.getReviews = function () {
        Ajax.Get('/doctor/ajaxGetReviewsList', {doctor_id: self.doctor_id, page: self.page}, function (data) {

            if (data.status == 0) {
                $('#review-container').append(data.result.html);
                $('.view-more i').removeClass('icon-loader');

                if (data.result.count < 10) {
                    $('#view_more_button').css('display', 'none');
                }
            }
        });
    };
};
