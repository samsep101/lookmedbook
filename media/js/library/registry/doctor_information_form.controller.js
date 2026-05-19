var DoctorInformationFormController = function(container, gender){
    var self = this;
    this.container = container;
    this.gender = gender;
};

extend(DoctorInformationFormController, ModerateFormController);

DoctorInformationFormController.prototype.getValidation = function()
{
    var result = [];
    result.push($('input[name="form[last_name]"]').validate(validation_rules['last_name']));
    result.push($('input[name="form[first_name]"]').validate(validation_rules['first_name']));
    //result.push($('input[name="form[first_name]"]').validate(validation_rules['existing_doctor_by_fio']));
    //result.push($('input[name="form[second_name]"]').validate(validation_rules['second_name']));
    result.push($('textarea[name="form[about]"]').validate(validation_rules['about']));
    return result;
};

DoctorInformationFormController.prototype.initFields = function()
{
    var self = this;

    this.doctor_query = '';
    this.delete_purpose_text = '';
    this.delete_doctor_id = null;

    $('.doctor-filter').change(function(){
        $('.doctor-list-data').html('');
        self.doctor_query = $(this).val();
        if (self.doctor_query.length >= 3) {
            Ajax.Get("/registry/ajax/getDoctorListByName", {query : self.doctor_query}, function(data){
                if (data.status == 0){
                    $('.doctor-list-data').html(data.result.html);
                }
            });
        }
    });

    $('select[name="form[full_name]"]').change(function(){

        $('input[name="form[to_validate]"]').val(0);

        $('.adult .chekBox').removeClass('act');
        $('input[name="form[is_adult]"]').val(0);

        $('.pregnant .chekBox').removeClass('act');
        $('input[name="form[is_pregnant]"]').val(0);

        $('.child .chekBox').removeClass('act');
        $('input[name="form[is_children]"]').val(0);

        $('div .chekBox').removeClass('act');
        $('input[name="form[is_leave_the_house]"]').val(0);

        var data = {
            doctor_id: $(this).find('option:selected').val()
        };

        var doctor_id = $(this).find('option:selected').val();
        Ajax.Get("/registry/ajax/getDoctorById", data, function(data){
            if (data.status == 0 && data.result[0]){
                $('input[name="form[last_name]"]').val(data.result[0].last_name);
                $('input[name="form[first_name]"]').val(data.result[0].first_name);
                $('input[name="form[second_name]"]').val(data.result[0].second_name);

                $('input[name="form[rate]"]').val(data.result[0].rate);

                if (data.result[0].sex_id == 1){
                    $('.man').addClass('selected');
                    $('.woman').removeClass('selected');
                } else {
                    $('.woman').addClass('selected');
                    $('.man').removeClass('selected');
                }
                $('input[name="form[sex_id]"]').val(data.result[0].sex_id);

                if (data.result[0].is_adult == 1){
                    $('.adult .chekBox').addClass('act');
                    $('input[name="form[is_adult]"]').val(data.result[0].is_adult);
                }

                if (data.result[0].is_pregnant == 1){
                    $('.pregnant .chekBox').addClass('act');
                    $('input[name="form[is_pregnant]"]').val(data.result[0].is_pregnant);
                }

                if (data.result[0].is_children == 1){
                    $('.child .chekBox').addClass('act');
                    $('input[name="form[is_children]"]').val(data.result[0].is_children);
                }

                if (data.result[0].is_leave_the_house == 1){
                    $('div .chekBox').addClass('act');
                    $('input[name="form[is_leave_the_house]"]').val(data.result[0].is_leave_the_house);
                }

                $('textarea[name="form[about]"]').val(data.result[0].about);

                self.entry_id = doctor_id;
            } else {
                $('input[name="form[last_name]"]').val('');
                $('input[name="form[first_name]"]').val('');
                $('input[name="form[second_name]"]').val('');
                $('input[name="form[rate]"]').val('');

                $('.woman').addClass('selected');
                $('.man').removeClass('selected');
                $('input[name="form[sex_id]"]').val(2);

                $('.adult .chekBox').removeClass('act');
                $('input[name="form[is_adult]"]').val(0);

                $('.child .chekBox').removeClass('act');
                $('input[name="form[is_children]"]').val(0);

                $('.pregnant .chekBox').removeClass('act');
                $('input[name="form[is_pregnant]"]').val(0);

                $('div .chekBox').removeClass('act');
                $('input[name="form[is_leave_the_house]"]').val(0);

                $('textarea[name="form[about]"]').val('');

                self.entry_id = 0;
            }
        });
    });

    // Блокировка submit при наличии ошибки в данных врача
    if($(".doctor-specialties-error").text() != '') {
        $(".btn-1").attr('disabled', 'disabled');
    }
    else {
        $(".btn-1").removeAttr('disabled');
    }

    $(".horizontal").on('click', '.adult.blocked', function() {
        var element = $(".doctor-specialties-error.adult");

        if(element.text() != '') {
            element.text('');
            element.hide();
            $(this).children(".chekBox").toggleClass('act');
            $(this).children().children("input").val(1);
            $(".btn-1").removeAttr('disabled');
        }
    });

    $(".horizontal").on('click', '.children.blocked', function() {
        var element = $(".doctor-specialties-error.children");

        if(element.text() != '') {
            element.text('');
            element.hide();
            $(this).children(".chekBox").toggleClass('act');
            $(this).children().children("input").val(1);
            $(".btn-1").removeAttr('disabled');
        }
    });

    var controller = new SexSelectFormController(self.container, self.gender);
    window.validation_span = true;
    controller.init();

    $(document).on('click','input[name="delete_doctor"]',function () {
        self.delete_doctor_id = $(this).attr('data-id');
        var popup = new Popup();
        popup.show('<div style="padding: 50px; font-size: 25px"><textarea id="deleted_doctor_review" class="review-textarea" placeholder="Причина удаления врача"></textarea><div class="delete-doctor-btns"><span class="btn-4"><input class="yes-delete-doctor-button" type="submit" value="Удалить"></span><span class="btn-4"><input class="fallback" type="submit" value="Отмена"></span></div></div>');
    });

    $(document).on('click','.yes-delete-doctor-button',function () {
        self.delete_purpose_text = $('#deleted_doctor_review').val();

        var options = {
            doctor_id : self.delete_doctor_id,
            purpose_text : self.delete_purpose_text
        };

         Ajax.Post('/registry/ajax/deleteDoctor', options, function (data) {
             if (data.status == 0) {
                 if (data.result.deleted) {
                     window.location = '/registry/manage';
                 }
             }
             else location.reload();
         });
    });
};