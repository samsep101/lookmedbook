var Loader = function () {
};

Loader.start = function () {
    Loader.run_flag = 1;
    setTimeout(function () {
        if (Loader.run_flag == 0)
            return;
        var html = '<div id="facebookG">';
        html += '<div id="blockG_1" class="facebook_blockG">';
        html += '</div>';
        html += '<div id="blockG_2" class="facebook_blockG">';
        html += '</div>';
        html += '<div id="blockG_3" class="facebook_blockG">';
        html += '</div>';
        html += '</div>';

        $('body').append(html);

        $('body').css('opacity', '0.5');
    }, 200);
};

Loader.stop = function () {
    Loader.run_flag = 0;
    $('#facebookG').remove();
    $('body').css('opacity', '1');
};

Loader.run_flag = 0;

var ModerateFormController = function () {
};

ModerateFormController.prototype = {
    form_values: [],
    model_name: null,
    status: null,
    disabled: false,
    lists: [],
    list_names: [],
    clinic_id : null,
    doctor_id : null,
    specialty_id : null,
    location : null,
    specialization_id : null,
    revision_conditions_fields : {},
    setModelName: function (model_name) {
        this.model_name = model_name;
    },
    setEntryId: function (entry_id) {
        this.entry_id = entry_id;
    }
};

ModerateFormController.prototype.container = null;

ModerateFormController.prototype.save_validation_obj = null;
ModerateFormController.prototype.moderate_validation_obj = null;

ModerateFormController.prototype.addValidationRule = function (el) {
    var self = this;
    var validation = $.data(self.save_validation_obj, "validate");

    el.each(function () {
        if (!$(this).data('pushed') && !$(this).data('pushed-save')) {
            $(this).data('pushed-save', 1);
            validation.push($(this));
        }
    });

    $.data(self.save_validation_obj, "validate", validation);


    var validation1 = $.data(self.moderate_validation_obj, "validate");

    el.each(function () {
        if (!$(this).data('pushed') && !$(this).data('pushed-moderate')) {
            $(this).data('pushed-moderate', 1);
            validation1.push($(this));
        }
    });

    $.data(self.moderate_validation_obj, "validate", validation1);


};

ModerateFormController.prototype.init = function (model_name, entry_id) {

    var Notifier = function () {
    };

    Notifier.errorNotify = function (message) {
        $.gritter.add({
            title: '',
            text: message,
            time: 2000,
            class_name: 'error-notify'
        });

    };

    var self = this;

    self.model_name = model_name;
    self.entry_id = entry_id;
    self.initFields();

    $('.cab-page-2 a, .prev-page-link').click(function(){
        self.location = $(this).attr('href');
        $(self.save_validation_obj.click());
        return false;
    });


    $('input[name="delete_clinic"]').click(function(){
        var modal_window = new ModalWindow();
        modal_window.setYesAction(function(){
            var data = {
                clinic_id : self.entry_id
            };
            Ajax.Post('/registry/manage/ajaxDeleteClinicById', data, function(data){
                if (data.status == 0) {
                    var popup = new PopupMessage();
                    popup.close_callback = function(){
                        window.location = '/registry/manage';
                    };
                    popup.show('Клиника удалена');
                }
            });
        });

        modal_window.yes_button_text = 'Удалить';
        modal_window.no_button_text = 'Нет';

        modal_window.show('Действительно удалить клинику?');
    });

    var save_obj = null;
    if ($('input[name="save"]').length > 0)
        save_obj = $('input[name="save"]');
    else {
        save_obj = $('<span></span>');
        $('body').append(save_obj);
    }


    self.save_validation_obj = $(save_obj).validation({
        validate: self.getValidation(),
        callback: function () {
            self.status = 'edit';
            self.sendRequest();
        },
        error_callback: function(){
            Notifier.errorNotify('Проверьте правильность заполнения данных');
        }
    });

    self.moderate_validation_obj = $(self.container + ' input[name="moderate"]').validation({
        validate: self.getValidation(),
        callback: function () {
            self.status = 'moderate';
            self.sendRequest();
        },
        error_callback: function(){
            Notifier.errorNotify('Проверьте правильность заполнения данных');
        }
    });

    self.problem_validation_obj = $(self.container + ' input[name="problem"]').validation({
        validate: self.getValidation(),
        callback: function () {
            self.status = 'problem';
            self.sendRequest();
        },
        error_callback: function(){
            Notifier.errorNotify('Проверьте правильность заполнения данных');
        }
    });

    self.moderate_validation_obj = $(self.container + ' input[name="publish"]').validation({
        validate: self.getValidation(),
        callback: function () {
            self.status = 'publish';
            self.sendRequest();
        },
        error_callback: function(){
            Notifier.errorNotify('Проверьте правильность заполнения данных');
        }
    });

    self.moderate_validation_obj = $(self.container + ' input[name="sent_back"]').validation({
        validate: self.getValidation(),
        callback: function () {
            self.status = 'sent_back';
            self.sendRequest();
        },
        error_callback: function(){
            Notifier.errorNotify('Проверьте правильность заполнения данных!');
        }
    });

    $(self.container + ' input[name="cancel"]').click(function () {
        location.reload();
    });


};

