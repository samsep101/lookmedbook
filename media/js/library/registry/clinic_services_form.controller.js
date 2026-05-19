var ClinicServicesFormController = function () {
    var self = this;

    self.list_names = ['specialty_to_clinic', 'purpose_of_visit_to_clinic','clinic_pricelist'];
};

extend(ClinicServicesFormController, ModerateFormController);

ClinicServicesFormController.prototype.getValidation = function()
{
    var result = [];
    result.push($('.third-level-specialty input[name="visit_price"]').validate(validation_rules['digits']));
    return result;
};

ClinicServicesFormController.prototype.initFields = function () {
    var self = this;
    $('.second-level-specialty-name').click(function (e) {
        e.preventDefault();

        if ($(this).hasClass('act')) {
            $('.third-level-specialty-' + $(this).parent().find('input[name="specialty_id"]').val()).find('input[name="is_selected"]').val(0);

            $('.third-level-specialty-' + $(this).parent().find('input[name="specialty_id"]').val()).find('.chekBox').each(function(){

            $(this).removeClass('act');
            if ($(this).hasClass('main'))
                return;

            $(this).data('disabled', 1);
            });
        } else {
            $('.third-level-specialty-' + $(this).parent().find('input[name="specialty_id"]').val()).find('.chekBox').each(function(){
                if (!$(this).hasClass('main'))
                    $(this).data('disabled', 0);
                else {
                    $(this).addClass('act');
                }
            });
        }
    });


    $('input[name="add_license_copy"]').click(function () {
        $('#file_upload-button').click();
    });

    $('#file_upload').uploadify({
        'buttonText'    : 'Загрузить прайс-лист',
        'buttonClass' : 'longest-button',
        'swf'      : '/media/js/uploadify/uploadify.swf',
        'uploader' : '/registry/ajax/uploadServicesFiles',
        'onUploadSuccess': function(file, data, response) {
            var json = JSON.parse(data);
            if (json.status == 0) {
                self.addFileToContainer(json.result.filename, json.result.filepath);

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



ClinicServicesFormController.prototype.modifySendData = function(data)
{

    var result = [];

    if (data.lists.specialty_to_clinic != 'clear') {
        for (i in data.lists.specialty_to_clinic) {
            var obj = data.lists.specialty_to_clinic[i];

            if (obj.is_selected == 1) {
                result.push(obj);
            }
        }
        data.lists.specialty_to_clinic = result;
    }

    result = [];

    if (data.lists.purpose_of_visit_to_clinic != 'clear') {
        for (i in data.lists.purpose_of_visit_to_clinic) {
            obj = data.lists.purpose_of_visit_to_clinic[i];

            if (obj.is_selected == 1) {
                result.push(obj);
            }
        }
        data.lists.purpose_of_visit_to_clinic = result;
    }

    return data;
};

ClinicServicesFormController.prototype.addFileToContainer = function(filename, filepath){

    if (filename){
        var image_block = $('.pricelist');
        var data_block = $('<div data-name="clinic_pricelist" data-label="clinic_pricelist" class="single-price-row"></div>');
        var li = $('<li></li>');
        var hidden_input = $('<input type="hidden" value="'+filename+'" name="filename" />');
        var file = $('<p style="color: #000000"><a href="'+filepath+'" target="_blank">'+filename+'</a></p>');
        var x_mark = $('<span class="remove-feature">X</span>');
        li.append(hidden_input);
        li.append(file);
        data_block.append(li);
        data_block.append(x_mark);
        var result = data_block;

        image_block.children().append(result);
    }
};