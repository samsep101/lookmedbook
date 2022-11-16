<?php
	/**
	 * @var View $this
	 * @var SpecialtyModel[] $specialties
	 */
?>
<?php if (!isset($show_all_option) || ($show_all_option)): ?>
    <option value="0" data-specialty_plural_name="Все">Все</option>
<?php endif; ?>
<?php if (count($mixed_array)):?>
    <?php foreach($mixed_array as $mixed_item_key => $mixed_item): ?>
        <option
            <?php if($current_item->alias == $mixed_item->alias) echo 'selected="selected"'; ?>
            value="<?php echo $mixed_item->alias; ?>"
            data-specialty_alias="<?php echo $mixed_item->alias; ?>"
            data-specialty_name="<?php echo $mixed_item->name; ?>" >

            <?php echo StringHelper::startProposalWord($mixed_item->name); ?>
        </option>
    <?php endforeach; ?>
<?php endif; ?>