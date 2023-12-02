<script type="text/javascript">
    $(document).ready(function () {
        var controller = new PersonalRoomAboutController(<?php echo $current_account->getId();?>);
        controller.init();
    });
</script>

    <div class="inner-3">
        <div class="cab-page flo">

            <?php $this->active_top_menu = 'profile'; ?>
            <?php $this->block('blocks/personal-room-top-menu'); ?>

            <?php $this->active_left_menu = 'about'; ?>
            <?php $this->block('blocks/personal-room-left-menu'); ?>

            <div class="cab-cont">
                <?php if (!$close_aboute_note_attribute): ?>
                    <div class="owl-block">
                        <div class="note info-block">
                            <span class="close" id="close_about_note"></span>
                            <span class="corn"></span>
                            <div class="note-cont">
                                <p class="center-align">
                                    <img src="/media/images/conf-pic.png" alt="">
                                </p>
                                <p>Данная информация хранится на нашем проекте и может быть передана в клинику только после записи к врачу</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <h2>О себе</h2>
                <div class="about-form form">
                    <div class="row flo">
                        <label class="lab">Имя на сайте</label>
                        <div class="data-box">
                            <div class="txt txt-name for_validate_nick">
                                <input type="text" name="nick" value="<?php echo $account->nick;?>" placeholder="Введите ваше имя">
                            </div>
                            <span class="note-txt">Под этим именем будут опубликованы ваши отзывы</span>
                        </div>
                    </div>
                    <div class="row flo">
                        <label class="lab">ФИО</label>
                        <div class="data-box">
                            <div class="txt txt-surname">
                                <input type="text" name="last_name" value="<?php echo $account->last_name;?>" placeholder="Фамилия">
                            </div>
                            <div class="txt txt-surname">
                                <input type="text" name="user_first_name" value="<?php echo $account->first_name;?>" placeholder="Имя">
                            </div>
                            <div class="txt txt-surname">
                                <input type="text" name="middle_name" value="<?php echo $account->middle_name;?>" placeholder="Отчество">
                            </div>
                            <span class="note-txt">Мы передадим Ваше имя  только в клинику</span> </div>
                        <img class="conf-pic" src="/media/images/conf-pic.png" alt="">
                    </div>
                    <div class="row row-gender flo">
                        <label class="lab">Ваш пол</label>
                        <div class="data-box">
                            <div class="gender flo">
                                <?php if ($account->sex_id): ?>
                                    <?php if ($account->sex_id == 1): ?>
                                        <span class="man selected" id="male_gender"></span>
                                        <span class="woman" id="female_gender"></span>
                                        <input type="hidden" value="1" name="sex_id">
                                    <?php else: ?>
                                        <span class="man" id="male_gender"></span>
                                        <span class="woman selected" id="female_gender"></span>
                                        <input type="hidden" value="2" name="sex_id">
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="man" id="male_gender"></span>
                                    <span class="woman" id="female_gender"></span>
                                    <input type="hidden" value="" name="sex_id" id="sex_id">
                                <?php endif; ?>
                            </div>
                            <span class="note-txt">Мы передадим эту информацию только в клинику</span> </div>
                    </div>
                    <div class="row flo">
                        <label class="lab">Дата рождения</label>
                        <div class="data-box birthday_data">
                            <?php echo FormViewHelper::selectDate($account->birthday); ?>
                            <span class="note-txt">Дата рождения нужна нам, чтобы поздравить Вас <br>с Днём рождения и сообщить возраст врачу.</span>
                        </div>
                        <img class="conf-pic" src="/media/images/conf-pic.png" alt="">
                    </div>

                    <div class="row flo search-city-block search-block">
                        <label class="lab">Город</label>
                            <div class="txt">
                                <input class="city-search-input" type="text" <?php if ($account->city_id) {?> value="<?php echo $account->city->name?>, <?php echo $account->city->region?>" data-id="<?php echo $account->city_id?>" <?php }?> autocomplete="off" name="city_query" placeholder="<?php if ($account->city_id) { echo $account->city->name?>, <?php echo $account->city->region?><?php } else {?>Укажите Ваш город<?php }?>" />
                            </div>
                            <ul class="drop-menu" style="clear:both">
                            </ul>
                        <img class="conf-pic" src="/media/images/conf-pic.png" alt="">
                    </div>

                    <div class="row-phone">
                        <?php if ($account->phones): ?>
                            <?php foreach ($account->phones as $account_phone): ?>
                                <div class="row flo">
                                    <label class="lab">Номер телефона</label>
                                    <div class="data-box">
                                        <div class="txt">
                                            <input type="text" disabled class="mask" value="<?php echo PhoneFormatViewHelper::view($account_phone->phone); ?>">
                                        </div>
                                        <span class="note-txt">Передадим в клинику, только после записи на приём</span>
                                    </div>
                                    <img class="conf-pic" src="/media/images/conf-pic.png" alt="">
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($current_account->not_confirmed_phones): ?>
                            <?php foreach ($current_account->not_confirmed_phones as $account_phone): ?>
                                <div class="row flo">
                                    <label class="lab">Номер телефона</label>
                                    <div class="data-box">
                                        <div class="txt">
                                            <input type="text" class="mask elreary_exist_phone" data-phone="<?php echo $account_phone->phone; ?>" data-old_phone_id="<?php echo $account_phone->getId(); ?>" value="<?php echo PhoneFormatViewHelper::view($account_phone->phone); ?>">
                                        </div>
                                        <span class="note-txt">Передадим в клинику, только после записи на приём</span>
                                    </div>
                                    <img class="conf-pic" src="/media/images/conf-pic.png" alt="">
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (!$account->phones && !$current_account->not_confirmed_phones): ?>
                            <div class="row flo">
                                <label class="lab">Номер телефона</label>
                                <div class="data-box">
                                    <div class="txt">
                                        <input type="text" class="mask new_phone" placeholder="+7-___-___-__-__">
                                    </div>
                                    <span class="note-txt">Передадим в клинику, только после записи на приём</span>
                                </div>
                                <img class="conf-pic" src="/media/images/conf-pic.png" alt="">
                            </div>
                        <?php endif; ?>
                    </div>
                    <span class="add-phone">
                        <img src="/media/images/add_icon.png" alt="Добавить номер телефона" id="add-personal-phone-button"> Добавить номер телефона
                    </span>
                    <div class="row flo">
                        <label class="lab">E-mail</label>
                        <div class="data-box">
                            <div class="txt">
                                <input type="email" name="email" value="<?php echo $account->email; ?>" placeholder="Введите Ваш e-mail">
                            </div>
                            <span class="note-txt">Мы всегда будем оставаться на связи</span> </div>
                        <img class="conf-pic" src="/media/images/conf-pic.png" alt="">
                    </div>

                    <!--<div class="row row_other flo">
                        <label class="lab">Дополнительно</label><br>
                        <div class="data-box">
                            <span class="note-txt">Данные передаваемые из социальных сетей</span><br><br>

                            <?php if ($vk_account && $vk_account->is_account_connected): ?>
                                <input class="btn-1" type="button" id="vk_data" value="ВКонтакте">&nbsp;
                            <?php endif; ?>

                            <?php if ($fb_account && $fb_account->is_account_connected): ?>
                                <input class="btn-1" type="button" id="fb_data" value="Facebook">&nbsp;
                            <?php endif; ?>

                            <?php if ($mailru_account && $mailru_account->is_account_connected): ?>
                                <input class="btn-1" type="button" id="mailru_data" value="Mail.ru">&nbsp;
                            <?php endif; ?>

                            <?php if ($ok_account && $ok_account->is_account_connected): ?>
                                <input class="btn-1" type="button" id="ok_data" value="Одноклассники"><br>
                            <?php endif; ?>

                            <div id="vk_account_block" style=" display: none;">

                                <label>Ссылка на профиль: </label>
                                <input type="text" class="mask" id="vk_profile_url" value="<?php if ($vk_account) echo $vk_account->profile_url; ?>"><br>

                                <label>Домашний телефон: </label>
                                <input type="text" id="vk_home_phone" value="<?php if ($vk_account) echo $vk_account->home_phone; ?>"><br>

                                <label>Статус: </label>
                                <input type="text" id="vk_activity" value="<?php if ($vk_account) echo $vk_account->activity; ?>"><br>

                                <label>Семейное положение: </label>
                                <input type="text" id="vk_relation_type" value="<?php if ($vk_account) echo $vk_account->relation_type; ?>"><br>

                                <label>Интересы: </label>
                                <textarea id="vk_interests"><?php if ($vk_account) echo $vk_account->interests; ?></textarea><br>

                                <label>Любимые фильмы: </label>
                                <textarea id="vk_movies"><?php if ($vk_account) echo $vk_account->movies;?></textarea><br>

                                <label>Любимые телешоу: </label>
                                <textarea id="vk_tv"><?php if ($vk_account) echo $vk_account->tv;?></textarea><br>

                                <label>Любимые книги: </label>
                                <textarea id="vk_books"><?php if ($vk_account) echo $vk_account->books;?></textarea><br>

                                <label>Любимые игры: </label>
                                <textarea id="vk_games"><?php if ($vk_account) echo $vk_account->games;?></textarea><br>

                                <label>О себе: </label>
                                <textarea id="vk_about"><?php if ($vk_account) echo $vk_account->about; ?></textarea><br>

                                <input type="button" class="btn-1" value="Сохранить" id="save_vk_account"><br>
                            </div>

                            <div id="fb_account_block" style="display: none;">

                                <label>Ссылка на профиль: </label>
                                <input type="text" id="fb_profile_url" value="<?php if ($fb_account) echo $fb_account->profile_url; ?>"><br>

                                <label>Ник пользователя: </label>
                                <input type="text" id="fb_user_name" value="<?php if ($fb_account) echo $fb_account->user_name; ?>"><br>

                                <label>Родной город: </label>
                                <input type="text" id="fb_hometown" value="<?php if ($fb_account) echo $fb_account->hometown; ?>"><br>

                                <label>О себе: </label>
                                <textarea id="fb_bio"><?php if ($fb_account) echo $fb_account->bio; ?></textarea><br>

                                <label>Цитаты: </label>
                                <textarea id="fb_quotes"><?php if ($fb_account) echo $fb_account->quotes;?></textarea><br>

                                <label>Политические взгляды: </label>
                                <input type="text" id="fb_political_view" value="<?php if ($fb_account) echo $fb_account->political_view; ?>"><br>

                                <label>Предпочтения: </label>
                                <input type="checkbox" id="fb_is_interested_in_male" <?php if ($fb_account && $fb_account->is_interested_in_male) echo 'checked="checked"'; ?> >Мужчины
                                <input type="checkbox" id="fb_is_interested_in_female" <?php if ($fb_account && $fb_account->is_interested_in_female) echo 'checked="checked"'; ?> >Женщины
                                <br>

                                <label>Семейное положение: </label>
                                <input type="text" id="fb_relationship_status" value="<?php if ($fb_account) echo $fb_account->relationship_status; ?>"><br>

                                <label>Вероисповедание: </label><input type="text" id="fb_religion" value="<?php if ($fb_account) echo $fb_account->religion; ?>"><br>

                                <label>Веб-сайт: </label><textarea id="fb_web_sites"><?php if ($fb_account) echo $fb_account->web_sites;?></textarea><br>

                                <input type="button" class="btn-1" value="Сохранить" id="save_fb_account"><br>
                            </div>

                            <div id="mailru_account_block" style="display: none;">

                                <label>Ник: </label>
                                <input type="text" id="mailru_nick_name" value="<?php if ($mailru_account) echo $mailru_account->nick_name; ?>"><br>

                                <label>Ссылка на профиль: </label>
                                <input type="text" id="mailru_profile_url" value="<?php if ($mailru_account) echo $mailru_account->profile_url; ?>"><br>

                                <label>Статус: </label>
                                <textarea id="mailru_status_text">
                                    <?php if ($mailru_account) echo $mailru_account->status_text; ?></textarea><br>

                                <input type="button" class="btn-1" value="Сохранить" id="save_mailru_account"><br>
                            </div>

                            <div id="ok_account_block" style="display: none;">
                                <label>Ссылка на профиль: </label>
                                <input type="text" id="ok_profile_url" value="<?php if ($ok_account) echo $ok_account->profile_url; ?>"><br>

                                <label>Возраст: </label>
                                <input type="text" id="ok_age" value="<?php if ($ok_account) echo $ok_account->age; ?>"><br>

                                <input type="button" value="Сохранить" id="save_ok_account"><br>
                            </div>

                        </div>
                    </div>-->
                    <div class="btns flo">
                        <a href="javascript:void(0)" class="save-info-link">
                            <input class="btn-1" type="button" id="save_info_button" value="Сохранить">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
