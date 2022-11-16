<script type="text/javascript">
    $(document).ready(function () {
        var controller = new PersonalRoomOptionsController();
        controller.init();
    });
</script>

<div class="inner-3">
    <div class="cab-page flo">
        <?php $this->active_top_menu = 'profile'; ?>
        <?php $this->block('blocks/personal-room-top-menu'); ?>

        <?php $this->active_left_menu = 'options'; ?>
        <?php $this->block('blocks/personal-room-left-menu'); ?>

        <?php if ($account->notifications):?>
        <div class="cab-cont">
            <?php if (!$close_settings_note_attribute): ?>
                <div class="owl-block">
                    <div class="note"> <span class="close" id="close_settings_note"></span>
                        <span class="corn"></span>
                        <div class="note-cont">
                            <p class="center-align"><img src="/media/images/note-pic2.png" alt=""></p>
                            <p>Наши оповещения позволят не пропустить прием. Мы отправляем только самую важную информацию</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <h2>Настройка</h2>
            <div class="settings">
                <div class="setting-section">
                    <h3>Мобильный телефон</h3>
                    <div class="chekBox sms-chk phone-settings-checkbox-main <?php if ($account->notifications->sms_notify){?>act<?php }?>">
                        <span></span> включить SMS оповещения
                        <input id="sms-checkbox-main" type="hidden"
                            <?php if ($account->notifications->sms_notify){?>value="1"<?php }?>>
                    </div>
                    <div class="options options-mobile"> <span class="lab">На номер</span>
                        <div class="sel-box">
                            <select class="chzn-select options-phones" style="width:303px;">
                                <option value="0">Выберите номер</option>
                                <?php foreach($account->phones as $phone):?>
                                    <option value="<?php echo $phone->id; ?>" <?php if ($phone->id == $account->notifications->sms_notify_phone_id){?>selected<?php }?>><?php echo $phone->phone; ?></option>
                                <?php endforeach?>
                            </select>
                        </div>
                        <!-- <a class="lnk" href="about">редактировать номер телефона</a> -->
                        <div class="chk-opt phone-settings-checkboxes">
                            <div class="chekBox phone-settings-checkbox <?php if ($account->notifications->sms_notify_visit){?>act<?php }?>">
                                <span></span> Напомнить про визит к врачу
                                <input id="sms-checkbox-1" type="hidden"
                                    <?php if ($account->notifications->sms_notify_visit){?>value="1"<?php }?>>
                            </div>
                            <div class="chekBox phone-settings-checkbox <?php if ($account->notifications->sms_notify_change){?>act<?php }?>">
                                <span></span> Оповещение об изменениях
                                <input id="sms-checkbox-2" type="hidden"
                                    <?php if ($account->notifications->sms_notify_change){?>value="1"<?php }?>>
                            </div>
                            <div class="chekBox phone-settings-checkbox <?php if ($account->notifications->sms_notify_news){?>act<?php }?>">
                                <span></span> Новости ресурса
                                <input id="sms-checkbox-3" type="hidden"
                                    <?php if ($account->notifications->sms_notify_news){?>value="1"<?php }?>>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="setting-section">
                    <h3>Электронная почта</h3>
                    <p class="txt">Отправлять сообщения на e-mail:</p>
                    <div class="options">
                        <div class="chk-opt">
                            <div class="chekBox mail-settings-checkbox <?php  if ($account->notifications->email_notify_bonus){?>act<?php }?>">
                                <span></span> Предлагать хорошие акции и бонусы клуба
                                <input id="mail-checkbox-1" type="hidden"
                                    <?php if ($account->notifications->email_notify_bonus){?>value="1"<?php }?>>
                            </div>
                            <div class="chekBox mail-settings-checkbox <?php  if ($account->notifications->email_notify_visit){?>act<?php }?>">
                                <span></span> Напоминать о визите к врачу
                                <input id="mail-checkbox-2" type="hidden"
                                    <?php if ($account->notifications->email_notify_visit){?>value="1"<?php }?>>
                            </div>
                            <div class="chekBox mail-settings-checkbox <?php  if ($account->notifications->email_notify_change){?>act<?php }?>">
                                <span></span> Уведомлять об изменениях в расписании
                                <input id="mail-checkbox-3" type="hidden"
                                    <?php if ($account->notifications->email_notify_change){?>value="1"<?php }?>>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btns-holder flo">
                    <input class="btn-1" type="button" id="save-options-button" value="Сохранить">
                </div>

            </div>
        </div>
        <?php endif?>
    </div>
</div>