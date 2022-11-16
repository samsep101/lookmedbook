<div class="error-messages">
    Просьбa заполнить информацию о следующих полях:
    <?php foreach ($reminds_info as $field_name): ?>
        <div class="error-message"><?php echo $field_name; ?></div>
    <?php endforeach; ?>
</div>