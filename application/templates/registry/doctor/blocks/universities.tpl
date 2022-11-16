<?php if ($type==1):?>
    <label>ВУЗ</label>
<?php else:?>
    <label>Учебное заведение</label>
<?php endif?>

<?php if ($universities):?>
    <select name="form[<?php echo $field_name; ?>]">
        <?php foreach ($universities as $university):?>
            <option value="<?php echo $university->getId(); ?>"><?php echo $university->name; ?></option>
        <?php endforeach?>
    </select>
<?php endif?>