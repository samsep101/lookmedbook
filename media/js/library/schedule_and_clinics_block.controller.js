var ScheduleAndClinicsFormController = function (container) {

    var self = this;

    this.container = container;

    this.init = function () {

        $(self.container + ' .location-box .tabs').each(function () {
            $('.location-box .tabs li:first-child').addClass('active');
            $(this).find('li').each(function (i) {
                $(this).click(function () {
                    $('.day').removeClass('active');
                    var id = $(this).data('id');

                    $(this).addClass('active').siblings().removeClass('active')
                        .parents('.location-box').find('.section').eq(i).fadeIn(150).siblings('.section').hide();
                    $(self.container + ' .time-clinic-' + id).addClass('active');
                });
            });
        });
        $(self.container + ' .location-box .tabs_schedule').each(function () {
            $(this).find('li').each(function (i) {
                $(this).click(function () {
                    var id = $(this).data('id');

                    $(this).addClass('active').siblings().removeClass('active')
                        .parents('.location-box_schedule').find('.section-in').eq(i).fadeIn(150).siblings('.section-in').hide();
                });
            });
        });

        $(self.container + ' .section.visible.flo').next('div.section.visible.flo').css('display', 'none');
        $(self.container + ' .section-in.visible.flo').next('div.section-in.visible.flo').css('display', 'none');

        self.attachCarousel();
    };

    this.selectClinic = function (clinic_id) {
        $(self.container + ' .clinic-' + clinic_id + '-button').click();
    };

    this.attachCarousel = function () {
        $(function() {
          $(self.container + " .vis-block").tabs();
          $(self.container + ' [data-jcarousel]').each(function () {
            var el = $(this);
            el.jcarousel(el.data());
          });

          $(self.container + " .schedule-extended ul").each(function (e) {
            $(this).parent('.schedule-extended').addClass('schedule-extended-'+e);
            $(this).nextAll('a').addClass('nav-' + e);
            $(self.container + '.location-box .tabs li').on('click', function(){
              $(self.container + '.schedule-extended-' + e + ' ul').carouFredSel({
                auto: false,
                prev: self.container +  '.prev-nav.nav-'+ e,
                next: self.container +  '.next-nav.nav-'+ e,
                scroll:{items:1},
                circular: false,
                infinite:false
              });
            });
            $(self.container + '.location-box .tabs li:first-child').trigger('click');
          });
        });
    };
}