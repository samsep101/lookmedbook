var AddClinicFormController = function () {

    var self = this;
    this.clinic_name = null;

    this.init = function () {

        $('input[name="save"]').validation({
            validate: [
                $('input[name="clinic_name"]').validate(validation_rules['required']),
                //$('input[name="user_phone"]').validate(validation_rules['user_phone']),
                //$('input[name="user_email"]').validate(validation_rules['user_email']),
                //$('input[name="user_password"]').validate(validation_rules['password']),
                //$('input[name="address"]').validate(validation_rules['clinic_address_required_and_unique']),
                //$('input[name="latitude"]').validate(validation_rules['coordinates']),
                //$('input[name="longitude"]').validate(validation_rules['coordinates'])
            ],
            callback: self.addClinic
        });

        $('input[name="user_phone"]').inputmask('+7-999-999-99-99');
    };

    this.addClinic = function(){

        var options = {
            clinic_name : $('input[name="clinic_name"]').val(),
            user_name : $('input[name="user_name"]').val(),
            user_phone : $('input[name="user_phone"]').val(),
            user_email : $('input[name="user_email"]').val(),
            user_password : $('input[name="user_password"]').val(),
            user_site : $('input[name="user_site"]').val(),
            city_id : $('select[name="city"]').val(),
            address : $('input[name="address"]').val(),
            latitude : $('input[name="latitude"]').val(),
            longitude : $('input[name="longitude"]').val()
        }

        Ajax.Post('/registry/ajax/addClinic', options, function(data){
            if (data.status == 0 && data.result)
            {
                var popup = new Popup();
                popup.show('<div style="padding: 50px; font-size: 25px">Клиника успешно добавлена</div>');
                window.location = '/registry/clinic/information?clinic_id=' + data.result;
            }
            else {
                showErrorLabel(data.data, $('.btn-appoint'));
            }
        });
    }
};