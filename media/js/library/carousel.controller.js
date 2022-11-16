var CarouselController = function(max_height) {

    var self = this;

    this.max_height = max_height;

    this.init = function() {
        $(function () {
          $('[data-jcarousel]').each(function() {
            var el = $(this);
            el.jcarousel(el.data());
          });

          $('[data-jcarousel-control]').each(function() {
            var el = $(this);
            el.jcarouselControl(el.data());
          });

          $(".main-cont .schedule-extended ul").each(function(e){
            $(this).parent('.schedule-extended').addClass('schedule-extended-'+e);
            $(this).nextAll('a').addClass('nav-'+e);
            $('.main-cont .location-box .tabs li').on('click', function(){
              $('.main-cont .schedule-extended-' + e + ' ul').carouFredSel({
                auto: false,
                prev: '.main-cont .prev-nav.nav-'+ e,
                next: '.main-cont .next-nav.nav-'+ e,
                scroll:{items:1},
                circular: false,
                infinite:false
              });
            });
            $('.main-cont .location-box .tabs li:first-child').trigger('click');
          });

          $(".connected-carousels .carousel-stage li a").fancybox({
            maxWidth	: 660,
            maxHeight	: self.max_height,
            fitToView	: false,
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none'
          });
        });
    };
}