var DoctorSmallCardController = function () {

    var self = this;
    this.doctor_id = null;

    this.init = function () {
        $(document).on('click', '.add_doctor_to_bookmark', function () {
            self.doctor_id = $(this).data('doctor_id');

            if (self.doctor_id) {
                self.getBookmarkBlock();
            }
        });
    };

    this.getBookmarkBlock = function () {
        Ajax.Post('/doctor/ajaxAddToMyDoctorList', {doctor_id: self.doctor_id}, function (data) {
            if (data.status == 0) {
                var isItAddOrKick = data.result.my_doctor;

                if (isItAddOrKick) {
                    $('#add_doctor_to_bookmark_' + self.doctor_id).addClass("btn-bookmark-added");
                    $('#add_doctor_to_bookmark_' + self.doctor_id).html('<i class="icon-add"></i> <span class="txt txt-added">В закладках</span>');
                } else {
                    $('#add_doctor_to_bookmark_' + self.doctor_id).removeClass('btn-bookmark-added');
                    $('#add_doctor_to_bookmark_' + self.doctor_id).html('<i class="icon-add"></i> <span class="txt">Добавить в закладки</span>');
                }
            }
        });
    };
}