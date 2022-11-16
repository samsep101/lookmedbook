var DoctorPhotosController = function (doctor_id) {

    this.doctor_id = doctor_id;
    this.image_path = '';

    var self = this;

    this.init = function () {

        $('.avatar a').removeAttr('href');

    };

    this.refreshDoctorAvatar = function (image_path) {
        self.image_path = image_path;
        $('.avatar img').attr('src',self.image_path);
    };
};