$(function () {
    $('.lazy,.lazy-carousel').Lazy();
    $('.prev-stage,.next-stage').click(function () {
        var instance = $('.lazy-carousel').Lazy({chainable: false});
        instance.force();
    });
    $('link.lazy-css').each(function () {
        var $tag = $(this);
        var newLink = document.createElement('link');
        newLink.rel = 'stylesheet';
        newLink.href = $tag.attr('href');
        newLink.type = 'text/css';
        var someLink = document.getElementsByTagName('link')[0];
        someLink.parentNode.appendChild(newLink);
    });
});