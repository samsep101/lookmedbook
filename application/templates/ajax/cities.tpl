<?php if ($cities): ?>
<?php foreach($cities as $city): ?>
    <li data-id="<?php echo $city->id; ?>"><a><?php echo $city->name.', '.$city->region?></a></li>
    <?php endforeach; ?>
<?php endif; ?>