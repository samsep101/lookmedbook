<script type="text/javascript">
    $(document).ready(function () {
        var controller = new SetPasswordLandingRegistrationController("<?php echo (isset($url)) ? $url : ''; ?>", "<?php echo (isset($email)) ? $email : '';?>");
        controller.init();
    });
</script>