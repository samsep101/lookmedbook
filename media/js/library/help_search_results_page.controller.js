var HelpSearchResultsPageController = function () {

    this.help_query = '';
    this.page = 1;
    this.by_page = 10;

    var controller = this;

    this.init = function () {
        $(document).on('click', '.view-more', function () {
            setCounters('next-10', 'unknown', '', SessionInfo.email);
            $('.view-more i').addClass('icon-loader');
            controller.loadNextPage();
        });

        var help_search_controller = new HelpQuickSearchFormController();

        help_search_controller.setInputElement($('.search-block .txt'));
        help_search_controller.setDrowDownContainer($('.search-block .drop-menu'));
        help_search_controller.setSubmitElement($('.search-block .btn-1'));
        help_search_controller.init();
    };

    this.sendRequest = function () {
        Ajax.Get('/help/ajaxMoreSearchResults', {help_query:controller.help_query, page:controller.page}, function (data) {
            if (data.result.materials) {
                $('.view-more').remove();
                $('.illness-results-list').append(data.result.materials);
            }
            if (data.result.next_page_button)
                $('.ilness-result').append(data.result.next_page_button);
        });
    };

    this.loadNextPage = function () {
        controller.page = controller.page + 1;
        controller.help_query = getParameterByName('help_query');
        controller.sendRequest();
    };
};