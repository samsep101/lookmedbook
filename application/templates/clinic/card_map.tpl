<?php
	/**
	 * @var View $this
	 * @var ClinicModel $clinic
	 */
?>
    <div class="pop-col-side">
        <div class="avatar">
            <?php echo ClinicAvatarViewHelper::viewOnCard($clinic, 74, 31); ?>
        </div>

        <?php echo RateViewHelper::viewSmall($clinic->rate, 0, $clinic->is_best); ?>
    </div>
    <div class="descr">
        <p class="name"><a href="<?php echo ClinicPageLinkViewHelper::getLink($clinic); ?>"><span class="post"><?php echo $clinic->name; ?></span></a></p>
        <div class="info-area">
            <div class="address">
                <p>
                    <?php if ($clinic->metro_station): ?>
                        <?php if ($clinic->metro_station->metro_branch): ?>
                            <?php echo MetroBranchIconViewHelper::getImage($clinic->metro_station->metro_branch)?>
                        <?php endif; ?>
                        <?php echo $clinic->metro_station->name;?><br>
                    <?php endif; ?>
                    <?php echo $clinic->address; ?>
                </p>
            </div>
            <?php if ($clinic->specialties):?>
                <p><strong>Врачи клиники</strong></p>
                <ul class="specializations">
                    <?php $counter = 1?>
                    <?php foreach ($clinic->specialties as $specialty) :?>
                        <?php if ($counter < 3):?>
                            <li><?php echo $specialty->name; ?></li>
                            <?php $counter++?>
                        <?php endif?>
                    <?php endforeach?>
                </ul>
                    <?php if ($counter > 2):?>
                        <a class="more" href="<?php echo ClinicPageLinkViewHelper::getLink($clinic); ?>">Подробнее</a>
                    <?php endif?>
            <?php endif?>
        </div>
    </div>
    <div class="btns flo">
        <a class="btn-appoint btn-appoint-sm" href="<?php echo ClinicPageLinkViewHelper::getLink($clinic); ?>">Записаться</a>

        <a onclick="
            if (SessionInfo.is_authed)
            {
                addTobookmarkSmallClinic(<?php echo $clinic->getId();?>);
            } else {
                var block = new LandingRegistrationPageController();
                var button = $(this);
                block.success_registration_callback = function(data){
                    addTobookmarkSmallClinic(<?php echo $clinic->getId();?>);
                    $('.header #authorization-block-on-disease-page').html('<div class=\'header-user\' style=\'margin: 2px 44px 0 24px;\'><a href=\'/account/message\' class=\'header-usernotification\'> <span style=\'display: none;\' class=\'notification\' id=\'usernotification\'></span> </a><div class=\'header-userinfo\'><a href=\'/account/about\' class=\'header-userprofile\'>'+ SessionInfo.email +'</a><ul class=\'header-usermenu\'><li><a href=\'/account/about\'>Профиль</a></li><li><a href=\'/help\'>Помощь</a></li><li><a href=\'/account/logout\'>Выйти</a></li></ul></div></div>');
                };
                block.block_title = 'для добавления в закладки';
                block.block_over_textbox = 'Получите доступ ко всем возможностям <?php echo SITE_NAME; ?>!';
                block.init();
            }
            " class="btn-bookmark btn-bookmark-sm clinic-bookmark-<?php echo $clinic->getId(); ?>">
            <?php if ($clinic->my_clinic): ?>
                <i class="icon-add" style="background-position: 0 100%;"></i>
                <span class="txt txt-added" style="display: inline; line-height: 26px;">В закладках</span>
            <?php else: ?>
                <i class="icon-add"></i>
                <span class="txt">Добавить в закладки</span>
            <?php endif; ?>
        </a>

    </div>
