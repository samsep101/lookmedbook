$(document).ready(function () {


    $(document).click(function (event) {
        if ($(event.target).closest('.fancybox-wrap').length) return;

        $('.fancybox-close').click(function () {
            $('.error_span').remove();
        });

        if ($('body').hasClass('fancybox-lock')) {
            $('.error_span').remove();
        }
    });

    $('.up_page').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });
    $('.left_search .search-form .head-label.second-label .visit-type').removeClass('radioBox').addClass('chekBox');

    $('.slider-cards .next-nav, .slider-cards .prev-nav, .all_city .next-nav, .all_city .prev-nav').live('hover',function(){
        $(this).find('.bg_nav').animate({
            opacity: 1
        }, 200);
    });
    $('.slider-cards .next-nav, .slider-cards .prev-nav, .all_city .next-nav, .all_city .prev-nav').live('mouseleave',function(){
        $(this).find('.bg_nav').animate({
            opacity: 0
        }, 100);
    });
    if (navigator.appName.indexOf('Explorer') + 1 && parseInt($.browser.version, 10) <= 9){
        $('input[placeholder]').placeholder();
    };

    $('.remove-feature-block .bound-doctor').live('click',function(){
        if($(this).hasClass('pass_doc')){
            $(this).removeClass('pass_doc').removeClass('rebound-doctor').addClass('unbound-doctor').text('Отвязать врача от клиники').parents('.remove-feature-block').find('span,br').remove();
        } else {
            $(this).removeClass('unbound-doctor').addClass('rebound-doctor').before('<span>Врач больше не привязан к этой клинике</span><br/>').addClass('pass_doc').text('Отменить');
        }
    });

    //setCursorDefault();
});
function setCookie(name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}

function getCookie(name) {
    var r = document.cookie.match("(^|;) ?" + name + "=([^;]*)(;|$)");
    if (r) return r[2];
    else return "";
}

var ajaxLoadCallbacks = [];

function ajax(url, blockId) {
    $('#' + blockId).html('<img src="/media/img/ajaxLoader.gif" />');

    Ajax.Get(url, {}, function (data) {
        $('#' + blockId).html(data);

        if (ajaxLoadCallbacks)
            for (var i in ajaxLoadCallbacks)
                ajaxLoadCallbacks[i].call();
    }, false);
}

function showOk(text) {
    alert(text);
}

function showError(text) {
    alert(text);
}

var Ajax = function () {
};

Ajax.Post = function (url, data, callback, json) {
    var result;

    if (json == undefined)
        json = true;

    var dataType = 'json';

    if (!json) {
        dataType = 'html';
    }

    data.csrf = SessionInfo.csrf;

    $.ajax({
        url:url,
        type:'POST',
        data:data,
        success:function (data) {
            if(callback) {
                callback(data);
            }
        },
        dataType:dataType
    });

    return result;
};

Ajax.SyncPost = function (url, data, callback, json) {
    var result;

    if (json == undefined)
        json = true;

    var dataType = 'json';

    if (!json) {
        dataType = 'html';
    }

    $.ajax({
        url:url,
        type:'POST',
        async:false,
        data:data,
        success:function (data) {
            callback(data);
        },
        dataType:dataType
    });

    return result;
};

Ajax.Get = function (url, data, callback, json) {
    var result;

    if (json == undefined)
        json = true;

    var data_type = json ? 'json' : 'html';

    $.ajax({
        url:url,
        data:data,
        type:'GET',
        success:function(data) {
            callback(data);
        },
        dataType:data_type
    });

    return result;
};

Ajax.SyncGet = function (url) {
    var result;

    $.ajax({
        url:url,
        type:'GET',
        async:false,
        success:function (data) {
            result = data;
        },
        error:function (data) {

        },
        dataType:'json'
    });

    return result;
}

function sel(obj) {
    var container = $($(obj).parent()).parent();
    $(container).children().each(function (i) {
        $(this).css('background', 'none');
        $(this).corner('5px');
    });
    $($(obj).parent()).css('background', '#0066CC');
}


