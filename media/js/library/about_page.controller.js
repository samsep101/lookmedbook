var AboutPageController = function(city_id) {
    var self= this;

    this.city_id = city_id;
    this.map_controller = null;

    this.init = function() {
        self.addMap();
    };

    this.addMap = function() {
        self.map_controller = new YandexMapController('about_page');
        self.map_controller.init();

        if (self.map_controller != undefined) {
            Ajax.Get('/ajax/getAboutMapAddressData', {}, function (data) {
                //self.map_controller.setData(data.result);
                var map = self.map_controller.getMap();
                var placemark = new ymaps.Placemark([48, 40], {
                    balloonContent: '<img src="//img-fotki.yandex.ru/get/6114/82599242.2d6/0_88b97_ec425cf5_M" />',
                    iconContent: "Азербайджан"
                }, {
                    preset: "twirl#yellowStretchyIcon",
                    // Отключаем кнопку закрытия балуна.
                    balloonCloseButton: false,
                    // Балун будем открывать и закрывать кликом по иконке метки.
                    hideIconOnBalloonOpen: false
                });

                //не нашел, где этот скрипт выполняется, не смог проверить, так ли сюда приходит data
                var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                    '<div class="flag_address">'+data[1]+'</div>'
                );
                ymaps.layout.storage.add('my#theaterlayout', myBalloonLayout);
                var balloon = new ymaps.Balloon(map, null, {
                    contentLayout: myBalloonLayout,
                    closeButton : false,
                    offset : [140, 50]
                });
                balloon.options.setParent(map.options);
                balloon.open([data[3],data[4]]);

                //$(".ymaps-b-balloon.ymaps-i-custom-scroll").text(123);
            });
        }



    };
}