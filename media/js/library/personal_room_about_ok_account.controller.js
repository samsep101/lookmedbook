var PersonalRoomAboutOkAccountController = function () {

    this.profile_url;
    this.age;

    var controller = this;

    this.init = function () {

        $('#save_ok_account').click(function () {
            controller.profile_url = $('#ok_profile_url').val();
            controller.age = $('#ok_age').val();

            controller.saveMainOkInfo();

        });
    };

    this.saveMainOkInfo = function () {
        Ajax.Post('/account/ajaxSaveOkAccountInfo', {
                profile_url:controller.profile_url,
                age:controller.age
            },
            function (data) {
                if (data.status == 0) {
                    showOk('Данные сохранены')
                    window.location = '/account/about';
                } else {
                    showError('Ошибка!');
                }
            }
        );
    }
};