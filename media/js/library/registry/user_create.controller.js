var UserCreateController = function()
{
    this.data = {
        login : null,
        password : null,
        role_id : null,
        clinic_id : null
    };

    var self = this;

    this.init = function()
    {

        $('input[name="save"]').validation({
            validate : [
                $('input[name="login"]').validate(validation_rules['user_login']),
                $('input[name="password"]').validate(validation_rules['password']),
                $('input[name="password2"]').validate(validation_rules['password2']),
                $('select[name="role_id"]').validate(validation_rules['required']),
                $('select[name="clinic_id"]').validate(validation_rules['required'])
            ],
            callback: function(){
                self.readData();
                self.sendData();
            }
        });
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
        self.data.clinic_id = $('select[name="clinic_id"] :selected').val();
    };

    this.sendData = function()
    {
        Ajax.Post('/manage/user/ajaxCreateAccount', self.data, function(data){
            if (data.status == 0)
            {
                var popup = new PopupMessage();
                popup.close_callback = function()
                {
                    window.location = '/manage/user';
                };

                popup.show('Пользователь успешно добавлен');
            } else {
                if (data.data)
                {
                    var popup = new PopupMessage();
                    popup.show(data.data[0]);
                }
            }
        });
    };
};