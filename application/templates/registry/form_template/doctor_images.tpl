<?php if ($model->image): ?>
    <div data-name="image_to_doctor" class="one_doctor_img" style="clear: both;">
            <input type="hidden" value="<?php echo $model->image->getId(); ?>" name="image_id" />

            <a href="<?php echo $model->image->path; ?>" rel="lightbox" onclick="javascript:void(0);" style="float: left">
                <?php $is_main = ($model->image->width < $model->image->height); ?>
                <img src="<?php echo $model->image->resize(140, 223)->path; ?>"
                        <?php echo  ($is_main) ? 'data-is-main="1"' : ''; ?>
                        >
            </a>
        <span class="remove-feature">X</span>
    </div>
<?php endif; ?>