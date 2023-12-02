<?php if ($materials): ?>
<?php foreach($materials as $material): ?>
    <li><a href="/help/searchResults?help_query=<?php echo $material->title; ?>"><?php echo $material->title; ?></a></li>
    <?php endforeach; ?>
<?php endif; ?>