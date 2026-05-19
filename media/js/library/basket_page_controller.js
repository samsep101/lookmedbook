var BasketPageController = function(basket)
{
    var self = this;
    /**
     *
     * @type ProductBasket
     */
    this.basket = basket;

    this.init = function()
    {
        self.basket.subscribe('product_change', function(){
            self.updateTotalPrice();
        });
        self.basket.subscribe('basket_clear', function(){
            self.updateTotalPrice();
        });

        $('.basket_clear').click(function(){
            self.clearBasket();
        });

        $(".basket_head .btn-appoint").click(function() {
            if(!$(this).attr('onclick')) {
                self.setError($(this), 'Минимальный заказ от 500 р.',-36,-30);
            }
        });
        $(".total_line .btn-appoint").click(function() {
            if(!$(this).attr('onclick')) {
                self.setError($(this), 'Минимальный заказ от 500 р.',-28,-50);
            }
        });

        self.updateTotalPrice();
    };

    this.clearBasket = function()
    {
        self.basket.clearBasket();
    };

    this.updateTotalPrice = function()
    {
        var price = self.basket.getTotalPrice();
        var count = self.basket.getTotalCount();

        if(price < 500) {
            $(".basket-limit").show();
            $(".btn-appoint").removeAttr('onclick');
        } else {
            $(".basket-limit").hide();
            $(".btn-appoint").attr('onclick', "window.location='/shop/basket/order'");
        }

        $('.count_all_good').html(count);
        $('.all_count_word').html(self.productAmountHelper(count));
        $('.price_all_good').html(price);

        $('.product_word').html(ProductAmountHelper.getForm(count));
    };

    this.setError = function (el, message, y, x) {
        var error = $('<span class="error_span"><label>' + message + '</label></span>');
        var pos = el.position();

        error.css({
            top: y,
            right: x
        });
        error.css('display', 'block').delay(2000).fadeOut(1000);
        $('.error_span').remove();
        el.after(error);
    };

    this.productAmountHelper = function(count)
    {
        var end = '';

        if (count % 100 > 9 && count % 100 < 21 || count % 10 == 0 || count % 10 > 4) {
            end = 'ов';
        }
        else {
            if (count % 10 > 1 && count % 10 < 5) {
                end = 'а';
            }
        }

        return ' товар' + end + ' на сумму ';
    };
};