<script type="text/javascript">
    $(document).ready(function(){
        var moderate_block_controller = new ModeratePageBlockController();
        moderate_block_controller.setContainer('#moderate_pages_block');
        moderate_block_controller.setModerateStatusId(2);
        moderate_block_controller.init();

        var sent_back_block_controller = new ModeratePageBlockController();
        sent_back_block_controller.setContainer('#sent_back_pages_block');
        sent_back_block_controller.setModerateStatusId(3);
        sent_back_block_controller.init();

        var edit_block_controller = new ModeratePageBlockController();
        edit_block_controller.setContainer('#edit_pages_block');
        edit_block_controller.setModerateStatusId(1);
        edit_block_controller.init();
    });
</script>
<div style="width: 1000px">
    <div class="div-row">
        <div class="updates registry-block pink-inner" id="moderate_pages_block">
            <h2>Обновления страниц</h2>
            <div class="search-box flo">
                <input class="txt" type="text" placeholder="Остион" style="width: 127px;">
                <input class="btn-search" type="submit">
                <ul class="drop-menu"> </ul>
            </div>
            <?php if ($updates): ?>
            <ul class="updates-list">
                <?php foreach($updates as $update): ?>
                <?php if ($update): ?>
                    <?php echo ModeratePageLinkViewHelper::getView($update); ?> <br /><br />
                <?php endif; ?>
                <?php endforeach; ?>
            </ul>
                <a class="show-all" href="javascript:void(0);">Показать все</a>
            <?php else: ?>
                нет страниц, требующих модерации
            <?php endif; ?>
        </div>
        <div class="sent_back  registry-block pink-inner" id="sent_back_pages_block">
            <h2>Отправлено в клинику на доработку</h2>
            <div class="search-box flo">
                <input data-id="clinic" class="txt" type="text" placeholder="Остион" style="width: 127px;">
                <input data-id="clinic" class="btn-search" type="submit">
                <ul class="drop-menu"> </ul>
            </div>
            <?php if ($sent_back_pages): ?>
            <ul class="updates-list">
                <?php foreach($sent_back_pages as $page): ?>
                    <?php if ($page): ?>
                        <?php echo ModeratePageLinkViewHelper::getView($page); ?> <br /><br />
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            нет страниц, отправленных на доработку
            <?php endif; ?>
        </div>
    </div>
    <div class="div-row" style="margin-top: 10px;">
        <div class="updates  registry-block blue-inner" id="edit_pages_block">
            <h2>Редактируется клиникой</h2>
            <div class="search-box flo">
                <input class="txt" type="text" placeholder="Остион" style="width: 127px;">
                <input class="btn-search" type="submit">
                <ul class="drop-menu"> </ul>
            </div>
            <?php if ($edit_pages): ?>
            <ul class="updates-list">
                <?php foreach($edit_pages as $page): ?>
                    <?php if ($page): ?>
                        <?php echo ModeratePageLinkViewHelper::getView($page); ?> <br /><br />
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <a class="show-all" href="javascript:void(0);">Показать все</a>
            <?php else: ?>
                нет страниц, редактируемых клиникой
            <?php endif; ?>
        </div>

    </div>
</div>