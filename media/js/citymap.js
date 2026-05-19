var ctx = null;
var gl_control;

function extend(Child, Parent) {
    var F = function () {
    };
    F.prototype = Parent.prototype;
    Child.prototype = new F();
    Child.prototype.constructor = Child;
    Child.superclass = Parent.prototype;
}

YController = function (model, options) {
    this.map = null;
    this.model = model;
    this.maxZoom = 22;
    this.logger = model.logger;
    this.projection = null;
    this.initMap(options);
    this.searchControl = null;
    this.hide_balloon = true;

};
YController.prototype.getMap = function () {
    return this.map;
}

YController.prototype.getSearchControl = function () {
    return this.searchControl;
}

YController.prototype.initMap = function (options) {
    if (!(window.ymaps)) {
        var self = this;
        this.logger.start('mapLoadTime');
        initFunc = '_citymap' + Math.round(Math.random() * 1000);
        window[initFunc] = function () {
            ymaps.load(['pane.movable.StepwisePane', 'geometry.pixel.Point', 'overlay.staticGraphics.Placemark', 'layer.tileContainer.DomContainer'], function () {
                self.initMap.call(self, options)
            });
        };
        // todo mode=standard
        $.getScript("https://api-maps.yandex.ru/2.0/?load=package.full,package.clusters,package.overlays" +
            "&lang=ru-RU&onload=" + encodeURIComponent(initFunc));


        return;
    }
    this.projection = CityMap.Projection(CityMap.Projection.wgs84Mercator);
    var self = this;
    ymaps.ready(function () {
        ymaps.load(['pane.movable.StepwisePane', 'geometry.pixel.Point', 'overlay.staticGraphics.Placemark', 'layer.tileContainer.DomContainer'], function () {
            self.map = new ymaps.Map(self.model.container.id, {
                behaviors:['default', 'drag', 'multiTouch'],
                center:[ options.center.lat, options.center.lng],
                type:"yandex#map",
                zoom:options.zoom});
            window.map = self.map;
            self.map.container.fitToViewport();

            if (options.bounds == null) {
                options.bounds = [
                    [56.349122, 36.589145],
                    [54.945227, 39.648837]
                ];
            }

            self.searchControl = new ymaps.control.SearchControl({
                provider:'yandex#map',
                boundedBy:options.bounds,
                strictBounds:true,
                resultsPerPage:5,
                noPlacemark:true,
                width:400
            });

            self.map.behaviors.disable('multiTouch');

            self.map.controls.add('zoomControl');

            self.logger.end('mapLoadTime');
            self.balloon = self._getBalloon();

            ymaps.layout.storage.add('my#placemark', ymaps.templateLayoutFactory.createClass(
                '<div style="background: url(' + '$[properties.icon.src]' + ') -$[properties.icon.origin.x]px -$[properties.icon.origin.y]px no-repeat;' +
                    'position: relative;' +
                    'width: $[properties.icon.size.width]px; ' +
                    'height: $[properties.icon.size.height]px;' +
                    'left: -$[properties.icon.anchor.x]px;' +
                    'top: -$[properties.icon.anchor.y]px;"></div>', {}));
            ymaps.layout.storage.add('my#shadow', ymaps.templateLayoutFactory.createClass(
                '<div style="background: url(' + '$[properties.icon.src]' + ') -$[properties.icon.shadow.origin.x]px -$[properties.icon.shadow.origin.y]px no-repeat;' +
                    'position: relative;' +
                    'width: $[properties.icon.shadow.size.width]px; ' +
                    'height: $[properties.icon.shadow.size.height]px;' +
                    'left: -$[properties.icon.shadow.anchor.x]px;' +
                    'top: -$[properties.icon.shadow.anchor.y]px;"></div>', {}));
            // hint layouts
            ymaps.layout.storage.add('my#markerlayout', ymaps.templateLayoutFactory.createClass('<b>$[title]</b>'));
            ymaps.layout.storage.add('my#grouplayout', ymaps.templateLayoutFactory.createClass('<p><b>$[title]</b></p>Объектов: $[count]'));
            ymaps.layout.storage.add('my#clusterlayout', ymaps.templateLayoutFactory.createClass('<b>Объектов: $[count]</b>'));
        });

    });
};
YController.prototype._getBalloon = function () {

    var Balloon = function (controller) {
        var self = this;

        this.open = function (coords, template, data) {
            if (ymaps.layout.storage.get(template) == null) {
                ymaps.layout.storage.add(template, ymaps.templateLayoutFactory.createClass($(template).html(), {
                        build:function () {
                            this.constructor.superclass.build.call(this);
                            controller.model.balloon.build(this.getParentElement());
                        },
                        clear:function () {
                            this.constructor.superclass.clear.call(this);
                            controller.model.balloon.clear(this.getParentElement());
                        }
                    }
                ));
            }
            var position = [ parseFloat(coords.lat), parseFloat(coords.lng)];

            balloonLayout = ymaps.templateLayoutFactory.createClass(
// шаблон баллуна у нас лежит в тэге <script type="text/html">
                $(template).html(), {
                    build:function () {
// исполняем конструктор суперкласса
                        balloonLayout.superclass.build.call(this);
// получает геообъект
                        //var geoObject = this.getData().geoObject,
// карту
                        controller.model.balloon.build(this.getParentElement());

                        var map = window.map,
// координаты геообъекта
                            coords = position,
// контейнер баллуна
                            container = $(this.getParentElement());
                        container.find('.corn-top').each(function () {
                            var zoom = map.getZoom(),
                                width = container.find('.map-card-block').width(),
                                height = container.find('.map-card-block').height(),

                                projection = map.options.get('projection'),
                                global = projection.toGlobalPixels(coords, zoom),
                                center = map.getGlobalPixelCenter(),
                                balloonGlobalBounds = [
                                    [ global[0] - Math.round(width / 4), global[1] + height + 50],
                                    [ global[0] + Math.round(width / 1.2), global[1] - 0]
                                ];

                            var bounds = map.getBounds(),
                                globalBounds = [ projection.toGlobalPixels(bounds[0], zoom),
                                    projection.toGlobalPixels(bounds[1], zoom)],
                                pan = [0, 0];

                            if (balloonGlobalBounds[0][0] < globalBounds[0][0]) {
                                pan[0] = balloonGlobalBounds[0][0] - globalBounds[0][0] - 20
                            } else if (balloonGlobalBounds[1][0] > globalBounds[1][0]) {
                                pan[0] = balloonGlobalBounds[1][0] - globalBounds[1][0] + 40
                            }
                            if (balloonGlobalBounds[0][1] > globalBounds[0][1]) {
                                pan[1] = balloonGlobalBounds[0][1] - globalBounds[0][1] + 40
                            } else if (balloonGlobalBounds[1][1] < globalBounds[1][1]) {
                                pan[1] = balloonGlobalBounds[1][1] - globalBounds[1][1] - 20
                            }

                            if (pan[0] || pan[1]) {
                                center[0] += pan[0];
                                center[1] += pan[1];
                                map.panTo(projection.fromGlobalPixels(center, zoom), { delay:0, duration:600});
                            }
                        }).
                            on('click', '.ymaps-b-balloon__close', function () {
                                map.balloon.close();
                            });
                    },
                    clear:function () {
                        this.constructor.superclass.clear.call(this);
                        controller.model.balloon.clear(this.getParentElement());

                        $('.partner-balloon').off();
                        balloonLayout.superclass.clear.call(this);
                    }
                });

            var balloon = controller.map.balloon.open(position, data,
                {
                    hasCloseButton: true,
                    layout:balloonLayout,
                    //layout: ymaps.layout.storage.get(template),
                    shadow:false,
                    autoPan:false,
                    autoPanMargin:30});

            if(this.hide_balloon)
            {
                if (window.timer == undefined) {

                    window.timer = new SimpleTimer(2000, function () {
                        self.close();
                    });

                } else {
                    window.timer.reset();
                }

                balloon.events.add('mouseenter', function () {
                    window.timer.stop();
                });

                balloon.events.add('mouseleave', function () {
                    window.timer.reset();
                });
            }
        };
        this.close = function () {
            controller.map.balloon.close();
        };
    };
    return new Balloon(this);
};
YController.prototype.initialize = function () {
    var self = this;

    this.map.events.add("typechange", function (e) {
        self.map.zoomRange.get(self.map.getCenter()).then(function (range) {
            if (self.map.getZoom() > range[1]) {
                self.map.setZoom(range[1]);
            }
        })
    });

    this.map.events.add("click", function (e) {
        self.map.balloon.close();
    });
};
YController.prototype.createHover = function () {
    var self = this;
    this._hover = new ymaps.Placemark([], {}, {visible:false, iconLayout:'my#placemark', iconShadowLayout:'my#shadow'});
    this.map.geoObjects.add(this._hover);
    this._hover.events.add('click', function (e) {
        e.stopImmediatePropagation();
        self.model.markerEvent.call(self.model, 'click');
    });
    this.map.events.add('mouseleave', function (e) {
        var coords = e.get('coordPosition');
        self.model.marker = self.model.marker || self.model.getPointByLatLng.call(self.model, coords[0], coords[1], self.map.getZoom());
        self.model.markerEvent.call(self.model, 'mouseenter');
    });
    this.map.events.add('mousemove', function (e) {
        var coords = e.get('coordPosition');
        self.model.marker = self.model.getPointByLatLng.call(self.model, coords[0], coords[1], self.map.getZoom());
        self.showHover.call(self, self.model.marker);
    });
    this.map.events.add('zoomchange', function (e) {
        self.showHover.call(self, null);
    });
    this.map.events.add('click', function (e) {
        var coords = e.get('coordPosition');
        self.model.marker = self.model.marker || self.model.getPointByLatLng.call(self.model, coords[0], coords[1], self.map.getZoom());
        self.model.markerEvent.call(self.model, 'click');
    });
};

YController.prototype.showHover = function (marker) {
    if (!!marker) {
        var icon = this.model.getIconImage(marker.icon),
            coords = this.projection.fromGlobalPixels(marker.wcoord);
        this._hover.geometry.setCoordinates([coords.lat, coords.lng]);
        this._hover.properties.set({hintContent:marker.title, icon:icon, marker:marker });
        this._hover.options.set({'visible':true, iconShadow:(typeof icon.shadow !== 'undefined')});
    } else {
        this._hover.options.set('visible', false);
    }
};
YController.prototype.getBounds = function () {
    var bounds = this.map.getBounds();
    return { west:bounds[0][1], north:bounds[1][0], east:bounds[1][1], south:bounds[0][0]}
};
YController.prototype.getBoundsForPointAndDistance = function (lat, lng, distance) {
    var circle = new ymaps.Circle(
        [[parseFloat(lat), parseFloat(lng)], distance], {}, { visible: false }
    );
    this.map.geoObjects.add(circle);
    var bounds = circle.geometry.getBounds();
    this.map.geoObjects.remove(circle);
    return bounds;
};
YController.prototype.setCenter = function (geo, zoom) {
    this.map.setCenter([geo.lat, geo.lng], zoom);
};
YController.prototype.getCenter = function () {
    var center = this.map.getCenter();
    return {lat:center[0], lng:center[1]};
};
YController.prototype.getZoom = function () {
    return this.map.getZoom();
};
YController.prototype.update = function () {
    this.logger.clear().log('update');
};