function getParameterByName(name, default_value) {

    if (default_value == undefined)
        default_value = '';

    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(window.location.search);
    if (results == null)
        return default_value;
    else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
}

function attachFancybox(el) {
    el.fancybox({
        padding:0,

        beforeShow:function () {
            $('.scroll-pane').jScrollPane();
        }
    });
}

function setCustomSelect(selector, value) {
    $(selector + ' option').removeAttr('selected');
    $(selector + ' option[value="' + value + '"]').attr('selected', 'selected');

    var id = $(selector).attr('id');
    var text = $(selector + ' option[value="' + value + '"]').html();

    $('#' + id + '_chzn .chzn-single span').html(text);
}

function setChosenSelect() {
    $(".chzn-select").chosen();
    $(".chzn-select-deselect").chosen({allow_single_deselect:true});
}

function showValidationError(obj, message) {
    error_el = $('<span class="error_span" />');
    error_el.html('<label for="' + obj.attr('name') + '" class="error" style="display:block;">' + message + '</label>');
    obj.after(error_el);
    error_el.delay(2000).fadeOut(500);
}

function showPopup(html, width, height, is_closed) {
    if (width == undefined) width = '639px';
    if (height == undefined) height = 'auto';
    if (is_closed == undefined) is_closed = true;

    var popup_block = $('<div></div>');

    popup_block.append($('<div class="fancybox-placeholder" style="display: none;"></div>'));
    var fancybox_overlay = $('<div class="fancybox-overlay fancybox-overlay-fixed" style="width: auto; height: auto; display: block;"></div>');
    popup_block.append(fancybox_overlay);

    var fancybox_wrap = $('<div class="fancybox-wrap fancybox-desktop fancybox-type-inline fancybox-opened" tabindex="-1" style="width: ' + width + '; height: ' + height + '; position: absolute; top: 20px; opacity: 1; overflow: visible;"></div>');
    fancybox_overlay.append(fancybox_wrap);

    var fancybox_skin = $('<div class="fancybox-skin" style="padding: 0px; width: auto; height: auto;"></div>');
    fancybox_wrap.append(fancybox_skin);

    var fancybox_outer = $('<div class="fancybox-outer"></div>');
    fancybox_skin.append(fancybox_outer);

    var fancybox_inner = $('<div class="fancybox-inner" style="overflow: hidden; width: ' + width + '; height: auto;"></div>');
    fancybox_outer.append(fancybox_inner);

    if (is_closed) {
        var close_button = $('<a class="fancybox-item fancybox-close" href="javascript:;" title="Close"></a>');
        fancybox_outer.append(close_button);
        close_button.click(function () {
            popup_block.remove();
            $('body').removeClass('fancybox-lock');
        });
    }

    fancybox_inner.append(html);
    $('body').addClass('fancybox-lock');
    $('body').append(popup_block);

    $('.fancybox-wrap').css('left', ($(window).width() - $('.fancybox-wrap').width()) / 2);

    $(window).on('resize', function () {
        if ($(this).width() - $('.fancybox-wrap').width() > 0) {
            $('.fancybox-wrap').css('left', ($(this).width() - $('.fancybox-wrap').width()) / 2);
        }
        else
            $('.fancybox-wrap').css('left', 20);
    });

    $('.city-block .show_all').toggle(function(){
        $(this).addClass('active');
        $(this).next('.all_city').show();

        $(".all_city > ul").each(function(e){
            $('.all_city > ul').carouFredSel({
                auto: false,
                prev: '.all_city .prev-nav',
                next: '.all_city .next-nav',
                scroll:{items:3},
                circular: false,
                infinite:false
            });
        });
    },function(){
        $(this).removeClass('active');
        $(this).next('.all_city').hide();
    });

    if (navigator.appName.indexOf('Explorer') + 1 && parseInt($.browser.version, 10) <= 9){
        $('input[placeholder]').placeholder();
    }
}

