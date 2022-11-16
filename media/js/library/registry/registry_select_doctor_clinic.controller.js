var RegistrySelectDoctorController = function(){

    var self = this;

    this.url = null;

    this.init = function(){
        $('select[name="doctor_clinic_id"]').change(function(){
            if (self.url)
            {
                window.location = self.url + $(this).val();
            } else {
                queryString.set('clinic_id', $(this).val());
                queryString.load();
            }
        });
    }
};