YController.prototype.mapReady = function () {
    return (!!this.map && !!this.map.getBounds());
};

YController.prototype.addLegend = function (legendElement) {

};
YGeoObjectController = function (model) {
    YGeoObjectController.superclass.constructor.apply(this, arguments);
    this._cluster = null;
};
extend(YGeoObjectController, YController);
YGeoObjectController.prototype.initialize = function () {
    YGeoObjectController.superclass.initialize.apply(this, arguments);
    this._cluster = new ymaps.Clusterer();

    var placemarks = [ ], self = this;
    this.logger.start(['mapTime']);
    this.model.eachDataTile(function (point) {
        var icon = self.model.getIconImage(self.model._icons[self.model.getSchema(point, 'icon')], 'big');
        placemarks.push(new ymaps.Placemark([ parseFloat(self.model.getSchema(point, 'lat')),
            parseFloat(self.model.getSchema(point, 'lng')) ],
            {   clusterCaption:self.model.getSchema(point, 'title'),
                hintContent:self.model.getSchema(point, 'title'),
                icon:icon},
            {   iconLayout:'my#placemark',
                iconShadowLayout:'my#shadow',
                iconShadow:(typeof icon.shadow !== 'undefined')
            }))
    });

    this.logger.start('clusterMaxTime');
    this._cluster.options.set({ gridSize:this.model._clusterDistance});
    this._cluster.options.set({ gridSize:64});
    this._cluster.add(placemarks);
    this.logger.end('clusterMaxTime').start('mapDrawTime');
    this.map.geoObjects.add(this._cluster);
    this.logger.end(['mapDrawTime', 'mapTime']);
    //this.update();
};
// yandex canvas controller
YCanvasController = function (model, options) {
    YCanvasController.superclass.constructor.apply(this, arguments);
};
extend(YCanvasController, YController);

YCanvasController.prototype.initialize = function () {
    YCanvasController.superclass.initialize.apply(this, arguments);
    this.createHover();
    this.update();
};

YCanvasController.prototype.initLayer = function (map) {
    var controller, MyDomTile, pane;
    controller = this;
    MyDomTile = function (url) {
        var tmp = url.split(',');
        this.coord = { x:tmp[0], y:tmp[1]};
        this.zoom = tmp[2];
        this.content = null;
        this.ready = false;
        this.events = new ymaps.event.Manager({context:this});
        this.destroy = function () {
            if (this.content && this.content.parentNode) {
                this.content.parentNode.removeChild(this.content);
            }
            this.content = null;
        };
        this.isReady = function () {
            return this.ready;
        };
        this.renderAt = function (context, clientBounds, animate) {
            if (controller.model.zoom != this.zoom) {
                controller.model.zoom = this.zoom;
                controller.clearMarkers();
            }
            if (!(this.content && this.content.parentNode == context )) {
                if (!(this.content && this.content.parentNode == context )) {
                    this.content = controller.model.renderTile.call(controller.model, this.coord, this.zoom);
                    context.appendChild(this.content);
                }
                this.content.id = this.coord.x + '~' + this.coord.y + '~' + this.zoom;
                this.content.style.left = Math.round(clientBounds[0][0]) + 'px';
                this.content.style.top = Math.round(clientBounds[0][1]) + 'px';
                this.content.style.position = 'absolute';
                this.ready = true;
            }
            this.content.id = this.coord.x + '~' + this.coord.y + '~' + this.zoom;
            this.content.style.left = Math.round(clientBounds[0][0]) + 'px';
            this.content.style.top = Math.round(clientBounds[0][1]) + 'px';
            this.content.style.position = 'absolute';
            this.ready = true;
        };
    };

    // hint layouts
    pane = this.map.panes.get('layers');
    this.layer = new ymaps.Layer(function (tileNumber, tileZoom) {
        return tileNumber[0] + ',' + tileNumber[1] + ',' + tileZoom
    }, {tileTransparent:true, pane:pane, 'tileClass':MyDomTile, 'zIndex':200});
    map.layers.add(this.layer);

};

YCanvasController.prototype.update = function () {
    YCanvasController.superclass.update.apply(this, arguments);
    if (this.layer) {
        this.model.logger.log('update layer');
        this.layer.update();
    } else {
        this.model.logger.log('init layer');
        this.initLayer(this.map);

    }
};
YCanvasController.prototype.clearMarkers = function () {
    this.model.tiles = [ ];
    this.logger.clear();
};

/// yandex api controller
YNativeController = function (model, options) {
    YNativeController.superclass.constructor.apply(this, arguments);
    this._tiles = [ ];
};
extend(YNativeController, YController);

YNativeController.prototype.initialize = function () {
    YNativeController.superclass.initialize.apply(this, arguments);

    this._markers = new ymaps.GeoObjectCollection({}, {iconLayout:'my#placemark', iconShadowLayout:'my#shadow'});
    this.map.geoObjects.add(this._markers);

    var self = this;
    this._markers.events.add('click', function (e) {
        e.stopImmediatePropagation();
        self.model.markerEvent.call(self.model, 'click', e.originalEvent.target.properties.get('marker'));
    });
    this.map.events.add('zoomchange', function (e) {
        self.clearMarkers();
    });
    this.map.events.add('boundschange', function (e) {
        self.drawMap.call(self);
    });
    this.update();
};
YNativeController.prototype.update = function () {
    YNativeController.superclass.update.apply(this, arguments);
    this.clearMarkers();
    this.drawMap();
};

YNativeController.prototype.clearMarkers = function () {
    this._markers.removeAll();
    this._tiles = [ ];
    //this.logger.clear();
};

YNativeController.prototype.drawMap = function () {
    this.logger.start('mapTime');
    var zoom = this.map.getZoom(),
        markers = [ ],
        tile,
        tilePoints;
    if (zoom != this.model.zoom) this.clearMarkers();
    this.model.zoom = zoom;
    var visibleTiles = this.model.getTilesFromBounds(this.getBounds(), zoom);
    for (var i = 0; i < visibleTiles.length; i++) {
        tile = visibleTiles[i];
        if (typeof this._tiles[tile.coord.x + '~' + tile.coord.y] != 'undefined') continue;
        this._tiles.push(tile.coord.x + '~' + tile.coord.y);
        tilePoints = this.model.getTileMarkers(tile);
        markers = markers.concat(tilePoints);
    }
    tilePoints = null;
    this.logger.start('mapDrawTime');
    for (var i = 0, len = markers.length; i < len; i++) {
        var icon = this.model.getIconImage(markers[i].icon),
            placemark = new ymaps.Placemark([parseFloat(markers[i].lat), parseFloat(markers[i].lng) ],
                { hintContent:markers[i].title, icon:icon, marker:markers[i] }, {
                    iconShadow:(typeof icon.shadow !== 'undefined')});
        this._markers.add(placemark);
    }
    this.logger.inc('visibleCount', len).
        set({ mapDOMCount:$(this.model.container).find('*').length, mapZoom:this.getZoom()}).
        stop(['mapDrawTime' , 'mapTime']);
};

YFullCanvasController = function (model, options) {
    YFullCanvasController.superclass.constructor.apply(this, arguments);
    this._tiles = [ ];
};
extend(YFullCanvasController, YController);

YFullCanvasController.prototype.initialize = function () {
    YFullCanvasController.superclass.initialize.apply(this, arguments);
    // create overlay
    var controller = this,
        map = this.map;

    var CanvasControl = function (params) {
        return {
            parent:null,
            layout:null,
            map:null,
            zoom:0,
            controller:params.controller,
            events:new ymaps.event.Manager({context:this}),
            state:new ymaps.data.Manager(params.data),
            pane:null,
            inaction:false,
            actiontimer:null,
            tick:5,
            getParent:function () {
                return this._parent;
            },
            setParent:function (parentObject) {
                this.parent = parentObject;
                this.map = parentObject.getMap();
                if (this.layout == null) {
                    this.createLayout();
                }
            },
            createLayout:function () {
                var controlsPane = this.getMap().panes.get('controls'),
                    container = this.getMap().container.getElement(),
                    self = this;
                this.pane = new ymaps.pane.movable.StepwisePane(this.map,
                    {className:'ymaps-nolan-canvases', zIndex:200});
                //this.pane = this.getMap().panes.get('layers');

                this.pane.events.add('actionend', function (evt) {
                    self._draw.call(self);
                });
                this.pane.events.add('zoomchange', function (evt) {
                    var zoom = self.pane.getZoom();
                    self._draw.call(self);
                });
                map.events.add('propertieschange', function (evt) {
                    self._draw.call(self);
                });
                map.events.add('boundschange', function (evt) {
                    self._draw.call(self);
                });
                this.layout = document.createElement('canvas');
                $(this.layout).
                    css({'position':'absolute',
                        zIndex:200}).
                    appendTo(this.pane.getElement());
                this._draw();
            },
            getMap:function () {
                return this.map;
            },
            getContainer:function () {
                return this.pane.getElement();
            },
            _draw:function () {
                var zoom = this.pane.getZoom(),
                    bounds = this.pane.getViewport(),
                    top = bounds[0][1],
                    left = bounds[0][0],
                    width = (typeof FlashCanvas == 'undefined') ? bounds[1][0] - bounds[0][0] : 2000,
                    height = (typeof FlashCanvas == 'undefined') ? bounds[1][1] - bounds[0][1] : 2000;

                if (Math.round(zoom) == zoom) {
                    var c1 = this.pane.fromClientPixels(bounds[0]),
                        c2 = this.pane.fromClientPixels(bounds[1]),
                        k = this.controller.model.pow2(Math.round(zoom)),
                        globalBounds = [
                            {x:(c1[0] / k), y:(c1[1] / k)},
                            {x:(c2[0] / k), y:(c2[1] / k)}
                        ];

                    this.zoom = zoom;
                    this.layout.width = width;
                    this.layout.height = height;
                    this.controller.model.renderMapCanvas(this.layout, globalBounds, zoom);
                } else {
                    var d = (zoom > this.zoom) ? (1 + zoom - this.zoom) : (1 - this.zoom + zoom );
                    left = left * d;
                    top = top * d;
                    width = width * d;
                    height = height * d;
                }
                $(this.layout).css({ left:left + 'px', top:top + 'px',
                    width:width + 'px', height:height + 'px'});
            }
        }
    };

    var control = new CanvasControl({data:{}, controller:this});

    this.map.controls.add(control);

    gl_control = control;

    this.createHover();

};
/// overlay canvas

