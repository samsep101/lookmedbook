var ClinicUserFormController = function () {

    var self = this;


};

extend(ClinicUserFormController, ModerateFormController);

ClinicUserFormController.prototype.getValidation = function()
{
    var result = [];

    result.push($('input[name="form[email]"]').validate(validation_rules['clinic_email']));

    return result;

};

ClinicUserFormController.prototype.initFields = function()
{
    var self = this;
    $('input[name="form[phone]"]').inputmask('+7-999-999-99-99');
};