ModerateFormController.prototype.sendRequest = function () {
    var self = this;
    if (self.disabled == true)
        return;


    self.disabled = true;

    var data = {};

    data.lists = {};

    self.readValues();

    for (var i in self.form_values) {
        if(/^\+7\-[0-9]{3}\-[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/.test(self.form_values[i])){
            var str=self.form_values[i].replace('-','');
            str=str.replace('-','');
            str=str.replace('-','');
            str=str.replace('-','');
            self.form_values[i] = parseInt(str);
        }

        var index = i;
        if (i.indexOf('Array') != -1) {
            index = i.substring(0, i.indexOf('Array'));

            if (self.form_values[i])
                data.lists[index] = self.form_values[i];

            else
                data.lists[index] = 'clear';
        } else
            data[index] = self.form_values[i];
    }

    data.entry_id = self.entry_id;
    data.clinic_id = self.clinic_id;
    data.doctor_id = self.doctor_id;
    data.specialty_id = self.specialty_id;
    data.specialization_id = self.specialization_id;

    data.model_name = self.model_name;
    data.status = self.status;
    data.revision_conditions_fields = self.revision_conditions_fields;

    data = self.modifySendData(data);
    var dat = self.modifySendData(data);

    for (i in self.list_names) {
        var list_name = self.list_names[i];
        if ((data.lists[list_name] == undefined) || (data.lists[list_name].length == 0)) {
            data.lists[list_name] = 'clear';
        }
    }

    Loader.start();
    Ajax.Post('/registry/ajax/saveModeratedInfo', data, function (data) {
        if (data.status == 0) {
            if (ModerateFormController.redirect == false) {
                if (self.location) {
                    var location = self.location;
                    window.location = location;
                    return;
                } else {
                    Loader.stop();
                    var popup = new Popup();

                    var callback = null;

                    if (self.status == 'problem') {
                        callback =  function(){
                            if(data.result.role_id == 5)
                                window.location = '/registry/manage/regions?status_id=2';
                            else
                                window.location.reload();
                        };
                        popup.close_callback = callback;
                        popup.show('<div style="padding: 50px; font-size: 25px">Клиника помечена как проблемная</div>');

                    } else if (self.status == 'publish') {
                        callback = function(){
                            if(data.result.role_id == 5)
                                window.location = '/registry/manage/regions?status_id=2';
                            else
                                window.location.reload();
                        };
                        popup.close_callback = callback;

                        popup.show('<div style="padding: 50px; font-size: 25px">Данные опубликованы на сайте</div>');
                    } else {
                        callback = function(){
                            if ($('.prev-page-link').length > 0) {
                                var prev = $('.prev-page-link').attr('href');
                                var preg = /^.+?(\/(?:manage)|(?:registry).+)$/;

                                var matches = prev.match(preg);
                                queryString.set('prev', matches[1]);
                                queryString.load();
                            } else {
                                window.location.reload();
                            }
                        };
                        popup.setCloseCallback(callback);
                        popup.show('<div style="padding: 50px; font-size: 25px">Данные успешно сохранены</div>');
                    }

                    setTimeout(function(){
                        callback();
                    }, 3000);
                }

                if (self.status == 'moderate') {
                    self.lock();
                }

            } else {
                Loader.stop();
                var popup = new Popup();
                popup.show('<div style="padding: 50px; font-size: 25px">Запись успешно добавлена</div>');
                window.location = ModerateFormController.redirect + data.result.moderate_entity_id;
            }

            self.reloadDoctorFIO();

        } else if (data.status == 112) {
            Loader.stop();
            var options = {};
            options = {
                first_name : self.form_values["form[first_name]"],
                second_name : self.form_values["form[second_name]"],
                last_name : self.form_values["form[last_name]"],
                clinic_id : self.form_values["clinic_id"]
            };

            Ajax.Get('/registry/ajax/getExistingDoctors', options, function (data) {
                if (data.status == 0) {
                    var block_code = data.result.html;

                    self.popup = new Popup();
                    self.popup.show(block_code, 800);

                    $(document).on('click','.create-new-doctor',function () {
                        $('.fancybox-close').trigger('click');
                        $('input[name="form[to_validate]"]').val(0);
                        $('#information-doctor-form .buttons input[name="save"]').trigger('click');
                    });

                    $(document).on('click','.add-new-bound',function () {
                        self.bounded_clinic_id = $(this).attr('data-clinic-id');
                        self.bounded_doctor_id = $(this).attr('data-doctor-id');
                        options = {
                            clinic_id : self.bounded_clinic_id,
                            doctor_id : self.bounded_doctor_id
                        };
                        Ajax.Post('/registry/ajax/addDoctorToClinic', options, function (data) {
                            if (data.status == 0) {
                                window.location = '/registry/doctor/information?id='+self.bounded_doctor_id+'&clinic_id='+self.bounded_clinic_id;
                            }
                        });
                    });
                }
            });
        } else {
            var popup = new Popup();
            popup.show('<div style="padding: 50px; font-size: 25px">Ошибка при сохранении</div>');
            Loader.stop();
        }

        self.disabled = false;
    });
};

ModerateFormController.prototype.initFields = function () {

};

ModerateFormController.prototype.modifySendData = function (data) {
    return data;
};


ModerateFormController.prototype.setContainer = function (container) {
    this.container = container;
};

ModerateFormController.prototype.readValues = function () {
    var self = this;
    self.beforeReadValues();
    self.form_values = $(self.container).jqDynaForm('get');
    $('.htmlarea').each(function(){
        var id = $(this).attr('id');
        var name = $(this).attr('name');
        self.form_values[name] =  CKEDITOR.instances[id].getData();
    });
};

ModerateFormController.prototype.beforeReadValues = function(){

};

ModerateFormController.prototype.readListValues = function () {
};

ModerateFormController.prototype.lock = function () {
    var self = this;
    lock_div = $('<div class="lock-div"></div>');

    lock_div.css({
        position: 'absolute',
        left: 0,
        top: 170,
        width: $(self.container).width() + 300,
        height: $(self.container).height() + 50
    });

    var prev_height = $(self.container).height();

    setInterval(function(){
        var height = $(self.container).height();
        if (prev_height != height) {
            lock_div.css({
                position: 'absolute',
                left: 0,
                top: 170,
                width: $(self.container).width() + 300,
                height: $(self.container).height() + 50
            });
        }
    }, 100);


    $(this.container).append(lock_div);
    $('.swfupload').css('z-index', '0');
};


ModerateFormController.redirect = false;

ModerateFormController.prototype.getValidation = function () {
    return [];
};


ModerateFormController.prototype.reloadDoctorFIO = function () {
    var first_name = $('input[name="form[first_name]"]').val();
    var last_name = $('input[name="form[last_name]"]').val();
    var second_name = $('input[name="form[second_name]"]').val();
    if (first_name && last_name && second_name)
        $('.doctor-fio').html(last_name + ' ' + first_name + ' ' + second_name);
};
