var OrderFormController = function (product_basket) {
    var self = this;

    this.order_products = [];

    this.order_id = null;
    this.shipping_type = 1;

    /**
     * @type ProductBasket
     */
    this.basket = product_basket;

    this.blocked_flag = false;

    /**
     * @type ShippingCostAlgorithm
     */
    this.shipping_cost_algorithm = null;

    this.data = {
        shipping_type_id : null,
        shipping_cost : null,
        address : null,
        name : null,
        phone_number : null,
        email : null,
        comment : null
    };

    this.check_id = null;

    this.init = function () {
        this.order_products = self.basket.getProductList();

        this.shipping_cost_algorithm = new ShippingCostAlgorithm(self.basket);


        $(function() {
          $(".magazine input[name='phone_number']").inputmask("+7-999-999-99-99");
        });


        $('.order_tabs .further').validation({
            validate : [
                $('textarea[name="address"]').validate(validation_rules['shipping_address'])
            ],
            callback : function(){
                self.data.comment = $('textarea[name="address"]').val();
                self.data.shipping_type_id = $('input[name="shipping_type_id"]').val();
                self.data.address = $('textarea[name="address"]').val();
                self.toStep2();
            }
        });

        $('.order_ready').validation({
            validate : [
                $('input[name="name"]').validate(validation_rules['first_name']),
                $('input[name="phone_number"]').validate(validation_rules['user_phone']),
                $('input[name="email"]').validate(validation_rules['correct_email'])
            ],
            callback: function(){
                self.makeOrder();
            }
        });

        $('input.styled').radio();
        $('.order_tabs .for_radio span').first().trigger('click').parents('.for_radio').next().show().find('.for_radio_child').first().find('span').first().trigger('click');
        $('.for_radio span').on('click',function(){
            if ($('.for_radio input[type="radio"]:checked') && !$(this).parent().children("input").prop('disabled')) {
                $('.radio_hide').hide();
                $(this).parents('.for_radio').next().show();
                $(this).parents('.for_radio').next().find('.for_radio_child').first().find('span').trigger('click');
            }

        });

        $('span.radio, span.for_radio').click(function(){
            if(!$(this).parent().children("input").prop('disabled')) {
                //$('input[type="radio"]').removeAttr('checked');
                $(this).parent().children('input[type="radio"]').attr('checked', 'checked');
                self.data.shipping_type_id = parseInt($('input[name="shipping_type_id"]:checked').val());
                self.shipping_type = $(this).parent().children("input").attr('data-id');
                self.updateShippingCost();
            }
        });

        $(".tarif_link").click(function() {
            location.href = '/shop/catalog/payments';
            return false;
        });

        self.data.shipping_type_id = parseInt($('input[name="shipping_type_id"]:checked').val());
        self.updateShippingCost();

        if(self.shipping_type) {
            self.initShippingType();
        }
    };

    this.makeOrder = function()
    {
        if(self.blocked_flag)
            return;

        var inputs = $('input[type="radio"][checked="checked"]');

        self.data.name = $('input[name="name"]').val();
        self.data.phone_number = $('input[name="phone_number"]').val();
        self.data.email = $('input[name="email"]').val();
        self.data.comment = $('textarea[name="comment"]').val();
        self.data.shipping_type_id = inputs.eq(inputs.length - 1).attr('data-id');

        var data = {
            'order_info' : self.data,
            'order_products' : self.order_products,
            'check_id' : self.check_id
        };

        self.blocked_flag = true;
        Ajax.Post('/shop/basket/ajaxOrder', data, function(data){
            if(data.status == 0)
            {
                self.order_id = data.result.system_code;
                $('.order-number').html(self.order_id);
                self.toStep3();
            } else {
                if(data.status == 144)
                {
                    self.blocked_flag = false;

                    var check_phone_controller = new ConfirmPhoneController();
                    check_phone_controller.phone_number = self.data.phone_number;
                    check_phone_controller.setSuccessCallback(function(){
                        self.check_id = check_phone_controller.check_id;
                        self.makeOrder();
                    });
                    check_phone_controller.tmp_init();
                }
            }
        });
    };

    this.updateShippingCost = function()
    {
        var cost = self.getShippingCost(self.data.shipping_type_id);

        var total_cost = self.basket.getTotalPrice();
        if((cost != 'by_tarif') && (cost != null))
        {
            total_cost += cost;
            self.data.shipping_cost = cost;
        } else {
            self.data.shipping_cost = null;
        }

        $('.order_all_p').html(total_cost);

        $('.good_inbasket li.shipping').remove();

        if(cost == null)
            return;

        if(cost == 0) {
            cost = 'бесплатно';
        } else if (cost == 'by_tarif') {
            cost = 'согласно <a href="http://www.emspost.ru/ru/calc/" target="_blank">тарифам</a>'
        } else {
            cost += ' р.';
        }

        var struct = $('<li class="shipping">' +
            '<span class="namegood">Доставка</span>' +
            '<span class="pricegood">' +
            '<span class="order_n"></span><span class="order_p">' +
            cost +
            '</span>' +
            '</li>');

        $('.good_inbasket').append(struct);
    };

    this.getShippingCost = function(shipping_type_id)
    {
        return this.shipping_cost_algorithm.getCost(shipping_type_id);
    };

    this.toStep2 = function () {
        $('.order_tabs').find('ul li').first().removeClass('active');
        $('.order_tabs').find('ul li').first().addClass('stepgood');
        $('.forstep1').hide();
        $('.forstep2').show();
        $('.order_tabs').find('ul li').next().addClass('active');
        $('.order_tabs .box .section').first().hide();
        $('.order_tabs .box .section').first().next().show();
    };

    this.toStep3 = function(){
        $('.order_tabs .section').hide();
        $('.order_tabs .section.step_fin').addClass('flo').show();
        $('.forstep_fin').show();
        $('.order_tabs').find('ul li').removeClass('active').addClass('stepgood');
        $(".n_goods").hide();
    };

    this.initShippingType = function() {
        var radio_label_1 = null;
        var radio_label_2 = null;

        switch(self.shipping_type) {
            case 2:
                radio_label_1 = $('[for = "rb3"]');
                radio_label_1.trigger('click');
                break;

            case 4:
                radio_label_1 = $('[for = "rb4"]');
                radio_label_1.trigger('click');
                break;

            case 5:
                radio_label_1 = $('[for = "rb4"]');
                radio_label_2 = $('[for = "rb6"]');
                radio_label_1.trigger('click');
                radio_label_2.trigger('click');
                break;

            case 6:
                radio_label_1 = $('[for = "rb7"]');
                radio_label_1.trigger('click');
                break;
        }
    };
};