// Google controller
GController = function (model, options) {
    this.map = null;
    this.model = model;
    this.logger = model.logger;
    this.maxZoom = 22;
    this._hover = null;
    this.initMap(options);
};
GController.prototype.initMap = function (options) {
    var self = this;
    if (!(window.google)) {
        this.logger.start('mapLoadTime');
        initFunc = '_citymap' + Math.round(Math.random() * 1000);
        window[initFunc] = function () {
            self.initMap.call(self, options);
        };
        this.projection = CityMap.Projection(CityMap.Projection.sphericalMercator);
        $.getScript("//maps.google.ru/maps/api/js?sensor=true&callback=" + encodeURIComponent(initFunc));
        return;
    }
    var mapOptions = {
        mapTypeId:google.maps.MapTypeId.ROADMAP,
        zoom:options.zoom,
        center:new google.maps.LatLng(options.center.lat, options.center.lng),
        streetViewControl:false,
        featureType:'all',
        panControl:false,
        navigationControlOptions:{
            position:google.maps.ControlPosition.LEFT_CENTER
        }
    };

    this.map = new google.maps.Map(this.model.container, mapOptions);

    if (options.map == 'osm') {
        this.map.mapTypes.set("OSM", new google.maps.ImageMapType({
            getTileUrl:function (coord, zoom) {
                return "http://tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
            },
            tileSize:new google.maps.Size(256, 256),
            name:"OpenStreetMap",
            maxZoom:18
        }));
        this.map.setMapTypeId("OSM");
    }
    this.logger.end('mapLoadTime');
    // balloon overlay
    var Balloon = function (map) {
        this.map = map;
        this.controller = null;
        var isOpen = false,
            latlng = null,
            templates = [ ],
            layout = '',
            element = null,
            offset = 20,
            self = this;

        this.onAdd = function () {
            var panes = this.getPanes();
            if (panes) {
                element = $(layout).appendTo(panes.floatPane);
                this.controller.model.balloon.build(element[0]);
                element.on('mousemove click dblclick', function (e) {
                    e.stopPropagation()
                })
            }
        };
        this.onRemove = function () {
            this.controller.model.balloon.clear(element[0]);
            element.off('mousemove click dblclick').remove();
            isOpen = false;
        };
        this.draw = function () {
            var projection = this.getProjection();
            var pos = projection.fromLatLngToDivPixel(latlng),
                pos1 = projection.fromLatLngToContainerPixel(latlng),
                container = $(this.map.getDiv()),
                x = Math.max(Math.min(0, pos1.x - offset - (element.width() / 2)), pos1.x + element.width() / 2 + offset - container.width()),
                y = Math.min(0, pos1.y - element.height() - offset);
            element.css({'left':pos.x + 'px', 'top':pos.y + 'px'});
            if (x || y)  this.map.panBy(x, y);
            isOpen = true;
        };
        this.open = function (coords, template, data) {
            latlng = new google.maps.LatLng(coords.lat, coords.lng);
            if (typeof templates[template] == 'undefined') {
                templates[template] = $(template).html();
            }
            layout = templates[template];
            layout = layout.replace(/\$\[(.*?)\]/g, function (a, b) {
                return (data.hasOwnProperty(b)) ? data[b] : '';
            });

            this.setMap(this.controller.map);
        };
        this.close = function () {
            this.setMap(null);
        };
        this.isOpen = function () {
            return isOpen;
        };
        return this;
    };
    extend(Balloon, google.maps.OverlayView);
    this.balloon = new Balloon(this.map);
    this.balloon.controller = this;
};

GController.prototype.initialize = function () {

};
GController.prototype.createHover = function () {
    this._hover = new google.maps.Marker({ map:this.map, visible:false, cursor:'pointer'});
    var self = this;
    google.maps.event.addListener(this.map, 'mousemove', function (e) {
        self.model.marker = self.model.getPointByLatLng.call(self.model, e.latLng.lat(), e.latLng.lng(), self.map.getZoom());
        self.showHover.call(self, self.model.marker);
    });
    google.maps.event.addListener(this._hover, 'click', function (e) {
        self.showHover.call(self, null);
        self.model.markerEvent.call(self.model, 'click');
    });
    google.maps.event.addListener(this.map, 'zoomchange', function (e) {
        self.showHover.call(self, null);
    });
    google.maps.event.addDomListener(this.map.getDiv(), 'touchstart', function (e) {
        var divpos = $(self.map.getDiv()).position(),
            point = {x:e.pageX - divpos.left, y:e.pageY - divpos.top},
            bounds = self.getBounds(),
            pixel = self.projection.toGlobalPixels({lat:bounds.north, lng:bounds.west}, self.getZoom()),
            latLng = self.projection.fromGlobalPixels({x:pixel.x + point.x, y:pixel.y + point.y}, self.getZoom());

        self.model.marker = self.model.getPointByLatLng.call(self.model, latLng.lat, latLng.lng, self.getZoom());

        self.model.markerEvent.call(self.model, 'click');
    });
};
GController.prototype.showHover = function (marker) {
    if (!!marker) {
        var iconImage = this.createIcon(marker.icon);
        this._hover.setIcon(iconImage.icon);
        if (typeof iconImage.shadow != 'undefined')    this._hover.setShadow(iconImage.shadow);
        this._hover.setTitle(marker.title);
        this._hover.setPosition(new google.maps.LatLng(marker.lat, marker.lng));
        this._hover.setVisible(true);
    } else {
        this._hover.setVisible(false);
    }
};
GController.prototype.createIcon = function (icon) {
    icon = this.model.getIconImage(icon);
    var iconImage = { icon:new google.maps.MarkerImage(icon.src,
        new google.maps.Size(icon.size.width, icon.size.height),
        new google.maps.Point(icon.origin.x, icon.origin.y),
        new google.maps.Point(icon.anchor.x, icon.anchor.y))};
    if (typeof icon.shadow != 'undefined') {
        iconImage.shadow = new google.maps.MarkerImage(icon.src,
            new google.maps.Size(icon.shadow.size.width, icon.shadow.size.height),
            new google.maps.Point(icon.shadow.origin.x, icon.shadow.origin.y),
            new google.maps.Point(icon.shadow.anchor.x, icon.shadow.anchor.y))
    }
    return iconImage;
};
GController.prototype.initialize = function () {
};
GController.prototype.getBounds = function () {
    var bounds = this.map.getBounds();
    return { south:bounds.getSouthWest().lat(), east:bounds.getNorthEast().lng(), north:bounds.getNorthEast().lat(), west:bounds.getSouthWest().lng()}
};
GController.prototype.setCenter = function (geo, zoom) {
    this.map.setCenter(new google.maps.LatLng(geo.lat, geo.lng));
    this.map.setZoom(zoom);
};
GController.prototype.getCenter = function () {
    var center = this.map.getCenter();
    return {lat:center.lat(), lng:center.lng()};
};
GController.prototype.getZoom = function () {
    return this.map.getZoom();
};
GController.prototype.mapReady = function () {
    return (!!this.map && !!this.map.getBounds())
};
GController.prototype.addLegend = function (legendElement) {
    legendElement.attr('index', 1);
    this.map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(legendElement[0]);
};
GController.prototype.update = function () {
    this.logger.clear();
};

// Google API Controller
GNativeController = function (model) {
    GNativeController.superclass.constructor.apply(this, arguments);
    this.placeMarks = [ ];
    this.tiles = [ ];
};
extend(GNativeController, GController);

GNativeController.prototype.initialize = function () {
    GNativeController.superclass.initialize.apply(this, arguments);
    var self = this;
    google.maps.event.addListener(this.map, 'bounds_changed', function () {
        self.drawMap.call(self);
    });
    this.update();
};
GNativeController.prototype.update = function () {
    GNativeController.superclass.update.apply(this, arguments);
    this.clearMarkers();
    this.drawMap();
};
GNativeController.prototype.clearMarkers = function () {
    for (var i = 0; i < this.placeMarks.length; i++) {
        this.placeMarks[i].setMap(null);
    }
    this.placeMarks = [ ];
    this.tiles = [ ];
    this.logger.clear();
};
GNativeController.prototype.drawMap = function () {
    this.logger.start('mapTime');
    var self = this,
        bounds = this.map.getBounds(),
        zoom = this.map.getZoom();
    if (zoom != this.model.zoom)     this.clearMarkers();
    this.model.zoom = zoom;
    var markers = [ ],
        visibleTiles = this.model.getTilesFromBounds(this.getBounds(), zoom);

    for (var i = 0; i < visibleTiles.length; i++) {
        var tile = visibleTiles[i];
        if ($.inArray((tile.coord.x + '~' + tile.coord.y), this.tiles) != -1) continue;
        this.tiles.push(tile.coord.x + '~' + tile.coord.y);
        var tileMarkers = this.model.getTileMarkers(tile);
        markers = markers.concat(tileMarkers);
        this.logger.log('tile ' + tile.coord.x + '~' + tile.coord.y + '~' + tile.zoom + ' markers: ' + tileMarkers.length);
    }
    this.logger.start('mapDrawTime');
    for (var i = 0; i < markers.length; i++) {
        var marker = markers[i],
            icon = this.createIcon(marker.icon);
        placeMark = new google.maps.Marker({map:this.map, icon:icon.icon,
            shadow:icon.shadow,
            position:new google.maps.LatLng(marker.lat, marker.lng),
            visible:true,
            title:marker.title,
            marker:marker
        });
        google.maps.event.addListener(placeMark, 'click', function (e) {
            e.stop();
            self.model.markerEvent.call(self.model, 'click', this.marker);
        });
        this.placeMarks.push(placeMark);
        placeMark.setMap(this.map);
    }
    this.logger.end(['mapDrawTime', 'mapTime']).
        inc('visibleCount', markers.length).
        set({ mapDOMCount:$(this.model.container).find('*').length, mapZoom:zoom});
};
//
//
//
GCanvasController = function (model, options) {
    GCanvasController.superclass.constructor.apply(this, arguments);
};
extend(GCanvasController, GController);

GCanvasController.prototype.initialize = function (options) {
    GCanvasController.superclass.initialize.apply(this, arguments);
    var self = this;
    this.tileSize = new google.maps.Size(this.model.TILE_SIZE, this.model.TILE_SIZE);
    this.createHover();
    this.update();
};

GCanvasController.prototype.update = function () {
    GCanvasController.superclass.update.apply(this, arguments);
    if (this._ready) {
        this.map.overlayMapTypes.setAt(0, this)
    } else {
        this.map.overlayMapTypes.insertAt(0, this);
        this._ready = true;
    }
};

GCanvasController.prototype.getTile = function (coord, zoom, ownerDocument) {
    return  this.model.renderTile(coord, zoom);
};

GCanvasController.prototype.releaseTile = function (div) {
//	return this.model.releaseTile.call(this.model, div);
};

GFullCanvasController = function (model, options) {
    GFullCanvasController.superclass.constructor.apply(this, arguments);
};
extend(GFullCanvasController, GController);

