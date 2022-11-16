<?php $destination = (isset($destinaton)) ? $destinaton : ''; ?>
<script type="text/javascript">
    $(document).ready(function () {
        var controller = new NewEmailController('<?php echo $destination; ?>');
        controller.init();
    });
</script>