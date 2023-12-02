<div class="cllSTBlocks">
    <ul>
        <?php foreach($services_and_types AS $satItem) { ?>
            <?php if($satItem->alias != $current_item->alias) { ?>

                <li>
                    <a href="<?php echo "/clinic/{$satItem->alias}"; ?>" title="<?php echo $satItem->title; ?>"><?php echo $satItem->title; ?></a>
                </li>

            <?php } ?>
        <?php } ?>
    </ul>
    <div class="clearfix"></div>
</div>
