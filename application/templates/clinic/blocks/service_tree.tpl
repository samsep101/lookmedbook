<?php
/**
 * @var array $serviceTree
 */
foreach ($serviceTree as $id => $treeItem) { ?>
    <span class="pseudo-button">
        <input type="radio" name=" clinic-service-tree__toggle" class="pseudo-button__input clinic-service-tree__toggle"
               value="#clinic-service-tree-<?= $id ?>" id="clinic-service-tree-toggle-<?= $id ?>">
        <label class="pseudo-button__label" for="clinic-service-tree-toggle-<?= $id ?>">
            <?= htmlentities($treeItem['name']) ?>
        </label>
    </span>
<?php }

$renderPriceList = function ($subslugs, $level = 0) use (&$renderPriceList) {
    foreach ($subslugs as $subone) { ?>
        <li class="clinic-service-tree__pricelist-item item-level-<?= $level ?>">
            <span class="clinic-service-tree__pricelist-item-name"><?= htmlentities($subone['name']) ?></span>
            <?php if ($subone['min_price']) { ?>
                <span class="clinic-service-tree__pricelist-item-price"> от <?= $subone['min_price'] ?> руб.</span>
            <?php } ?>
        </li>
        <?php
        if (!empty($subone['subslugs'])) {
            $renderPriceList($subone['subslugs'], $level + 1);
        }
    }
};

foreach ($serviceTree as $id => $treeItem) { ?>
    <div class="js--clinic-service-tree clinic-service-tree" id="clinic-service-tree-<?= $id ?>">
        <h3>Цены на услуги в области <?= !empty($treeItem['name_genitive']) ? $treeItem['genitive_name'] : $treeItem['name'] ?></h3>
        <ul class="clinic-service-tree__pricelist">
            <?php $renderPriceList($treeItem['subslugs']) ?>
        </ul>
    </div>
<?php }
