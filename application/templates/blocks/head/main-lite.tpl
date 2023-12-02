<?php if (debug): ?>
    <?php $this->block('blocks/head/main') ?>
    <script type="text/javascript" src="/media/js/jquery-ui-1.10.2.custom.min.js?<?php echo RELEASE__NUMBER?>" defer></script>
<?php else: ?>
    <script type="text/javascript" src="/media/js/min/main-new.js?<?php echo RELEASE__NUMBER?>" defer></script>
<?php endif; ?>

