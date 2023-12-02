<?php if ($model->image): ?>
    <div data-name="clinic_license_image" data-label="clinic_license_image">
        <li style="width: 250px; margin-bottom: 15px">
            <input type="hidden" value="<?php echo $model->image->getId(); ?>" name="image_id" />
            <a class="show-license" href="<?php echo $model->image->path;?>"><img src="<?php echo $model->image->resize(200, 100)->path; ?>"></a>
        </li>
        <span class="remove-feature">X</span>
    </div>
<?php endif; ?>