GFullCanvasController.prototype.initialize = function (options) {
    GFullCanvasController.superclass.initialize.apply(this, arguments);

    this.model.tileOffset = {x:0, y:0};
    this.canvasOverlay = this.createCanvasOverlay();
    this.canvasOverlay.setMap(this.map);

    var self = this;
    google.maps.event.addListener(this.map, 'center_changed', function (e) {
        self.canvasOverlay.draw();
    });
    this.createHover();
};
GFullCanvasController.prototype.createCanvasOverlay = function () {
    // init overlayview
    var CanvasOverlay = function (controller) {
        this.controller = controller;
        var self = this,
            element = null;
        this._tiles = [ ];
        this._zoom = 0;
        this._tick = 0;
        this._ticktimer = 0;
        this.onAdd = function () {
            var panes = this.getPanes(),
            // получаем элемент, который содержит карту
                container = this.getMap().getDiv(),
            // определяем высоту и ширину для канваса
                width = (typeof FlashCanvas == 'undefined') ? Math.ceil(($(container).width() + 1 ) / 256) * 256 : 2000,
                height = (typeof FlashCanvas == 'undefined') ? Math.ceil(($(container).height() + 1 ) / 256) * 256 : 2000;
            this._offset = {x:(width - $(container).width()) / 2, y:(height - $(container).height()) / 2};
            if (panes) {
                element = document.createElement('canvas');
                $(element).
                    css({'position':'relative',
                        width:width + 'px',
                        height:height + 'px'}).
                    attr({'width':width, height:height}).
                    appendTo(panes.overlayLayer);
            }
        };
        this.onRemove = function () {
            $(element).off('mousemove click dblclick').remove();
        };
        this.draw = function () {
            if (this._ticktimer) window.clearTimeout(this._ticktimer);
            var self = this;
            if (this._zoom != this.controller.getZoom()) {
                this._tick = 0;
                this._zoom = this.controller.getZoom();
                this.render.call(self)
            } else {
                this._ticktimer = window.setTimeout(function () {
                    self.render.call(self)
                }, this._tick);
            }
        };
        this.render = function () {
            var projection = this.getProjection(),
                map = this.getMap(),
                div = map.getDiv(),
                zoom = this.controller.getZoom(),
                bounds = this.controller.getBounds(),
                globalBounds = [this.controller.projection.toGlobalPixels({lat:bounds.north, lng:bounds.west}),
                    this.controller.projection.toGlobalPixels({lat:bounds.south, lng:bounds.east})],
                pos = projection.fromLatLngToDivPixel(new google.maps.LatLng(bounds.north, bounds.west)),
                tickstart = new Date();
            globalBounds[0].x -= this._offset.x / this.controller.model.pow2(zoom);
            globalBounds[0].y -= this._offset.y / this.controller.model.pow2(zoom);
            globalBounds[1].x += this._offset.x / this.controller.model.pow2(zoom);
            globalBounds[1].y += this._offset.y / this.controller.model.pow2(zoom);

            this.controller.model.renderMapCanvas(element, globalBounds, zoom);
            $(element).css({'left':pos.x - this._offset.x + 'px', 'top':pos.y - this._offset.y + 'px'});

            if (this._tick < (new Date() - tickstart)) this._tick = new Date() - tickstart;
        };
        return this;
    };
    extend(CanvasOverlay, google.maps.OverlayView);
    return new CanvasOverlay(this);
};

GFullCanvasController.prototype.update = function () {
    GFullCanvasController.superclass.update.apply(this, arguments);
};

///
CityMap = function (container, options) {
    // constants
    this.container = container;
    this.mapTypeOptions = {GOOGLEmapS:'google', YANDEXmapS:'yandex'};
    this.MAX_INDEXED_ZOOM = 14;
    this.MAX_LOAD_ATTEMPTS = 20;
    this.MAX_ZOOM = 20;
    this.TILE_SIZE = 256;

    this._pow2 = [ ];
    this._data = null;
    this._tileCache = [ ];
    /** @var {YController|GController} */
    this.controller = null;
    this._sprite = null;
    this._clusterIcon = null;
    this._groupIcon = null;
    this._dataUrl = '';
    this._icons = [ ];
    this._readyTimer = null;
    this._loadStart = new Date();
    this._debug = (typeof options.debug !== 'undefined' && options.debug);

    this._legendElement = null;
    this._clusterDistance = 20; //pixels

    this.groupDistance = 0.0001; //pixels
    this.clusterGrid = this.TILE_SIZE / 2; // grid distance
    this.tileOffset = {x:0, y:0};

    // private

    this.zoom = 0;
    this.center = null;
    this.marker = null;
    this.balloon = CityMap.Balloon(this);

    this.points = [];

    this.logger = CityMapLogger;
    this.logger.set({clusterDistance:this._clusterDistance, clusterDepth:this.clusterDepth});

    // controller
    this.controller = new (window[(typeof options.controller == 'undefined') ? 'GCanvasController' : options.controller])(this, options);

    this.ready();
};
// data handling

CityMap.prototype.loadData = function (url) {
    // load data
    if (typeof window.citymap != 'undefined' && $('#mapsdata').length == 0) {
        this.logger.log('get data');
        $.getScript(url);
    } else {
        var self = this, dataUrl = url
        window.setTimeout(function () {
            self.loadData(dataUrl)
        }, 200);
    }
};

CityMap.prototype.ready = function () {
    var self = this;
    if (!!this.controller &&
        this.controller.mapReady() && !!this._data && !!this._sprite && (this._sprite.complete || (typeof this._sprite.naturalWidth != "undefined" && this._sprite.naturalWidth !== 0))) {
        if (!!this._readyTimer) window.clearTimeout(this._readyTimer);
        this.logger.log('ready');
        this.controller.initialize();
        //this.showLegend();
    } else {
        this.logger.log('not ready');
        if ((new Date() - this._loadStart) > 20000) {
            if (!confirm('loading map tmeout. continue?'))   return;
        } else {
            this._loadStart = new Date();
        }
        this._readyTimer = window.setTimeout(function () {
            self.ready.apply(self, arguments);
        }, 500);
    }
};
// set icons
CityMap.prototype.setOptions = function (options) {
    this._sprite = document.createElement('img');
    this._sprite.src = options.sprite.src;
    this._icons = options.icons;
    this._clusterIcon = options.cluster.icon;
    this._groupIcon = options.group.icon;
    this._dataUrl = options.dataUrl;
    this.setClusterDistance((options.clusterdist) ? parseInt(options.clusterdist) : 20);
    this.wideZoom = (options.wideZoom) ? parseInt(options.wideZoom) : 14;
    this.tileOffset = {'x':0, 'y':0};
    for (var i = 0; i < this._icons.length; i++) {
        if (this.tileOffset.x < this._icons[i].anchor.x) this.tileOffset.x = this._icons[i].anchor.x;
        if (this.tileOffset.y < this._icons[i].anchor.y) this.tileOffset.y = this._icons[i].anchor.y;
        if ((typeof this._icons[i].big != 'undefined') && this.tileOffset.x < this._icons[i].big.anchor.x) this.tileOffset.x = this._icons[i].big.anchor.x;
        if ((typeof this._icons[i].big != 'undefined') && this.tileOffset.y < this._icons[i].big.anchor.y) this.tileOffset.y = this._icons[i].big.anchor.y;
    }

    this.logger.set('totalIcons', this._icons.length);
    this._schema = options.schema;
    this._tileCache = [ ];
};

CityMap.prototype.getMap = function () {
    this.controller.getMap();
};

CityMap.prototype.getPoints = function()
{
    return this.points;
}

// set points data
CityMap.prototype.setData = function (rawdata) {
    var points = rawdata.split('|'),
        row,
        i = 0,
        tempdata = [ ];
    rawdata = null;
    this._data = [ ];


    this.points = [];
    while (row = points.pop()) {
        var point = row.split(':'),
            geo = {lat:parseFloat(this.getSchema(point, 'lat')), lng:parseFloat(this.getSchema(point, 'lng'))};

        this.points.push(geo);

        point.wcoord = this.controller.projection.toGlobalPixels(geo);
        var tcoord = this.controller.projection.globalToTileCoord(point.wcoord, this.MAX_INDEXED_ZOOM);
        if (typeof tempdata[tcoord.x] == 'undefined') tempdata[tcoord.x] = [ ];
        if (typeof tempdata[tcoord.x][tcoord.y] == 'undefined')
            tempdata[tcoord.x][tcoord.y] = parseInt(this.getTileId(tcoord, this.MAX_INDEXED_ZOOM), 4).toString(10);
        var tile_id = tempdata[tcoord.x][tcoord.y];
        if (typeof this._data[tile_id] == 'undefined') this._data[tile_id] = [ ];
        this._data[tile_id].push(point);
        i++;
    }
    tempdata = null;

    this.clearTileCache();

    if (gl_control != undefined)
        gl_control._draw();

    $('#map').css('opacity', 1);
};
CityMap.prototype.setClusterDistance = function (value) {
    if (this._clusterDistance != value) {
        this._clusterDistance = value;
        this.logger.clear(['clusterDistance']).set('clusterDistance', value);
    }
};
CityMap.prototype.getSchema = function (data, field) {
    return (typeof this._schema[field] == 'undefined') ? null : data[this._schema[field]];
};

CityMap.prototype.getIconImage = function (icon) {

    if (icon == undefined)
        return;

    var iconImage;
    if (arguments.length == 2) {
        iconImage = (typeof icon[arguments[1]] != 'undefined') ? icon[arguments[1]] : icon;
    } else {
        iconImage = ((typeof icon.big != 'undefined') && this.zoom >= this.wideZoom) ? icon.big : icon;
    }
    iconImage.src = this._sprite.src;
    iconImage.title = icon.title;
    return iconImage;
};

CityMap.prototype.eachDataTile = function (callback) {
    for (var tile in this._data) {
        if (this._data.hasOwnProperty(tile))
            for (var i in this._data[tile])
                if (this._data[tile].hasOwnProperty(i)) {
                    callback(this._data[tile][i]);
                }
    }
};
//
CityMap.prototype.getTilesFromBounds = function (bounds, zoom) {
    var lt = this.controller.projection.toTileCoord({lat:bounds.north, lng:bounds.west}, zoom),
        rb = this.controller.projection.toTileCoord({lat:bounds.south, lng:bounds.east}, zoom);
    return this.getTiles([lt, rb], zoom);
};
CityMap.prototype.getTilesFromGlobalBounds = function (global, zoom) {
    var lt = this.controller.projection.globalToTileCoord(global[0], zoom),
        rb = this.controller.projection.globalToTileCoord(global[1], zoom);
    return this.getTiles([lt, rb], zoom);
};
CityMap.prototype.getTiles = function (bounds, zoom) {
    var tiles = [ ],
        lt = bounds[0],
        rb = bounds[1],
        x1 = (lt.x < rb.x) ? lt.x : rb.x,
        x2 = (lt.x > rb.x) ? lt.x : rb.x,
        y1 = (lt.y < rb.y) ? lt.y : rb.y,
        y2 = (lt.y > rb.y) ? lt.y : rb.y;
    for (var x = x1; x <= x2; x++)
        for (var y = y1; y <= y2; y++)
            tiles.push({id:this.getTileId({x:x, y:y}, zoom), coord:{x:x, y:y}, zoom:zoom});
    return tiles;
};

