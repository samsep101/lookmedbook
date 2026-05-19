var DiseaseSearchResultsPageController = function () {

    this.query = '';
    this.page = 1;
    this.by_page = 10;

    var controller = this;

    this.init = function () {
        $(document).on('click', '.view-more', function () {
            setCounters('next-10', 'unknown', '', SessionInfo.email);
            $('.view-more i').addClass('icon-loader');
            controller.loadNextPage();
        });

        var disease_search_controller2 = new DiseaseQuickSearchFormController(0,0);

        disease_search_controller2.setInputElement($('.search-block .illness-search-input'));
        disease_search_controller2.setDrowDownContainer($('.search-block .drop-menu'));
        disease_search_controller2.setSubmitElement($('.search-block .illness-search-submit'));
        disease_search_controller2.init();
    };

    this.sendRequest = function () {
        Ajax.Get('/disease/moreSearchResults', {query:controller.query, page:controller.page}, function (data) {
            if (data.result.diseases){
                $('.view-more').remove();
                $('.illness-results-list').append(data.result.diseases);
            }
            if (data.result.next_page_button){
                $('.ilness-result').append(data.result.next_page_button);
            }
        });
    };

    this.loadNextPage = function () {
        controller.page = controller.page + 1;
        controller.disease_query = getParameterByName('query');
        controller.sendRequest();
    };
};