function showLandingPopup(html) {
    var popup_block = $('<div id="main-landing-popup"></div>');

    popup_block.append($('<div class="fancybox-placeholder" style="display: none;"></div>'));
    var fancybox_overlay = $('<div class="fancybox-overlay fancybox-overlay-fixed" style="width: auto; height: auto; display: block;"></div>');
    popup_block.append(fancybox_overlay);

    var fancybox_wrap = $('<div class="fancybox-wrap fancybox-desktop fancybox-type-inline fancybox-opened" tabindex="-1" style="width: 680px; height: auto; position: absolute; top: 20px; opacity: 1; overflow: visible;"></div>');
    fancybox_overlay.append(fancybox_wrap);

    var fancybox_skin = $('<div class="fancybox-skin" style="padding: 0px; width: auto; height: auto;"></div>');
    fancybox_wrap.append(fancybox_skin);

    var fancybox_outer = $('<div class="fancybox-outer"></div>');
    fancybox_skin.append(fancybox_outer);

    var fancybox_inner = $('<div class="fancybox-inner" style="overflow: auto;"></div>');
    fancybox_outer.append(fancybox_inner);

    var fancybox_item = $('<a style="display: none" class="fancybox-item fancybox-close" href="javascript:;" title="Close"></a>');
    fancybox_outer.append(fancybox_item);

    fancybox_inner.append(html);
    $('body').addClass('fancybox-lock');
    $('body').append(popup_block);

    $('.fancybox-wrap').css('left', ($(window).width() - $('.fancybox-wrap').width()) / 2);
}

function showLandingForgotPassPopup(html) {
    var popup_block = $('<div id="main-landing-popup"></div>');

    popup_block.append($('<div class="fancybox-placeholder" style="display: none;"></div>'));
    var fancybox_overlay = $('<div class="fancybox-overlay fancybox-overlay-fixed" style="width: auto; height: auto; display: block;"></div>');
    popup_block.append(fancybox_overlay);

    var fancybox_wrap = $('<div class="fancybox-wrap fancybox-desktop fancybox-type-inline fancybox-opened" tabindex="-1" style="width: 660px; height: auto; position: absolute; top: 250px; opacity: 1; overflow: visible;"></div>');
    fancybox_overlay.append(fancybox_wrap);

    var fancybox_skin = $('<div class="fancybox-skin" style="padding: 0px; width: auto; height: auto;"></div>');
    fancybox_wrap.append(fancybox_skin);

    var fancybox_outer = $('<div class="fancybox-outer"></div>');
    fancybox_skin.append(fancybox_outer);

    var fancybox_inner = $('<div class="fancybox-inner" style="overflow: auto;"></div>');
    fancybox_outer.append(fancybox_inner);

    var fancybox_item = $('<a style="display: none" class="fancybox-item fancybox-close" href="javascript:;" title="Close"></a>');
    fancybox_outer.append(fancybox_item);

    fancybox_inner.append(html);
    $('body').addClass('fancybox-lock');
    $('body').append(popup_block);

    $('.fancybox-wrap').css('left', ($(window).width() - $('.fancybox-wrap').width()) / 2);
}


function writeLogAccountActivity(code, log) {
    var options = {
        code:code,
        log:log
    };

    Ajax.Post('/ajax/logAccountActivity', options, function (data) {
        if (data.status == 0) {
            return true;
        } else {
            return false;
        }
    });
}

function addTobookmarkSmallClinic(clinic_id) {
    if (clinic_id) {
        getSmallClinicBookmarkBlock(clinic_id);
    }

}

function getSmallClinicBookmarkBlock(clinic_id) {
    Ajax.Post('/clinic/ajaxAddToMyClinicList', {clinic_id:clinic_id}, function (data) {
        if (data.status == 0) {
            var isItAddOrKick = data.result.my_clinic;

            if (isItAddOrKick) {
                $('.clinic-bookmark-' + clinic_id).each(function () {
                    $(this).addClass("btn-bookmark-added");
                    $(this).html('<i class="icon-add"></i> <span class="txt txt-added">В закладках</span>');
                });
            }
            else {
                $('.clinic-bookmark-' + clinic_id).each(function () {
                    $(this).removeClass("btn-bookmark-added");
                    $(this).html('<i class="icon-add"></i> <span class="txt">Добавить в закладки</span>');
                });
            }
        }
    });
}


