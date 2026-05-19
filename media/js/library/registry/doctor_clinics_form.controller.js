var DoctorClinicsFormController = function (doctor_id, clinic_id) {
    var self = this;

    this.doctor_id = 0;

    this.init = function () {
        self.setDeleteButtonVisible();
        
        $('.row-record').css('overflow', 'visible');
        $('.clinic_id:visible').chosen();
        
        $(document).on('click', '.delete-clinic-button', function () {
            $(this).parent().parent().parent().remove();
            self.setDeleteButtonVisible();
        });

        $('.add-clinic').click(function(){
            var container = $($('#new-clinic').html());
            $(this).parent().before(container);
            self.setDeleteButtonVisible();
            $('.clinic_id:visible').chosen();
        });

        $('input[name=cancel]').click(function(){
            self.cancel();
        });

        $('input[name=publish]').click(function(){
            self.sendRequest();
        });
    };

    this.setDeleteButtonVisible = function(){
        var clinics_count = $('#doctor-clinics-form > .fields-block-inner').length;

        if (clinics_count == 1)
        {
            $('.delete-clinic-button').addClass('hidden');
        } else {
            $('.delete-clinic-button').removeClass('hidden');
        }
    };

    this.sendRequest = function(){

        var clinics = self.readData();

        var data = {
            clinics : clinics,
            doctor_id: self.doctor_id
        };

        Ajax.Post('/registry/doctor/ajaxSaveClinics', data, function(result){
            if (result.status == 0){
                var popup = new Popup();
                callback = function(){
                    window.location.reload();
                }
                popup.close_callback = callback;
                popup.show('<div style="padding: 50px; font-size: 25px">Данные успешно сохранены</div>');
                setTimeout(callback, 3000);
            }
        });
    };

    this.readData = function(){
        var result = [];
        $('select[name="clinic_id"]').each(function(){
            var val = $(this).find(':selected').val();
            if (val)
                result.push(val);
        });

        return array_unique(result);
    };

    this.cancel = function(){
        window.location.reload();
    }
};
