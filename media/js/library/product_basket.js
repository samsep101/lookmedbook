var ProductBasket = function () {
    var self = this;

    this.products = [];
    this.subscribes = [];


    this.setProducts = function (products) {
        self.products = products;
        self.runEvent('product_change');
    };

    this.init = function () {

    };

    this.setProductCount = function (product_id, count, element) {

        self.products[product_id] = self.products[product_id] = {
            count:count,
            product_id:product_id
        };

        var data = {
            product_id:product_id,
            count:count
        };

        Ajax.Post('/shop/basket/ajaxAddProduct', data, function (data) {
            if (data.status == 0) {
                self.products[product_id] = {
                    count:count,
                    product_id:product_id,
                    price: parseInt(data.result.price)
                };

                self.runEvent('product_change_' + product_id, element);
                self.runEvent('product_change', element);
            } else {
                // что-то пошло не так
            }
        });

    };

    this.getProductInfoByProductId = function (product_id) {
        // если потребуются какие-либо данные, кроме id, имени и количества
        // необходимо в данном методе реализовать выборку данных из базы
        var info = self.products[product_id];

        if (info) {
            return info;
        } else {
            return null;
        }
    };

    this.getProductList = function () {
        return self.products;
    };

    this.deleteProduct = function (product_id, element) {
        self.setProductCount(product_id, 0, element);
    };

    this.clearBasket = function () {
        this.products = [];
        self.runEvent('basket_clear');
    };

    this.subscribe = function (event, callback) {
        if (self.subscribes[event] == undefined) {
            self.subscribes[event] = [];
        }

        self.subscribes[event].push(callback);
    };

    this.runEvent = function (event, element) {
        if (self.subscribes[event]) {
            for (var i in self.subscribes[event]) {
                self.subscribes[event][i](event, element);
            }
        }
    };

    this.getTotalCount = function()
    {
        var count = 0;

        for(var i in self.products)
        {
            count += parseInt(self.products[i]['count']);
        }
        return count;
    };

    this.getTotalPrice = function()
    {
        var price = 0;

        for(var i in self.products)
        {
            price += parseInt(self.products[i]['count']) * parseFloat(self.products[i]['price']);
        }

        return (!isNaN(price)) ? price : 0;
    };

};

var product_basket = new ProductBasket();