CityMap.prototype.getTileMarkers = function (tile) {
    var markers = this._getTileCache(tile);
    if (!!markers) {
        return markers;
    }
    var points = [ ], tcoord, point, total_points;
    this.logger.start('mapSelectTime');
    if (tile.zoom >= this.MAX_INDEXED_ZOOM) {
        var t = parseInt(tile.id.substr(0, this.MAX_INDEXED_ZOOM + 1), 4).toString(10);
        if (t in this._data) {
            for (var i = 0, len = this._data[t].length; i < len; i++) {
                point = this._data[t][i];
                tcoord = { x:Math.floor(point.wcoord.x * this.pow2(tile.zoom) / this.TILE_SIZE),
                    y:Math.floor(point.wcoord.y * this.pow2(tile.zoom) / this.TILE_SIZE)};
                if (tcoord.x == tile.coord.x && tcoord.y == tile.coord.y)
                    points.push(point);
            }
        }

    } else {
        var tiles,
            tileRange = { 'start':tile.id, 'end':tile.id};
        for (var i = tile.zoom; i < this.MAX_INDEXED_ZOOM; i++) {
            tileRange.start += '0';
            tileRange.end += '3';
        }
        tileRange.start = parseInt(tileRange.start, 4);
        tileRange.end = parseInt(tileRange.end, 4);

        for (var t in this._data) {
            if (this._data.hasOwnProperty(t)) {
                t = parseInt(t);
                if ((t >= tileRange.start) && (t <= tileRange.end) && !(typeof this._data[t] == 'undefined')) {
                    points = points.concat(this._data[t]);
                }
            }
        }
    }
    this.logger.stop('mapSelectTime');
    total_points = points.length;
    markers = this.clusterize(tile, points);

    this._setTileCache(tile, markers);
    this.logger.max('tileMaxCount', total_points).
        max('tileMaxVisibleCount', markers.length).
        inc('visibleTileCount', 1).
        inc('clusterCount', markers.length);
    return markers;
};
CityMap.prototype.explodeCluster = function (marker) {
    var dist = Math.max(Math.abs(marker.cbounds[0][0] - marker.cbounds[1][0]),
        Math.abs(marker.cbounds[0][1] - marker.cbounds[1][1]));
    return Math.min(this.MAX_ZOOM, Math.ceil(Math.log(this._clusterDistance / dist) / Math.log(2)) + 1);
};
CityMap.prototype._doClusterize = function (points, distance) {
    var i = 0, mlen = 0, len = points.length, delta, markers = [ ], marker;
    while (i < len) {
        if (typeof points[i].type == 'undefined') {
            marker = {type:'marker',
                points:[points[i]],
                count:1,
                wcoord:points[i].wcoord,
                cbounds:[
                    [ points[i].wcoord.x, points[i].wcoord.y ],
                    [ points[i].wcoord.x, points[i].wcoord.y ]
                ]
            }
        } else {
            marker = points[i];
        }
        for (var j = 0; j < mlen; j++) {
            delta = Math.max(Math.abs(marker.wcoord.x - markers[j].wcoord.x), Math.abs(marker.wcoord.y - markers[j].wcoord.y));
            if (delta < distance) // make cluster
            {
                markers[j].type = (markers[j].type == 'cluster' || marker.type == 'cluster') ? 'cluster' : ((delta < this.groupDistance) ? 'group' : 'cluster');
                markers[j].points.push(points[i]);
                marker = null;
                break;
            }
        }
        if (marker) {
            markers.push(marker);
            mlen++;
        }
        i++;
    }
    for (var i = 0, len = markers.length; i < len; i++) {
        marker = markers[i];
        marker.count = marker.points.length;
        marker.title = this.getSchema(marker.points[0], 'title');
        if (marker.type == 'cluster') {
            for (var j = 1; j < marker.count; j++) {
                if (marker.cbounds[0][0] > marker.points[j].wcoord.x) marker.cbounds[0][0] = marker.points[j].wcoord.x;
                if (marker.cbounds[1][0] < marker.points[j].wcoord.x) marker.cbounds[1][0] = marker.points[j].wcoord.x;
                if (marker.cbounds[0][1] > marker.points[j].wcoord.y) marker.cbounds[0][1] = marker.points[j].wcoord.y;
                if (marker.cbounds[1][1] < marker.points[j].wcoord.y) marker.cbounds[1][1] = marker.points[j].wcoord.y;
            }
            marker.wcoord = { x:marker.cbounds[0][0] + (marker.cbounds[1][0] - marker.cbounds[0][0]) / 2,
                y:marker.cbounds[0][1] + (marker.cbounds[1][1] - marker.cbounds[0][1]) / 2};
            var latlng = this.controller.projection.fromGlobalPixels(marker.wcoord);
            marker.lat = latlng.lat;
            marker.lng = latlng.lng;
            marker.icon = this._clusterIcon;
        } else {
            if (marker.type == 'group') {
                marker.icon = this._groupIcon;
            } else {
                marker.icon = this._icons[this.getSchema(marker.points[0], 'icon')];
            }
            marker.lat = parseFloat(this.getSchema(marker.points[0], 'lat'));
            marker.lng = parseFloat(this.getSchema(marker.points[0], 'lng'));
        }
    }
    return markers;
};

CityMap.prototype.doSeldom = function (markers, ref, distance) {
    var i = 0, len = markers.length;
    while (i < len) {
        // check clustered
        var clustered = false, dist;
        for (var k = 0, rlen = ref.length; k < rlen; k++) {
            if ((Math.abs(ref[k].wcoord.x - markers[i].wcoord.x) < distance) && (Math.abs(ref[k].wcoord.y - markers[i].wcoord.y) < distance)) {
                clustered = true;
                break;
            }
        }
        if (clustered) {
            dist = Math.max(Math.abs(ref[k].wcoord.x - markers[i].wcoord.x), Math.abs(ref[k].wcoord.y - markers[i].wcoord.y));
            ref[k] = this.makeCluster(ref[k], markers[i], dist);
            markers.splice(i, 1);
            len--;
        } else {
            i++;
        }
    }
    return markers;
};

CityMap.prototype.makeCluster = function (cluster, marker, delta) {
    cluster.type = (cluster.type == 'cluster' || marker.type == 'cluster') ? 'cluster' : ((delta < this.groupDistance) ? 'group' : 'cluster');
    cluster.points = cluster.points.concat(marker.points);
    if (cluster.type == 'cluster') {
        if (cluster.cbounds[0][0] > marker.cbounds[0][0]) cluster.cbounds[0][0] = marker.cbounds[0][0];
        if (cluster.cbounds[1][0] < marker.cbounds[1][0]) cluster.cbounds[1][0] = marker.cbounds[1][0];
        if (cluster.cbounds[0][1] > marker.cbounds[0][1]) cluster.cbounds[0][1] = marker.cbounds[0][1];
        if (cluster.cbounds[1][1] < marker.cbounds[1][1]) cluster.cbounds[1][1] = marker.cbounds[1][1];

        cluster.wcoord = { x:cluster.cbounds[0][0] + (cluster.cbounds[1][0] - cluster.cbounds[0][0]) / 2,
            y:cluster.cbounds[0][1] + (cluster.cbounds[1][1] - cluster.cbounds[0][1]) / 2};
        var latlng = this.controller.projection.fromGlobalPixels(cluster.wcoord);
        cluster.lat = latlng.lat;
        cluster.lng = latlng.lng;
        cluster.icon = this._clusterIcon;
    } else {
        // group
        cluster.icon = this._groupIcon;
    }
    cluster.count = cluster.points.length;
    return cluster;
};

CityMap.prototype.clusterize = function (tile, points) {
    this.logger.start(['clusterMaxTime', 'clusterTime']);
    var subTiles, gridPixels, distance;
    subTiles = [ ];
    gridPixels = this.clusterGrid / this.pow2(tile.zoom);
    distance = this._clusterDistance / this.pow2(tile.zoom);

    for (var i = 0, len = points.length; i < len; i++) {
        var coord = { x:Math.ceil(points[i].wcoord.x / gridPixels), y:Math.ceil(points[i].wcoord.y / gridPixels) };
        if (!(coord.x in subTiles)) subTiles[coord.x] = [ ];
        if (!(coord.y in subTiles[coord.x])) subTiles[coord.x][coord.y] = [ ];
        subTiles[coord.x][coord.y].push(points[i]);
    }

    var markers = [ ];
    for (var x in subTiles) {
        for (var y in subTiles[x]) {
            markers = markers.concat(this._doClusterize(subTiles[x][y], distance));
        }
    }
    subTiles = null;
    // second pass
    markers = this._doClusterize(markers, distance);
    for (var x = tile.coord.x - 1; x < (tile.coord.x + 1); x++)
        for (var y = tile.coord.y - 1; y < (tile.coord.y + 1); y++)
            if (!( (x == tile.coord.x) && (y == tile.coord.y))) {
                var cache = this._getTileCache({'coord':{'x':x, 'y':y}, 'zoom':tile.zoom});
                if (!!cache) {
                    markers = this.doSeldom(markers, cache, distance);
                    // to do rerender changed canvas
                }
            }

    cache = null;

    this.logger.end('clusterMaxTime').stop('clusterTime');

    this.logger.start('sortTime');

    markers.sort(function (a, b) {
        if (a.lat < b.lat)
            return 1;
        else if (a.lat > b.lat)
            return -1;
        else
            return 0;
    });
    this.logger.stop('sortTime');
    return markers;
};
CityMap.prototype._getTileCache = function (tile) {
    if ((typeof this._tileCache[tile.zoom] != 'undefined') &&
        (typeof this._tileCache[tile.zoom][tile.coord.x] != 'undefined') &&
        (typeof this._tileCache[tile.zoom][tile.coord.x][tile.coord.y] != 'undefined')) {
        return this._tileCache[tile.zoom][tile.coord.x][tile.coord.y]
    }
    return false;
};
CityMap.prototype._setTileCache = function (tile, points) {
    if (typeof this._tileCache[tile.zoom] == 'undefined') this._tileCache[tile.zoom] = [ ];
    if (typeof this._tileCache[tile.zoom][tile.coord.x] == 'undefined') this._tileCache[tile.zoom][tile.coord.x] = [ ];
    this._tileCache[tile.zoom][tile.coord.x][tile.coord.y] = points;
};
CityMap.prototype.renderMapCanvas = function (canvas, globalBounds, zoom) {
    this.zoom = zoom;
    if (typeof FlashCanvas !== 'undefined') {
        //alert('444');
        FlashCanvas.initElement(canvas);
    }

    var visibleTiles = this.getTilesFromGlobalBounds(globalBounds, zoom),
        tile,
        markers = [ ];

    for (var i = 0; i < visibleTiles.length; i++) {
        tile = visibleTiles[i];
        markers = markers.concat(this.getTileMarkers(tile));
    }

    this.renderCanvas(canvas, globalBounds[0], zoom, markers);
};

