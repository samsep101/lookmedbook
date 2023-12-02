<?php if ($model->filename): ?>
    <div data-name="clinic_pricelist" data-label="clinic_pricelist" class="single-price-row">
        <li>
            <input type="hidden" value="<?php echo $model->filename; ?>" name="filename" />
            <p style="color: #000000" align="right">
                <a target="_blank" href="<?php echo SITE_URL .MEDIA_UPLOAD_PATH.'clinic/services/'.$model->filename; ?>"><?php echo $model->filename; ?></a>
            </p>
        </li>
        <span class="remove-feature">X</span>
    </div>
<?php endif; ?>