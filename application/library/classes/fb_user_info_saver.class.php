<?php

    class FbUserInfoSaver
    {

        public function save($token, $account_id)
        {
            $facebook_client_id = SettingsManager::get('facebook_client_id');
            $facebook_client_secret = SettingsManager::get('facebook_client_secret');
            $redirect_url = SITE_URL . $_SERVER['REQUEST_URI'];

            $fb_auth = new FacebookAuth($facebook_client_id, $facebook_client_secret, $redirect_url);

            $user_data = $fb_auth->getAllUserData($token); //инфа о текущем пользователе

            $fb_account_manager = new FbAccountManager();
            $fb_account = $fb_account_manager->getOneByUid($user_data['id']);

            if (!$fb_account)
                $fb_account = new FbAccountModel();

            if (!$fb_account->is_account_connected || !isset($fb_account->is_account_connected)) {

                $this->saveMainFbInfo($fb_account, $user_data, $account_id); //общая инфа

                if (isset($user_data['education']))
                    $this->saveEducationFbInfo($fb_account, $user_data['education']); // инфа об образовании

                if (isset($user_data['languages']))
                    $this->saveLanguagesFbInfo($fb_account, $user_data['languages']); // инфа о языках

                if (isset($user_data['work']))
                    $this->saveWorksFbInfo($fb_account, $user_data['work']); //инфа о местах работы

                $this->saveFriendsFbInfo($fb_account, $fb_friends = $fb_auth->getUserFriends($token)); //инфа о друзьях
                $this->saveBooksFbInfo($fb_account, $user_books = $fb_auth->getUserBooks($token)); //инфа о книгах
                $this->saveGroupsFbInfo($fb_account, $user_groups = $fb_auth->getUserGroups($token)); // инфа о группах
                $this->saveInterestsFbInfo($fb_account, $user_interests = $fb_auth->getUserInterests($token)); // инфа об интересах
                $this->saveLikesFbInfo($fb_account, $user_likes = $fb_auth->getUserLikes($token)); // инфа о likes
                $this->saveMoviesFbInfo($fb_account, $user_movies = $fb_auth->getUserMovies($token)); // инфа о фильмах
                $this->saveMusicFbInfo($fb_account, $user_musics = $fb_auth->getUserMusics($token)); // инфа о мызуке
                $this->saveTelevisionsFbInfo($fb_account, $user_tv = $fb_auth->getUserTelevisions($token)); // инфа о тв

                return true;
            }
        }


        private function saveMainFbInfo($fb_account, $user_data, $account_id)
        {
            if (isset($user_data['interested_in'])) {

                if (count($user_data['interested_in']) > 1) {
                    $interested_in_female = 1;
                    $interested_in_male = 1;
                } else {
                    $interested_in_male = ($user_data['interested_in'][0] == 'male') ? 1 : null;
                    $interested_in_female = ($user_data['interested_in'][0] == 'female') ? 1 : null;
                }
            } else {
                $interested_in_female = null;
                $interested_in_male = null;
            }

            $fb_account->account_id = $account_id;
            $fb_account->uid = $user_data['id'];
            $fb_account->first_name = (isset($user_data['first_name'])) ? $user_data['first_name'] : '';
            $fb_account->last_name = (isset($user_data['last_name'])) ? $user_data['last_name'] : '';
            $fb_account->middle_name = (isset($user_data['middle_name'])) ? $user_data['middle_name'] : '';
            $fb_account->user_name = (isset($user_data['username'])) ? $user_data['username'] : '';
            $fb_account->profile_url = $user_data['link'];
            $fb_account->bio = (isset($user_data['bio'])) ? $user_data['bio'] : '';
            $fb_account->quotes = (isset($user_data['quotes'])) ? $user_data['quotes'] : '';
            $fb_account->hometown = $user_data['hometown'];
            $fb_account->political_view = (isset($user_data['political'])) ? $user_data['political'] : '';
            $fb_account->is_interested_in_male = $interested_in_male;
            $fb_account->is_interested_in_female = $interested_in_female;
            $fb_account->relationship_status = (isset($user_data['relationship_status'])) ? $user_data['relationship_status'] : '';
            $fb_account->relation_partner_uid = $user_data['relation_partner_uid'];
            $fb_account->relation_partner_name = $user_data['relation_partner_name'];
            $fb_account->religion = (isset($user_data['religion'])) ? $user_data['religion'] : '';
            $fb_account->web_sites = (isset($user_data['website'])) ? $user_data['website'] : '';

            $fb_account->is_account_connected = 1;

            ModelManagerFactory::getByName('fb_account')->save($fb_account);
        }

        private function saveFriendsFbInfo($fb_account, $user_friends)
        {
            //друзья
            if (count($user_friends)) {
                foreach ($user_friends as $user_friend) {
                    $fb_account_friend = new FbAccountFriendModel();

                    $fb_account_friend->fb_account_id = $fb_account->getId();
                    $fb_account_friend->fb_system_id = (isset($user_friend['id'])) ? $user_friend['id'] : '';
                    $fb_account_friend->first_name = (isset($user_friend['first_name'])) ? $user_friend['first_name'] : '';
                    $fb_account_friend->last_name = (isset($user_friend['last_name'])) ? $user_friend['last_name'] : '';
                    $fb_account_friend->profile_url = (isset($user_friend['link'])) ? $user_friend['link'] : '';

                    ModelManagerFactory::getByName('fb_account_friend')->save($fb_account_friend);
                }
            }
        }

        private function saveBooksFbInfo($fb_account, $user_books)
        {
            //книги
            if (count($user_books)) {
                foreach ($user_books as $user_book) {
                    $fb_account_book = new FbAccountBookModel();

                    $fb_account_book->fb_account_id = $fb_account->getId();
                    $fb_account_book->fb_system_id = (isset($user_book['id'])) ? $user_book['id'] : '';
                    $fb_account_book->name = (isset($user_book['name'])) ? $user_book['name'] : '';
                    $fb_account_book->category = (isset($user_book['category'])) ? $user_book['category'] : '';

                    ModelManagerFactory::getByName('fb_account_book')->save($fb_account_book);
                }
            }
        }

        //todo: $fb_account_work->city_id =
        private function saveWorksFbInfo($fb_account, $user_works)
        {
            //места работы
            if (count($user_works)) {
                foreach ($user_works as $user_work) {
                    $fb_account_work = new FbAccountWorkModel();

                    $fb_account_work->fb_account_id = $fb_account->getId();
                    $fb_account_work->employer_name = (isset($user_work['employer']['name'])) ? $user_work['employer']['name'] : '';
                    $fb_account_work->description = (isset($user_work['description'])) ? $user_work['description'] : '';

                    $fb_account_work->position_name = (isset($user_work['position']['name'])) ? $user_work['position']['name'] : '';
                    $fb_account_work->dt_start = (isset($user_work['start_date'])) ? $user_work['start_date'] . '-01' : null;
                    $fb_account_work->dt_end = (isset($user_work['end_date'])) ? $user_work['end_date'] . '-01' : null;

                    ModelManagerFactory::getByName('fb_account_work')->save($fb_account_work);
                }
            }
        }

        private function saveEducationFbInfo($fb_account, $user_educations)
        {
            //образовательные учреждения
            if (count($user_educations)) {
                foreach ($user_educations as $user_education) {
                    $fb_account_education = new FbAccountEducationModel();

                    $fb_account_education->fb_account_id = $fb_account->getId();
                    $fb_account_education->school_name = (isset($user_education['school']['name'])) ? $user_education['school']['name'] : '';
                    $fb_account_education->type = (isset($user_education['type'])) ? $user_education['type'] : '';
                    $fb_account_education->year_end = (isset($user_education['year']['name'])) ? $user_education['year']['name'] : null;
                    $fb_account_education->degree_name = (isset($user_education['degree']['name'])) ? $user_education['degree']['name'] : '';
                    $fb_account_education->concentration_1 = (isset($user_education['concentration'][0]['name'])) ? $user_education['concentration'][0]['name'] : '';
                    $fb_account_education->concentration_2 = (isset($user_education['concentration'][1]['name'])) ? $user_education['concentration'][1]['name'] : '';
                    $fb_account_education->concentration_3 = (isset($user_education['concentration'][2]['name'])) ? $user_education['concentration'][2]['name'] : '';

                    ModelManagerFactory::getByName('fb_account_education')->save($fb_account_education);

                    if (isset($user_education['classes']))
                        $this->saveClassesFbInfo($fb_account_education, $user_education['classes']);
                }
            }
        }

        private function saveClassesFbInfo($fb_account_education, $user_classes)
        {
            //классы (курсы)
            if (count($user_classes)) {
                foreach ($user_classes as $user_class) {
                    $fb_account_class = new FbAccountClassModel();

                    $fb_account_class->fb_account_education_id = $fb_account_education->getId();
                    $fb_account_class->fb_system_id = (isset($user_class['id'])) ? $user_class['id'] : '';
                    $fb_account_class->name = (isset($user_class['name'])) ? $user_class['name'] : '';
                    $fb_account_class->description = (isset($user_class['description'])) ? $user_class['description'] : '';

                    ModelManagerFactory::getByName('fb_account_class')->save($fb_account_class);
                }
            }
        }

        private function saveGroupsFbInfo($fb_account, $user_groups)
        {
            //группы
            if (count($user_groups)) {
                foreach ($user_groups as $user_group) {
                    $fb_account_group = new FbAccountGroupModel();

                    $fb_account_group->fb_account_id = $fb_account->getId();
                    $fb_account_group->fb_system_id = (isset($user_group['id'])) ? $user_group['id'] : '';
                    $fb_account_group->name = (isset($user_group['name'])) ? $user_group['name'] : '';
                    $fb_account_group->is_current_group_product = (isset($user_group['version'])) ? $user_group['version'] : null;
                    $fb_account_group->bookmark_order = (isset($user_group['bookmark_order'])) ? $user_group['bookmark_order'] : null;

                    ModelManagerFactory::getByName('fb_account_group')->save($fb_account_group);
                }
            }
        }

        private function saveInterestsFbInfo($fb_account, $user_interests)
        {
            //интересы
            if (count($user_interests)) {
                foreach ($user_interests as $user_interes) {
                    $fb_account_interes = new FbAccountInteresModel();

                    $fb_account_interes->fb_account_id = $fb_account->getId();
                    $fb_account_interes->fb_system_id = (isset($user_interes['id'])) ? $user_interes['id'] : '';
                    $fb_account_interes->name = (isset($user_interes['name'])) ? $user_interes['name'] : '';
                    $fb_account_interes->category = (isset($user_interes['category'])) ? $user_interes['category'] : null;

                    ModelManagerFactory::getByName('fb_account_interes')->save($fb_account_interes);
                }
            }
        }

        private function saveLanguagesFbInfo($fb_account, $user_languages)
        {
            //языки
            if (count($user_languages)) {
                foreach ($user_languages as $user_language) {
                    $fb_account_language = new FbAccountLanguageModel();

                    $fb_account_language->fb_account_id = $fb_account->getId();
                    $fb_account_language->fb_system_id = (isset($user_language['id'])) ? $user_language['id'] : '';
                    $fb_account_language->name = (isset($user_language['name'])) ? $user_language['name'] : '';

                    ModelManagerFactory::getByName('fb_account_language')->save($fb_account_language);
                }
            }
        }

        private function saveLikesFbInfo($fb_account, $user_likes)
        {
            //likes
            if (count($user_likes)) {
                foreach ($user_likes as $user_like) {
                    $fb_account_like = new FbAccountLikeModel();

                    $fb_account_like->fb_account_id = $fb_account->getId();
                    $fb_account_like->fb_system_id = (isset($user_like['id'])) ? $user_like['id'] : '';
                    $fb_account_like->name = (isset($user_like['name'])) ? $user_like['name'] : '';
                    $fb_account_like->category = (isset($user_like['category'])) ? $user_like['category'] : null;

                    ModelManagerFactory::getByName('fb_account_like')->save($fb_account_like);
                }
            }
        }

        private function saveMoviesFbInfo($fb_account, $user_movies)
        {
            //фильмы
            if (count($user_movies)) {
                foreach ($user_movies as $user_movie) {
                    $fb_account_movie = new FbAccountMovieModel();

                    $fb_account_movie->fb_account_id = $fb_account->getId();
                    $fb_account_movie->fb_system_id = (isset($user_movie['id'])) ? $user_movie['id'] : '';
                    $fb_account_movie->name = (isset($user_movie['name'])) ? $user_movie['name'] : '';
                    $fb_account_movie->category = (isset($user_movie['category'])) ? $user_movie['category'] : null;

                    ModelManagerFactory::getByName('fb_account_movie')->save($fb_account_movie);
                }
            }
        }

        private function saveMusicFbInfo($fb_account, $user_musics)
        {
            //музыка
            if (count($user_musics)) {
                foreach ($user_musics as $user_music) {
                    $fb_account_music = new FbAccountMusicModel();

                    $fb_account_music->fb_account_id = $fb_account->getId();
                    $fb_account_music->fb_system_id = (isset($user_music['id'])) ? $user_music['id'] : '';
                    $fb_account_music->name = (isset($user_music['name'])) ? $user_music['name'] : '';
                    $fb_account_music->category = (isset($user_music['category'])) ? $user_music['category'] : null;

                    ModelManagerFactory::getByName('fb_account_music')->save($fb_account_music);
                }
            }
        }

        private function saveTelevisionsFbInfo($fb_account, $user_televisions)
        {
            //тв
            if (count($user_televisions)) {
                foreach ($user_televisions as $user_tv) {
                    $fb_account_tv = new FbAccountTelevisionModel();

                    $fb_account_tv->fb_account_id = $fb_account->getId();
                    $fb_account_tv->fb_system_id = (isset($user_tv['id'])) ? $user_tv['id'] : '';
                    $fb_account_tv->name = (isset($user_tv['name'])) ? $user_tv['name'] : '';
                    $fb_account_tv->category = (isset($user_tv['category'])) ? $user_tv['category'] : null;

                    ModelManagerFactory::getByName('fb_account_television')->save($fb_account_tv);
                }
            }
        }



    }