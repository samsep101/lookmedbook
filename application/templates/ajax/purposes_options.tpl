
    <option value="0"></option>
    <option value="0">Все</option>
    <?php if ($purposes): ?>
        <?php foreach($purposes as $purpose): ?>
            <option value="<?php echo $purpose->getId(); ?>"><?php echo $purpose->name; ?></option>
        <?php endforeach; ?>
    <?php endif; ?>

