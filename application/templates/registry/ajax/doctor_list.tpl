<?php if ($doctors): ?>
    <option selected="" value="0"></option>
    <?php foreach($doctors as $doctor): ?>
        <option value="<?php echo $doctor->getId();?>"><?php echo $doctor->full_name;?></option>
    <?php endforeach; ?>
<?php endif; ?>