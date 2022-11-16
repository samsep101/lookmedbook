var ClinicInformationFormController = function()
{
    var self = this;

    self.metro_station_id = null;
    self.city_id = null;
    self.city = null;
    self.address = null;
    self.not_show = false;

    this.loadMetroStationBlock = function(city_id){
        Ajax.Get('/registry/ajax/getMetroStationsSelectByCityId', {city_id : city_id}, function(data){
            if (data.status == 0){
                $('#metro-station-select-container').html(data.result.html);
            };
        });
    };


    this.setCityId = function(city_id)
    {
        self.city_id = city_id;
    };

    this.setMetroStationId = function(metro_station_id){
        self.metro_station_id = metro_station_id;
    };

    self.list_names = ['clinic_phone', 'clinic_email', 'metro_station_to_clinic'];
};

extend(ClinicInformationFormController, ModerateFormController);



ClinicInformationFormController.prototype.getValidation = function()
{
    var result = [];

    result.push($('input[name="phone_number"]').validate(validation_rules['clinic_phone']));
    result.push($('input[name="email"]').validate(validation_rules['clinic_email']));
    //result.push($('input[name="form[director_fio]"]').validate(validation_rules['director_fio']));
    //result.push($('input[name="form[full_name]"]').validate(validation_rules['clinic_full_name']));
    //result.push($('select[name="form[city_id]"]').validate(validation_rules['required']));
    //result.push($('input[name="form[address]"]').validate(validation_rules['clinic_address']));
    //result.push($('select[name="form[clinic_type_id]"]').validate(validation_rules['required']));
    //result.push($('input[name="form[longitude]"]').validate(validation_rules['coordinates']));
    //result.push($('input[name="form[latitude]"]').validate(validation_rules['coordinates']));
    //result.push($('input[name="form[postcode]"]').validate(validation_rules['clinic_postcode']));
    //result.push($('input[name="form[date_contract]"]').validate(validation_rules['date']));

    return result;

};

ClinicInformationFormController.prototype.initFields = function()
{
    var self = this;
    this.loadMetroStationBlock(self.city_id);

    $('input[name="postcode"]').ForceNumericOnly();

    $('select[name="form[city_id]"]').change(function(){
        self.loadMetroStationBlock($(this).val());
        if ($('select[name="form[metro_station_id]"]').length != 0) {
            $('.metro-add').hide();
        } else {
            $('.metro-add').show();
        }
    });

    $('input[name="phone_number"]').inputmask("+99999999999");


    $('#kladr-button').click(function(){
        self.city = $('select[name="form[city_id]"] option:selected').text();
        if ($('input[name="form[address]"]').val() != $('input[name="form[address]"]').attr('placeholder'))
            self.address = $('input[name="form[address]"]').val();

        Ajax.Get('/registry/ajax/getClinicPostIndexByCityAndAddress', {city : self.city, address : self.address}, function(data){
            if (data.status == 0){
                $('input[name="form[postcode]"]').val(data.result.html);
            }
            else {
                $('.error_span').remove();
                showErrorLabel(data.data, $('#kladr-button'));
            }
        });
    });

    $('body').on('click', 'span.remove', function(){
        var container = $(this).parent().parent().parent();

        if (container.parent().find('.list-form-row').length == 1)
        {
            container.find('input').val('');
        } else {
            container.remove();
        }

    });

    $('.remove-metro').click(function (){
        $(this).parent().remove();
    });

    $('.metro-add').click(function (){
        if ($('select[name="form[metro_station_id]"]').find('option:selected').val() != 0) {
            Ajax.Get('/registry/clinic/ajaxCheckMetroStationForClinic', {clinic_id: self.clinic_id, metro_station_id: $('select[name="form[metro_station_id]"]').find('option:selected').val()}, function (data){
                if (data.status == 0) {

                    for (var i = 0; i < $('.metro-station-to-clinic').find('input').length; i++) {
                        if ($('.metro-station-to-clinic').find('input')[i].value == data.result.metro_station_id) {
                            showErrorLabel('Это метро уже есть в списке', $('select[name="form[metro_station_id]"]'));
                            self.not_show = true;
                        }
                    }

                    if (!self.not_show){
                        var html = '<div class="metro-station-name" data-name="metro_station_to_clinic">' +
                            '<input type="hidden" name="metro_station_id" value="'+data.result.metro_station_id+'">' + data.result.metro_station_name +
                            '<span class="remove-metro">удалить</span>' +
                            '</div>';
                        $('.metro-station-to-clinic').append(html);

                        self.loadMetroStationBlock(self.city_id);
                    }

                    $('.remove-metro').click(function (){
                        $(this).parent().remove();
                    });

                    self.not_show = false;
                }
                if (data.status == 200) {
                    showErrorLabel('Это метро уже есть в списке', $('select[name="form[metro_station_id]"]'));
                }
            });
        }
    });


};