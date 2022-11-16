var DoctorScheduleViewPageController = function()
{
    var self = this;

    this.schedule_controller = null;

    this.init = function(){
        self.schedule_controller = new DoctorScheduleController();
        self.schedule_controller.init();
    };
};