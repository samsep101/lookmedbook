var ClinicServiceFormController = function (entry_id, container) {
    var self = this;
    this.entry_id = entry_id;
    this.container = container;
    this.disabl = false;

    self.list_names = ['feature_to_clinic'];
};

extend(ClinicServiceFormController, ModerateFormController);

ClinicServiceFormController.prototype.initFields = function () {
    var self = this;

    Inputmask.extendDefinitions({
        'f':{
            "validator":"[А-Яа-яA-Za-z0-9()«\"»., -]",
            "cardinality":1,
            'prevalidator':null
        }
    });
    $('#new_feature').inputmask({ "mask":'f', "repeat":70, "greedy":false });

    $('.plus').click(function () {
        var new_feeature = $('#new_feature').val();
        if ((new_feeature != '') && (!new_feeature != $('#new_feature').attr('placeholder'))) {
            var feature = '<li><span class="feature">' + new_feeature + '</span><span class="remove-feature">X</span></li>';
            $('.new_features_list').append(feature);
            $('.new_features_list').find('.remove-feature').append('<input type="hidden" name="new_service[' + new_feeature + ']" value="' + new_feeature + '">');
            $('#new_feature').val('');
        }
    });

    $('input[name="save"]').click(function () {
        self.addNewFeatures('edit');
    });

    $('input[name="moderate"]').click(function () {
        self.addNewFeatures('moderate');
    });

    $('input[name="sent_back"]').click(function () {
        self.addNewFeatures('sent_back');
    });

    $('input[name="publish"]').click(function () {
        self.addNewFeatures('publish');
    });


    $(document).on('click', '.remove-feature', function () {
        var feature_name = $(this).parent().find('.feature').text();
        if (feature_name)
            self.deleteFeature($(this), feature_name);
    });
};

ClinicServiceFormController.prototype.modifySendData = function (data) {
    var result = [];

    if (data.lists['feature_to_clinic'] != 'clear') {
        for (i in data.lists.feature_to_clinic) {
            var obj = data.lists.feature_to_clinic[i];

            if (obj.is_selected == 1) {
                result.push(obj);
            }
        }
        data.lists.feature_to_clinic = result;
    }

    return data;
};

ClinicServiceFormController.prototype.addNewFeatures = function (status) {

    var self = this;
    if (self.disabl == true)
        return;

    self.disabl = true;

    var data = {};

    var form_values = [];

    $('input[type="hidden"]').each(function () {
        form_values[$(this).attr('name')] = $(this).val();
    });

    for (i in form_values) {
        data[i] = form_values[i];
    }

    data.entry_id = self.entry_id;
    data.status = status;

    Ajax.Post('/registry/ajax/addNewFeature', data, function (data) {
        if (data.status == 0 || data.status == 25) {

            if (status == 'moderate') {
                self.lock();
            }
        };

        self.disabl = false;
    });
};

ClinicServiceFormController.prototype.deleteFeature = function (el, feature_name) {
    Ajax.Post('/registry/ajax/deleteFeature', {feature_name:feature_name}, function (data) {
        if (data.status == 0) {
            el.parent().remove();
        }
    });
};

ClinicServiceFormController.prototype.lock = function () {

    lock_div = $('<div class="lock-div"></div>');

    lock_div.css({
        position:'absolute',
        left:0,
        top:150,
        width:$('#service-form').width() + 100,
        height:$('#service-form').height() + 50
    });

    $('#service-form').append(lock_div);
};


ClinicServiceFormController.prototype.inputmask = function () {

}
