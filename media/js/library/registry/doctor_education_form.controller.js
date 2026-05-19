var DoctorEducationFormController = function()
{
    var self = this;

    self.list_names = ['doctor_education','doctor_certificate'];
};

extend(DoctorEducationFormController, ModerateFormController);

DoctorEducationFormController.prototype.getValidation = function () {
    var result = [];
    result.push($('input[name="date"]').validate(validation_rules['certificate_date']));
    return result;
};

DoctorEducationFormController.prototype.initFields = function()
{
    var self = this;
    this.type = 0;
    this.institution_name = '';

    $('input[name="form[high_education_end_year]"]').inputmask('y');
    $('input[name="form[secondary_education_end_year]"]').inputmask('y');
    $('input[name="end_year"]').inputmask('y');
    $('input[name="date"]').inputmask('d-m-y');
    $('input[name="duration"]').inputmask('9');

    $("input.add-education").click(function () {
        self.type = $(this).data('type');

        Ajax.Get('/registry/ajax/getEducationAddPopup', null, function (data) {
            if (data.status == 0) {
                var popup = new Popup();
                popup.show(data.result.html, '460px');
                //$('.fancybox-wrap').css('top', ($(window).height() - $('.fancybox-wrap').height()) / 2);
            }
        });
    });

    $(document).on('click',"input.copy-education",function () {
        $(this).parent().parent().parent().append($(this).parent().parent().clone());
        $(this).parents('.cab-page-3').css('height', ($(this).parents('.cab-page-3').height() + 200));
        $(this).parent().css('display','none');
    });

    $(document).on('click',"input.delete-education",function () {
        var this_block = $(this).parent().parent().parent();
        $(this).parent().parent().remove();
        if (this_block.children('div').length == 1) {
            this_block.find('.copy-button').css('display','block');
        }
        this_block.parents('.cab-page-3').css('height', (this_block.parents('.cab-page-3').height() - 200));
    });

/* на потом (но все равно придется переделывать)
    $(document).on('click','.education-add .save-institution',function(){
        self.institution_name = $('.education-add .education-parameter input').val();
        if ((self.institution_name != '') && (self.institution_name != $('.education-add .education-parameter input').attr('placeholder'))) {

            data = {
                name: self.institution_name,
                type: self.type
            };

            Ajax.Post('/registry/ajax/addNewInstitution', data, function (data) {
                if (data.status == 0) {
                    $('.fancybox-close').click();
                    $('.institution-list-'+self.type).html(data.result.html);
                }
            });

        }
    });
*/
};

DoctorEducationFormController.prototype.modifySendData = function (data) {

    for (var i in data['lists']['doctor_certificate']) {
        var matches = data['lists']['doctor_certificate'][i]['date'].match(/^([0-9]{2})\-([0-9]{2})\-([0-9]{4})$/);

        if (matches) {
            data['lists']['doctor_certificate'][i]['date'] = matches[3] + '-' + matches[2] + '-' + matches[1];
        } else {
            data['lists']['doctor_certificate'][i]['date'] = null;
        }
    }
    return data;
};