(function ($) {
    $.fn.ymap = function (params) {

        var canvasId = this.attr('id');

        if (canvasId.length < 1) {
            return false;
        }

        var map;

        ymaps.ready(function () {
            var map = new ymaps.Map(canvasId, params.map);
            // bind map toggling
            $('button', params.toggler).on('click', function () {
                var status = $(this).parent().attr('data-toggler');
                if (status == 'small') {
                    map.destroy();
                    $(this).parent().attr('data-toggler', 'large');
                    $('#' + canvasId).attr('data-map', 'large');
                    map = new ymaps.Map(canvasId, params.map);
                } else if (status == 'large') {
                    map.destroy();
                    $(this).parent().attr('data-toggler', 'small');
                    $('#' + canvasId).attr('data-map', 'small');
                    map = new ymaps.Map(canvasId, params.map);
                }
            });
        });

    }
})(jQuery);