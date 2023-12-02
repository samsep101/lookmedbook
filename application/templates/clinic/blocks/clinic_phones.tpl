<?php foreach ($phones as $phone): ?>
<?php echo $phone->phone_number; ?> <?php echo (count($phones)>1) ? '&nbsp&nbsp&nbsp&nbsp' : ''; ?>
<?php endforeach; ?>