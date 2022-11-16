var AnalysisPageController = function(){
    var self = this;

    this.map_controller = null;

    this.latitude = null;
    this.longitude = null;

    this.is_metro = 0;
    this.metro_station_name = null;
    this.metro_branch_name = null;

    this.urgent_tests = null;
    this.card_pay = null;
    this.work_seven_days = null;
    this.easy_entry = null;
    this.without_turn = null;
    this.day_and_night = null;

    this.city_id = null;

    this.reset_filter = false;

    this.bounds = null;

    this.init = function(){
        window.city_controller.subscribe(self.setCityInfo);
        self.city_id = window.city_controller.city_id;

        self.setActualCriteria();

        $('.not-empty-cities a').click(function (){
            self.latitude = null;
            self.longitude = null;
            alert(self.latitude);
        });

        self.sendRequest();

        self.map_controller = new YandexMapController(self);
        self.map_controller.setDataUrl('/ajax/getLaboratoryMapCard?big=0&id=');
        self.map_controller.page = 'laboratory';
        self.map_controller.city_id = self.city_id;
        self.map_controller.init();

        var city_width = $('.map-city a').width();

        $('.location-box .tabs').each(function (){
            $('.location-box .tabs li:first-child').addClass('active');
            $('.location-box .box .section:last-child').css('display','none');
            $(this).find('li').each(function (i) {
                $(this).click(function () {
                    $(this).addClass('active').siblings().removeClass('active')
                        .parents('.location-box').find('.section').eq(i).fadeIn(150).siblings('.section').hide();

                });
            });
        });

        $('.choose-list .urgent_tests').click(function (){
            if($(this).find('.chekBox').data('disabled'))
                return;
            self.urgent_tests = ($(this).find('.chekBox').hasClass('act')) ? 0 : 1;
            self.sendRequest();
        });

        $('.choose-list .work_seven_days').click(function (){
            if($(this).find('.chekBox').data('disabled'))
                return;
            self.work_seven_days = ($(this).find('.chekBox').hasClass('act')) ? 0 : 1;
            self.sendRequest();
        });

        $('.choose-list .easy_entry').click(function (){
            if($(this).find('.chekBox').data('disabled'))
                return;
            self.easy_entry = ($(this).find('.chekBox').hasClass('act')) ? 0 : 1;
            self.sendRequest();
        });

        $('.choose-list .card_pay').click(function (){
            if($(this).find('.chekBox').data('disabled'))
                return;
            self.card_pay = ($(this).find('.chekBox').hasClass('act')) ? 0 : 1;
            self.sendRequest();
        });

        $('.choose-list .day_and_night').click(function (){
            if($(this).find('.chekBox').data('disabled'))
                return;
            self.day_and_night = ($(this).find('.chekBox').hasClass('act')) ? 0 : 1;
            self.sendRequest();
        });
    };

    this.sendRequest = function () {

        var data = {
            datatype: 'analysis',
            latitude: self.latitude,
            longitude: self.longitude,
            is_metro: self.is_metro,
            metro_station_name: self.metro_station_name,
            metro_branch_name: self.metro_branch_name,
            landing : 1,
            city_id : self.city_id,
            without_turn: self.without_turn,
            easy_entry: self.easy_entry,
            work_seven_days: self.work_seven_days,
            card_pay: self.card_pay,
            urgent_tests: self.urgent_tests,
            day_and_night: self.day_and_night
        };

        if (SessionInfo.reset_filter){
            self.latitude = null;
            self.longitude = null;

            SessionInfo.reset_filter = false;
        }


        Ajax.Get('/ajax/getMapData', data, function (data) {
            self.map_controller.setData(data.result);
            if (self.latitude && self.longitude) {
                self.map_controller.setBoundsFromPointAndDistance(
                    self.latitude, self.longitude
                );
            }
        });
    };

    this.setActualCriteria = function(){
        var data = {
            city_id : self.city_id
        };
        Ajax.Get('/analysis/ajaxGetExistsOfStatusesByCityId', data, function(data){
            $('.analizes-center .choose-list li').removeClass('not-active');
            $('.analizes-center .choose-list li div.chekBox').data('disabled', 0);

            if (data.status == 0)
            {
                var statuses_lookup = {
                    'is_urgent_tests' : 'urgent_tests',
                    'is_card_pay' : 'card_pay',
                    'is_work_seven_days' : 'work_seven_days',
                    'is_easy_entry' : 'easy_entry',
                    'is_day_and_night' : 'day_and_night'
                };

                for (var i in data.result)
                {
                    var is_active = data.result[i];

                    if (statuses_lookup[i])
                    {
                        if (is_active == 0)
                        {
                            $('li.'+statuses_lookup[i]).addClass('not-active');
                            $('li.'+statuses_lookup[i] + ' div.chekBox').data('disabled', 1);
                            $('li.'+statuses_lookup[i] + ' div.chekBox').removeClass('act');
                            $('li.'+statuses_lookup[i] + ' div.chekBox input').val(0);
                        }
                    }
                }

            }
        });
    };

    this.setCityInfo = function(city_info){
        self.setCityId(city_info.city_id);

        if (city_info.city_alias && (city_info.city_alias != 'moskva'))
            window.location = '//'+city_info.city_alias + '.'+SessionInfo.domain+'/analysis';
        else if(city_info.city_alias == 'moskva')
            window.location = '//'+SessionInfo.domain+'/analysis';
    };

    this.setCityId = function(city_id){
        self.city_id = city_id;

        if (self.map_controller != null) {
            self.map_controller.setCityId(self.city_id);
        }
    };
};
