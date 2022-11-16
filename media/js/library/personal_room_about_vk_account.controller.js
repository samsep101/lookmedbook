var PersonalRoomAboutVkAccountController = function () {

    this.profile_url;
    this.home_phone;
    this.activity;
    this.relation_type;
    this.interests;
    this.movies;
    this.tv;
    this.books;
    this.games;
    this.about;

    var controller = this;

    this.init = function () {

        $('#save_vk_account').click(function () {
            controller.profile_url = $('#vk_profile_url').val();
            controller.home_phone = $('#vk_home_phone').val();
            controller.vk_activity = $('#vk_activity').val();
            controller.relation_type = $('#vk_relation_type').val();
            controller.interests = $('#vk_interests').val();
            controller.movies = $('#vk_movies').val();
            controller.tv = $('#vk_tv').val();
            controller.books = $('#vk_books').val();
            controller.games = $('#vk_games').val();
            controller.about = $('#vk_about').val();
            //console.log(controller);
            controller.saveMainVkInfo();

        });
    };

    this.saveMainVkInfo = function () {
        Ajax.Post('/account/ajaxSaveVkAccountInfo', {
                profile_url:controller.profile_url,
                home_phone:controller.home_phone,
                activity:controller.activity,
                relation_type:controller.relation_type,
                interests:controller.interests,
                movies:controller.movies,
                tv:controller.tv,
                books:controller.books,
                games:controller.games,
                about:controller.about
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