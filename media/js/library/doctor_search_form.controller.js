var DoctorSearchFormController = function (
    landing,
    already_registred_account,
    url_page,
    ajax_search_url
) {

    this.landing_page = landing;
    this.url_page = url_page;
    this.ajax_search_url = ajax_search_url;
    this.already_registred_account = already_registred_account;
    this.service_categories = null;

    this.specialty_id = null;
    this.specialty_alias = null;
    this.discount = null;
    this.purpose_of_visit_id = null;

    this.doctor_name = null;
    this.doctor_sex_id = null;

    this.visit_type = null;
    this.doctor_type = null;
    this.morning_time = 0;
    this.evening_time = 0;
    this.weekend_time = 0;
    this.any_time = 0;

    this.latitude = null;
    this.longitude = null;

    this.is_metro = 0;
    this.metro_station_name = null;
    this.metro_branch_name = null;

    this.page = 1;
    this.by_page = 10;

    this.sort_by = 'rate';
    /** @var {YandexMapController} */
    this.map_controller = null;
    this.searchProvider = new SearchProvider(this);

    this.city_id = null;
    this.district_id = null;
    this.region_id = null;
    this.street_id = null;
    this.metro_station_id = null;

    this.bounds = null;

    this.container = '#doctor-search-form';
    this.mode = 'block';

    this.full_map = null;

    this.listing = true;

    this.doctor_name_search_flag = null;
    var self = this;

    this.setCityId = function(city_id){
        self.city_id = city_id;

        if (self.map_controller != null) {
            self.map_controller.setCityId(self.city_id);
        }

        if (self.searchProvider != null) {
            self.searchProvider.setCityId(self.city_id);
        }
    };

    this.init = function () {
        self.attachEvents();
        self.searchProvider.init();
        if (!self.url_page) {
            self.url_page = '/doctor';
        }
        if (this.mode == 'page') {
            city_controller.subscribe(self.setCityInfo);
            city_controller.subscribe(self.changeSpecialtiesListToSearchDoctors);
            self.city_id = city_controller.city_id;
        };

        if (this.mode == 'page') {
            self.initParamsFromUrl();
            self.initElements();

            self.map_controller = new YandexMapController(self);
            self.map_controller.city_id = self.city_id;
            self.map_controller.page = 'doctor';
            self.map_controller.full_map = self.full_map;
            self.map_controller.setDataUrl('/ajax/getDoctorClinicCard?big=0&id=');
            self.map_controller.init();
            self.getMap();
        }
        else {
            self.specialty_id = 29;
            self.specialty_alias = 'terapevt';
            setCustomSelect(self.container + ' select[name="specialty_id"]', self.specialty_id);
            self.loadPurposeOfVisitBlock();
        }
        $(document).on('click', '.h1_colapse', function () {
            $(this).toggleClass('active');

            if ($(this).hasClass('active')) {
                self.removeColapse();
            } else {
                self.addColapse();
            };
        });

        $(document).on('click', '.colapse', function () {
            $(this).toggleClass('active');

            if ($(this).hasClass('active')) {
                self.removeColapse();
            } else {
                self.addColapse();
            };
        });

        $('#doctor-find-txt').hide();
        $('.doctor_search_options').show();

        $('#doctor-find-txt a').click(function(){
            $('#doctor-find-txt').hide();
            $('.doctor_search_options').show();
        });

        $('#doctor-search-form input[name="doctor_name"]').keyup(function(e){
            e = e || window.event;
            if(e.keyCode == 13){

                $('#doctor-search-form input[type="submit"]').click();
            }
        });

        $('.btn-doctor').click(function(){
            var action_for_counters = $(this).data('action-for-counters');
            var category_for_counters = $(this).data('category-for-counters');
            setCounters(category_for_counters, action_for_counters, '', SessionInfo.email);

            send('//ad.adriver.ru/cgi-bin/rle.cgi?sid=194132&sz=naiti_vracha&bt=55&pz=0&rnd=![rnd]');
        });

        $('.in_colapse .chzn-search').remove();

        $('.old_resize').click(function () {
            self.full_map = 0;
        });
    };

    this.setPageMode = function () {
        this.mode = 'page';
    };

    this.setCityInfo = function(city_info){
        self.setCityId(city_info.city_id);

        if (city_info.city_alias && (city_info.city_alias != 'moskva'))
          window.location = '//'+city_info.city_alias + '.'+SessionInfo.domain + self.url_page + self.buildUrl();
        else if(city_info.city_alias == 'moskva')
          window.location = '//'+SessionInfo.domain + self.url_page + self.buildUrl();
    };

    this.setBlockMode = function () {
        this.mode = 'block';
    };

    this.initParamsFromUrl = function () {
        self.from_url = 1;
        self.page = 1;

        self.full_map = getParameterByName('full_map', 0);

        //self.purpose_of_visit_id = parseInt(getParameterByName('purpose_of_visit_id', 0));
        self.time_of_visit = getParameterByName('time_of_visit', 'any');
        self.doctor_name = getParameterByName('doctor_name');
        self.doctor_sex_id = parseInt(getParameterByName('doctor_sex_id', 0));
/* commented for a while (it might be needed!! it relates too to any other commented lines in this file)
        if(self.doctor_name || self.doctor_sex_id)
        {
            $('#doctor-find-txt').hide();
            $('.doctor_search_options').show();
        }
        */
        if(self.doctor_name)
        {
            $('.show_inp').hide();
            $('.pad_tb .search_txt').show();
        }

        self.sort_by = getParameterByName('sort_by', 'rate');
        //self.evening_time = getParameterByName('morning_time', 0);
        self.weekend_time = getParameterByName('weekend_time', 0);
        //self.morning_time = getParameterByName('morning_time', 0);
        self.any_time = getParameterByName('any_time', 1);

        self.longitude = getParameterByName('longitude');
        self.latitude = getParameterByName('latitude');

        if (self.visit_type==null) {
            self.visit_type = getParameterByName('visit_type', 'clinic');
        }

        if (self.doctor_type==null) {
            self.doctor_type = getParameterByName('doctor_type', 'adult');
        }

        if (self.doctor_type == 'male' || self.doctor_type == 'female' || self.doctor_type == 'pregnant'){
            self.doctor_type = 'adult';
        }

        if (self.doctor_type == 'newborn'){
            self.doctor_type = 'children';
        }

        if (self.doctor_type != 'children'){
            $(".inner .colleft  .radioBox, .inner-2 .colleft  .radioBox").removeClass('disable');
        }

        self.longitude = getParameterByName('longitude');
        self.latitude = getParameterByName('latitude');
        if (!self.service_categories)
          self.service_categories = getParameterByName('service_categories', 0)
    };

    this.initElements = function () {
        if (self.specialty_id) {
            setCustomSelect(self.container + ' select[name="specialty_id"]', self.specialty_id);
            //self.loadPurposeOfVisitBlock(self.purpose_of_visit_id, false);
        }

        if (self.doctor_name)
            $('input[name="doctor_name"]').val(self.doctor_name);

        if (self.doctor_sex_id) {
            if (self.mode === 'page') {
                if(self.doctor_sex_id == 3) {
                    $('.sex-1').parent().addClass('act');
                    $('.sex-2').parent().addClass('act');
                } else {
                    $('.sex-' + self.doctor_sex_id).parent().addClass('act');
                }
            } else if (self.mode === 'block') {
                $('.sex').removeClass('selected');

                if(self.doctor_sex_id == 3) {
                    $('.sex-1').addClass('selected');
                    $('.sex-2').addClass('selected');
                } else {
                    $('.sex-' + self.doctor_sex_id).addClass('selected');
                }
            }
        }

        if (self.sort_by)
            $('.sortby[data-type="' + self.sort_by + '"]').parent().addClass('current');

        if (self.visit_type) {

            $('.visit-type').removeClass('act');

            if (self.visit_type == 'clinic') {
                $('.visit-type-clinic').addClass('act');
            }

            if (self.visit_type == 'home') {
                $('.visit-type-home').addClass('act');
            }
        }

        if (self.doctor_type) {

            $('.doctor-type').removeClass('act');

            if (self.doctor_type == 'adult') {
                $('.doctor-type-adult').addClass('act');
            }
            if (self.doctor_type == 'children') {
                $('.doctor-type-children').addClass('act');
            }

            if (self.doctor_type == 'pregnant') {
                $('.doctor-type-pregnant').addClass('act');
            }
        }

        if (self.any_time == 0)
            $('.time-all').removeClass('act');
        else
            $('.time-all').addClass('act');

        if (self.morning_time == 0)
            $('.time-morning').removeClass('act');
        else
            $('.time-morning').addClass('act');

        if (self.evening_time == 0)
            $('.time-evening').removeClass('act');
        else
            $('.time-evening').addClass('act');

        if (self.weekend_time == 0)
            $('.time-weekend').removeClass('act');
        else
            $('.time-weekend').addClass('act');
    };

    this.attachEvents = function () {

        $(self.container + ' select[name="specialty_id"]').change(function () {

            self.specialty_id = $(this).val();
            if ($(self.container + ' select[name="specialty_id"] option:selected').data('specialty_name') &&
                $(self.container + ' select[name="specialty_id"] option:selected').data('specialty_name').toLowerCase().indexOf('педиатр') == 0){
                if (!$(self.container + ' .choose-list .doctor-type-children').hasClass('act')){
                    $(self.container + ' .choose-list .doctor-type-adult').removeClass('act').addClass('disable');
                    $(self.container + ' .choose-list .doctor-type-adult input').val(0);

                    $(self.container + ' .choose-list .doctor-type-pregnant').removeClass('act').addClass('disable');
                    $(self.container + ' .choose-list .doctor-type-pregnant input').val(0);

                    $(self.container + ' .choose-list .doctor-type-children').addClass('act').removeClass('disable');
                    $(self.container + ' .choose-list .doctor-type-children input').val(1);
                }
            } else {
                if (!$(self.container + ' .choose-list .doctor-type-pregnant').hasClass('act')){
                    $(self.container + ' .choose-list .doctor-type').removeClass('disable');
                    $(self.container + ' .choose-list .doctor-type-children').removeClass('act');
                    $(self.container + ' .choose-list .doctor-type-children input').val(0);

                    $(self.container + ' .choose-list .doctor-type-adult').addClass('act');
                    $(self.container + ' .choose-list .doctor-type-adult input').val(1);
                }
            }

            $(self.container + ' select[name="specialty_id"] option').removeAttr('selected');
            $(self.container + ' select[name="specialty_id"] option[value="' + self.specialty_id + '"]').attr('selected', true);
            setCustomSelect(self.container + ' select[name="specialty_id"]', self.specialty_id);
            self.page = 1;
            //self.loadPurposeOfVisitBlock();
        });

        $(document).on('click', this.container + ' input[type="submit"]', function () {
            var addressInput = $('#address-input');
            if (!addressInput.val() || !addressInput.val().trim().length) {
                self.latitude = self.longitude = null;
            }
            self.page = 1;
            self.listing = 1;
            self.setParams();
            self.sendRequest();
        });

        $(document).on('keypress', this.container, function (e) {
            if (e.which == 13) {
                $(this).find('input[type="submit"]').click();
            }
        });

        $('#search-show-on-map').click(function () {
            // яндекс карта некорректно инициализируется с display:none
            $('.map-box').toggleClass('off-screen');
        });

        if (self.mode == 'page') {
            $('.sort_by').click(function () {
                var type = $(this).data('type');

                if (type) {
                    self.page = 1;
                    self.sort_by = type;
                    self.sendRequest();
                }
            });

            $(".filter li").click(function (e) {
                e.preventDefault();
                $(".filter li").removeClass('current');
                $(this).addClass('current');
            });

            $(".filter .price-tab li").click(function (e) {
                e.preventDefault();
                $(".filter .price-tab li").removeClass('active');
                $(this).addClass('active');
            });

            $(".map-box .resize").click(function (e) {
                self.full_map = 1;
                $("body").addClass('hidden');
                $("footer").hide();
            });

            $(".full-map .resize, .full-map .shell a").click(function (e) {
                self.full_map = 0;
                e.preventDefault();
                $("body").removeClass('hidden');
                $("footer").show();
            });

            $(function () {
                $(window).resize(function () {
                    $('.full-map').height($(window).height());
                });
                $(window).resize();
            });

            $(document).on('click', '.load-next-page', function () {
                setCounters('next-10', 'unknown', '', SessionInfo.email);
                $('.view-more i').addClass('icon-loader');
                self.loadNextPage();
            });
        }
    };

    this.updateSeoInfo = function(){
        var data = {
            city_id : self.city_id,
            district_id: self.district_id,
            region_id: self.region_id,
            street_id: self.street_id,
            metro_station_id: self.metro_station_id,
            specialty_id : self.specialty_id,
            location : 'doctor'
        };
        Ajax.Get('/ajax/getSeoInfoByCityIdAndSpecialtyId', data, function(data){
            if (data.status == 0)
            {
                $('.doctor-special-links').html(data.result.html);
                $('title').html(data.result.title);
                $('h1.js--main-h1').html(data.result.h1);
                $('.js--ajax-breadcrumbs').html(data.result.breadcrumbs);
            }
            else
            {
                $('.doctor-special-links').html('');
                $('title').html('Найти хорошего врача в Москве онлайн. Поиск врачей по всем специальностям, отзывы, рейтинг, запись на прием – Lookmedbook');
            }
        });
    };

    this.setParams = function () {
        var selected_specialty_option = $(self.container + ' select[name="specialty_id"] option[selected="selected"]');

        self.specialty_id = selected_specialty_option.val();
        var breadcrumbs = '<a href="/" title="Главная">Главная</a>', specialtyOption = selected_specialty_option.data('specialty_plural_name');

        if(!self.specialty_id || self.specialty_id == 0) breadcrumbs += ' &rarr; Врачи';
        else breadcrumbs += ' &rarr; <a href="/doctor" title="Врачи">Врачи</a> &rarr; ' + specialtyOption;


        $('.breadcrumbs').html(breadcrumbs);
        self.updateSeoInfo();

        self.specialty_alias = selected_specialty_option.data('specialty_alias');
        self.purpose_of_visit_id = $(self.container + ' select[name="purpose_of_visit_id"]').val();
        self.visit_type = $(self.container + ' .visit-type-clinic').hasClass('act') ? 'clinic' : 'home';
        self.visit_type = $(self.container + ' .visit-type-home').hasClass('act') ? 'home' : 'clinic';
        self.any_time = $(self.container + ' .time-any').hasClass('act') ? 1 : 0;
        self.morning_time = $(self.container + ' .time-morning').hasClass('act') ? 1 : 0;
        self.evening_time = $(self.container + ' .time-evening').hasClass('act') ? 1 : 0;
        self.weekend_time = $(self.container + ' .time-weekend').hasClass('act') ? 1 : 0;

        var man, woman;
        if (self.mode === 'page') {
            man = $(self.container + ' span.man').parent().hasClass('act') ? 1 : 0;
            woman = $(self.container + ' span.woman').parent().hasClass('act') ? 1 : 0;
        } else if (self.mode === 'block') {
            man = $(self.container + ' span.man').hasClass('selected') ? 1 : 0;
            woman = $(self.container + ' span.woman').hasClass('selected') ? 1 : 0;
        }

        if (self.mode === 'page') {
            if ($(self.container + ' .doctor-type-children').hasClass('act')) {
                self.doctor_type = 'children';
            } else {
                self.doctor_type = 'adult';
            }
        } else if (self.mode === 'block') {
            if ($(self.container + ' .doctor-type').hasClass('act')) {
                if ($(self.container + ' .doctor-type-adult').hasClass('act')) self.doctor_type = 'adult';
                if ($(self.container + ' .doctor-type-children').hasClass('act')) self.doctor_type = 'children';
                if ($(self.container + ' .doctor-type-pregnant').hasClass('act')) self.doctor_type = 'pregnant';
            }
        }

        if (man && woman) {
            self.doctor_sex_id = 3;
        } else if (!man && !woman) {
            self.doctor_sex_id = 0;
        } else if (man) {
            self.doctor_sex_id = 1;
        } else if (woman) {
            self.doctor_sex_id = 2;
        }

        var name = $(self.container + ' input[name="doctor_name"]').val();

        if (name && name != 'Введите имя врача')
            self.doctor_name = $(self.container + ' input[name="doctor_name"]').val();
        else
            self.doctor_name = null;
    };

    this.getMap = function () {
        if (!self.map_controller.isLoaded()) {
            setTimeout(function(){
                self.getMap();
            }, 500);
            return;
        }

        Ajax.Get('/ajax/getMapData', self.collectSearchParams(), function (data) {
            self.map_controller.setData(data.result);
            if (self.latitude && self.longitude) {
                self.map_controller.setBoundsFromPointAndDistance(
                    self.latitude, self.longitude
                );
            }
        });
    };

    this.collectSearchParams = function () {
        var params = {
            datatype: 'doctor',
            specialty_id: self.specialty_id,
            //purpose_of_visit_id:self.purpose_of_visit_id,
            doctor_sex_id: self.doctor_sex_id,
            doctor_name: self.doctor_name,
            service_categories: self.service_categories,
            visit_type: self.visit_type,
            doctor_type: self.doctor_type,
            any_time: self.any_time,
            morning_time: self.morning_time,
            evening_time: self.evening_time,
            weekend_time: self.weekend_time,
            page: self.page,
            by_page: self.by_page,
            sort_by: self.sort_by,
            latitude: self.latitude,
            longitude: self.longitude,
            is_metro: self.is_metro,
            metro_station_name: self.metro_station_name,
            metro_branch_name: self.metro_branch_name,
            metro_station_id: self.metro_station_id,
            landing: 1,
            city_id: self.city_id,
            district_id: self.district_id,
            region_id: self.region_id,
            street_id: self.street_id,
            discount: self.discount
        };
        return params;
    };

    this.sendRequest = function (push_state) {
        if (this.mode == 'block') {
            self.setParams();
            var url = self.buildUrl();
            window.location.href = self.url_page + url;
            return;
        }

        // Если обычный решим

        if (push_state == undefined)
            push_state = true;

        if (push_state) {
            var state = {
                title: $('title').val(),
                url: self.url_page + self.buildUrl()
            };

            // заносим ссылку в историю
            if (navigator.appName.indexOf('Explorer') < 0 || (navigator.appName.indexOf('Explorer') + 1 && parseInt($.browser.version, 10) > 8)){
                history.pushState(state, state.title, state.url);
            }
        }

        /* если происходит поиск по имени врача*/
        if (self.doctor_name_search_flag == 1){
            /*$('#doctor-find-txt').hide();
            $('.doctor_search_options').show();*/
            $('.show_inp').hide();
            $('.pad_tb .search_txt').show();
        }

        Ajax.Get(self.ajax_search_url, self.collectSearchParams(), function (data) {
            if (data.status == 0) {
                if (self.page == 1) {

                    if (self.listing) {
                      if (data.result.full_search == false) {
                        if (data.result.is_empty_city == 0)
                        {
                          $('#our-doctors').html('<div class="error-plate">По Вашему запросу ничего не найдено. Возможно Вам подойдёт один из врачей в нашей базе</div>');
                          $(".search-count-block").hide();
                        }
                        else {
                          $('#our-doctors').html('<div class="error-plate">У нас пока нет врачей в городе '+data.result.city_name+'. Мы сообщим, как только они появятся!</div>');
                          $(".search-count-block").hide();
                        }
                        $('#our-doctors').append(data.result.html);
                    } else {
                        $('#our-doctors').empty();
                        $(".count-digit").text(data.result.doctors_total_count);
                        $(".count-doctor").text(data.result.doctor_word_form);
                        $(".count-specialty").text(data.result.specialty_name);
                        $(".search-count-block").show();

                        $('#our-doctors').append(data.result.html);
                      }
                    }

                  self.getBounds(data);
                }
                else {
                    $('.view-more').remove();
                    $('#our-doctors').append(data.result.html);
                }

                if(data.result.search_page_description) {
                    $('.seo-specialty-description').html(data.result.search_page_description);
                }

                if(!data.result.defaultSpecialty) {
                    $('.doctor-special-links .geoBlocks').show();
                }

                if(data.result.topNumberH1) {
                    $('.top_number h1').html(data.result.topNumberH1);
                }

                if(data.result.canonicalLink) {
                    $('link[rel=canonical]').attr('href', data.result.canonicalLink);
                }

                if(!$.isEmptyObject(data.result.specialty_params)) {
                    var formArea = $('.choose-list');
//                    $.each(data.result.specialty_params.formFields, function(typeID, typeFields) {
//
//                        $.each(typeFields, function(fieldsID, fields) {
//                            if(typeID == 'disable') {
//                                formArea.find('.' + fields).removeClass('act').addClass('disable').find('input[type=hidden]').val(0);
//                            } else if(typeID == 'activate') {
//                                formArea.find('.' + fields).addClass('act').find('input[type=hidden]').val(1);
//                            }
//                        });
//                    });
                    $.each(data.result.specialty_params.formFields, function(typeID, typeFields) {
                        $.each(typeFields, function(fieldsID, fieldsData) {
                            if(typeID == 'disable') {
                                formArea.find('.' + fieldsData).removeClass('act').addClass('disable').find('input[type=hidden]').val(0);
                            } else if(typeID == 'activate') {
                                self[fieldsID] = fieldsData;
                            }
                        });
                    });
                    self.initElements();
                }

                if (self.listing && data.result.next_page) {
                    $('#our-doctors').append('<a class="load-next-page view-more" href="javascript:void(0);"><i></i>Показать ещё 10 врачей</a>');
                }

                if (self.listing) {
                  $('.pagination-sm').hide();
                }
                self.listing = 1;

            } else if (data.status == 4) {
                window.location = '/';
            }
        });
    };

    this.getBounds = function (data) {
        if (!self.map_controller.isLoaded()) {
            setTimeout(function(){
                self.getBounds(data);
            }, 500);
            return;
        }

        self.bounds = data.result.bounds;

        if (self.map_controller != undefined)
        {
            self.getMap();
        }
    }

    this.buildUrl = function () {

        var str = '';


        if (self.purpose_of_visit_id > 0) {
            //str = str + '&purpose_of_visit_id=' + self.purpose_of_visit_id;
        }

        if ((self.time_of_visit != null) && (self.time_of_visit != 'any')) {
            str = str + '&time_of_visit=' + self.time_of_visit;
        }

        if ((self.doctor_name != '') && (self.doctor_name != null)) {
            str = str + '&doctor_name=' + self.doctor_name;
        }

        if (self.doctor_sex_id >= 0) {
            str = str + '&doctor_sex_id=' + self.doctor_sex_id;
        }

        if ((self.visit_type != null) && (self.visit_type != 'clinic')) {
            str = str + '&visit_type=' + self.visit_type;
        }

        if ((self.doctor_type != null) && (self.doctor_type != 'adult')) {
            str = str + '&doctor_type=' + self.doctor_type;
        }

        if (self.district_id) {
            str += '&district_id=' + self.district_id;
        }

        if (self.region_id) {
            str += '&region_id=' + self.region_id;
        }

        if (self.street_id) {
            str += '&street_id=' + self.street_id;
        }

        if (self.service_categories) {
          str += '&service_categories=' + self.service_categories;
        }

        if (self.district_id) {
            str += '&district_id=' + self.district_id;
        }

        if (self.region_id) {
            str += '&region_id=' + self.region_id;
        }

        if (self.street_id) {
            str += '&street_id=' + self.street_id;
        }

        if (self.weekend_time > 0)
            str += '&weekend_time=' + self.weekend_time;

        if (self.evening_time > 0)
            str += '&evening_time=' + self.evening_time;

        if (self.morning_time > 0)
            str += '&morning_time=' + self.morning_time;

        /*if (self.any_time != 1)
            str += '&any_time=' + self.any_time;*/

        if (self.latitude > 0)
            str += '&latitude=' + self.latitude;

        if (self.longitude > 0)
            str += '&longitude=' + self.longitude;

        if (self.metro_station_name > 0)
            str += '&metro_station_name=' + self.metro_station_name;

        if (self.metro_station_id > 0)
            str += '&metro_station_id=' + self.metro_station_id;

        if (self.metro_branch_name > 0)
            str += '&metro_branch_name=' + self.metro_branch_name;

        if (self.sort_by && (self.sort_by != 'recomend')) {
            str = str + '&sort_by=' + self.sort_by;
        }

        if (self.full_map > 0)
            str += '&full_map=' + self.full_map;

        if(str)
          str = '?' + str.substring(1, str.length);

        if (self.specialty_alias) {
            str = '/' + self.specialty_alias + str;
        }

        return str;
    };

    this.loadPurposeOfVisitBlock = function (value, open_form) {

        if(open_form == undefined)
            open_form = true;

        Ajax.Get('/ajax/getPurposesOfVisitToDoctorsBySpecialtyId', {specialty_id: self.specialty_id}, function (data) {
            if (data.status == 0) {
                $('#purpose_of_visit_block').html(data.result);

                if (!value)
                    self.purpose_of_visit_id = 0;
                else {
                    $(self.container + ' select[name="purpose_of_visit_id"] option[value="' + value + '"]').attr('selected', 'selected');
                    $(self.container + ' select[name="purpose_of_visit_id"]').trigger('liszt:updated');
                }

                $(".chzn-select").chosen();
                $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                setCustomSelect(self.container + ' select[name="specialty_id"]', self.specialty_id);

                if (open_form && this.mode == 'page')
                    $(self.container + 'select[name="purpose_of_visit_id"]').trigger('liszt:open');
            }
        });
    };

    this.loadNextPage = function () {
        self.page = self.page + 1;
        self.sendRequest(false);
    };

    this.addColapse = function () {
        $('#box_h1').addClass('h1_colapse');
        $('.colapse').css('display', 'block');
        $('.in_colapse').css('display', 'none');

        $('.resize.old_resize').click(function(){
            $('.top_number').show();
            $('.ymaps-b-zoom__button_type_plus').trigger('click');
            doctor_form_controller = new DoctorSearchFormController();
            doctor_form_controller.removeColapse();
            $('#box_h1').removeClass('h1_colapse');
            $('#map').css('width',649);
        });

        $('.resize.old_resize').next().click(function(){
            doctor_form_controller = new DoctorSearchFormController();
            doctor_form_controller.removeColapse();
            $('#box_h1').removeClass('h1_colapse');
        });
    };

    this.removeColapse = function () {
        $('.colapse').css('display', 'none');
        $('.in_colapse').css('display', 'block');
    };

    this.changeSpecialtiesListToSearchDoctors = function(city_info)
    {
        Ajax.Get('/ajax/changeSpecialtiesListToSearchDoctorsByCityId', {city_id : city_info.city_id}, function(data){
            if (data.status == 0)
            {
                $('#specialties_to_search_doctor').html(data.result.option);

                $(self.container + ' select[name="specialty_id"]').trigger('liszt:updated');
                setCustomSelect('#specialties_to_search_doctor', self.specialty_id);
                $(self.container + ' select[name="purpose_of_visit_id"]').html('<option value=""></option>');

                $(self.container + ' select[name="purpose_of_visit_id"]').html('<option value=""></option>');
                self.loadPurposeOfVisitBlock();
            }
        });
    }
};
