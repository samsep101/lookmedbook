<script type="text/javascript">
    $(document).ready(function () {
        var my_disease_controller = new PersonalRoomMyDiseaseController('<?php if (isset($_GET['letter'])) echo $_GET['letter']; ?>');
        my_disease_controller.init();
    });
</script>

    <div class="inner-3">
        <div class="cab-page flo">

            <?php $this->active_top_menu = 'my_diseases'; ?>
            <?php $this->block('blocks/personal-room-top-menu'); ?>

            <?php $this->active_left_disease_menu = 'read'; ?>
            <?php $this->block('blocks/personal-room-left-menu'); ?>

            <div class="cab-cont flo">
                <h2>К прочтению</h2>
                <ul class="letters">
                    <?php echo StringHelper::showLetters('/account/my_disease'); ?>
                </ul>

                <?php if ($diseases): ?>



                    <div class="ilness-section flo">
                        <?php foreach ($diseases as $my_disease) :?>
                            <div class="item flo">
                                <div class="descr my_disease_description">
                                    <h3><a href="/disease/get?id=<?php echo $my_disease->current_disease->getId();?>"><?php echo $my_disease->current_disease->title; ?></a></h3>

                                    <!--<?php if ($my_disease->disease_tags): ?>
                                        <div class="tags">
                                            <?php foreach ($my_disease->disease_tags as $disease_tag): ?>
                                                <a href="javascript:void(0)"><?php echo $disease_tag->tag; ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>-->

                                    <p>
                                        <p>
                                            <?php echo StringHelper::trim(strip_tags(htmlspecialchars_decode($my_disease->current_disease->content)), 200, 'читать далее', '/disease/get?id='.$my_disease->current_disease->getId()); ?>
                                        </p>
                                    </p>
                                </div>
                                <div class="btns">
                                    <!--<a class="btn-4" href="/disease/get?id=<?php echo $my_disease->current_disease->getId();?>">-->
                                    <a class="btn-4" data-id="<?php echo $my_disease->current_disease->getId(); ?>">
                                        <span>Прочитать</span>
                                    </a>
                                    <?php if (!$my_disease->is_archive): ?>
                                        <span class="btn-5" id="archive_buttons_<?php echo $my_disease->getId();?>">
                                            <input type="button" id="archive_button<?php echo $my_disease->getId();?>" class="archive_button" value="В архив" data-my_disease_id="<?php echo $my_disease->getId();?>">
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <ul class="pager">
                            <?php if (isset($_GET['letter']) && $_GET['letter']): ?>
                                <?php echo PagingViewHelper::diseasePaging('/account/my_disease/?letter='.$_GET['letter'].'&page=:page:',$pages_num,$page); ?>
                            <?php else: ?>
                                <?php echo (PagingViewHelper::diseasePaging("/account/my_disease/?page=:page:",$pages_num,$page)); ?>
                            <?php endif; ?>
                        </ul>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>