<script style="text/javascript">
    $(document).ready(function(){
        $('.registry-search-box .btn-search').click(function(){
            var val = $('.registry-search-box input.txt').val();
            window.location = "/registry/manage/moderate_pages?clinic_name="+val+"&moderate_status_id=<?php echo $moderate_status_id; ?>";
        });
        $('.registry-search-box input.txt').keydown(function(e){
            if (e.keyCode == 13)
            {
                var val = $('.registry-search-box input.txt').val();
                window.location = "/registry/manage/moderate_pages?clinic_name="+val+"&moderate_status_id=<?php echo $moderate_status_id; ?>";
            }
        });
    });
</script>
<div class="registry-search-box flo">
    <input class="txt" type="text" placeholder="Остион" style="width: 127px;" value="<?php echo $clinic_name; ?>">
    <input class="btn-search" type="submit">
    <ul class="drop-menu"> </ul>
</div>

<div class="search-results" style="margin-top: 40px;">
    <?php if ($pages): ?>

        <?php foreach($pages as $page): ?>
            <?php echo ModeratePageLinkViewHelper::getView($page); ?><br /><br />
        <?php endforeach; ?>

        <?php echo PagingViewHelper::paging($page_url.'?clinic_name='.$clinic_name.'&moderate_status_id='.$moderate_status_id.'&page=:page:', $pages_total, $current_page); ?>
    <?php else: ?>
        нет результатов
    <?php endif; ?>
</div>