var HeaderController = function () {

  var self = this;

  this.registration_form_controller = null;
  this.login_form_controller = null;

  this.init = function () {
    $('.disease-link').click(function () {
      setCounters('diseases', 'top-disease', '', SessionInfo.email);
    });

    $('.doctor-link').click(function () {
      setCounters('doctors', 'top', '', SessionInfo.email);
    });

    $('.clinic-link').click(function () {
      setCounters('clinics', 'top', '', SessionInfo.email);
    });

    $(window).scroll(function () {
      if ($(window).scrollTop() == ($(document).height() - $(window).height())) {
        setCounters('full-scroll-down', 'unknown', '', SessionInfo.email);
      }
    });

    $('.no-auth-buttons .btn-enter').click(function () {
      self.login_form_controller = new LoginFormController();
      self.login_form_controller.init();
    });

    $('.no-auth-buttons .btn-reg').click(function () {
      if (!self.registration_form_controller)
        self.registration_form_controller = new RegistrationFormController();
      self.registration_form_controller.init();
    });
    $('.popup_city').click(function () {
      if ($('.fancybox-inner .city-block').length === 0) {
          var data = {
              page: self.page,
              city_id: self.city_id
          };
          Ajax.Get('/ajax/getCityChoicePopup', data, function (data) {
              if (data.status == 0) {
                  var block_code = data.result.html;
                  showPopup(block_code);
              }
          });
      }
    });
    window.city_controller.subscribe(function(city_info){
      if (city_info.city_alias && (city_info.city_alias != 'moskva'))
        window.location = '//'+city_info.city_alias + '.'+SessionInfo.domain;
      else if(city_info.city_alias == 'moskva')
        window.location = '//'+SessionInfo.domain+'/';
    });
  };
};