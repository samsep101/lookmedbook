var ClinicPageController = function (id, latitude, longitude,landing, already_registred_account, url_page) {

    this.clinic_id = id;
    this.page = 1;

    this.city_id = null;
    this.latitude = latitude;
    this.longitude = longitude;
    this.landing_page = landing;
    this.url_page = url_page;
    this.already_registred_account = already_registred_account;

    this.form_controller = null;

    this.map_controller = null;

    var self = this;

    this.block_title = null;
    this.doctor_icon_text = null;
    this.specialty_text = null;
    this.block_over_textbox = null;

    this.init = function () {

        if(self.specialty_text == null)
            self.specialty_text = 'врачи';

        if (self.block_title == null)
            self.block_title = 'Лучшие врачи Москвы';

        if (self.doctor_icon_text == null)
            self.doctor_icon_text = 'В нашей базе лучшие врачи Москвы';

        if (window.matchMedia('(max-width: 420px)').matches) {
            $('.map-block-btn').show();
            $('#map-block').hide();
        } else {
            // map is hidden by default on mobile
            self.initMap();
        }

        $(document).on('click', '.map-block-btn', function () {
            $('#map-block').show();
            $(this).hide();
            self.initMap();
            self.map_controller.map.container.fitToViewport();
        });

        $(document).on('click', '.click_btn_bookmark', function () {
            if (!SessionInfo.is_authed) {
                if (self.already_registred_account != 1) {
                    landing_login_page = new LandingRegistrationPageController(self.url_page,self.specialty_text);
                    landing_login_page.block_title = self.block_title;
                    landing_login_page.init();
                } else {
                    landing_registration_page_controller = new LandingRegistrationPageController(self.url_page,self.specialty_text);
                    landing_registration_page_controller.block_title = self.block_title;
                    landing_registration_page_controller.block_over_textbox = self.block_over_textbox;
                    landing_registration_page_controller.init();
                }
            } else {
                Ajax.Post('/clinic/ajaxAddToMyClinicList', {clinic_id:self.clinic_id}, function (data) {
                    var isItAddOrKick = data.result.my_clinic;

                    if (isItAddOrKick) {
                        $('.btn-bookmark.btn-bookmark-big').addClass("btn-bookmark-added");
                        $('.btn-bookmark.btn-bookmark-big').html('<i class="icon-add"></i> <span class="txt txt-added">В закладках</span>');
                    }
                    else {
                        $('.btn-bookmark.btn-bookmark-big').removeClass("btn-bookmark-added");
                        $('.btn-bookmark.btn-bookmark-big').html('<i class="icon-add"></i> <span class="txt">Добавить в закладки</span>');
                    }

                });
            }
        });




        $(document).on('click', '#more_reviews', function () {
            $('#more_reviews i').addClass('icon-loader');
            self.page++;
            self.getReviews();
        });

        this.getReviews = function () {
            Ajax.Get('/clinic/ajaxGetReviewsList', {clinic_id:self.clinic_id, page:self.page}, function (data) {

                if (data.status == 0) {
                    $('#review-container').append(data.result.html);
                    $('#more_reviews i').removeClass('icon-loader');

                    if (data.result.count < 10) {
                        $('#view_more_reviews').css('display', 'none');
                    }
                }
            });
        };
        search_form_controller = new ClinicDoctorSearchFormController(self.clinic_id);
        search_form_controller.main_specialty = self.main_specialty;
        search_form_controller.init();

        self.form_controller = search_form_controller;

        $('.service-link').click(function () {
            //if($(this).parent().attr('class') == 'like_service_ul') {
                self.form_controller.setSpecialtyId($(this).data('specialty-id'));
            //}
        });

        if(self.main_specialty) {
            self.form_controller.setSpecialtyId(self.main_specialty);
        }

        $('.col-about .about-cont ul').addClass('list');

        $('.btn-find-doctor-2').click(function(){
            setCounters('find-doctor', 'find-doctor', '', SessionInfo.email);
        });

        var scrollTo = '';
        
        if(window.location.href.indexOf('scroll=specialization-area') >= 0) {
            scrollTo = '#clinic-description';
        } else if(window.location.href.indexOf('scroll=doctors-area') >= 0) {
            scrollTo = '#our-doctors';
        }

        if(scrollTo) {
            setTimeout(function() {
                $('html, body').animate({
                    scrollTop: $(scrollTo).offset().top
                }, 2000);
            }, 200);
        }

        $('.clinic-service-tree__toggle').change(function () {
            $('.js--clinic-service-tree').removeClass('active');
            $($(this).val()).addClass('active');
        });
    };

    this.initMap = function () {
        if (self.map_controller == null) {
            self.map_controller = new MapController();
            self.map_controller.setLatitude(self.latitude);
            self.map_controller.setLongitude(self.longitude);
            self.map_controller.init('map-block');
        }
    };
};
