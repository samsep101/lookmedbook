<?php $current_setialization = $specialization; ?>
<?php if (!isset($show_all_option) || ($show_all_option)): ?>
    <option class="specialization" <?php if(!isset($current_setialization)) echo 'selected="selected"'; ?> value="0" data-specialty_plural_name="Все">Все</option>
<?php endif;?>
<?php if (count($specializations)):?>
    <?php foreach($specializations as $specialization): ?>
			<?php
			if(!is_object($specialization)) { continue; } ?>
        <option <?php if(isset($current_setialization) and is_object($current_setialization) and !isset($home_page) && $current_setialization->id == $specialization->getId()) echo 'selected="selected"'; ?>
            value="<?php echo $specialization->getId(); ?>"
            class="specialization"
            data-specialty_alias="<?php echo $specialization->alias; ?>"
            data-specialty_name="<?php echo $specialization->name; ?>"
            data-specialty_plural_name="<?php echo $specialization->plural_name; ?>">

            <?php echo StringHelper::startProposalWord($specialization->name); ?>

        </option>
    <?php endforeach; ?>
<?php endif; ?>