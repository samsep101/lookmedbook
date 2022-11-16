var UserEditController = function()
{
    this.user_id = null;

    this.data = {
        user_id : null,
        login : null,
        password : null
    };

    this.container = null;
    this.change_flag = false;

    var self = this;

    this.init = function()
    {
        $(self.container + ' input[name=login]').change(function (){
            self.change_flag = true;
            $(self.container + ' input[name="save"]').validation({
                validate : [
                    $(self.container + ' input[name="login"]').validate(validation_rules['user_login']),
                    $(self.container + ' input[name="password"]').validate(validation_rules['edit_password']),
                    $(self.container + ' input[name="password2"]').validate(validation_rules['password2']),
                    $(self.container + ' select[name="role_id"] :selected').validate(validation_rules['required'])
                ],
                callback: function(){
                    self.readData();
                    self.sendData();
                }
            });
        });

        if (!self.change_flag){
            $(self.container + ' input[name="save"]').validation({
                validate : [
                    $(self.container + ' input[name="password"]').validate(validation_rules['edit_password']),
                    $(self.container + ' input[name="password2"]').validate(validation_rules['password2']),
                    $(self.container + ' select[name="role_id"] :selected').validate(validation_rules['required'])
                ],
                callback: function(){
                    self.readData();
                    self.sendData();
                }
            });
        }
        /*
         $('input[name="save"]').click(function(){
         self.readData();
         self.sendData();
         });*/
    };

    this.readData = function()
    {
        self.data.login = $('input[name="login"]').val();
        self.data.password = $('input[name="password"]').val();
        self.data.role_id = $('select[name="role_id"] :selected').val();
        self.data.user_id  = self.user_id;
    };

    this.sendData = function()
    {
        Ajax.Post('/manage/user/ajaxEditAccount', self.data, function(data){
            if (data.status == 0)
            {
                var popup = new PopupMessage();
                popup.close_callback = function()
                {
                    window.location.reload();
                };

                popup.show('Данные успешно сохранены');
            }
        });
    };
};