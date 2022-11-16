var ProductLiveSearchController = function(pattern) {
    var self = this;

    this.site_url = null;
    this.pattern = pattern;

    this.init = function() {
        $(".btn-1").prop('disabled', true);

        $(".txt").keypress(function(e) {
            if($(this).val().length < 3 && e.keyCode == '13') {
                e.preventDefault();
            }
        });

        $(".txt").keyup(function() {
            self.getString();
        });

        $(document).on('click', ".magazine .drop-menu li a", function() {
            var id = $(this).attr('data-id');
            var query = $(".search-block .txt").val();

            self.redirectWithLog(id, query);
        });
    };

    this.getString = function() {
        var str = $(".txt").val();

        if(str.length > 2) {
            self.getProductsLive(str);
            $(".btn-1").prop('disabled', false)
        }
        else {
            $(".drop-menu").eq(1).hide();
            $(".btn-1").prop('disabled', true);
        }
    };

    this.getProductsLive = function(pattern) {
        var container = $(".drop-menu").eq(1);

        Ajax.Post(
            '/shop/catalog/ajaxLiveSearch',

            {
                pattern: pattern
            },

            function(data) {
                if(data.result.success == 1) {
                    container.html(data.result.html);

                    if(container.css('display') == 'none') {
                        container.show();
                    }
                }
                else {
                    container.hide();
                }
            },

            'json'
        );
    };

    this.redirectWithLog = function(id, query) {
        Ajax.Post(
            '/shop/catalog/ajaxSearchLog',

            {query: query},

            function() {
                window.location = '/shop/product/' + id;
            },

            'json'
        );
    };
}