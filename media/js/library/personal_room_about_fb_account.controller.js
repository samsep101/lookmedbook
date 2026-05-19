var PersonalRoomAboutFbAccountController = function () {

    this.profile_url;
    this.user_name;
    this.hometown;
    this.bio;
    this.quotes;
    this.political_view;
    this.is_interested_in_male;
    this.is_interested_in_female;
    this.relationship_status;
    this.religion;
    this.web_sites;

    var controller = this;

    this.init = function () {

        $('#save_fb_account').click(function () {
            controller.profile_url = $('#fb_profile_url').val();
            controller.user_name = $('#fb_user_name').val();
            controller.hometown = $('#fb_hometown').val();
            controller.bio = $('#fb_bio').val();
            controller.quotes = $('#fb_quotes').val();
            controller.political_view = $('#fb_political_view').val();
            controller.is_interested_in_male = ($('#fb_is_interested_in_male').is(':checked')) ? 1 : null;
            controller.is_interested_in_female = ($('#fb_is_interested_in_female').is(':checked')) ? 1 : null;
            controller.relationship_status = $('#fb_relationship_status').val();
            controller.religion = $('#fb_religion').val();
            controller.web_sites = $('#fb_web_sites').val();

            controller.saveMainFbInfo();

        });
    };

    this.saveMainFbInfo = function () {
        Ajax.Post('/account/ajaxSaveFbAccountInfo', {
                profile_url: controller.profile_url,
                user_name: controller.user_name,
                hometown: controller.hometown,
                bio: controller.bio,
                quotes: controller.quotes,
                political_view: controller.political_view,
                is_interested_in_male: controller.is_interested_in_male,
                is_interested_in_female: controller.is_interested_in_female,
                relationship_status: controller.relationship_status,
                religion: controller.religion,
                web_sites: controller.web_sites
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