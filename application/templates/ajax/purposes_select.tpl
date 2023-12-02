<?php
    /**
     * @var PurposeOfVisitModel[] $purposes
     */
?>

<select data-placeholder="Цель визита" class="chzn-select" name="purpose_of_visit_id" style="<?php echo (isset($select_style)) ? $select_style : 'width: 390px'; ?>">
    <option value="0"></option>
    <?php if ($purposes): ?>
        <?php foreach($purposes as $purpose): ?>
            <option data-price="<?php echo $purpose->visit_price; ?>" value="<?php echo $purpose->getId(); ?>"><?php echo $purpose->name; ?></option>
        <?php endforeach; ?>
    <?php endif; ?>
</select>