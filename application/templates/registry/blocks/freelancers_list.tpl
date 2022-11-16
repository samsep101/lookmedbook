<?php
/**
 * @var View $this
 * @var UserModel[] $freelancers
 * @var int $registry_user_id
 */
?>
<script>
    $(document).ready(function(){
        var freelancer_select_controller = new FreelancersSelectController();
        freelancer_select_controller.init();
    });
</script>

<select class="specialty-pick" name="freelancer-pick">
    <option value="">Все фрилансеры</option>
    <?php if ($freelancers): ?>
        <?php foreach ($freelancers as $freelancer): ?>
            <option <?php if ($registry_user_id==$freelancer->getId()) { ?>selected<?php } ?> value="<?php echo $freelancer->getId(); ?>"><?php echo $freelancer->login; ?></option>
        <?php endforeach; ?>
    <?php endif; ?>
</select>