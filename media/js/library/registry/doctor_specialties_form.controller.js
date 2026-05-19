var DoctorSpecialtiesFormController = function (doctor_id, clinic_id) {
    var self = this;

    self.delete_specialty_id = null;

    /*this.init = function(){
        $('.yes-delete-doctor-button').click(function(){
            self.deleteSpecialty();
        });
    };*/

    $('.yes-delete-doctor-button').click(function(){
        $(this).parent().parent().parent().remove();
    });

    $('.fields-block-inner').each(function(){
        var purpose_controller = new PurposesBlockController();
        purpose_controller.container_object = $(this);
        purpose_controller.doctor_id = doctor_id;
        purpose_controller.clinic_id = clinic_id;
        purpose_controller.init();
    });

    self.list_names = ['purpose_of_visit_to_doctor', 'doctor_specialty_to_clinic'];
    self.lists_revision_conditions = {
        purpose_of_visit_to_doctor : {
            'doctor_id' : doctor_id,
            'clinic_id' : clinic_id
        },
        specialty_to_doctor : {
            'doctor_id' : doctor_id,
            'clinic_id' : clinic_id
        }
    };

    /*
    this.deleteSpecialty = function(){
        if (self.delete_specialty_id) {
            var data = {
                specialty_id: self.delete_specialty_id,
                doctor_id: doctor_id,
                clinic_id: clinic_id,
            };
            Ajax.Post('/registry/doctor/ajaxDeleteSpecialty', data, function (data) {
                if (data.status != '100') {
                    if (data.result.deleted) {
                        location.reload();
                    }
                }
                else window.location = '/';
            });
        }
    };
    */
};

extend(DoctorSpecialtiesFormController, ModerateFormController);



DoctorSpecialtiesFormController.prototype.getValidation = function () {
    var result = [];
    result.push($('input[name="form[license_number]"]').validate(validation_rules['license_number']));
    result.push($('input[name="form[license_issue_date]"]').validate(validation_rules['license_issue_date']));
//    result.push($('input[name="form[license_validity_date]"]').validate(validation_rules['license_validity_date']));
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

    $(document).on('click', '.purpose-row .price .set_price_button', function(){
        var value  = $(this).parent().find('.clinic_price input[name="clinic_price_value"]').val().replace(/\s/g, '');
        var el = $(this).parent().prev().find('input[name="visit_price"]');
        el.val(value);
        $(this).remove();
    });
};


DoctorSpecialtiesFormController.prototype.modifySendData = function (data) {


    if (data.lists.purpose_of_visit_to_doctor != 'clear') {
        var result = [];
        for (i in data.lists.purpose_of_visit_to_doctor) {
            obj = data.lists.purpose_of_visit_to_doctor[i];

            if (obj.is_selected == 1) {
                result.push(obj);
            }
        }
        data.lists.purpose_of_visit_to_doctor = result;
    }

    if (data.lists.doctor_specialty_to_clinic != 'clear') {
        var result1 = [];
        for (i in data.lists.doctor_specialty_to_clinic) {
            obj = data.lists.doctor_specialty_to_clinic[i];

            if (obj.specialty_id != '') {
                result1.push(obj);
            }
        }
        data.lists.doctor_specialty_to_clinic = result1;
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
