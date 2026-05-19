var FooterBlockController = function () {
    var self = this;

    this.init = function () {
        $(document).on('click', '.show_license', function(){
            setCounters('eula', 'unknown', '', SessionInfo.email);

            var license_popup = new LandingLicensePageController();
            license_popup.sent_back_callback = function(){
                self.popup.show();
            };
            license_popup.init();

            $('.license_back_button').css('display', 'none');
        });

        $('.help-link').click(function(){
            setCounters('help', 'unknown', '', SessionInfo.email);
        });

        $('.about-link').click(function(){
            setCounters('about', 'unknown', '', SessionInfo.email);
        });
    };

}