CityMap.prototype.clearTileCache = function () {
    this._tileCache = [];
};
CityMap.prototype.renderTile = function (coord, zoom) {
    if (this.zoom != zoom) this.zoom = zoom;

    coord.x = parseInt(coord.x);
    coord.y = parseInt(coord.y);
    zoom = parseInt(zoom);
    this.logger.start(['tileMaxTime', 'mapTime']);

    var markers = this.getTileMarkers({coord:coord, zoom:zoom, id:this.getTileId(coord, zoom)}),
        canvas = document.createElement('canvas'),
        wcoord = { x:(coord.x * this.TILE_SIZE - this.tileOffset.x) / this.pow2(zoom),
            y:(coord.y * this.TILE_SIZE - this.tileOffset.y) / this.pow2(zoom)};
    canvas.width = this.TILE_SIZE + this.tileOffset.x * 2;
    canvas.height = this.TILE_SIZE + this.tileOffset.y * 2;
    canvas.style.border = 'none';
    canvas.style.borderWidth = '0';
    canvas.style.overflow = 'visible';
    canvas.style.width = this.TILE_SIZE + this.tileOffset.x * 2 + 'px';
    canvas.style.height = this.TILE_SIZE + this.tileOffset.y * 2 + 'px';
    canvas.style.marginLeft = -this.tileOffset.x + 'px';
    canvas.style.marginTop = -this.tileOffset.y + 'px';

    canvas = this.renderCanvas(canvas, wcoord, zoom, markers);

    this.logger.end('tileMaxTime').
        stop('mapTime').
        inc({visibleCount:markers.length}).
        set({mapZoom:zoom, mapDOMCount:$(this.container).find('*').length}).
        log('tile ' + coord.x + '~' + coord.y + '~' + zoom + '~' + markers.length);
    return canvas;
};


CityMap.prototype.renderCanvas = function (canvas, wcoord, zoom, markers) {
    this.logger.start(['renderMaxTime', 'mapDrawTime']);
    var cnvdata = [ ];
    for (var i = 0, len = markers.length; i < len; i++) {
        var marker = markers[i],
            pos = { x:Math.round((marker.wcoord.x - wcoord.x) * this.pow2(zoom)),
                y:Math.round((marker.wcoord.y - wcoord.y) * this.pow2(zoom))};

        // todo: разобраться, почему бывает undefined
        if (marker.icon == undefined) {
            continue;
        }
        var iconImage = this.getIconImage(marker.icon);
        cnvdata.push([this._sprite, iconImage.origin.x, iconImage.origin.y, iconImage.size.width, iconImage.size.height,
            (pos.x - iconImage.anchor.x), (pos.y - iconImage.anchor.y),
            iconImage.size.width, iconImage.size.height]);
        if ('shadow' in iconImage) {
            pos = { x:pos.x - iconImage.shadow.anchor.x, y:pos.y - iconImage.shadow.anchor.y };
            cnvdata.unshift([this._sprite, iconImage.shadow.origin.x, iconImage.shadow.origin.y, iconImage.shadow.size.width, iconImage.shadow.size.height,
                pos.x, pos.y, iconImage.shadow.size.width, iconImage.shadow.size.height]);
        }
    }


    if (ctx != null) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    } else {
        if(!canvas.getContext)
            return;
        ctx = canvas.getContext("2d");
    }



    ctx.rect(0, 0, canvas.width, canvas.height);

    if (this._debug) {
        ctx.globalAlpha = 0.1;
        ctx.fillStyle = 'blue';
        ctx.fill(0, 0, canvas.width, canvas.height);
        ctx.globalAlpha = 1;
    }
    for (var i = 0; i < cnvdata.length; i++) {
        ctx.drawImage.apply(ctx, cnvdata[i]);
    }
    this.logger.end('renderMaxTime').stop('mapDrawTime').inc('mapRenderCount');
    return canvas;
};

CityMap.prototype.getIconBounds = function (marker, zoom) {
    var iconImage = this.getIconImage(marker.icon);
    return {left:(iconImage.anchor.x) / this.pow2(zoom), right:(iconImage.size.width - iconImage.anchor.x) / this.pow2(zoom),
        top:(iconImage.anchor.y) / this.pow2(zoom), bottom:(iconImage.size.height - iconImage.anchor.y) / this.pow2(zoom) };
};
CityMap.prototype.getPointByLatLng = function (lat, lng, zoom) {
    if (!!this.marker && this.marker.lat == lat && this.marker.lng == lng) return this.marker.point;
    var wcoord = this.controller.projection.toGlobalPixels({lat:lat, lng:lng}),
        tcoord = { 'x':Math.floor(wcoord.x * this.pow2(zoom) / this.TILE_SIZE), 'y':Math.floor(wcoord.y * this.pow2(zoom) / this.TILE_SIZE)},
        pixelPoint = {'x':wcoord.x * this.pow2(zoom), 'y':wcoord.y * this.pow2(zoom)},
        pixelTile = {'x':tcoord.x * this.TILE_SIZE, 'y':tcoord.y * this.TILE_SIZE},
        tiles = [ ];

    tiles.push({coord:tcoord, zoom:zoom});
    if (pixelPoint.x - pixelTile.x < this.tileOffset.x)
        tiles.push({coord:{'x':tcoord.x - 1, 'y':tcoord.y}, zoom:zoom});
    if (pixelTile.x + this.TILE_SIZE - pixelPoint.x < this.tileOffset.x)
        tiles.push({coord:{'x':tcoord.x + 1, 'y':tcoord.y}, zoom:zoom});
    if (pixelPoint.y - pixelTile.y < this.tileOffset.y)
        tiles.push({coord:{'x':tcoord.x, 'y':tcoord.y - 1}, zoom:zoom});
    if (pixelTile.y + this.TILE_SIZE - pixelPoint.y < this.tileOffset.y)
        tiles.push({coord:{'x':tcoord.x, 'y':tcoord.y + 1}, zoom:zoom});
    for (var i = 0; i < tiles.length; i++) {
        var markers = this._getTileCache(tiles[i]);
        if (!!markers) {
            for (var j = 0, len = markers.length; j < len; j++) {
                var marker = markers[j];
                if (!('bounds' in marker)) marker.bounds = this.getIconBounds(marker, zoom);
                if ((wcoord.x > (marker.wcoord.x - marker.bounds.left)) && (wcoord.x < (marker.wcoord.x + marker.bounds.right)) &&
                    (wcoord.y > (marker.wcoord.y - marker.bounds.top)) && ( wcoord.y < (marker.wcoord.y + marker.bounds.bottom))) {
                    this.marker = marker;
                    return this.marker;
                }
            }
        }
    }
    this.marker = null;
    return this.marker;
};

CityMap.prototype.markerEvent = function (eventType, marker) {
    var self = this;
    if (typeof marker != 'undefined') {
        this.marker = marker;
    }
    switch (eventType) {
        case 'mouseenter':
            break;
        case 'click':
            if (!!(this.marker)) {
                if (this.marker.type == 'cluster') {
                    if (eventType == 'click') {
                        var zoom = this.explodeCluster(this.marker);
                        this.controller.setCenter(this.marker, zoom);
                    }
                } else {
                    this.balloon.open(this.marker);
                }
            }
            break;
        case 'mouseup':
            break;
    }
    ;
};

