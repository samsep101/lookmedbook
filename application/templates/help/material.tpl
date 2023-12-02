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
            <?php  if ($current_material) : ?>
                <h1><?php echo $current_material->title; ?></h1>
                <p><?php echo $current_material->content; ?></p>
            <?php endif?>
        </div>
    </div>
</div>

<br>