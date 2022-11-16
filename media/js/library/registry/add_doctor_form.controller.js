var AddDoctorFormController = function () {

    var self = this;

    this.init = function () {
        $('.adult .chekBox').addClass('act');
        $('input[name="form[is_adult]"]').val(1);
    };
};