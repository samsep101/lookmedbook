<script type="text/javascript">
    $(document).ready(function () {
        var controller = new SetNewPasswordController("<?php if ($email) echo $email; ?>", <?php echo ($not_confirm_email) ? 1 : 0; ?>);
        controller.init();
    });
</script>