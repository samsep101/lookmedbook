var ShippingCostAlgorithm = function(basket)
{
    var self = this;

    /**
     * @type ProductBasket
     */
    this.basket = basket;

    this.init = function()
    {

    };

    this.getCost = function(shipping_type_id)
    {
        var total_price = self.basket.getTotalPrice();

        if(total_price < 500)
        {
            return null;
        }

        var result = null;
        switch(shipping_type_id)
        {
            case ShippingType.MOSCOW_BUTOVO:
                if(total_price < 900)
                {
                    result = 150;
                } else {
                    result = 0;
                }
                break;
            case ShippingType.REGION:
                result = 300;
                break;
            case ShippingType.TO_10KM:
                if(total_price < 900)
                {
                    result = null;
                } else {
                    result = 250;
                }
                break;
            case ShippingType.FROM_10KM:
                if(total_price < 900)
                {
                    result = null;
                } else {
                    result = 400;
                }
                break;
            case ShippingType.EMS_TO_1KG_ADMIN_CENTRE:
                if(total_price < 2000)
                {
                    result = null;
                } else {
                    result = 499;
                }
                break;
            case ShippingType.EMS_TO_1KG_OTHER:
                if(total_price < 2000)
                {
                    result = null;
                } else {
                    result = 649;
                }
                break;
            case ShippingType.EMS_FROM_1KG:
                if(total_price < 2000)
                {
                    result = null;
                } else {
                    result = 'by_tarif';
                }
                break;
        }

        return result;
    };
};


var ShippingType = function()
{

};

ShippingType.MOSCOW_BUTOVO = 1;
ShippingType.REGION = 2;
ShippingType.TO_10KM = 4;
ShippingType.FROM_10KM = 5;
ShippingType.EMS = 6;
ShippingType.AUTO = 7;
ShippingType.BY_SELF = 8;

ShippingType.EMS_TO_1KG_ADMIN_CENTRE = 9;
ShippingType.EMS_TO_1KG_OTHER = 10;
ShippingType.EMS_FROM_1KG = 11;