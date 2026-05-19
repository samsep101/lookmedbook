var CityController = function (city_id, latitude, longitude, alias) {

    var self = this;

    this.city_id = city_id;
    this.city_alias = alias;
    this.latitude = latitude;

    this.longitude = longitude;

    this.subscribes = [];

    this.getCurrentCityInfo = function () {
        return {
            city_id:self.city_id,
            latitude:self.latitude,
            longitude:self.longitude,
            city_alias:self.city_alias
        }
    };

    this.subscribe = function(callback){
        this.subscribes[this.subscribes.length] = callback;
    };


    this.setCityInfo = function(city_id, latitude, longitude, alias){
        self.latitude = latitude;
        self.longitude = longitude;
        self.city_id = city_id;
        self.city_alias = alias;

        if (self.subscribes.length)
        {
            for (i in self.subscribes){
                if (self.subscribes[i])
                    self.subscribes[i](self.getCurrentCityInfo());
            }
        }
    };
};