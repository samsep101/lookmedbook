var OrdersPageController = function() {
    var self = this;

    this.init = function() {
        self.getOrders();

        $(".load-next-page").click(function() {
            var page_number = $(".load-next-page");

            if(page_number.children("i").attr('class') != 'icon-loader') {
                page_number.html('<i class="icon-loader"></i>');
                self.getOrders();
            }
        });
    };

    this.getOrders = function() {
        var page_number = $(".load-next-page");
        var page_number_value = page_number.attr('data-page');
        var page_number_year = page_number.attr('data-year');

        Ajax.Post(
            '/account/ajaxGetOrders',

            {
                page: page_number_value,
                year: page_number_year
            },

            function(data) {
                $(data.result.html).insertBefore(page_number);

                if(data.result.button_more_enable == '1') {
                    page_number.attr('data-page', parseInt(page_number_value) + 1);
                    page_number.attr('data-year', data.result.year);
                    page_number.html('<i></i>Показать еще');
                }
                else {
                    page_number.hide();
                }
            },

            'json'
        );
    };

    this.changeStatus = function(element) {
        Ajax.Post(
            '/account/ajaxChangeOrderStatus',

            {
                id: element.attr('data-id'),
                status: element.attr('data-status_id')
            },

            function(data) {
                element.attr('data-status_id', data.result.status);
                element.text(data.result.value);
            },

            'json'
        );
    };
};