function loadActions () {

    /* для главной */
    $('.show_input').on('click',function(){
        $(this).hide();
        $(this).parent('.search-by-name ').find('.txt').show();
    });
    $('.show_inp').on('click',function(){
        $(this).hide();
        $(this).parents('.search-by-name').find('.search_txt').show();
    });



    $('.left_search .radioBox, .left_search .chekBox').on('click',function(){
        $(this).parents('.in_colapse').find('.txt').hide();
        $(this).parents('.in_colapse').find('.show_input').show();
    });
//    $('[placeholder]').blur( function () {
//        var input = $(this);
//        if (input.val() == '' || input.val() == input.attr('placeholder')) {
//            input.addClass('placeholder');
//            input.val(input.attr('placeholder'));
//        }
//    });

    /* для главной */

    $('.search-form .tabs').each(function () {
        $(this).find('li').each(function (i) {
            $(this).click(function () {
                $(this).addClass('active').siblings().removeClass('active')
                    .parents('.search-form').find('.section').eq(i).fadeIn(0).siblings('.section').hide();
            });
        });
    });

    if (navigator.appName.indexOf('Explorer') + 1 && parseInt($.browser.version, 10) <= 9){
        $('*[placeholder]').placeholder();
    }


// MAGAZINE
    $('.good_info .left_nav').each(function () {
        $(this).find('li').first().addClass('active');
        $(this).find('li').each(function (i) {
            $(this).click(function () {
                $(this).addClass('active').siblings().removeClass('active')
                    .parents('.good_info').find('.goods_txt').eq(i).css('display','table-cell').siblings('.goods_txt').hide();
            });
        });
    });


    $('.showhide').live('click',function(){
        if($(this).parents('.orders_claster').hasClass('active')) {
            $(this).parents('.orders_claster').removeClass('active').next('.showhide_block').hide(200);
        }   else {
            $(this).parents(".orders_claster").addClass('active');
            $(this).parents(".bg_gradient").children(".showhide_block").show(200);
        }
    });

    $('.orders_claster').eq(0).find('.showhide').trigger('click');


    /*$('.price-btn .btn-w').live('click',function(){
        $(this).hide();
        $(this).parents('.buy').find('.inbasket').css('visibility','visible');
        $(this).parents('.buy').find('.counter').show();
        $(this).parents('.buy').find('.count').show();
    });*/

    /*$('.good .btn-appoint').live('click',function(){
        $(this).hide();
        $(this).parents('.good').find('.showhide').css('visibility','visible');
        $(this).parents('.good').find('.goood_totalp').css('display','inline-block');
        $('.good_basket').fadeIn(600);
    });*/



//tabs


    $(".cab-cont .chzn-select, .inner-2 .chzn-select").chosen();
    $(".cab-cont .chzn-select-deselect, .inner-2 .chzn-select").chosen({allow_single_deselect:true});

    $(".illness-nav .nav li:last").addClass("last");
    $(".illness-nav .sub-nav li:first").addClass("first");
    $(".illness-nav .sub-nav li:last").addClass("last");

//search dropdown menu
    /*
     $(".quick-search-block input.txt").keydown(function(){
     $(".search-info .drop-menu").slideDown();
     });*/

    $(".wrap").click(function () {
        $(".search-info .drop-menu, .quick-search .drop-menu, .search-block .drop-menu").slideUp();
    });

    /**
     $(".quick-search input.quick-search-input").keydown(function(){
	$(".quick-search .drop-menu").slideDown();
});

     $(".search-block input.txt").keydown(function(){
	$(".search-block .drop-menu").slideDown();
});*/

    $(".quick-search .drop-menu li").click(function () {
        var value = $(this).html();
        $(".quick-search-input").val(value);
    });
    $(".quick-search-block .drop-menu li").click(function () {
        var value = $(this).html();
        $(".quick-search-block .txt").val(value);
    });
    $(".search-block .drop-menu li").click(function () {
        var value = $(this).html();
        $(".search-block .txt").val(value);
    });
    //search dropdown menu end

//gender choose
    $('body').on('click', '.gender span', function () {
        $(this).toggleClass("selected");
    });

    $('body').on('click', '.about-form .gender span', function () {
        $(".about-form .gender span").removeClass('selected');
        $(this).toggleClass("selected");
    });

//gender choose end

    $('.clinic-landing .btn-find-doctor-2, .clinic-landing .comments-count a, .doctor-landing .comments-count, .like_service_ul, .landing-header-links a, .order-link').on('click', function (e) {
        e.preventDefault();
        var link = $(this).attr('href'),
            linkTop = $(link).offset().top - $().height();
        $('html, body').animate({scrollTop:linkTop}, 1000);
    });


    /*
        $(window).scroll(function(){
            clearTimeout(stimer);
            var w_off = $(window).scrollTop()
            ancor.push({id:"ws",of:w_off+100})

            ancor.sort(function(a,b) {
                return a.of - b.of;
            });

            var i = getIndex(ancor, "id", "ws")

            if (i != 0) {

                if (s_click == false) {
                    $('.sub-nav ul a').removeClass('active')
                    $('.sub-nav ul li').removeClass('active')

                    $('.sub-nav ul a[data-t="'+ancor[i - 1].id+'"]').addClass('active');
                    $('.sub-nav ul a[data-t="'+ancor[i - 1].id+'"]').parent().addClass('active');
                }
            }
           // ancor.splice(i, 1)

            stimer = setTimeout( stopscroll , 150 );
        });

        function stopscroll() {
            s_click = false;
        }
     */

    $(".other-links li span").click(function () {
            $(this).parent().children('.drop-box').slideToggle(200);
            $(this).toggleClass("collapsed");
        }
    );

    $(".help-list .help-target").click(function (e) {
            e.preventDefault();
            $(this).parent().children('.drop').slideToggle(200);
            $(this).toggleClass("collapsed");
        }
    );

    $(".help-page .sub-menu li").click(function () {
        $(".help-page .sub-menu li").removeClass('active');
        $(this).addClass("active");
    });


    $('.rating-block-clinics').hide();
    $('.search-form .tab-clinics').click(function () {
        $('.rating-block-clinics').show();
        $('.rating-block-doctors').hide();
    });
    $('.search-form .tab-doctors').click(function () {
        $('.rating-block-clinics').hide();
        $('.rating-block-doctors').show();
    });


//search filter
    $('body').on('click', '.for-whom-clinic .radioBox', function () {
        if(!$(this).hasClass('disable')) {
            $(".for-whom-clinic .radioBox").removeClass("act").find('input[type=hidden]').val(0);
            $(this).addClass("act");
            $(this).children("span").find('input[type=hidden]').val(1);
        }
    });
    $('body').on('click', '.colright .radioBox', function () {
        $(".colright").find(".act").removeClass("act");
        $(this).addClass("act");
        $(".colright .radioBox").find('input[type=hidden]').val(0);
        $(this).children("span").find('input[type=hidden]').val(1);

    });

    $('body').on('click', '.inner .colleft  .radioBox, .inner-2 .colleft  .radioBox', function () {
        if(!$(this).hasClass('disable')) {
            $(".colleft").find(".act").removeClass("act");
            $(this).addClass("act");
            $('.colleft .radioBox').find('input[type=hidden]').val(0);
            $(this).find('input[type=hidden]').val(1);
        }

    });

    $('.chekBox-allTime').click(function () {
        $(this).toggleClass('act');
    });

    $('body').on('click', '.chekBox', function (e) {
        if ($(this).data('disabled'))
            return;
        $(this).toggleClass('act');
        $(this).parent().find('.search-param-icon').toggleClass('act');

        if ($(this).hasClass('act'))
        {
            $('input[name="form[fact_address]"]').val($('input[name="form[legal_address]"]').val());
            $(this).find('input[type="hidden"]').val(1);
        } else {
            $(this).find('input[type="hidden"]').val(0);
        }
        e.preventDefault();
    });

    $('body').on('.choose-section .chekBox-allTime', function () {
        $('.choose-section .chekBox').removeClass('act');
        $(this).addClass('act');
        $('.choose-section .first-label .radioBox').addClass('act');
        $('.choose-section .first-label .radioBox').find('input[type=hidden]').val(1);
    });

    $('body').on('click', '.choose-section .chekBox', function () {
        $('.choose-section .chekBox-allTime').removeClass('act');
        $('.choose-section .second-label .radioBox').removeClass('act');
        $('.choose-section .second-label .radioBox').find('input[type=hidden]').val(0);
        $('.choose-section .first-label .radioBox').addClass('act');
        $('.choose-section .first-label .radioBox').find('input[type=hidden]').val(1);
        $('.choose-section .chekBox-allTime').find('input[type=hidden]').val(0);
        $('.choose-section .radio').toggleClass('act');
        if ($(this).hasClass('act')) $(this).find('input[type=hidden]').val(1);
        else  $(this).find('input[type=hidden]').val(0);
    });

    $('.booking .chekBox, .reg-popup .chekBox, .settings .chekBox').click(function () {
        $(this).find('input[type=hidden]').val() == 0 ? $(this).find('input[type=hidden]').val(1) : $(this).find('input[type=hidden]').val(0)
    })

    $('.settings .sms-chk').click(function () {
        if ($(this).hasClass('act')) $('.options-mobile').fadeIn();
        else $('.options-mobile').fadeOut();
    });

    $('body').on('click', '.choose-section .chekBox-allTime', function () {
        if ($(this).hasClass('act')) $(this).find('input[type=hidden]').val(1);
        else  $(this).find('input[type=hidden]').val(0);
        $('.choose-section .second-label .radioBox').removeClass('act');
        $('.choose-section .second-label .radioBox').find('input[type=hidden]').val(0);
    });

    $('.inner .choose-section .second-label, .inner-2 .choose-section .second-label').click(function () {
        $('.choose-section .chekBox, .choose-section .chekBox-allTime').removeClass('act');
    });

    $('.choose-section .first-label, .choose-section .chekBox-allTime').click(function () {
        $('.choose-section .chekBox-allTime').find('input[type=hidden]').val(1);
    });

    $('.choose-section .first-label').click(function () {
        $('.choose-section .chekBox-allTime').addClass('act');
    });
//search filter end


    if ($(".full-width .info-col .about-cont").height() < 297){
        $(".full-width .col-about .more-link").hide();
        $(".full-width .col-about-doctor .more-link").hide();
    }

    $('.full-width .info-col .more-link').click(function () {
        $(this).hide();
        $(this).parent().children('ul, .about-cont').addClass('expand');
    });



    // registration popups
    $(function () {
        $(".reg-link").fancybox({
            padding:0,

            beforeShow:function () {
                $('.scroll-pane').jScrollPane();
            },
            afterLoad:function () {
                $("#registration-form").validate({
                    wrapper:'span',
                    rules:{
                        password:{
                            required:true
                        },
                        repeat_password:{
                            required:true,
                            equalTo:"#password"
                        }
                    }
                });

                $("#registration-form2").validate({
                    wrapper:'span',
                    rules:{
                        password:{
                            required:true
                        },
                        repeat_password:{
                            required:true,
                            equalTo:"#password"
                        }
                    }
                });

            }
        });
    });

    $(function () {
        $("#recovery-password").validate({
            wrapper:'span'
        });
        $("#forgotpass-form").validate({
            wrapper:'span'
        });

        $("#authorization-form").validate({
            wrapper:'span',
            rules:{
                password:{
                    required:true
                }
            }
        });

        $("#authorization-form2").validate({
            wrapper:'span',
            rules:{
                password:{
                    required:true
                }
            }
        });
    });

    /*setTimeout(function() { $('a.reg-link-default').click(); }, 10);*/

    $("input.password-field").focus(function () {
        this.setAttribute("type", "password");
    });
// registration popups end


// more/less text
    $(function () {

        var adjustheight = 100;
        var moreText = "Еще заболевания";
        var lessText = "Скрыть";

        $(".list-item ul").css('height', adjustheight).css('overflow', 'hidden');

        $(".list-item a.adjust").text(moreText);

        $(".list-item .adjust").toggle(function () {
            $(this).parent().children(".list-item ul").css('height', 'auto').css('overflow', 'visible');
            $(this).text(lessText);
        }, function () {
            $(this).parent().children(".list-item ul").css('height', adjustheight).css('overflow', 'hidden');
            $(this).text(moreText);
        });
    });

    $('.stage .map-corn').click(function () {
        $('.nav .tab-map').addClass('ui-state-active');
        $('.nav .tab-foto').removeClass('ui-state-active');
        $('#tabs-1').hide();
        $('#tabs-2').show();
    });

    $('.nav .tab-foto').click(function () {
        $(this).addClass('ui-state-active');
        $('.nav .tab-map').removeClass('ui-state-active');
        $('#tabs-2').hide();
        $('#tabs-1').show();
    });

    //$('.connected-carousels .next-navigation').removeClass('inactive');

    $('body').on('click', '.radio-label-container .radioBox span', function () {
        $(this).parent().parent().parent().find(".act").removeClass("act");
        $(this).parent().addClass("act");
        $(this).parent().parent().parent().find('input[type=hidden]').val(0);
        $(this).parent().find('input[type=hidden]').val(1);
    });

    /*banners*/

    $('.second-opinion-block .close').click(function() {
        $('.second-opinion-block .close').css('display', 'none');
        $('.second-opinion-1').hide(300);
        $('.second-opinion-2').show(300);
        Ajax.Post('/ajax/changeBannerVisibility', {
                key: 'isSecondOpinionVisible',
                value: 0
            }
        );
    });

    $(document).on('click', '.banner-treatment-in-switz .close', function() {
        $('.banner-treatment-in-switz .close').css('display', 'none');
        $('.banner-treatment-in-switz-link').hide();
        $('.banner-treatment-in-switz-open').show();
        $('.banner-treatment-in-switz').addClass('small-banner-visible');
        Ajax.Post('/ajax/changeBannerVisibility', {
                key: 'isTreatmentInSwitzVisible',
                value: 0
            }
        );
    }).on('click', '.second-opinion-block .open', function() {
        $('.second-opinion-block .close').css('display', 'block');
        $('.second-opinion-2').hide(300);
        $('.second-opinion-1').show(300);
        Ajax.Post('/ajax/changeBannerVisibility', {
                key: 'isSecondOpinionVisible',
                value: 1
            }
        );
    }).on('click', '.banner-treatment-in-switz-open', function() {
        $('.banner-treatment-in-switz .close').css('display', 'block');
        $('.banner-treatment-in-switz-open').hide();
        $('.banner-treatment-in-switz-link').show();
        $('.banner-treatment-in-switz').removeClass('small-banner-visible');
        Ajax.Post('/ajax/changeBannerVisibility', {
                key: 'isTreatmentInSwitzVisible',
                value: 1
            }
        );
    });

    self.send_discount_request = true;
    $('.form-discount input[type="text"]').val('');
    $('input[name="discount_phone_number"]').inputmask('+7-999-999-99-99');

    $(document).on('click', '.btn-discount', function() {
        if (self.send_discount_request) {
            self.send_discount_request = false;
            self.phone = $('.form-discount input[name="discount_phone_number"]').val();
            Ajax.Post('/ajax/addCallToUser', {
                    name: "Запрос на скидку",
                    phone: self.phone
                },
                function (data) {
                    if (data.status == 0) {
                        $('.form-discount-step-1').css('display', 'none');
                        $('.form-discount input[type="text"]').val('');
                        $('.form-discount-step-2').css('display', 'block');
                        setTimeout(function () {
                                self.send_discount_request = true;
                                $('.discount').fadeOut(500)},
                            8000);
                    } else {
                        $('.btn-discount').parent().find('input[type="text"]').focus();
                        self.send_discount_request = true;
                    }
                }
            );
        }
    }).on('click', '.discount-close', function() {
        $('.discount-close').css('display', 'none');
        $('.discount-open .btn-open').css('display', 'inline');
        $('.form-discount').hide(200);
        Ajax.Post('/ajax/changeBannerVisibility', {
                key: 'isDiscountVisible',
                value: 0
            }
        );
    }).on('click', '.discount-open', function() {
        $('.form-discount-step-1').show(200, function(){
            $('.discount-open .btn-open').css('display', 'none');
            $('.discount-close').css('display', 'block');
        });
        Ajax.Post('/ajax/changeBannerVisibility', {
                key: 'isDiscountVisible',
                value: 1
            }
        );
    });
}


$(document).ready(function () {
    if (window.hasSeoHide === false) {
        loadActions();
    }
});

$(document).on('content-ready', function () {
    loadActions();
});



















