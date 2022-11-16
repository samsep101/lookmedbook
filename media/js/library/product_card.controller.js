var ProductCardController  = function(basket, container)
{
    var self = this;
    this.product_id = null;
    this.price = null;

    /**
     * @type ProductBasket
     */
    this.basket = basket;
    this.container = container;

    this.timer = null;

    this.counter = null;

    this.max_count = null;

    this.spin_controller = null;

    this.init = function()
    {
        self.basket.subscribe('product_change_'+self.product_id, self.updateView);
        self.basket.subscribe('basket_clear', self.updateView);

        this.timer = new SimpleTimer(500, self.sendRequest);
        this.timer.stop();

        var spin_controller = new SpinController();
        spin_controller.decrement_button_container = self.container + ' .decrement';
        spin_controller.increment_button_container = self.container + ' .increment';
        spin_controller.counter_view_container = self.container + ' .count';
        spin_controller.max_value = self.max_count;
        spin_controller.subscribe('counter_update', function(){
            self.timer.reset();
            self.updateCard();
        });
        spin_controller.init();

        self.spin_controller = spin_controller;

        $(document).on('click', self.container + " .btn-w", function() {
            self.spin_controller.setCurrentValue(1);
            self.timer.reset();
            self.updateCard();
        });

        $(document).on('click', self.container + ' .delete-from-basket', function()
        {
            self.basket.deleteProduct(self.product_id, self);
            self.spin_controller.setCurrentValue(0);
            self.updateCard();
        });

        self.updateView(null, null);
    };

    this.updateCard = function()
    {
        var count = self.getCount();

        if(self.getCount() > 0) {
            $(self.container + " .btn-w").css('display', 'none');
            $(self.container + " .inbasket").css('visibility', 'visible');
            $(self.container + " .counter").show();
            $(self.container + " .count").show();
            $(self.container + " .total_price").text(self.price * self.getCount());
        }
        else if($(self.container + " .btn-w").css('display') == 'none' && (self.getCount() == 0)) {
            $(self.container + " .btn-w").css('display', 'block');
            $(self.container + " .inbasket").css('visibility', 'hidden');
            $(self.container + " .counter").hide();
            $(self.container + " .count").hide();
            $(self.container + " .total_price").text($(self.container + " .gprice").text());
        }
    };

    this.getCount = function()
    {
        return self.spin_controller.getCurrentValue();
    };

    this.setCount = function(value)
    {
        self.spin_controller.setCount(value);
    };

    this.updateView = function(event_type, element)
    {
        if(element == self)
            return;

        var info = self.basket.getProductInfoByProductId(self.product_id);
        var count = (info != null) ? info.count : 0;
        self.spin_controller.setCurrentValue(count);
        self.updateCard();
    };

    this.sendRequest = function()
    {
        self.basket.setProductCount(self.product_id, self.getCount(), self);
    };
};