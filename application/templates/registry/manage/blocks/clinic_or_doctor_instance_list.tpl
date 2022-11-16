<?php
    /**
     * @var string $instance_name
     */
?>

<?php if ($instances):?>
    <ul class="updates-list <?php echo $instance_name;?>-list">
        <?php foreach ($instances as $instance):?>
            <li><?php echo ModeratePageLinkViewHelper::getClinicOrDoctorLinkView($instance, $instance_name); ?></li>
        <?php endforeach?>
    </ul>
    <a class="show-all" href="/registry/manage/<?php echo $instance_name;?>s">Показать все</a>
<?php else:?>
    <div style="clear: both; margin-top: 10px;">
        нет данных
    </div>
<?php endif?>