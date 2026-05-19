var BasketItemController = function(basket)
{
    var self = this;

    self.product_id = null;
    self.price = null;
    /**
     *
     * @type ProductBasket
     */
    self.basket = basket;
    self.manufacturer = null;

    self.max_count = 0;

    self.spin_controller = null;
    self.container = null;
    self.max_count = null;

    self.name = '';

    self.timer = null;

    self.repair_block_container = null;
    self.repair_count = null;

    this.init = function()
    {
        self.timer = new SimpleTimer(500, function(){
            self.sendRequest();
            self.updateCard();
        });

        self.basket.subscribe('product_change', self.updateView);
        self.basket.subscribe('basket_clear', function(){
            self.repair_count = self.getCount();
            self.setCount(0);
        });

        $(self.container + ' span.del_good').click(function(){
            self.repair_count = self.getCount();
            self.setCount(0);
        });

        $(self.repair_block_container + ' .repair').click(function(){
            if(self.repair_count)
            {
                self.setCount(self.repair_count);
            } else {
                self.setCount(1);
            }
        });

        self.spin_controller = new SpinController();
        self.spin_controller.decrement_button_container = $(self.container + ' .decrement');
        self.spin_controller.increment_button_container = $(self.container + ' .increment');
        self.spin_controller.counter_view_container = $(self.container + ' .count');
        self.spin_controller.max_value = self.max_count;
        self.spin_controller.subscribe('counter_update', function(){
            self.timer.reset();
            self.updateCard();
        });
        self.spin_controller.init();

        self.updateView();
    };

    this.getCount = function()
    {
        return self.spin_controller.getCurrentValue();
    };

    this.setCount = function(count)
    {
        return self.spin_controller.setCount(count);
    };

    this.updateCard = function()
    {

        if(self.getCount() == 0)
        {
            $(self.repair_block_container).css('display', 'block');
            $(self.container).css('display', 'none');
        } else {
            $(self.repair_block_container).css('display', 'none');
            $(self.container).css('display', 'block');
            $(self.container + ' .total_price').html(self.price * self.getCount());
        }

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