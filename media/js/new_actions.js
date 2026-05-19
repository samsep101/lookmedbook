$(function () {
    $(document).on('click', '.chekBox', function (e) {
        if($(this).parent().attr('class') != 'adult blocked' && $(this).parent().attr('class') != 'child blocked') {
            if ($(this).data('disabled'))
                return;
            $(this).toggleClass('act');

            if ($(this).hasClass('act'))
            {
                $('input[name="form[fact_address]"]').val($('input[name="form[legal_address]"]').val());
                $(this).find('input[type="hidden"]').val(1);
            } else {
                $(this).find('input[type="hidden"]').val(0);
            }
            e.preventDefault();
        }
    });
    $('.first-tab-header').click(function () {
        $(this).parent().parent().parent().parent().find('.second-tab').css('display','none');
        $(this).parent().parent().parent().parent().find('.first-tab').css('display','block');
        $(this).parent().parent().find('li').removeClass('ui-state-active');
        $(this).parent().addClass('ui-state-active');
    });
    $('.second-tab-header').click(function () {
        $(this).parent().parent().parent().parent().find('.first-tab').css('display','none');
        $(this).parent().parent().parent().parent().find('.second-tab').css('display','block');
        $(this).parent().parent().find('li').removeClass('ui-state-active');
        $(this).parent().addClass('ui-state-active');
    });
    $('.pick-a-pic').each(function(){
        $(this).append('<img class="on_pic" alt="" src="/media/images/on_lookmedbook.png">');
    });

    $('[placeholder]').each(function () {
        var input = $(this);
        if (input.val() == '' || input.val() == input.attr('placeholder')) {
            input.addClass('placeholder');
            input.val(input.attr('placeholder'));
        }
    });

    $(document).on('focus','[placeholder]', function () {
        var input = $(this);
        if (input.val() == input.attr('placeholder')) {
            input.val('');
            input.removeClass('placeholder');
        }
    });

    $('[placeholder]').blur( function () {
        var input = $(this);
        if (input.val() == '' || input.val() == input.attr('placeholder')) {
            input.addClass('placeholder');
            input.val(input.attr('placeholder'));
        }
    });
});