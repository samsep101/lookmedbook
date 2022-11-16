var ProductCountBlockController = function(basket, container)
{
    var self = this;

    /**
     *
     * @type ProductBasket
     */
    this.basket = basket;
    this.container = container;

    this.init = function()
    {
        self.basket.subscribe('product_change', self.updateView);
        self.updateView();

        $(".n_goods").click(function() {
            location.href = '/shop/basket';
            return false;
        });
    };

    this.updateView = function()
    {
        var count = self.basket.getTotalCount();

        if(count > 0)
        {
            $(self.container).show();
            if($('nav .shop-link').hasClass('active')) {
                $('nav .shop-link.active').next('.n_goods').hide();
            } else {
                $('nav .shop-link').find('.n_goods').hide();
                $('nav .shop-link').next('.n_goods').show();
            }
            $(self.container).text(count);
        } else {
            $(self.container).hide();
        }
    };
};