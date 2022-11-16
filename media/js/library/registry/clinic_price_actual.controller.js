    var ClinicPriceActualController = function()
    {
        var self = this;

        this.clinic_id = null;

        this.init = function()
        {
             $('.set-dt-actual').click(function(){
                 var data = {
                    clinic_id : self.clinic_id
                 };
                 Ajax.Post('/registry/clinic/ajaxSetPriceActual', data, function(data){
                     if(data.status == 0)
                     {
                         // всё хорошо
                     }
                 });
             });
        }
    }