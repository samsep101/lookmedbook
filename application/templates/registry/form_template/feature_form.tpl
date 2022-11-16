<div data-name="feature_to_clinic" data-label="feature_to_clinic">
    <li style="width: 250px; margin-bottom: 15px">
        <?php $class = $model->is_selected ? 'act' : ''; ?>
        <input type="hidden" name="feature_id" value="<?php echo $model->id; ?>" />
        <div class="chekBox <?php echo $class; ?>">
            <span></span>
            <?php echo $model->name; ?>
            <input type="hidden" name="is_selected" value="<?php echo $model->is_selected; ?>" />
        </div>
    </li>
</div>