CityMap.prototype.showLegend = function () {
    var html = '', iconImage, legendIcons, legendElement, legendWrapper, shrinkedWidth;
    if (this._icons.length == 0) return;
    legendIcons = this._icons.concat([this._clusterIcon, this._groupIcon]);
    shrinkedWidth = 0;
    for (var i = 0; i < legendIcons.length; i++) {
        iconImage = legendIcons[i];
        shrinkedWidth = Math.max(shrinkedWidth, iconImage.size.width, (iconImage.shadow) ? iconImage.shadow.size.width : 0);
        html += '<tr>' +
            '<td class="citymaps-legend-icon">';
        if (!!iconImage.shadow) {
            html += '<div style="position:relative;' +
                'width: ' + Math.max(iconImage.size.width, iconImage.shadow.size.width) + 'px;  ' +
                'height: ' + Math.max(iconImage.size.height, iconImage.shadow.size.height) + 'px; ">';
            html += '<div style="width: ' + iconImage.size.width + 'px; ' +
                'height: ' + iconImage.size.height + 'px; ' +
                'background: url(' + this._sprite.src + ') no-repeat -' + (iconImage.origin.x) + '.0px -' + (iconImage.origin.y) + '.0px;' +
                'position: absolute;' +
                'overflow: hidden;' +
                'left: ' + (iconImage.shadow.anchor.x - iconImage.anchor.x) + 'px;' +
                'top: ' + (iconImage.shadow.anchor.y - iconImage.anchor.y) + 'px;' + '"></div>';

            html += '<div style="' +
                'width: ' + iconImage.shadow.size.width + 'px; ' +
                'height: ' + iconImage.shadow.size.height + 'px; ' +
                'overflow : hidden;' +
                'background: url(' + this._sprite.src + ') no-repeat -' + (iconImage.shadow.origin.x) + 'px -' + (iconImage.shadow.origin.y) + 'px;' +
                '"></div>';
            html += '</div>';
        } else {
            html += '<div style="width: ' + iconImage.size.width + 'px; height: ' + iconImage.size.height + 'px; ' +
                'background: url(' + this.sprite.src + ') no-repeat -' + (iconImage.origin.x + 1) + 'px -' + (iconImage.origin.y + 1) + 'px"></div>';
        }
        html += '</td>' +
            '<td class="citymaps-legend-title">' + iconImage.title + '</td>' +
            '</tr>';
    }
    shrinkedWidth += 5;
    legendElement = $('<div class="citymaps-legend-container" ><table>' + html + '</table></div>');
    if (this._legendElement) {
        this._legendElement.replaceWith(legendElement)
    } else {
        legendWrapper = $('<div class="citymaps-legend-wrapper"></div>').
            append(legendElement).
            css({'width':shrinkedWidth + 'px'}).
            mouseenter(function () {
                $(this).stop(true, true).delay(500).animate({'width':'120'})
            }).
            mouseleave(function () {
                $(this).stop(true, true).animate({'width':'+=5'}, function () {
                    $(this).animate({'width':shrinkedWidth })
                })
            });
        this.controller.addLegend(legendWrapper);
    }
    this._legendElement = legendElement;
};
// computing funcs
CityMap.prototype.pow2 = function (k) {
    if (typeof this._pow2[k] == 'undefined') {
        this._pow2[k] = Math.pow(2, k);
    }
    return this._pow2[k];
};
CityMap.prototype.getTileId = function (coord, zoom) {
    var test = 0, res = '', t = '', i = 0;
    for (var z = zoom; z >= 0; z--) {
        test = this.pow2(z);
        t = ((((coord.x & test) > 0) ? 1 : 0) + (((coord.y & test) > 0) ? 2 : 0)).toString();
        if (i <= this.MAX_INDEXED_ZOOM) res += t;
        i++;
    }
    return res;
};
// static projection
CityMap.Projection = function (options) {
    var radius = options && options.radius || 6378137,
        equator = 2 * Math.PI * radius,
        subequator = 1 / equator,
        halfEquator = equator / 2,
        c_180pi = 180 / Math.PI,
        pixelsPerMeter = [ ],
        _pow2 = [ ],
        self = this,
        mercator = new function () {
            var radius = options && options.radius || 6378137,
                e = options && typeof options.e != "undefined" ? options.e : 0.0818191908426,
            // Р¤Р»Р°Рі РёРЅРІРµСЂСЃРЅРѕРіРѕ РїРѕСЂСЏРґРєР° РєРѕРѕСЂРґРёРЅР°С‚
                inverseOrder = options && options.coordinatesOrder == 'latlong',
            // Р§РµС‚РЅС‹Рµ СЃС‚РµРїРµРЅРё СЌРєСЃС†РµРЅС‚СЂРёСЃРёС‚РµС‚Р°
                e2 = e * e, e4 = e2 * e2, e6 = e4 * e2, e8 = e4 * e4,
                subradius = 1 / radius,
            // РџСЂРµРґРІС‹С‡РёСЃР»РµРЅРЅС‹Рµ РєРѕСЌС„С„РёС†РёРµРЅС‚С‹ РґР»СЏ Р±С‹СЃС‚СЂРѕРіРѕ РѕР±СЂР°С‚РЅРѕРіРѕ РїСЂРµРѕР±СЂР°Р·РѕРІР°РЅРёСЏ РњРµСЂРєР°С‚РѕСЂР°
            // РџРѕРґСЂРѕР±РЅРµРµ СЃРј. С‚СѓС‚: http://mercator.myzen.co.uk/mercator.pdf С„РѕСЂРјСѓР»Р° 6.52
            // Р Р°Р±РѕС‚Р°РµС‚ С‚РѕР»СЊРєРѕ РїСЂРё РЅРµР±РѕР»СЊС€РёС… Р·РЅР°С‡РµРЅРёСЏ СЌРєСЃС†РµРЅС‚СЂРёСЃРёС‚РµС‚Р°!
                d2 = e2 / 2 + 5 * e4 / 24 + e6 / 12 + 13 * e8 / 360,
                d4 = 7 * e4 / 48 + 29 * e6 / 240 + 811 * e8 / 11520,
                d6 = 7 * e6 / 120 + 81 * e8 / 1120,
                d8 = 4279 * e8 / 161280,

                c_pi180 = Math.PI / 180,
                c_180pi = 180 / Math.PI;
            this.mercatorToGeo = function (mercator) {
                var longitude = this.xToLongitude(mercator[0]),
                    latitude = this.yToLatitude(mercator[1]);

                return {lat:latitude, lng:longitude};
            };

            this.geoToMercator = function (geo) {
                return { x:this.longitudeToX(geo.lng),
                    y:this.latitudeToY(geo.lat)
                };
            };

            this.xToLongitude = function (x) {
                return cycleRestrict(x * subradius, -Math.PI, Math.PI) * c_180pi;
            };

            this.yToLatitude = function (y) {
                var xphi = Math.PI * 0.5 - 2 * Math.atan(1 / Math.exp(y * subradius)),
                    latitude = xphi + d2 * Math.sin(2 * xphi) + d4 * Math.sin(4 * xphi) + d6 * Math.sin(6 * xphi) + d8 * Math.sin(8 * xphi);
                return latitude * c_180pi;
            };

            this.longitudeToX = function (lng) {
                var longitude = self.cycleRestrict(lng * c_pi180, -Math.PI, Math.PI);
                return radius * longitude;
            };

            this.latitudeToY = function (lat) {
                var latitude = lat * c_pi180,
                    epsilon = 1e-10,
                    esinLat = e * Math.sin(latitude);
                // Р”Р»СЏ С€РёСЂРѕС‚С‹ -90 РїРѕР»СѓС‡Р°РµС‚СЃСЏ 0, Рё РІ СЂРµР·СѓР»СЊС‚Р°С‚Рµ РїРѕ С€РёСЂРѕС‚Рµ РІС‹С…РѕРґРёС‚ -Infinity
                var tan_temp = Math.tan(Math.PI * 0.25 + latitude * 0.5) || epsilon,
                    pow_temp = Math.pow(Math.tan(Math.PI * 0.25 + Math.asin(esinLat) * 0.5), e),
                    U = tan_temp / pow_temp;

                return radius * Math.log(U);
            };
        };
    this.pow2 = function (k) {
        if (typeof _pow2[k] == 'undefined') _pow2[k] = Math.pow(2, k);
        return _pow2[k];
    };
    this.fromGlobalPixels = function (wcoord) {
        var zoom = (arguments.length == 2) ? arguments[1] : 0;
        if (typeof pixelsPerMeter[zoom] == 'undefined') {
            pixelsPerMeter[zoom] = this.pow2(zoom + 8) * subequator;
        }
        var longitude = this._globalPixelXToGeo(wcoord.x, zoom),
            latitude = mercator.yToLatitude(halfEquator - wcoord.y / pixelsPerMeter[zoom]);

        return {lat:latitude, lng:longitude};
    };

    this.toGlobalPixels = function (geo) {
        var zoom = (arguments.length == 2) ? arguments[1] : 0;
        if (typeof pixelsPerMeter[zoom] == 'undefined') {
            pixelsPerMeter[zoom] = this.pow2(zoom + 8) * subequator;
        }

        var mercatorCoords = mercator.geoToMercator(geo);
        return { x:(halfEquator + mercatorCoords.x) * pixelsPerMeter[zoom],
            y:(halfEquator - mercatorCoords.y) * pixelsPerMeter[zoom]
        };
    };

    this.toTileCoord = function (geo, zoom) {
        var wcoord = this.toGlobalPixels(geo, zoom - 8);
        return {x:Math.floor(wcoord.x), y:Math.floor(wcoord.y) };
    };
    this.globalToTileCoord = function (wcoord, zoom) {
        return {x:Math.floor(wcoord.x * this.pow2(zoom - 8)), y:Math.floor(wcoord.y * this.pow2(zoom - 8)) };
    };

    this.distanceToGlobalPixels = function (geo, distance, zoom) {
        // bugbug - works?
        var meterPerPixels = (equator * Math.cos(geo.lng * Math.PI / 180)) / Math.pow(2, zoom + 8);
        return Math.abs(distance / meterPerPixels);
    };
    this._globalPixelXToGeo = function (x, zoom) {
        return this.cycleRestrict(Math.PI * x / this.pow2(zoom + 7) - Math.PI, -Math.PI, Math.PI) * c_180pi;
    };
    this.cycleRestrict = function (value, min, max) {
        if (value == Number.POSITIVE_INFINITY) {
            return max;
        } else if (value == Number.NEGATIVE_INFINITY) {
            return min;
        }
        return value - Math.floor((value - min) / (max - min)) * (max - min);
    };
    return this;
};
CityMap.Projection.wgs84Mercator = { };
CityMap.Projection.sphericalMercator = {e:0};
// static balloon
CityMap.Balloon = function (model) {
    var self = this;
    this.marker = null;
    this.point = -1;
    this.nli;

    this.timer = null;
    this.model = model;
    this.build = function (parentElement) {
        $(parentElement).find('.citymaps-balloon-buttons').on('click', 'li',function (e) {
            e.stopPropagation();
            switch (e.target.className) {
                case 'citymaps-balloon-back':
                    self.back();
                    break;
                case 'prev-doctor':
                    self.prev();
                    break;
                case 'next-doctor':
                    self.next();
                    break;
                case 'prev-doctor-way':
                    self.prev();
                    break;
                case 'next-doctor-way':
                    self.next();
                    break;
                case 'citymaps-balloon-close':
                    self.close();
            }
        }).
            end().
            find('.citymaps-balloon-content').
            on('mouseenter', 'td',function () {
                $(this).parent().addClass('citymaps-balloon-content-hover');
            }).
            on('mouseout', 'td',function () {
                $(this).parent().removeClass('citymaps-balloon-content-hover');
            }).
            on('click', 'td',function () {
                $(this).parent().addClass('citymaps-balloon-content-selected');
                self.open.call(self, self.marker, $(this).parent().index());
            }).
            end().
            find('.citymaps-balloon-buttons li a').each(function () {
                switch (this.className) {
                    case 'citymaps-balloon-back':
                        $(this).css('display', (self.point >= 0) ? 'block' : 'none');
                        break;
                    case 'prev-doctor':
                        $(this).css('display', (self.point > 0) ? 'block' : 'none');
                        break;
                    case 'next-doctor':
                        $(this).css('display', (self.point >= 0 && self.point < (self.marker.points.length - 1) ? 'block' : 'none'));
                        break;
                }
            });

        for (var i=1;i<=self.marker.points.length;i++) {
            if (self.marker.points.length>1) {
                $(parentElement).find('.nav-doctor-card').append('<li></li>');
            }
        }
        $(parentElement).find('.nav-doctor-card li').on('click',function (e) {
            self.sel($(this).index('.nav-doctor-card li'));
        });
        self.actnav(this.nli);
    };
    this.sel = function (x) {
        this.open(this.marker, x);
        this.nli = x;
    };
    this.actnav = function (x) {
        $('.citymaps-balloon-content').find('.nav-doctor-card li').eq(x).addClass('active');
    };
    this.clear = function (parentElement) {
        $(parentElement).find('.citymaps-balloon-content').off('**');
    };
    this.back = function () {
        if (this.marker && this.marker.type == 'group') {
            this.open(this.marker);
        }
    };
    this.prev = function () {
        if (this.marker && this.point > 0) {
            this.open(this.marker, this.point - 1);
        }
        this.nli = this.point;
    };
    this.next = function () {
        if (this.marker && this.point < (this.marker.points.length - 1)) {
            this.open(this.marker, this.point + 1);
        }
        this.nli = this.point;
    };
    this.open = function (marker) {
        this.nli = 0;
        // showpoint data
        var self = this, coords, url;
        this.marker = marker;
        this.point = (arguments.length == 2) ? arguments[1] : -1;
        if (this.marker.type == 'group' && this.point == -1) {
            var cols,
                rows = '';
            coords = {lat:this.marker.lat, lng:this.marker.lng};
            for (var i = 0, len = this.marker.points.length; i < len; i++) {
                point = this.marker.points[i];
                rows += '<tr>';
                cols = this.model.getSchema(point, 'data').split('%');
                for (var j = 0; j < cols.length; j++)  rows += '<td>' + cols[j] + '</td>';
                rows += '</tr>';
            }
            this.model.controller.balloon.open(coords, '#citymaps-balloon-group-template', {rowContent:rows, title:marker.title});
            $('.citymaps-balloon-content table tr:first-child td').trigger('click');
        } else {
            point = (this.point == -1) ? marker.points[0] : marker.points[this.point];
            url = this.model._dataUrl + this.model.getSchema(point, 'id');
            coords = this.model.controller.projection.fromGlobalPixels(marker.wcoord);
            $.get(url, function (data) {
                self.model.controller.balloon.open.call(self.model.controller.balloon, coords, '#citymaps-balloon-template', data);
            });
        }
    };
    this.close = function () {
        this.model.controller.balloon.close();
        marker = null;
    };
    return this;
};
/// static logger
CityMapLogger = {
    _vars:{ },
    _timer:{ },
    _timeout:null,
    _logText:'',
    onchange:null,
    start:function (varnames) {
        var paramName;
        if (typeof varnames != 'object') varnames = [ varnames ];
        for (var i = 0; i < varnames.length; i++) {
            paramName = varnames[i];
            if (!((typeof this._timer[paramName] != 'undefined') && this._timer[paramName] != 0)) this._timer[paramName] = new Date();
        }
        return this;
    },
    stop:function (varnames) {
        var value, paramName;
        if (typeof varnames != 'object') varnames = [varnames];
        for (var i = 0; i < varnames.length; i++) {
            paramName = varnames[i];
            value = (typeof this._timer[paramName] == 'undefined') ? 0 : (new Date() - this._timer[paramName]);
            if (typeof this._vars[paramName] == 'undefined') this._vars[paramName] = 0;
            this._vars[paramName] += value;
            this._timer[paramName] = 0;
        }
        this.change();
        return this;
    },
    end:function (varnames) {
        var value, paramName;
        if (typeof varnames != 'object') varnames = [varnames];
        for (var i = 0; i < varnames.length; i++) {
            paramName = varnames[i];
            value = (typeof this._timer[paramName] == 'undefined') ? 0 : (new Date() - this._timer[paramName]);
            if (typeof this._vars[paramName] == 'undefined') this._vars[paramName] = 0;
            this._vars[paramName] = Math.max(this._vars[paramName], value);
            this._timer[paramName] = 0;
        }
        this.change();
        return this;
    },
    change:function () {
        if (this.onchange) {
            var self = this;
            if (this._timeout) window.clearTimeout(this._timeout);
            this._timeout = window.setTimeout(function () {
                window.clearTimeout(self._timeout);
                self._timeout = null;
                self.onchange()
            }, 500);
        }
        return this;
    },
    set:function (varHash) {
        var varname, value;
        if (arguments.length == 2) {
            varname = arguments[0];
            value = arguments[1];
            varHash = { };
            varHash[varname] = value;
        }
        for (varname in varHash) {
            if (varHash.hasOwnProperty(varname)) {
                this._vars[varname] = varHash[varname];
            }
        }
        this.change();
        return this;
    },
    max:function (varHash) {
        var varname, value;
        if (arguments.length == 2) {
            varname = arguments[0];
            value = arguments[1];
            varHash = { };
            varHash[varname] = value;
        }
        for (varname in varHash) {
            if (varHash.hasOwnProperty(varname)) {
                this._vars[varname] = (typeof this._vars[varname] == 'undefined') ? varHash[varname] : Math.max(this._vars[varname], varHash[varname]);
            }
        }
        this.change();
        return this;
    },
    inc:function (varHash) {
        var varname, value, inc;
        if (arguments.length == 2) {
            varname = arguments[0];
            value = arguments[1];
            varHash = { };
            varHash[varname] = value;
        } else if (typeof varHash != 'Object') {
            varname = varHash;
            value = 1;
            varHash = { };
            varHash[varname] = value;
        }
        for (varname in varHash) {
            if (varHash.hasOwnProperty(varname)) {
                if (typeof this._vars[varname] == 'undefined') {
                    this._vars[varname] = (typeof varHash[varname] == 'undefined') ? 1 : varHash[varname];
                } else {
                    this._vars[varname] += (typeof varHash[varname] == 'undefined') ? 1 : varHash[varname];
                }
            }
        }
        this.change();
        return this;
    },
    clear:function (varnames) {
        if (typeof varnames == 'undefined')
            varnames = ['visibleCount', 'mapDrawTime', 'mapSelectTime', 'mapTime', 'sortTime', 'clusterTime', 'tileMaxCount', 'tileMaxVisibleCount', 'clusterMaxTime', 'clusterTime', 'mapDOMCount', 'visibleTileCount', 'renderMaxTime', 'tileMaxTime', 'mapTime'];
        //varnames = ['visibleCount', 'tileMaxCount', 'tileMaxVisibleCount', 'clusterMaxTime', 'mapDOMCount', 'visibleTileCount', 'renderMaxTime', 'tileMaxTime'];
        for (var index in varnames) {
            if (varnames.hasOwnProperty(index) && (typeof this._vars[varnames[index]] != 'undefined')) {
                this._vars[varnames[index]] = 0;
            }
        }
        this.change();
        return this;
    },
    get:function (param) {
        return (typeof this._vars[param] == 'undefined') ? 'NaN' : this._vars[param];
    },
    log:function (text) {
        return this;
    },
    print:function () {
        var t = this._logText;
        this._logText = '';
        return t;
    },
    update:function () {
        this.set({ mapDOMCount:$(citymap.container).find('*').length, mapZoom:citymap.controller.map.getZoom()});
        this.change();
    }
};

