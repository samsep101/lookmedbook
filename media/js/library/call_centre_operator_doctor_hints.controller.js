var CallCentreOperatorDoctorHintsController = function()
{
    var self = this;

    self.doctor_id = null;

    this.init = function(){
        setTimeout(self.setActiveHint, 100);
        $('.doctor-card-'+self.doctor_id+' .tabs li').click(function(){
            setTimeout(self.setActiveHint, 100);
        });
    };

    this.setActiveHint = function(){
        var active_clinic_id = $('.doctor-card-'+self.doctor_id+' .tabs li.active').data('id');
        $('.doctor-card-'+self.doctor_id+' .clinic-info-hint').css('display', 'none');
        $('.doctor-card-'+self.doctor_id+' .clinic-info-hint-'+active_clinic_id).css('display', 'block');
    };
};