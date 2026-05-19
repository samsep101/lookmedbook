var PersonalRoomAboutMailruAccountController = function () {

    this.profile_url;
    this.nick_name;
    this.status_text;

    var controller = this;

    this.init = function () {

        $('#save_mailru_account').click(function () {
            controller.profile_url = $('#mailru_profile_url').val();
            controller.nick_name = $('#mailru_nick_name').val();
            controller.status_text = $('#mailru_status_text').val();

            controller.saveMainMailruInfo();

        });
    };

    this.saveMainMailruInfo = function () {
        Ajax.Post('/account/ajaxSaveMailruAccountInfo', {
                profile_url:controller.profile_url,
                nick_name:controller.nick_name,
                status_text:controller.status_text
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