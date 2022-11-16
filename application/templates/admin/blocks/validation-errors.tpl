<?php if (isset($validation_errors)): ?>
<div class="validation-errors">
    <?php foreach ($validation_errors as $error): ?>
    <div class="error">
        <?php echo $error; ?>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>