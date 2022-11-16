<?php echo $this->block('blocks/help-search-block'); ?>

<div class="help-tabs">

    <div class="rybriki">
        <?php  if ($rubrics) : ?>
            <?php  foreach ($rubrics as $rubric) : ?>
                <a href="/help/rubric?id=<?php echo $rubric->id; ?>"><?php echo $rubric->title; ?></a>
            <?php  endforeach ?>
        <?php endif?>
    </div>
    <div class="rybrika-content">
        <div class="podrybriki-left">
            <?php  if ($subrubrics) : ?>
                <?php  foreach ($subrubrics as $subrubric) : ?>
                    <a href="/help/subrubric?id=<?php echo $subrubric->id; ?>"><?php echo $subrubric->title; ?></a><br>
                <?php  endforeach ?>
            <?php endif?>
        </div>
        <div class="materials-right">
            <div>
                <?php  if ($current_subrubric) : ?>
                    <h1><?php echo $current_subrubric->title; ?></h1>
                    <p><?php echo $current_subrubric->content; ?></p>
                <?php endif?>
            </div>
            <div>
                <?php  if ($materials) : ?>
                    <?php  foreach ($materials as $material) : ?>
                        <h1></a><a href="/help/material?id=<?php echo $material->id; ?>"><?php echo $material->title; ?></a></h1><br>
                    <?php  endforeach ?>
                <?php endif?>
            </div>
        </div>
    </div>
</div>

<br>