// Google controller
GisController = function (model, options) {
    this.map = null;
    this.model = model;
    this.logger = model.logger;
    this.maxZoom = 22;
    this._hover = null;
    this.initMap(options);
};

GisController.prototype.mapReady = function () {
    return (!!this.map)
};

GisController.prototype.initMap = function (options) {
    /*
     var mapOptions = {
     mapTypeId:google.maps.MapTypeId.ROADMAP,
     zoom:options.zoom,
     center:new google.maps.LatLng(options.center.lat, options.center.lng),
     streetViewControl:false,
     featureType:'all',
     panControl:false,
     navigationControlOptions:{
     position:google.maps.ControlPosition.LEFT_CENTER
     }
     };*/
    this.map = new DG.Map(this.model.container);
    this.map.setCenter(new DG.GeoPoint(options.center.lng, options.center.lat));
    this.map.setZoom(12);
    this.projection = CityMap.Projection(CityMap.Projection.sphericalMercator);
};

GisFullCanvasController = function (model, options) {
    GisFullCanvasController.superclass.constructor.apply(this, arguments);
    this._tiles = [ ];
};
extend(GisFullCanvasController, GisController);

GisController.prototype.initialize = function () {
    var self = this;
    this.model.tileOffset = {x:0, y:0};
    this.canvasOverlay = this.createCanvasOverlay();
    var self = this;

};

GisController.prototype.createHover = function () {
    this._hover = new google.maps.Marker({ map:this.map, visible:false, cursor:'pointer'});
    var self = this;
    google.maps.event.addListener(this.map, 'mousemove', function (e) {
        self.model.marker = self.model.getPointByLatLng.call(self.model, e.latLng.lat(), e.latLng.lng(), self.map.getZoom());
        self.showHover.call(self, self.model.marker);
    });
    google.maps.event.addListener(this._hover, 'click', function (e) {
        self.showHover.call(self, null);
        self.model.markerEvent.call(self.model, 'click');
    });
    google.maps.event.addListener(this.map, 'zoomchange', function (e) {
        self.showHover.call(self, null);
    });
    google.maps.event.addDomListener(this.map.getDiv(), 'touchstart', function (e) {
        var divpos = $(self.map.getDiv()).position(),
            point = {x:e.pageX - divpos.left, y:e.pageY - divpos.top},
            bounds = self.getBounds(),
            pixel = self.projection.toGlobalPixels({lat:bounds.north, lng:bounds.west}, self.getZoom()),
            latLng = self.projection.fromGlobalPixels({x:pixel.x + point.x, y:pixel.y + point.y}, self.getZoom());

        self.model.marker = self.model.getPointByLatLng.call(self.model, latLng.lat, latLng.lng, self.getZoom());

        self.model.markerEvent.call(self.model, 'click');
    });
};
GisController.prototype.showHover = function (marker) {
    if (!!marker) {
        var iconImage = this.createIcon(marker.icon);
        this._hover.setIcon(iconImage.icon);
        if (typeof iconImage.shadow != 'undefined')    this._hover.setShadow(iconImage.shadow);
        this._hover.setTitle(marker.title);
        this._hover.setPosition(new google.maps.LatLng(marker.lat, marker.lng));
        this._hover.setVisible(true);
    } else {
        this._hover.setVisible(false);
    }
};

/*
 GController.prototype.createIcon = function (icon) {
 icon = this.model.getIconImage(icon);
 var iconImage = { icon:new google.maps.MarkerImage(icon.src,
 new google.maps.Size(icon.size.width, icon.size.height),
 new google.maps.Point(icon.origin.x, icon.origin.y),
 new google.maps.Point(icon.anchor.x, icon.anchor.y))};
 if (typeof icon.shadow != 'undefined') {
 iconImage.shadow = new google.maps.MarkerImage(icon.src,
 new google.maps.Size(icon.shadow.size.width, icon.shadow.size.height),
 new google.maps.Point(icon.shadow.origin.x, icon.shadow.origin.y),
 new google.maps.Point(icon.shadow.anchor.x, icon.shadow.anchor.y))
 }
 return iconImage;
 };*/

GisFullCanvasController.prototype.createCanvasOverlay = function () {
    var CanvasControl = function (params, controller) {
        return {
            parent:null,
            layout:null,
            map:null,
            zoom:0,
            controller:params.controller,
            // events:new ymaps.event.Manager({context:this}),
            // state:new ymaps.data.Manager(params.data),
            pane:null,
            inaction:false,
            actiontimer:null,
            tick:5,
            getParent:function () {
                return this._parent;
            },
            setParent:function (parentObject) {
                this.parent = parentObject;
                this.map = parentObject.getMap();
                if (this.layout == null) {
                    this.createLayout();
                }
            },
            createLayout:function () {
                // var controlsPane = this.getMap().panes.get('controls'),
                var container = this.getContainer(),
                    self = this;

                this.getMap().addEventListener(this.getMap().getContainerId(), 'DgMapMove', function (e) {
                    self._draw();
                });

                this.getMap().addEventListener(this.getMap().getContainerId(), 'DgClick', function (e) {
                    self._draw();
                });

                this.layout = document.createElement('canvas');

                $(this.layout).
                    css({'position':'absolute',
                        zIndex:1000}).
                    appendTo(container);
                this._draw();
            },
            getMap:function () {
                return this.controller.map;
            },
            getContainer:function () {
                return this.getMap().getContainer();
            },
            getPane:function () {
                return this.getMap().getPane();
            },
            _draw:function () {
                var zoom = this.getMap().getZoom(),
                    bounds = this.getMap().getBounds();

                var topleft = this.getMap().converter.coordinatesToMapPixels(bounds.getLeftTop());
                var rightbottom = this.getMap().converter.coordinatesToMapPixels(bounds.getRightBottom());


                var top = 0,
                    left = 0,
                    width = (typeof FlashCanvas == 'undefined') ? Math.ceil(($(this.getContainer()).width() + 1 ) / 256) * 256 : 2000,
                    height = (typeof FlashCanvas == 'undefined') ? Math.ceil(($(this.getContainer()).height() + 1 ) / 256) * 256 : 2000;

                this._offset = {x:(width - $(this.getContainer()).width()) / 2, y:(height - $(this.getContainer()).height()) / 2};

                if (Math.round(zoom) == zoom) {
                    var k = this.controller.model.pow2(Math.round(zoom));

                    var globalBounds = [this.controller.projection.toGlobalPixels({lat:bounds.getTop(), lng:bounds.getLeft()}),
                        this.controller.projection.toGlobalPixels({lat:bounds.getBottom(), lng:bounds.getRight()})];

                    this.zoom = zoom;
                    this.layout.width = width;
                    this.layout.height = height;
                    this.controller.model.renderMapCanvas(this.layout, globalBounds, zoom);
                } else {
                    var d = (zoom > this.zoom) ? (1 + zoom - this.zoom) : (1 - this.zoom + zoom );
                    left = left * d;
                    top = top * d;
                    width = width * d;
                    height = height * d;
                }

                $(this.layout).css({ left:left + 'px', top:top + 'px',
                    width:width + 'px', height:height + 'px'});
            }
        }
    };


    var control = new CanvasControl({data:{}, controller:this});
    control.createLayout();

    gl_control = control;

};
