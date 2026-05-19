var UserEditPageController = function(){

    var self = this;

    this.user_id = null;

    this.init = function(){
        var edit_controller = new UserEditController();
        edit_controller.container = '.edit-form';
        edit_controller.user_id = self.user_id;
        edit_controller.init();

        var add_clinic_controller = new UserAddClinicController();
        add_clinic_controller.container = '.new_clinic_block';
        add_clinic_controller.user_id = self.user_id;
        add_clinic_controller.init();

        $('.delete-clinic').click(function(){
            var clinic_id = $(this).data('id');


            var modal_window = new ModalWindow();
            modal_window.setYesAction(function(){
                Ajax.Post('/manage/user/ajaxDeleteClinic', {clinic_id : clinic_id, user_id : self.user_id}, function(data){
                    if (data.status == 0)
                    {
                        $('.clinic-row-'+clinic_id).remove();
                    }
                });
            });

            modal_window.setNoAction(function(){
               modal_window.close();
            });

            modal_window.yes_button_text = 'Да';
            modal_window.no_button_text = 'Нет';

            modal_window.show('Подтверждаете удаление?');
        });
    };
};