var DoctorSpecialtiesFormController = function (doctor_id, clinic_id) {
    var self = this;


    $('.fields-block-inner').each(function(){
        var purpose_controller = new PurposesBlockController();
        purpose_controller.container_object = $(this);
        purpose_controller.doctor_id = doctor_id;
        purpose_controller.clinic_id = clinic_id;
        purpose_controller.init();
    });

    self.list_names = ['purpose_of_visit_to_doctor', 'specialty_to_doctor'];
};

extend(DoctorSpecialtiesFormController, ModerateFormController);



DoctorSpecialtiesFormController.prototype.getValidation = function () {
    var result = [];
    result.push($('input[name="form[license_number]"]').validate(validation_rules['license_number']));
    result.push($('input[name="form[license_issue_date]"]').validate(validation_rules['license_issue_date']));
    result.push($('input[name="form[license_validity_date]"]').validate(validation_rules['license_validity_date']));
    return result;
};

DoctorSpecialtiesFormController.prototype.initFields = function () {



    $(document).on('change', 'input[name="first_visit_price"], input[name="second_visit_price"]', function () {
        if ($(this).val()) {
            var container = $(this).parent().parent();
            var checkbox = container.find('.chekBox');
            if (!checkbox.hasClass('act')) {
                checkbox.click();
            }
        }
    });
};


DoctorSpecialtiesFormController.prototype.modifySendData = function (data) {
    var result = [];

    if (data.lists.purpose_of_visit_to_doctor != 'clear') {
        for (i in data.lists.purpose_of_visit_to_doctor) {
            obj = data.lists.purpose_of_visit_to_doctor[i];

            if (obj.is_selected == 1) {
                result.push(obj);
            }
        }
        data.lists.purpose_of_visit_to_doctor = result;
    }

    return data;
};

var PurposesBlockController = function(){

    var self = this;

    self.doctor_id = null;
    self.clinic_id = null;

    self.container_object = null;

    this.init = function(){
        self.container_object.find('select[name="specialty_id"]').change(function () {
            self.loadPurposesBlock();
        });

        self.loadPurposesBlock();
    };

    this.loadPurposesBlock = function () {
        Inputmask.extendDefinitions({
            'n': {
                "validator": "[0-9]",
                "cardinality": 1,
                'prevalidator': null
            }
        });

        var specialty_id = self.container_object.find('select[name="specialty_id"] option:selected').val();
        if (specialty_id) {
            var data = {
                specialty_id: specialty_id,
                doctor_id: self.doctor_id,
                clinic_id: self.clinic_id
            };
            Ajax.Get('/registry/ajax/getPurposeOfVisitsBlockByDoctorId', data, function (data) {
                if (data.status == 0) {
                    self.container_object.find('#purposes-block').html(data.result.html);
                    self.container_object.find('input[name="first_visit_price"]').inputmask({ "mask": 'n', "repeat": 1000, "greedy": false });
                    self.container_object.find('input[name="second_visit_price"]').inputmask({ "mask": 'n', "repeat": 1000, "greedy": false });
                }
            });
        } else {
            self.container_object.find('#purposes-block').html('');
        }
    };
}
