var DoctorBigCardButtonsController = function (doctor_id) {

    var self = this;
    this.doctor_id = doctor_id;

    this.init = function () {
        if (self.doctor_id) {
            self.getBookmarkBlock();
        }

    };

    this.getBookmarkBlock = function () {
        Ajax.Post('/doctor/ajaxAddToMyDoctorList', {doctor_id: self.doctor_id}, function (data) {
            if (data.status == 0) {
                var isItAddOrKick = data.result.my_doctor;

                if (isItAddOrKick) {
                    $('.doctor_bookmark' + self.doctor_id).addClass("btn-bookmark-added");
                    $('.doctor_bookmark' + self.doctor_id).html('<i class="icon-add"></i> <span class="txt txt-added">В закладках</span>');
                } else {
                    $('.doctor_bookmark' + self.doctor_id).removeClass('btn-bookmark-added');
                    $('.doctor_bookmark' + self.doctor_id).html('<i class="icon-add"></i> <span class="txt">Добавить в закладки</span>');
                }
            }
        });
    };
}