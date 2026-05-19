var RequisitesFormController = function()
{
    var self = this;

};

extend(RequisitesFormController, ModerateFormController);

RequisitesFormController.prototype.getValidation = function()
{
    var result = [];
    result.push($('input[name="form[bank_inn]"]').validate(validation_rules['digits']));
    result.push($('input[name="form[bank_bik]"]').validate(validation_rules['digits']));
    result.push($('input[name="form[bank_kpp]"]').validate(validation_rules['digits']));
    result.push($('input[name="form[current_account]"]').validate(validation_rules['digits']));
    return result;
};

RequisitesFormController.prototype.initFields = function()
{
    var self = this;


    $('input[name="form[bank_inn]"]').ForceNumericOnly();
    $('input[name="form[bank_bik]"]').ForceNumericOnly();
    $('input[name="form[bank_kpp]"]').ForceNumericOnly();
    $('input[name="form[current_account]"]').ForceNumericOnly();

    $('input[name="form[legal_address]"]').keyup(function(){
        if ($('.chekBox').hasClass('act')) {
            self.address = $(this).val();
            $('input[name="form[fact_address]"]').val(self.address);
        }
    });

    $('input[name="form[fact_address]"]').keyup(function(){
        if ($('.chekBox').hasClass('act')) {
            self.address = $(this).val();
            $('input[name="form[legal_address]"]').val(self.address);
        }
    });
};
