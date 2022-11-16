<div class="inner-3">
    <div class="cab-page flo">
        <?php $this->active_top_menu = 'profile'; ?>
        <?php $this->block('blocks/personal-room-top-menu'); ?>

        <?php $this->active_left_menu = 'message'; ?>
        <?php $this->block('blocks/personal-room-left-menu'); ?>

        <div class="cab-cont cab-messages">
            <!--<a href="#" class="btn-link message-btn">
                <span><img src="/media/images/message-btn-ico.png" alt=""> Написать сообщение</span>
            </a>-->
            <h2>Сообщения</h2>
            <div class="message-list flo">
                <?php if ($messages): ?>
                    <?php foreach ($messages as $message) : ?>
                        <div class="message-item flo  <?php echo ($message->is_readed == 0) ? 'message-old' : '';?>">
                            <span class="time"><?php echo DateViewHelper::message_date($message->dt);?></span>
                            <a class="theme" href="/account/message/get?id=<?php echo $message->getId(); ?>"><?php echo $message->name;?></a>
                            <div class="clear"></div>
                            <div class="author">Администрация</div>
                            <div class="message">
                                <span class="corn"></span>
                                <div class="cont">
                                    <p><?php echo $message->text;?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <ul class="pager">
                    <?php echo (PagingViewHelper::diseasePaging("/account/message/?page=:page:",$pages_num,$page)); ?>
                </ul>
            </div>
        </div>
    </div>
</div>