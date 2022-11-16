(function ($) {
    $(document).ready(function () {
        if ($('.responsive-header__nav_mobile').length > 0) {
            var nav = responsiveNav('.responsive-header__nav_mobile', {
                customToggle: '#toggle-nav',
            });
        }

        if ($('.left_nav').length > 0) {
            var nav2 = responsiveNav('.left_nav', {
                customToggle: '#open-left-nav',
                closeOnNavClick: true,
            });

        }

        $('.resposive-body__doctors').slick({
            dots: true,
            infinite: false,
            slidesToShow: 2,
            slidesToScroll: 2,
            responsive: [
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
            ],
        });

        $('.js-equal').each(function () {
            equal_height($(this));
        });

    });

    $('.spec-tabs').on('click', function (e) {
        e.preventDefault();
        var _ = $(this);
        var card = _.closest('.doctor-landing');
        var spec = _.data('spec');
        card.find('.spec-tabs, .cost-visit-specialization').each(function () {
            $(this).removeClass('active')
        });
        card.find('[data-spec="' + spec + '"], [data-specialization="' + spec + '"]').each(function () {
            $(this).addClass('active')
        });
    });


    function equal_height(object) {
        var height = 0;
        object.find('.address-and-time-area').each(function () {
            if ($(this).outerHeight() > height)
                height = $(this).outerHeight();
        });
        object.find('.address-and-time-area').each( function() {
            $(this).css({'height': height + "px"});
        });

    }

})(jQuery)