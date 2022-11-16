<div class="inner-3">
    <div class="cab-page flo">
        <?php $this->active_top_menu = 'profile'; ?>
        <?php $this->block('blocks/personal-room-top-menu'); ?>

        <?php $this->active_left_menu = 'message'; ?>
        <?php $this->block('blocks/personal-room-left-menu'); ?>


        <div class="cab-cont cab-messages">
            <a href="javascript:history.back();" class="btn-link">
                <span>Назад к сообщениям</span>
            </a>
            <h2>Сообщения</h2>
            <?php if ($message): ?>
                <div class="message-in">
                    <div class="message-item my-message flo">
                        <p><a href="#" class="theme"><?php echo $message->name;?></a></p>
                        <span class="time"><?php echo DateViewHelper::message_date($message->dt);?></span>
                        <div class="author">Кому: Администрации</div>
                        <div class="clear"></div>
                        <div class="message">
                            <span class="corn"></span>
                            <div class="cont">
                                <p><?php echo $message->text;?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>