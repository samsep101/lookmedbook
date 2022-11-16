var YandexMapController = function (form_controller) {
    this.container = 'map';

    this.map = null;
    this.cluster = null;
    this.collection = null;
    this.placemarks = [];
    this.span = null;
    this.bounds = null;

    this.latitude = null;
    this.longitude = null;

    this.dataUrl = null;

    this.form_controller = form_controller;

    this.mode = 'small';
    this.page = null;

    this.full_map = null;

    var controller = this;

    var bounds = [];

    this.city_id = null;

    this.clinics_count = null;
    this.filter_active = null;
    this.isset_region = null;

    var self = this;

    this.isLoaded = function () {
        return !!self.map && !!self.map.controller.projection;
    };

    this.setCoordinates = function(latitude, longitude) {

        self.latitude = latitude;
        self.longitude = longitude;

        self.bounds = [[latitude + 2, longitude - 2], [latitude - 2, longitude + 2]];

        if (self.getMap() != null)
        {
            self.setMapCenter(self.latitude, self.longitude);
        }
    };

    this.setCityId = function(city_id){
        self.city_id = city_id;
    };


    this.setCityInfo = function(city_info){
        self.latitude = city_info.latitude;
        self.longitude = city_info.longitude;
        self.city_id = city_info.city_id;
    };

    this.setBoundsFromPointAndDistance = function (lat, lng, distance) {
        var bounds = self.map.controller
            .getBoundsForPointAndDistance(lat, lng, distance || 2000);
        self.setBounds(bounds);
    };

    this.setBounds = function(bounds){
        if(self.getMap())
        {
            self.clinics_count = 2;

            var count_in_bounds = self.getCountInBounds(bounds);
            if((count_in_bounds < self.clinics_count) && self.map.getPoints().length >= self.clinics_count || self.isset_region)
            {
                var i = 0;
                while((i<1000) && (self.getCountInBounds(bounds) < self.clinics_count))
                {
                    bounds[0][0] -= 0.0005;
                    bounds[1][0] += 0.0005;
                    bounds[0][1] -= 0.0005;
                    bounds[1][1] += 0.0005;

                    if(self.getCountInBounds(bounds) >= self.clinics_count) {
                        break;
                    }

                    i++;
                }

                if(self.isset_region) {
                    while((bounds[1][0] - bounds[0][0]) < 0.045) {
                        bounds[0][0] -= 0.0005;
                        bounds[1][0] += 0.0005;
                        bounds[0][1] -= 0.0005;
                        bounds[1][1] += 0.0005;
                    }
                }
            }

            bounds[0][0] -= 0.00005;
            bounds[1][0] += 0.00005;
            bounds[0][1] -= 0.00005;
            bounds[1][1] += 0.00005;

            self.getMap().setBounds(bounds, {
                checkZoomRange: true
            });
        } else {
            setTimeout(function(){
                self.setBounds(bounds);
            }, 500)
        }
    };

    this.getCountInBounds = function(bounds)
    {
        var points = self.map.getPoints();

        var count = 0;

        for(var i in points)
        {
            var lat = points[i]['lat'];
            var lng = points[i]['lng'];
            var condition = ((lat >= bounds[0][0]) && (lat <= bounds[1][0]))
                               && ((lng >= bounds[0][1]) && (lng <= bounds[1][1]));

            if(condition)
            {
                count ++;
            }
        }

        return count;
    };

    this.init = function () {
        city_info = city_controller.getCurrentCityInfo();
        city_controller.subscribe(self.setCityInfo);
        var controllers = ('GNativeController,GCanvasController,YGeoObjectController,YNativeController,YCanvasController,YFullCanvasController,GFullCanvasController').split(',');
        var options = {
            controller:'YFullCanvasController',
            center:{ lat:parseFloat(city_info.latitude), lng:parseFloat(city_info.longitude)},
            zoom:12,
            debug:false,
            bounds: self.bounds
        };

        self.map = new CityMap($('#map')[0], options);
        this.initMap();

        if ($('.analysis-link').hasClass('active')){
            $(".map-box").height($(window).height());
            $(".map-box").width($(window).width());
            $("#map").height($(window).height());
            $("#map").width($(window).width());

            self.setDataUrl('/ajax/getLaboratoryMapCard');
        }

        if (self.full_map == 1) {
            self.resizeMap();
        }

        $(".map-box .resize").click(function (e) {
            self.resizeMap();
        });
    };

    this.resizeMap =function () {
        $('.top_number').hide();
        $('.breadcrumbs').hide();
        $('.ymaps-b-zoom__button_type_minus').trigger('click');

        if (self.mode == 'small') {
            $("body").addClass('hidden');

            if (self.form_controller.container == '#clinic-search-form') {
                self.setDataUrl('/ajax/getClinicMapCard?big=1&id=');
            }
            else if (self.form_controller.container == '#doctor-search-form') {
                self.setDataUrl('/ajax/getDoctorClinicCard?big=1&id=');
            }
            self.initMap();
            $('.doc-popup-sm').remove();
            var new_map_block = $('<div />', {
                'class': 'full-map',
                'style': 'height: 361px'
            });

            map_block = $('<div class="map-block"></div>');
            map_block.html($('#map'));
            new_map_block.html(map_block);
            //  new_map_block.append($('<div class="cards"></div>'));
            new_map_block.append($('<div class="bott-panel"><div class="shell"><span class="resize old_resize">Уменьшить</span><a href="#">Вернуться к результатам поиска</a></div></div>'));

            $('header').before(new_map_block);

            $('.map-box').css('margin-top','-4px');
            $('.map-box').css('display','none');
            //$('#our-doctors .item-row:first').clone().appendTo('.full-map .cards');
            $("footer").hide();

            $(".full-map .resize, .full-map .shell a").click(function (e) {
                e.preventDefault();
                $('.breadcrumbs').show();
                $("body").removeClass('hidden');
                if (self.form_controller.container == '#clinic-search-form') {
                    self.setDataUrl('/ajax/getClinicMapCard?big=0&id=');
                }
                else if (self.form_controller.container == '#doctor-search-form') {
                    self.setDataUrl('/ajax/getDoctorClinicCard?big=0&id=');
                }
                self.initMap();
                $('.ymaps-balloon-overlay .info-card').remove();
                $("footer").show();

                $('.search-form').css('margin-top','0px');
                $('.map-box').css('margin-top','0px');
                $('.map-box').css('display','block');

                $('.map-box > *:first').before($('#map'));
                $('#map').css({
                    width: '100%',
                    height: '472px'
                });
                if (self.map.controller.map)
                    self.map.controller.map.container.fitToViewport();
                $('.full-map').remove();
                self.mode = 'small';
            });

            $('.cards .info-card').mouseenter(function () {
                $(this).animate({top: '-150'});
            });
            $('.cards .info-card').mouseleave(function () {
                $(this).animate({top: '0'});
            });
            $('.cards .info-card').click(function (e) {
                e.preventDefault();
                $("body").removeClass('hidden');
                $("footer").show();
            });

            $(window).resize(function () {
                $('.full-map').height($(window).height());
                $('.full-map .map-block').height($(window).height()-91);
                $("#map").height($(window).height()-91);
                $("#map").width($(window).width());
            });

            $("#map").height($(window).height()-91);
            $("#map").width($(window).width());

            if (self.map.controller.map)
                self.map.controller.map.container.fitToViewport();
            $(window).resize();

            doctor_form_controller = new DoctorSearchFormController();
            doctor_form_controller.addColapse();
        }
    };

    this.getMap = function () {
        return self.map.controller.map;
    };

    this.setMapCenter = function (latitude, longitude) {
        var geo = {};
        geo.lat = latitude;
        geo.lng = longitude;
        self.map.controller.map.setCenter(geo, 12);
    };

    this.setLoader = function () {
        $('#map').css('opacity', '0.5');
    };

    this.removeLoader = function () {
        $('#map').css('opacity', '1');
    };

    this.setData = function (data) {
        self.map.setData(data);
        this.setViewRange();
    };

    this.setViewRange = function () {

        if (self.map.points.length > 0) {
            var max_latitude = 0;
            var min_latitude = 10000;
            var max_longitude = 0;
            var min_longitude = 10000;


            if (self.latitude)
            {
                min_latitude = max_latitude = self.latitude;
            }

            if (self.longitude)
            {
                min_longitude = max_longitude = self.longitude;
            }

            for (var i in self.map.points) {
                var point = self.map.points[i];

                if (typeof point.lat !== 'number')
                    continue;

                if (point.lat < min_latitude) {
                    min_latitude = point.lat;
                }

                if (point.lat > max_latitude) {
                    max_latitude = point.lat;
                }

                if (point.lng < min_longitude) {
                    min_longitude = point.lng;
                }

                if (point.lng > max_longitude) {
                    max_longitude = point.lng;
                }
            }


            if (self.getMap() && (min_longitude != 10000)) {
                self.getMap().setBounds([
                    [min_latitude, min_longitude],
                    [max_latitude, max_longitude]
                ], {
                    checkZoomRange: true
                });
            }
        }

    };

    this.setDataUrl = function (url) {
        self.dataUrl = url;
    };

    this.initMap = function () {
        self.map.setOptions({"sprite": {"src": "\/media\/images\/sprite4.png?1"}, "offset": {"x": 30, "y": 30}, "clusterdist": 20,
            "icons": [
                {"src": "", "title": "комната", "size": {"width": 9, "height": 9}, "anchor": {"x": 4, "y": 4}, "origin": {"x": 0, "y": 0},
                    "shadow": {"src": "", "size": {"width": 9, "height": 9}, "anchor": {"x": 3, "y": 3}, "origin": {"x": 81, "y": 0}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 0, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                },
                {"src": "", "title": "1-комнатная", "size": {"width": 9, "height": 9}, "anchor": {"x": 4, "y": 4}, "origin": {"x": 9, "y": 0},
                    "shadow": {"src": "", "size": {"width": 9, "height": 9}, "anchor": {"x": 3, "y": 3}, "origin": {"x": 81, "y": 0}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 26, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                },
                {"src": "", "title": "2-комнатная", "size": {"width": 9, "height": 9}, "anchor": {"x": 4, "y": 4}, "origin": {"x": 18, "y": 0},
                    "shadow": {"src": "", "size": {"width": 9, "height": 9}, "anchor": {"x": 3, "y": 3}, "origin": {"x": 81, "y": 0}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 52, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                },
                {"src": "", "title": "3-комнатная", "size": {"width": 9, "height": 9}, "anchor": {"x": 4, "y": 4}, "origin": {"x": 27, "y": 0},
                    "shadow": {"src": "", "size": {"width": 9, "height": 9}, "anchor": {"x": 3, "y": 3}, "origin": {"x": 81, "y": 0}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 78, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                },
                {"src": "", "title": "4-комнатная",
                    "size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 104, "y": 17},
                    "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 104, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                },
                {"src": "", "title": "5-комнатная", "size": {"width": 9, "height": 9}, "anchor": {"x": 4, "y": 4}, "origin": {"x": 45, "y": 0},
                    "shadow": {"src": "", "size": {"width": 9, "height": 9}, "anchor": {"x": 3, "y": 3}, "origin": {"x": 81, "y": 0}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 130, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                },
                {
                    "src": "",
                    "title": "6-комнатная",
                    "size": {"width": 9, "height": 9}, "anchor": {"x": 4, "y": 4}, "origin": {"x": 54, "y": 0},
                    "shadow": {"src": "", "size": {"width": 9, "height": 9}, "anchor": {"x": 3, "y": 3}, "origin": {"x": 81, "y": 0}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 156, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                }
            ],
            "cluster": {
                "icon": {"src": "", "title": "Несколько предложений рядом",
                    "size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 0, "y": 17},
                    "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 0, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                }
            },
            "group": {
                "icon": {"src": "", "title": "Несколько предложений по адресу",
                    "size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 104, "y": 17},
                    "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}},
                    "big": {"size": {"width": 26, "height": 32}, "anchor": {"x": 13, "y": 26}, "origin": {"x": 209, "y": 17},
                        "shadow": {"src": "", "size": {"width": 34, "height": 40}, "anchor": {"x": 17, "y": 30}, "origin": {"x": 235, "y": 10}}}
                }
            },
            "dataUrl": self.dataUrl,
            "schema": {"id": 0, "title": 1, "data": 2, "lat": 3, "lng": 4, "icon": 5}
        });
    };

    this.getPlacemarks = function()
    {
        return self.map.placeMarks;
    }

};
