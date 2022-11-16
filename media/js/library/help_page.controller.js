var HelpPageController = function () {

    this.init = function () {
        // вещаем обработчики на кнопки
        var controller = this;
        $( "#tabs" ).tabs();

        var help_search_controller = new HelpQuickSearchFormController();

        help_search_controller.setInputElement($('.search-block .txt'));
        help_search_controller.setDrowDownContainer($('.search-block .drop-menu'));
        help_search_controller.setSubmitElement($('.search-block .btn-1'));
        help_search_controller.init();

        $(document).on('click', '.nav ul li a', function () {
            $(".sub-menu").each(function () {
                $(this).children().removeClass('active');
            });
            $(".sub-menu").each(function () {
                $(this).find(':first-child').addClass('active');
            });
        });
        $(".sub-menu").each(function () {
            $(this).find(':first-child').addClass('active');
        });

    };
}