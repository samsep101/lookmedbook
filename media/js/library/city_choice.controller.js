var CityChoiceController = function () {
    this.city_id = null;
    this.city_alias = null;
    this.save_city_flag = 1;

    this.type = 'doctor';

    this.container = null;

    var self = this;

    this.setContainer = function (container) {
        self.container = container;
    };

    this.init = function () {

        var controller = this;

        $(document).on('click', self.container + ' .search-block .drop-menu li', function () {
            $('.search-block .txt').val($(this).text());
            self.city_name = $(this).text();

            self.city_id = $(this).attr('data-id');

            $('.search-block .drop-menu').slideUp();
            $('.search-block .drop-menu').html('');
        });

        $(document).on('click', self.container + ' .save-city-checkbox', function () {
            if ($('#save-city-checkbox').val() == 1) {
                $('.save-city-checkbox').removeClass('act');
                $('#save-city-checkbox').val(0);
                self.save_city_flag = 0;
            }
            else {
                $('.save-city-checkbox').addClass('act');
                $('#save-city-checkbox').val(1);
                self.save_city_flag = 1;
            }
        });

        $(document).on('click', self.container + ' .not-empty-cities a', function () {
            SessionInfo.reset_filter = true;
            $('.not-empty-cities a').removeClass('user-city');
            $(this).addClass('user-city');
            self.city_id = $(this).attr('data-id');
            self.city_alias = $(this).data('city_alias');
            self.changeCitySearch();
        });


        $(document).on('click', '.city-block h3 a', function () {
            SessionInfo.reset_filter = true;
            $('.not-empty-cities a').removeClass('user-city');
            $(this).addClass('user-city');
            self.city_id = $(this).attr('data-id');
            self.city_alias = $(this).data('city_alias');
            self.changeCitySearch();
        });

        $(document).on('click', self.container + ' .other-cities a', function () {
            SessionInfo.reset_filter = true;
            $('.not-empty-cities a').removeClass('user-city');
            $(this).addClass('user-city');
            self.city_id = $(this).attr('data-id');
            self.city_alias = $(this).data('city_alias');
            self.changeCitySearch();
        });

        $(document).on('keyup', self.container + ' .search-block .txt', function () {
            controller.updateDropDownList();
        });

        $(document).on('click', ":not(.drop-menu)", function () {
            $('.search-block .drop-menu').slideUp();
            $('.search-block .drop-menu').html('');
        });




        $(document).on('click', self.container + ' .fancybox-close', function () {
            $('.fancybox-overlay').remove();
            $('fancybox-placeholder').remove();
            $('body').removeClass('fancybox-lock');
        });

        $(document).on('click', self.container + ' #save-city-button', function () {
            if (self.city_id == null) {
                self.city_id = $('.not-empty-cities a[class="user-city"]').attr('data-id');
                self.city_alias = $('.not-empty-cities a[class="user-city"]').attr('data-city_alias');
            }

            var input_text = $('.search-block .txt').val();
            if (input_text != '') {
                writeLogAccountActivity('search_city', input_text);
            }
            ;

            self.changeCitySearch();
        });
    };

    this.updateDropDownList = function () {
        var text = '';
        var input = $('.search-block .txt');

        if(input.length > 1) {
            if(window.location.pathname == '/') {
                text = input.eq(1).val();
            }
            else {
                text = input.eq(0).val();
            }
        }
        else {
            text = input.val();
        }

        if (text.length > 1) {
            var data = {
                query : text,
                page : self.page
            };
            Ajax.Get('/ajax/getCities', data, function (data) {
                if (data.status == 0) {

                    $(self.container+' .search-block .drop-menu').html(data.result);

                    $(self.container+' .search-block .drop-menu').slideDown();
                }

                if (data.status == 2) {
                    $('.search-block .drop-menu').slideUp();
                    $('.search-block .drop-menu').html('');
                }
            });
        } else {
            $('.search-block .drop-menu').slideUp();
            $('.search-block .drop-menu').html('');
        }
    };

    this.changeCitySearch = function () {
        var data = {
            city_id:self.city_id,
            save_city_flag:self.save_city_flag
        };

        Ajax.Post('/ajax/changeCitySearch', data, function (data) {
            if (data.status == 0) {
                $('#address-input').val('');
                window.city_controller.setCityInfo(data.result.city_id, data.result.latitude, data.result.longitude, data.result.city_alias);
                $('.map-city a').html(data.result.city);
                $('.fancybox-overlay').remove();
                $('fancybox-placeholder').remove();
                $('body').removeClass('fancybox-lock');

                var city_width = $('.map-city').width();
                $('.map-box .search-box input.txt').css('width', 485 - city_width);
                $('.map-city').css('left', 525 - city_width);
            }
        });
    };

};