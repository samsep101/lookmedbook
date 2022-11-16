var DoctorListController = function (clinic_id) {

    this.public_doctor_id = null;
    this.specialty_id = null;
    this.doctor_name = null;
    this.page = 1;

    this.delete_doctor_id = null;
    this.delete_purpose_text = '';

    this.unbound_doctor_id = null;
    this.rebound_doctor_id = null;
    this.rebound_specialty_id = null;
    this.rebound_first_visit_price = null;
    this.rebound_second_visit_price = null;

    this.clinic_id = clinic_id;
    var self = this;

    this.init = function () {
        self.searchDoctor();

        $(document).on('click','.publish-button input',function () {
            self.public_doctor_id = $(this).attr('data-id');
            self.publishDoctor($(this));
        });

        $('.find-doctor').click(function () {
            if ($('.fio-field').val() != 'ФИО врача')
                self.doctor_name = $('.fio-field').val();
            else self.doctor_name = null;
            self.page = 1;
            self.searchDoctor();
        });

        $('.fio-field').keyup(function (event) {
            if (event.keyCode==13) {
                if ($('.fio-field').val() != 'ФИО врача')
                    self.doctor_name = $('.fio-field').val();
                else self.doctor_name = null;
                self.page = 1;
                self.searchDoctor();
            }
        });

        $('.specialty-pick').change(function () {
            if ($('.fio-field').val() != 'ФИО врача')
                self.doctor_name = $('.fio-field').val();
            else self.doctor_name = null;
            self.page = 1;
            self.specialty_id = $(this).val();
            self.searchDoctor();
        });

        $(document).on('click','.paging-previous, .paging-next',function () {
            if ($(this).hasClass('paging-previous')) self.page = self.page-1;
            else if ($(this).hasClass('paging-next')) self.page = self.page+1;
            self.searchDoctor();
        });

        $(document).on('click','.doctor-record .remove-feature',function () {
            self.delete_doctor_id = $(this).attr('data-id');
            var popup = new Popup();
            popup.show('<div style="padding: 50px; font-size: 25px"><textarea id="deleted_doctor_review" class="review-textarea" placeholder="Причина удаления врача"></textarea><div class="delete-doctor-btns"><span class="btn-4"><input class="yes-delete-doctor-button" type="submit" value="Удалить"></span><span class="btn-4"><input class="fallback" type="submit" value="Отмена"></span></div></div>');
        });

        $(document).on('click','.doctor-record .unbound-doctor',function () {
            self.unbound_doctor_id = $(this).attr('data-id');
            var data = {};

            data = {
                doctor_id : self.unbound_doctor_id,
                clinic_id : self.clinic_id
            };
            Ajax.Post('/registry/ajax/deleteDoctorToClinic', data, function (data) {
                if (data.status == 0) {

                }
            });
        });

        $(document).on('click','.doctor-record .rebound-doctor',function () {
            self.rebound_doctor_id = $(this).attr('data-id');
            self.rebound_specialty_id = $(this).attr('data-specialty-id');
            self.rebound_first_visit_price = $(this).attr('data-first-price-id');
            self.rebound_second_visit_price = $(this).attr('data-second-price-id');
            var data = {};

            data = {
                doctor_id : self.rebound_doctor_id,
                clinic_id : self.clinic_id,
                first_visit_price : self.rebound_first_visit_price,
                second_visit_price : self.rebound_second_visit_price,
                specialty_id : self.rebound_specialty_id
            };
            Ajax.Post('/registry/ajax/restoreDoctorToClinic', data, function (data) {
                if (data.status == 0) {

                }
            });
        });

        $(document).on('click','.yes-delete-doctor-button',function () {
            self.delete_purpose_text = $('#deleted_doctor_review').val();
            self.deleteDoctor();
        });

    };

    this.searchDoctor = function () {
        var options = {};

        if (self.clinic_id) {
            options = {
                doctor_name : self.doctor_name,
                specialty_id : self.specialty_id,
                page : self.page,
                clinic_id : self.clinic_id
            };
        } else {
            options = {
                doctor_name : self.doctor_name,
                specialty_id : self.specialty_id,
                page : self.page,
                clinic_id : getParameterByName('clinic_id')
            };
        }
        Ajax.Get('/registry/ajax/searchDoctor', options, function (data) {
            if (data.status != '100') {
                if (data.status == 0) {
                    $('.doctor-list').html(data.result.html);
                }
            }
            else window.location = '/';
        });
    };

    this.publishDoctor = function (button) {
        Ajax.Post('/registry/ajax/publishDoctor', {doctor_id : self.public_doctor_id}, function (data) {
            if (data.status != '100') {
                var isItAddOrKick = data.result.published;

                if (isItAddOrKick){
                    button.removeClass('btn-appoint');
                    button.addClass('btn-1');
                    button.val('Снять');
                }
                else {
                    button.removeClass('btn-1');
                    button.addClass('btn-appoint');
                    button.val('Опубликовать');
                }
            }
            else window.location = '/';
        });
    };

    this.deleteDoctor = function () {
        var options = {};

        if (self.clinic_id) {
            options = {
                doctor_id : self.delete_doctor_id,
                purpose_text : self.delete_purpose_text,
                clinic_id : self.clinic_id
            };
        } else {
            options = {
                doctor_id : self.delete_doctor_id,
                purpose_text : self.delete_purpose_text
            };
        }

        Ajax.Post('/registry/ajax/deleteDoctor', options, function (data) {
            if (data.status != '100') {
                if (data.result.deleted) {
                    location.reload();
                }
            }
            else window.location = '/';
        });
    };
};