<?php if (count($updates)): ?>
    <h2 style="margin-top: 30px">Требуют проверки</h2>
<div style="margin-top: 30px;">
    <ul class="updates-list">
        <?php foreach($updates as $update): ?>
        <li><?php echo ModeratePageLinkViewHelper::getView($update); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php else: ?>
    нет данных на модерации
<?php endif; ?>