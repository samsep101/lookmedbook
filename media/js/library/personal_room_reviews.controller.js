var PersonalRoomReviewsController = function (account_id) {

    var self = this;

    self.doctor_page = 1;
    self.clinic_page = 1;
    self.account_id = account_id;
    self.note_type = '';

    this.init = function () {

        $.fn.raty.defaults.path = '/media/images';

        $('.rev-rating').raty({
            starOn: 'star-on-big.png',
            starOff: 'star-off-big.png',
            width: 210,
            cancel: true
        });

        // reviews
        $('.switch').each(function () {
            $(this).find('li').each(function (i) {
                $(this).click(function () {
                    $(this).addClass('active').siblings().removeClass('active')
                        .parents('.rev-block').find('.section').eq(i).fadeIn(150).siblings('.section').hide();
                });
            });
        });

        self.getDoctorsReviews();
        self.getClinicsReviews();

        $(document).on('click', '#more_doctor_reviews .view-more', function () {
            setCounters('next-10', 'unknown', '', SessionInfo.email);
            $('#more_doctor_reviews .view-more i').addClass('icon-loader');
            self.getDoctorsReviews();
        });

        $(document).on('click', '#more_clinic_reviews .view-more', function () {
            setCounters('next-10', 'unknown', '', SessionInfo.email);
            $('#more_clinic_reviews .view-more i').addClass('icon-loader');
            self.getClinicsReviews();
        });

        $('#close_review_note').click(function () {
            self.note_type = 'review_note';
            self.closeNote();
        });
    };

    this.getDoctorsReviews = function () {
        var options = {
            page: self.doctor_page,
            account_id: self.account_id
        };

        Ajax.Get('/account/ajaxGetDoctorsReviews', options, function (data) {

                if (data.status == 0) {

                    if (self.doctor_page == 1)
                    {
                        $('#last_doctors_reviews_container').html(data.result.html);
                        $('#more_doctor_reviews .view-more i').removeClass('icon-loader');
                    } else {
                        $('#last_doctors_reviews_container').append(data.result.html);
                        $('#more_doctor_reviews .view-more i').removeClass('icon-loader');
                    }

                    if (data.result.more_button == 0) {
                        $('#more_doctor_reviews').css('display', 'none');
                    } else {
                        $('#more_doctor_reviews').css('display', 'block');
                        $('#more_doctor_reviews .view-more i').removeClass('icon-loader');
                    }
                    self.doctor_page++;
                }
            }
        );
    };

    this.getClinicsReviews = function () {
        var options = {
            page: self.clinic_page,
            account_id: self.account_id
        };

        Ajax.Get('/account/ajaxGetClinicsReviews', options, function (data) {

                if (data.status == 0) {

                    if (self.clinic_page == 1)
                    {
                        $('#clinics_reviews_container').html(data.result.html);
                        $('#more_clinic_reviews .view-more i').removeClass('icon-loader');
                    } else {
                        $('#clinics_reviews_container').append(data.result.html);
                        $('#more_clinic_reviews .view-more i').removeClass('icon-loader');
                    }

                    if (data.result.more_button == 0) {
                        $('#more_clinic_reviews').css('display', 'none');
                    } else {
                        $('#more_clinic_reviews').css('display', 'block');
                        $('#more_clinic_reviews .view-more i').removeClass('icon-loader');
                    }
                    self.clinic_page++;
                }
            }
        );
    }

    this.closeNote = function () {
        Ajax.Get('/ajax/closeNote', {note_type: self.note_type}, function (data) {
            if (data.status == 0) {
                $('#close_review_note').parent().parent().fadeOut();
            }
        });
    };
}