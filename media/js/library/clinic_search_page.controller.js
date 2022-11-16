var ClinicSearchPageController = function (
    landing,
    already_registred_account,
    url_page,
    ajax_search_url
) {
    var self = this;

    this.landing_page = landing;
    this.url_page = url_page;
    this.already_registred_account = already_registred_account;
    this.city_id = null;
    this.specialty_id = null;
    this.district_id = null;
    this.region_id = null;
    this.street_id = null;
    this.metro_station_id = null;
    this.service_categories = null;
    this.listing = true;

    this.form_controller = null;

    this.init = function () {
        var city_width = $('.map-city a').width();
        if (city_width>120){
            $('.map-box .search-box input.txt').css('width',465-city_width);
            $('.map-city').css('left',505-city_width);
        }
        else {
            $('.map-box .search-box input.txt').css('width',475-city_width);
            $('.map-city').css('left',515-city_width);
        }

        self.form_controller = new ClinicSearchFormController(
            landing,
            already_registred_account,
            url_page,
            ajax_search_url
        );

        if (self.specialty_id)
            self.form_controller.specialty_id = self.specialty_id;

        if (self.district_id)
            self.form_controller.district_id = self.district_id;

        if (self.region_id)
            self.form_controller.region_id = self.region_id;

        if (self.street_id)
            self.form_controller.street_id = self.street_id;

        if (self.metro_station_id)
            self.form_controller.metro_station_id = self.metro_station_id;

        if (self.service_categories)
            self.form_controller.service_categories = self.service_categories

        self.form_controller.listing = self.listing
        self.form_controller.setPageMode();
        self.form_controller.setCityId(self.city_id);
        self.form_controller.init();
    };

    this.setCityId = function(city_id){
        self.city_id = city_id;

        if (self.form_controller != null)
        {
            self.form_controller.setCityId(city_id);
        }
    };
};
