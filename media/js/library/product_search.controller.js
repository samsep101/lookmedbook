var ProductSearchController = function () {
    var self = this;

    this.product_category = null;
    this.is_leader = null;
    this.by_page = null;
    this.pattern = null;
    this.product_itself = null;
    /**
     *
     * @type ProductBasket
     */
    this.basket = null;

    this.init = function() {
        self.getProducts(self.product_category, self.is_leader, self.by_page, self.pattern);

        $(".load-next-page").click(function() {
            var page_number = $(".load-next-page");

            if(page_number.children("i").attr('class') != 'icon-loader') {
                page_number.html('<i class="icon-loader"></i>');
                self.getProducts(self.product_category, self.is_leader, self.by_page, self.pattern);
            }
        });

        var expanded_block = new ExpandedBlock();
        if(getCookie('products_expanded_block') == '1') {
            expanded_block.not_apply_onload = true;
        }
        expanded_block.block_container = $(".catalog_list");
        expanded_block.more_button_text = 'Показать все';
        expanded_block.max_height = 199;
        expanded_block.init();

        var more_link = $(".more-link");

        if(getCookie('products_expanded_block') == '1') {
            more_link.text('Скрыть');
        } else {
            more_link.text('Показать все категории');
        }

        more_link.css({'width': '190px', 'margin-left': '275px'});

        more_link.click(function() {
            if($(this).text() == 'Показать все') {
                setCookie("products_expanded_block", "0", "Mon, 01-Jan-2040 00:00:00 GMT", "/");
                $(this).text('Показать все категории');
                more_link.css({'width': '190px', 'margin-left': '275px'});
            } else {
                $(this).css({'width': '190px', 'margin-left': '275px'});
                setCookie("products_expanded_block", "1", "Mon, 01-Jan-2040 00:00:00 GMT", "/");
            }
        });

        $(".product-license-block .all-elements .show-all").click(function() {
            $(this).toggleClass('active');
            $(".product-license-container").slideToggle(150);
        });

        self.changeImages();
    };

    this.getProducts = function(product_category, is_leader, by_page, pattern) {
        var page_number = $(".load-next-page");
        var page_number_value = page_number.attr('data-page');

        Ajax.Post(
            '/shop/catalog/ajaxSearch',

            {
                product_category: product_category,
                is_leader: is_leader,
                page: page_number_value,
                by_page: by_page,
                pattern: pattern,
                product_itself: self.product_itself
            },

            function(data) {
                $(data.result.html).insertBefore(page_number);

                if(data.result.button_more_enable == '1') {
                    page_number.attr('data-page', parseInt(page_number_value) + 1);
                    page_number.html('<i></i>Показать еще');
                }
                else {
                    page_number.hide();

                    if(data.result.count == 0) {
                        $(".block_left h2").hide();
                    }
                }
            },

            'json'
        );
    };

    this.changeImages = function()
    {
        var image = $(".category-image");
        var indexes = [-12, -55, -98, -138, -180];

        for(var i = 0; i < image.length; i++) {
            image.eq(i).css('background-position', indexes[i%5] + 'px -9px');
        }
    };
};