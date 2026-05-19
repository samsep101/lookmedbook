var BasketBlockController = function(basket)
{
    var self = this;

    /**
     *
     * @type ProductBasket
     */
    this.basket = basket;

    this.container =  null;

    this.init = function()
    {
        self.basket.subscribe('product_change', self.updateView);
        self.updateView();

        $(".good_basket .btn-appoint").click(function() {
            if(!$(this).attr('onclick')) {
                self.setError($(this), 'Минимальный заказ от 500 р.');
            }
        });
    };

    this.updateView = function()
    {
        var count = self.basket.getTotalCount();
        var price = self.basket.getTotalPrice();

        if(count > 0)
        {
            if(price < 500) {
                $(".basket-limit").show();
                $(".order_btn").removeAttr('onclick');
            } else {
                $(".basket-limit").hide();
                $(".order_btn").attr('onclick', "window.location='/shop/basket/order'");
            }

            $(self.container).css('display', 'block');
            $(self.container + ' .basket_n').html(count);
            $(self.container + ' .basket_p').html(price);
            $(self.container + ' .product_word').html(ProductAmountHelper.getForm(count));
        } else {
            $(self.container).hide();
        }
    };

    this.setError = function (el, message) {
        var error = $('<span class="error_span"><label>' + message + '</label></span>');
        var pos = el.position();

        error.css({
            top: 70,
            right: -21
        });
        error.delay(2000).fadeOut(1000);
        $('.error_span').remove();
        el.after(error);
    };
};

var ProductAmountHelper = {};

ProductAmountHelper.getForm = function(count)
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

    return 'товар' + end;
};