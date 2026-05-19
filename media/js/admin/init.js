function setCookie(name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}


$(document).on('click', '.make-xml', function () {
    $(this).parent().submit();
});

$(document).on('click', '.spoiler-links', function () {
    $(this).parent().children('.spoiler-body').toggle('normal');
    return false;
});

function checked_all(el){

    var container = el.parent().parent().parent().parent();

    var status = el.attr("checked");
    if (status) {
        container.find('.input_check').attr("checked","checked");
    }
    else {
        container.find('.input_check').removeAttr("checked");
    }
}

function unchecked(el){
    var container = el.parent().parent().parent().parent();

    var input = el.attr("checked");
    if (!input) {
        container.find('.status_check').removeAttr("checked");
    }
}