/**
 * Small popuper plugin
 *
 * @author Zmicier Aliakseyeu <z.aliakseyeu@gmail.com>
 */

(function ($) {
    Popuper = function (params) {

        /**
         * @type object
         */
        var param = params;

        /**
         * @type String
         */
        this.window = '__window';

        this.closeBtn = '.window__close';
    }

    $.fn.popup = function (params) {
        var popup = new Popuper(params);

        if (undefined !== params.show && params.show != true) {
            $(params.element).addClass('hidden');
            $(params.element + popup.window).addClass('hidden');
        }


        if (this.length > 0) {
            this.on('click', function () {
                $(params.element).removeClass('hidden');
                $(params.element + popup.window).removeClass('hidden');
            });
        }

        $(popup.closeBtn).on('click', function () {
            $(params.element).addClass('hidden');
            $(params.element + popup.window).addClass('hidden');
        });
    }
})(jQuery);