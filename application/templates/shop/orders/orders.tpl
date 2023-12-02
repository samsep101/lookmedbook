<script type="text/javascript">
    $(document).ready(function() {
        var ordersPageController = new OrdersPageController();
        ordersPageController.init();
    });
</script>

<div class="inner-3">
    <div class="cab-page flo">
        <?php $this->active_top_menu = 'orders'; ?>
        <?php $this->block('blocks/personal-room-top-menu'); ?>
    </div>
</div>

<div class="magazine inner-2 flo" style="width: 970px;padding-top: 0;">
    <a class="load-next-page view-more" href="javascript:void(0);" data-page="1" data-year="0"><i class="icon-loader"></i></a>
</div>