jQuery.fn.ForceNumericOnly =
    function () {
        return this.each(function () {
            $(this).keydown(function (e) {
                var key = e.charCode || e.keyCode || 0;
                // Разрешаем backspace, tab, delete, стрелки, обычные цифры и цифры на дополнительной клавиатуре
                return (
                    key == 8 ||
                        key == 9 ||
                        key == 46 ||
                        (key >= 37 && key <= 40) ||
                        (key >= 48 && key <= 57) ||
                        (key >= 96 && key <= 105));
            });
        });
    };

function showErrorLabel(message, obj, error_span) {
    obj.addClass('error');
    if (obj.attr('class')) {

    }
    var for_name = (obj.attr('name')) ? obj.attr('name') : obj.attr('class');

    if (!error_span) $('.error_span').remove();
    error_el = $('<span class="error_span" />');
    error_el.html('<label for="' + for_name + '" class="error" style="display:block;">' + message + '</label>');
    obj.after(error_el);
    error_el.css({
        width:'auto'
    });

    //if (window.validation_span != undefined) {
    var left = obj.offset().left - 10;

    var after_right = 19;
    if (left < 0) {
        after_right -= left - 20;

        left = 10;
        error_el.addClass('custom-error23');
        $("head").append($('<style>span.custom-error23:after { right: ' + after_right + 'px !important; }</style>'));
    }
    error_el.css({
        position:'absolute',
        top:obj.offset().top - error_el.height() + 5,
        left:left,
        'z-index':20000
    });
    error_el.appendTo($('body'));
    //}
    error_el.delay(2000).fadeOut(500);
    return false;
}

function showLandingErrorLabel(message, obj, error_span) {
    obj.addClass('error');
    if (obj.attr('class')) {

    }
    var for_name = (obj.attr('name')) ? obj.attr('name') : obj.attr('class');

    if (!error_span) $('.error_span').remove();
    error_el = $('<span class="error_span" />');
    error_el.html('<label for="' + for_name + '" class="error" style="display:block;">' + message + '</label>');
    obj.after(error_el);
    error_el.css({
        width:'auto'
    });

    //if (window.validation_span != undefined) {
    var left = obj.offset().left - 10;

    var after_right = 19;
    if (left < 0) {
        after_right -= left - 20;

        left = 10;
        error_el.addClass('custom-error23');
        $("head").append($('<style>span.custom-error23:after { right: ' + after_right + 'px !important; }</style>'));
    }
    error_el.css({
        position:'absolute',
        top:obj.offset().top - error_el.height() - 10,
        left:left,
        'z-index':20000
    });
    error_el.appendTo($('body'));
    //}
    error_el.delay(2000).fadeOut(500);
    return false;
}

function extend(Child, Parent) {
    var F = function () {
    }
    F.prototype = Parent.prototype
    Child.prototype = new F()
    Child.prototype.constructor = Child
    Child.superclass = Parent.prototype
}

function setCounters(category, action, label, email) {
    if (!label)
        label = getCounterLabelByPageUrl();

    if (window.ga != undefined){
        ga('send', 'event', category, action, {'eventLabel':label, 'user':email});
    }

    var goalParams = {'action':action, 'label':label, 'user':email};


    if (undefined != window.yaCounterLookmedbook) {
        yaCounterLookmedbook.reachGoal(category, goalParams);
    }

}

function setNewCounters(number, category, object, action, position) {

    var param_string = category+'/'+object+'/'+action;
    if (position) {
        param_string+='/'+position;
    }

    if (window.ga != undefined){
        ga('send', 'pageview', param_string);
    }

    //if (undefined != window.yaCounterLookmedbook) {
    if (number && undefined != window['yaCounter'+number]) {
        eval("yaCounter"+number+".file('"+param_string+"')");
    }

}

