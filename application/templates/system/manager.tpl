    class <?php echo $class_name; ?>Manager extends ModelManager {
        protected $table_name = '<?php echo $table_name; ?>';
        protected $model_name = '<?php echo $class_name; ?>Model';

<?php if (count($methods)){ ?>
    <?php foreach($methods as $method): ?>
        <?php $this->field_name = $method['field_name']; ?>
        <?php $this->field_camel = $method['field_camel_name']; ?>
        <?php if ($method['type'] == 'List'): ?>
            <?php $this->block('system/method_list'); ?>
        <?php endif; ?>
        <?php if ($method['type'] == 'One'): ?>
            <?php $this->block('system/method_one'); ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php } ?>

    }