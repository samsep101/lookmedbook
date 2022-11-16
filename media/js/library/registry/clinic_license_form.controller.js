var ClinicLicenseFormController = function (session_name,session_id) {
    var self = this;
    this.imgCount = 0;
    this.imgSize = 0;
    this.session_name = session_name;
    this.session_id = session_id;

    self.list_names = ['clinic_license_image', 'specialization_to_clinic'];
};

extend(ClinicLicenseFormController, ModerateFormController);

ClinicLicenseFormController.prototype.getValidation = function () {
    var result = [];
    result.push($('input[name="form[license_number]"]').validate(validation_rules['license_number']));
    result.push($('input[name="form[license_issue_date]"]').validate(validation_rules['license_issue_date']));
//    result.push($('input[name="form[license_validity_date]"]').validate(validation_rules['license_validity_date']));
    return result;
};

ClinicLicenseFormController.prototype.beforeReadValues = function()
{
    $('.clinic_specialty > label > .specialty_to_clinic input[name="is_selected"]').each(function(){
        var val = $(this).val();

        var container = $(this).parent().parent().parent();

        if (val != 1)
        {
            container.find('.childs input[name="is_selected"]').val(0);
        }
    });
};

ClinicLicenseFormController.prototype.initFields = function () {
    var self = this;

    $('.show-license').lightBox();

    Inputmask.extendDefinitions({
        'i': {
            "validator": "[А-Яа-я0-9A-Za-z\-]",
            "cardinality": 1,
            'prevalidator': null
        }
    });

    $('input[name="form[license_number]"]').inputmask("**-**-******");
    $('input[name="add_license_copy"]').click(function () {
        $('#file_upload-button').click();
    });

    $('#file_upload').uploadify({
        'buttonText'    : 'Добавить копию',
        'buttonClass' : 'longest-button',
        'swf'      : '/media/js/uploadify/uploadify.swf',
        'uploader' : '/registry/ajax/uploadImage',
        'formData'   : { 'SESSID' : '"'+self.session_id+'"', 'csrf': SessionInfo.csrf},
        'fileTypeDesc'   : 'jpg,bmp,png,gif',
        'fileTypeExts'   : '*.jpg;*.bmp;*.png;*.gif',
        'onUploadSuccess': function(file, data) {
            var json = JSON.parse(data);
            if (json.status == 0) {
                self.addImageToContainer(json.result.image_id, json.result.image_path);
            }
        }
    });

    $('.remove-feature').click(function(){
        $(this).parent().remove();
    });

    $('body').on('click', '.remove-feature', function(){
        $(this).parent().remove();
    });
};

ClinicLicenseFormController.prototype.modifySendData = function (data) {
    var result = [];

    for (i in data['lists']['specialization_to_clinic']) {
        var obj = data['lists']['specialization_to_clinic'][i];
        if (obj.is_selected == 1) {
            result.push(obj);
        }
    }
    data.lists.specialization_to_clinic = result;

    return data;
};

ClinicLicenseFormController.prototype.addImageToContainer = function(image_id, image_path){
    if (image_id){
        var image_block = $('.license_images');
        var data_block = $('<div data-name="clinic_license_image" data-label="clinic_license_image"></div>');
        var li = $('<li></li>');
        var hidden_input = $('<input type="hidden" value="'+image_id+'" name="image_id" />');
        var image = $('<a class="show-license" href="'+image_path+'"><img src="'+image_path+'"></a>');
        var x_mark = $('<span class="remove-feature more-left">X</span>');
        li.append(hidden_input);
        li.append(image);
        data_block.append(li);
        data_block.append(x_mark);
        var result = data_block;

        $('.license_images > ul').append(result);
        $('.show-license').lightBox();
    }
};
