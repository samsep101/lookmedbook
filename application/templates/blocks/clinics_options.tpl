<?php if (count($clinics)): ?>
    <?php foreach($clinics as $clinic): ?>
        <option value="<?php echo $clinic->getId(); ?>"><?php echo $clinic->name; ?></option>
    <?php endforeach; ?>
<?php endif; ?>