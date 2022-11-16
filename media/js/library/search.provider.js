var SearchProvider = function (formController) {
    this.formController = formController;
    this.cityId = null;

    var self = this;

    this.init = function () {
        if ($('#address-input').length > 0) {
            var autocomplete = $('#address-input').autocomplete({
                source: '/ajax/metroStations?cityId=' + self.cityId,
                minLength: 2,
                select: function( event, ui ) {
                    self.formController.latitude = ui.item.lat;
                    self.formController.longitude = ui.item.lon;
                    self.formController.is_metro = 1;
                    self.formController.metro_station_name = ui.item.stationName;
                    self.formController.metro_branch_name = ui.item.branchName;
                }
            }).data("ui-autocomplete");
            autocomplete._renderItem = function( ul, item ) {
                var $el = $( "<li>" );
                $el
                    .addClass('search-result')
                return $el
                    .append('<a>Метро ' + item.label + '</a>')
                    .appendTo( ul );
            }
        }
    };

    this.setCityId = function(city_id){
        self.cityId = city_id;
    };
};
