<script type="text/javascript">
    ymaps.ready(function () {
        // Создание экземпляра карты и его привязка к созданному контейнеру
        var map = new ymaps.Map("YMapsID", {
            center: [55.751574, 37.573856],
            zoom: 9,
            behaviors: ['default']
        });

        // Создание кнопки определения местоположения
        var button = new GeolocationButton({
            data: {
                image: 'wifi.png',
                title: 'Определить местоположение'
            },
            geolocationOptions: {
                enableHighAccuracy: true // Режим получения наиболее точных данных
            }
        }, {
            // Зададим опции для кнопки.
            selectOnClick: false
        });

        map.controls.add(button, { top: 5, left: 5 });
    });
</script>
<style type="text/css">
    #YMapsID {
        width: 900px;
        height: 400px;
    }
</style>

<div style="margin-left: 30%">
    <p>Использование Geolocation API.</p>
    <div id="YMapsID">

    </div>
</div>