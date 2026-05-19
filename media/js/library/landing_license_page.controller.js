var LandingLicensePageController = function () {
    var self = this;

    this.popup = null;

    this.sent_back_callback = null;

    this.init = function () {

        var options = {
            landing: true,
            type: 'landing_license'
        };

        Ajax.Get('/ajax/getPopup', options, function (data) {
            if (data.status == 0) {
                var landing_license_popup = data.result.html;

                self.popup = new Popup();
                self.popup.show(landing_license_popup, '',undefined, true);

                $(self.popup.popup_block).find('.license_back_button a').click(function () {
                    self.popup.close();

                    if (self.sent_back_callback)
                        self.sent_back_callback();
                });
            };
        });
    };
}