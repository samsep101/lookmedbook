
var simplemap = null;
var MapController = function () {

    var self = this;

    this.map = null;

    this.latitude = null;
    this.longitude = null;


    this.setLatitude = function(val){
        self.latitude = val;
    };

    this.setLongitude = function(val){
        self.longitude = val;
    };

    this.init = function (container) {
        if (!(window.ymaps)) {
            initFunc = '_MapController' + Math.round(Math.random() * 1000);
            window[initFunc] = function () {
                self.init.call(self, container);
            };
            $.getScript("https://api-maps.yandex.ru/2.0/?load=package.full,package.clusters,package.overlays" +
                "&lang=ru-RU&onload=" + encodeURIComponent(initFunc));

            return;
        }
        self.map = new ymaps.Map(container, {
            behaviors:['default', 'drag'],
            center: [self.latitude, self.longitude],
            type:"yandex#map",
            zoom:15
        });

        var zoomOptions = $('#' + container).data('zoom-options') || {};

        self.map.controls.add(
            new ymaps.control.ZoomControl(zoomOptions)
        );
    simplemap = self.map;
        var myPlacemark = new ymaps.GeoObject({
            geometry: {
                type: "Point",
                coordinates: [self.latitude, self.longitude]
            }});

        this.map.geoObjects.add(myPlacemark);
    };

    this.setMapCenter = function (latitude, longitude) {
        this.map.setMapCenter([latitude, longitude]);
    }
};