function getCounterLabelByPageUrl() {
    var url = window.location.pathname;

    var disease_page_match = /^\/disease\/.+$/im;

    if (disease_page_match.test(url))
        return 'disease-page';

    if (url == '/')
        return 'home';

    if (url == '/disease')
        return 'disease-search';

    var doctor_page_match = /^\/doctor\/.+$/im;

    if (doctor_page_match.test(url))
        return 'doctor-page';

    if (url == '/doctor')
        return 'doctor-search';

    var clinic_page_match = /^\/clinic\/.+$/im;

    if (clinic_page_match.test(url))
        return 'clinic-page';

    if (url == '/clinic')
        return 'clinic-search';


    if (url == '/about')
        return 'about';

    if (url == '/help')
        return 'help';

    if (url == '/account')
        return 'main';

    url = url.replace('/', '');
    return  url.replace(/\//g, '-');
}

function showTopNumber(){
    var date = new Date();

    if (date.getDay() == 0 || date.getDay() == 6){
        $('.top_number').hide();
    } else
        if (date.getHours() >= 20 || date.getHours() <= 8){
        $('.top_number').hide();
        } else {
            $('.top_number').show();
        }
}

function showLoginOrPhoneValidationMessage(elem_email, elem_password) {
    if(!elem_email.val()){
        showErrorLabel('Поле обязательно для заполнения', elem_email, '');
    } else {
        if(elem_email.val().match(/[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-\.]/)) {
            Ajax.Post('/ajax/checkEmail', {email: elem_email.val()}, function(data){
                if (data.result){
                    showErrorLabel('Указанный адрес еще не зарегистрирован', elem_email, '');
                } else {
                    showErrorLabel('Неверный пароль', elem_password, '');
                }
            });
        } else {
            if(elem_email.val().match(/^((\+7)?|8)[0-9]{10}$/)) {
                Ajax.Post('/ajax/checkLoginPhone', {phone: elem_email.val()}, function(data){
                    if (data.result){
                        showErrorLabel('Указанный телефон еще не зарегистрирован', elem_email, '');
                    } else {
                        showErrorLabel('Неверный пароль', elem_password, '');
                    }
                });
            } else {
                showErrorLabel('В поле телефон или email должен быть введён корректный телефон или email-адрес', elem_email, '');
            }
        }
    }
}

var SessionInfo = function(){};

SessionInfo.is_authed = false;
SessionInfo.email = 'guest';
SessionInfo.reset_filter = false;


var DateHelper = function () {

};

DateHelper.getEngNameByDayNumber = function(day_number) {
    switch (day_number) {
        case 1:
            return 'monday';
            break;
        case 2:
            return 'tuesday';
            break;
        case 3:
            return 'wednesday';
            break;
        case 4:
            return 'thursday';
            break;
        case 5:
            return 'friday';
            break;
        case 6:
            return 'saturday';
            break;
        case 0:
            return 'sunday';
            break;
    }
};

DateHelper.getRuMonthGenitiveNameByMonthNumber = function(month_number){

    switch(month_number)
    {
        case 1:
            return 'января';
        case 2:
            return 'февраля';
        case 3:
            return 'марта';
        case 4:
            return 'апреля';
        case 5:
            return 'мая';
        case 6:
            return 'июня';
        case 7:
            return 'июля';
        case 8:
            return 'августа';
        case 9:
            return 'сентября';
        case 10:
            return 'октября';
        case 11:
            return 'ноября';
        case 12:
            return 'декабря';
    }

    return '';
};

DateHelper.getDayNumberByEngName = function (eng_name) {
    switch (eng_name) {
        case 'monday':
            return 1;
        case 'tuesday':
            return 2;
        case 'wednesday':
            return 3;
        case 'thursday':
            return 4;
        case 'friday':
            return 5;
        case 'saturday':
            return 6;
        case 'sunday':
            return 7;
    }

    return false;
};

DateHelper.getRuNameByDayNumber = function (day_number) {
    switch (day_number) {
        case 1:
            return 'Понедельник';
            break;
        case 2:
            return 'Вторник';
            break;
        case 3:
            return 'Среду';
            break;
        case 4:
            return 'Четверг';
            break;
        case 5:
            return 'Пятницу';
            break;
        case 6:
            return 'Субботу';
            break;
        case 0:
            return 'Воскресенье';
            break;
    }
};

DateHelper.getTimeByDate = function (date) {
    var hours = (date.getHours() < 10) ? '0' + date.getHours() : date.getHours();
    var minutes = (date.getMinutes() < 10) ? '0' + date.getMinutes() : date.getMinutes();
    return hours + ':' + minutes;
}

DateHelper.getHoursByTime = function (time) {
    var preg = /^([0-9]{2}):([0-9]{2})$/;
    var matches = time.match(preg);

    return matches[1];
};

DateHelper.getMinutesByTime = function (time) {
    var preg = /^([0-9]{2}):([0-9]{2})$/;
    var matches = time.match(preg);

    return matches[2];
};

DateHelper.compareDates = function (date1, date2) {
    var date1 = parseInt(date1.split('-').reverse().join('')); // из '06.10.2012' получаем число 20121006
    var date2 = parseInt(date2.split('-').reverse().join('')); // из '01.10.2012' получаем число 20121001
    if (date1 == date2)
        return 0;
    else {
        return date1 - date2;
    }
}

DateHelper.getDateByDayNameAndWeekNumberAndYear = function (day_name, week_number, year) {
    var day_number = DateHelper.getDayNumberByEngName(day_name);

    var w = week_number || 1, n = day_number || 1, y = year || new Date().getFullYear(); //defaults
    var d = new Date(y, 0, 7 * w);
    d.setDate(d.getDate() - (d.getDay() || 7) + n);
    return d;
};

DateHelper.getRuNameByDate = function(date)
{
    var preg = /^([0-9]{4})\-([0-9]{2})-([0-9]{2})/;

    var matches = date.match(preg);

    if (!matches)
        return '';

    var str = matches[3] + ' ' + DateHelper.getRuMonthGenitiveNameByMonthNumber(parseInt(matches[2]));

    return str;
};

var ElementsHelper = function () {
};

ElementsHelper.blockElement = function (el) {
    var self = this;
    lock_div = $('<div class="lock-div"></div>');

    lock_div.css({
        position:'absolute',
        left:0,
        top:170,
        width:el.width() + 300,
        height:el.height() + 50,
        'z-index':1000
    });

    var prev_height = el.height();

    setInterval(function () {
        var height = el.height();
        if (prev_height != height) {
            lock_div.css({
                position:'absolute',
                left:0,
                top:170,
                width:el.width() + 300,
                height:el.height() + 50,
                'z-index':1000
            });
        }
    }, 100);

    el.append(lock_div);
};

DateHelper.isToday = function(date1){
    var date = new Date();

    var year = date.getYear() + 1900;
    var month = date.getMonth() + 1;

    if(month < 10)
        month = '0'+month;

    var day = date.getDate();
    if (day < 10)
        month = '0'+day;

    return (year+'-'+month+'-'+day) == date1;
};

function send(src){
    if ((location.href.indexOf('mngcgi')!=-1)||(!src)){
        return false;
    }
    src= ('https:'== document.location.protocol ? 'https:' : 'http:') + src;
    src+='&tail256='+escape(document.referrer?document.referrer:'unknown');
    src=src.split('![rnd]').join(Math.round(Math.random()*1000000000));
    var d=document,b=d.body;
    if (b){
        var i=d.createElement('img');
        with(i.style){
            position='absolute';
            width=height=0;
        }
        i.onload=i.onerror=function(){b.removeChild(i)};
        i.src=src;
        b.insertBefore(i,b.firstChild)
    }else{
        new Image().src=src
    }
};

function array_unique( array ) {
    var p, i, j;
    for(i = array.length; i;){
        for(p = --i; p > 0;){
            if(array[i] === array[--p]){
                for(j = p; --p && array[i] === array[p];);
                i -= array.splice(p + 1, j - p).length;
            }
        }
    }
    return array;
}
