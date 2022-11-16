var ProductViewCardController = function(basket)
{
    var self = this;
    this.product_id = null;
    this.price = null;

    /**
     * @type ProductBasket
     */
    this.basket = basket;

    this.counter = 0;
    this.container = ".good.flo";

    this.timer = null;
    this.max_count = null;
    this.spin_controller = null;

    this.init = function()
    {
        self.price = parseInt($(self.container + " .gprice").text());

        self.basket.subscribe('product_change_'+self.product_id, self.updateView);
        self.basket.subscribe('basket_clear', self.updateView);

        this.timer = new SimpleTimer(500, self.sendRequest);
        this.timer.stop();

        //self.updateView();

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

        $(document).on('click', self.container + " .btn-appoint", function() {
            self.spin_current_counter = self.spin_controller.getCurrentValue();
            if (self.spin_current_counter < 1) {
                self.spin_controller.setCount(1);
            } else {
                self.spin_controller.setCount(self.spin_current_counter);
            }
            $(self.spin_controller.decrement_button_container).removeClass('disabled');
        });

        $('.good_info .left_nav').each(function () {
            $(this).find('li').first().addClass('active');
            $(this).find('li').each(function (i) {
                $(this).click(function () {
                    $(this).addClass('active').siblings().removeClass('active')
                        .parents('.good_info').find('.goods_txt').eq(i).css('display','table-cell').siblings('.goods_txt').hide();
                });
            });
        });

        $(".good .foto img").load(function() {
            var margin_top = (200 - $(this).height())/2;
            var margin_left = (186 - $(this).width())/2;

            if(margin_top > 0) {
                $(this).css('margin-top', margin_top);
            }

            if(margin_left > 0) {
                $(this).css('margin-left', margin_left);
            }
        });

        self.updateView();
    };

    this.updateCard = function()
    {
        var count = self.spin_controller.getCurrentValue();
        if(count >= 1) {
            var pr_g = $(self.container + " .product_price .gprice").text();
            $(self.container + " .btn-appoint").hide();
            $(self.container + " .numeric").show();
            $(self.container + " .goood_totalp").show();
            $(self.container + " .goood_totalp .total_price").html(count*pr_g);
            //$(self.container + " .count").html(count);
            self.spin_controller.block_buy = false;
        }
        else {
            $(self.container + " .btn-appoint").show();
            $(self.container + " .goood_totalp").hide();
            self.spin_controller.block_buy = true;
        }

    };

    this.updateView = function(event_type, element)
    {
        if(element == self)
            return;

        var info = self.basket.getProductInfoByProductId(self.product_id);
        var count = (info != null) ? info.count : 0;
        self.counter = count;
        self.spin_controller.setCurrentValue(count);
        self.updateCard();

        if (self.spin_controller.getCurrentValue() == 0) {
            self.spin_controller.setCurrentValue(1);
        }
    };

    this.sendRequest = function()
    {
        self.basket.setProductCount(self.product_id, self.spin_controller.getCurrentValue(), self);
    };
};