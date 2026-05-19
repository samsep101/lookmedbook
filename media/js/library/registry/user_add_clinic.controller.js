var UserAddClinicController  = function(){
    var self = this;

    this.container = null;
    this.user_id = null;

    this.init = function(){
        $(self.container + ' input[type="submit"]').validation({
            validate: [
                $(self.container + ' select[name="clinic_id"]').validate(validation_rules['required'])
            ],
            callback: function(){
                var data = {
                    clinic_id : $(self.container + ' select[name="clinic_id"] :selected').val(),
                    user_id : self.user_id
                };

                Ajax.Post('/manage/user/ajaxAddClinic', data, function(data){
                    if (data.status == 0)
                    {
                        window.location.reload();
                    }
                });
            }
        });
    };
};