var PersonalRoomFamilyController = function () {

    this.first_name = '';
    this.last_name = '';
    this.middle_name = '';
    this.phone = 0;
    this.email = '';
    this.family_relation_status_id = 0;
    this.relation_id = 0;

    var self = this;
    this.init = function () {

        var controller = this;
        controller.getAccountRelations();

        $('input[name="phone"]').inputmask('+7-999-999-99-99');

        $('#add-relation-button').validation({
            validate: [
                $('input[name="phone"]').validate(validation_rules['phone_right']),
                $('input[name="email"]').validate(validation_rules['email_right']),
                $('input[name="first_name"]').validate(validation_rules['required']),
                $('input[name="last_name"]').validate(validation_rules['required']),
                $('input[name="middle_name"]').validate(validation_rules['required'])
            ],
            callback: controller.sendFamilyRequest
        });

        $(document).on('click', '.delete-relation', function () {
            controller.relation_id = $(this).attr("data-id");
            controller.relation_table = $(this).attr("class");
            controller.deleteRelation();
        });

        $(document).on('click', '.delete-relation-moderate', function () {
            controller.relation_id = $(this).attr("data-id");
            controller.relation_table = $(this).attr("class");
            controller.deleteRelation();
        });

        $(document).on('click', '.confirm-relation', function () {
            controller.relation_id = $(this).attr("data-id");
            controller.confirmRelation();
        });
    };

    this.deleteRelation = function () {
        controller1 = this;
        Ajax.Post('/account/ajaxDeleteFamilyRelation', {relation_id: this.relation_id, relation_table: this.relation_table}, function (data) {
            if (data.result == true) {
                //self.showPopup('Связь удалена');
                controller1.getAccountRelations();
            }
            else self.showPopup('Не получилось удалить связь!');
        });
    };

    this.confirmRelation = function () {
        controller1 = this;
        Ajax.Post('/account/ajaxConfirmFamilyRelation', {relation_id: this.relation_id}, function (data) {
            if (data.result == true) {
                controller1.getAccountRelations();
            }
            else self.showPopup('Не получилось подтвердить связь!');
        });
    };

    this.getAccountRelations = function () {
        Ajax.Get('/account/ajaxGetAccountRelations', {}, function (data) {
            if (data.status == 0){
                $('.account-relations').html(data.result.account_relations);
                $('.cab-family > h2').remove();
            } else if (data.status == 26){
                $('.account-relations').html('Родственные связи отсутсвуют');
            }
        });
    };

    this.sendFamilyRequest = function () {
        self.first_name = $('input[name="first_name"]').val();
        self.last_name = $('input[name="last_name"]').val();
        self.middle_name = $('input[name="middle_name"]').val();

        self.family_relation_status_id = $('.add-relation-status').val();

        if ($('input[name="phone"]').val() != '+7-___-___-__-__')
            self.phone = $('input[name="phone"]').val();
        else
            self.phone = '';

        self.email = $('input[name="email"]').val();
        Ajax.Post('/account/ajaxSaveFamilyRelation', {
            first_name: self.first_name,
            last_name: self.last_name,
            middle_name: self.middle_name,
            phone: self.phone,
            email: self.email,
            family_relation_status_id: self.family_relation_status_id},

            function (data) {
                var popup_message = new PopupMessage();
                if (data.result.add_relation == true) {
                    popup_message.show('Пользователю отправлен запрос для подтверждения');
                    $('#cab-form').trigger('reset');
                    $('input[name="phone"]').val('');
                }
                else popup_message.show(data.result.warning);
        });
    };

    this.showPopup = function (text) {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }
}