var BasketInfoController = function(basket)
{
    var self = this;

    this.container = $(".good_basket");
    this.basket = basket;

    this.init = function()
    {
        self.basket.subscribe('update_basket', self.updateBasket);
        self.basket.subscribe('clear_basket', self.clearBasket);
    };

    this.updateBasket = function()
    {
        if(self.container.css('display') == 'none') {
            self.container.fadeIn(600);
        }

        var total_count = self.basket.getTotalCount();
        $(".basket_n").text(total_count);
        $(".product_word").text(self.productAmountHelper(total_count));
        $(".basket_p").text(self.basket.getTotalPrice());
    };

    this.clearBasket = function()
    {
        self.container.fadeOut(600);
    };

    this.productAmountHelper = function(count)
    {
        var end = '';

        if(count % 100 > 9 && count % 100 < 21 || count % 10 == 0 || count % 10 > 4) {
            end = 'ов';
        }
        else {
            if(count % 10 > 1 && count % 10 < 5) {
                end = 'а';
            }
        }

        return 'товар' + end;
    };
};