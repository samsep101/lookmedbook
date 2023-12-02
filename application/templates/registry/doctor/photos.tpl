<script type="text/javascript" xmlns="http://www.w3.org/1999/html">
    $(document).ready(function(){
        window.validation_span = true;
        var doctor_photo_form_controller = new DoctorPhotosFormController();
        doctor_photo_form_controller.setContainer('#moderated_doctor_photos_block_controller');
        doctor_photo_form_controller.init('<?php echo $model_name; ?>', <?php echo $entry_id; ?>);
        doctor_photo_form_controller.doctor_id = <?php echo $entry_id; ?>;

        <?php if (!RegistryAccessHelper::hasAccessToEdit($model->moderate_status_id)): ?>
            doctor_photo_form_controller.lock(1);
        <?php endif; ?>
    });
</script>

<?php $this->block('registry/blocks/moderate_status'); ?>

<div class="fields-block-for-photos flo" id="moderated_doctor_photos_block_controller">
    <p class="pink">Фотографии врача</p>
    <div class="block-photos flo">
        <div class="photo-add">
            <input type="file" id="add_main_doctor_photo" />
            <p class="pink">Размер фотографии <br> 74 x 111</p>
            <div id="avatar-progress-block"></div>
            <div class="images-block" style="margin-top: 40px; margin-left: 20px;">
                <div class="image">
                    <?php if ($model->card_image): ?>
                        <img src="<?php echo $model->card_image->resize(72,112)->path; ?>" />
                    <?php endif; ?>
                </div>
                <input type="hidden" value="<?php echo $model->card_image_id; ?>" name="form[card_image_id]" />
            </div>
        </div>
        <div id="doctor_card_image" class="doctor-card-preview">
            <h2>Как это выглядит на <?php echo SITE_NAME; ?></h2>
            <?php $this->block('registry/doctor/blocks/card_big'); ?>
        </div>
        <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::DOCTOR_PHOTOS_CARD, $model->revision_number); ?>

        <div class="photo-add doctors_other_photo">
            <input type="file" id="add_doctor_photos" />
            <p class="pink">Размер фотографии <br> 675 x 450 <br> или <br> 298 x 450</p>
        </div>
        <div id="photo-progress-block" style="clear:both"></div>
        <div class="doctor_images" data-holder-for="image_to_doctor">
            <div class="connected-carousels">
                <div class="stage">
                    <div class="carousel carousel-stage">
                        <ul>
                            <?php if ($doctor_images): ?>
                                <?php foreach($doctor_images as $image): ?>
                                    <li style="display: table-cell; width: 658px;" data-name="image_to_doctor">
                                        <img src="<?php echo $image->image->resize(675, 450)->path; ?>" alt="" data-width="<?php echo $image->image->width; ?>" data-height="<?php echo $image->image->height; ?>">
                                        <input type="hidden" name="image_id"  value="<?php echo $image->image->getId(); ?>" />
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="delete-when-add-new" style="text-align: center; width: 650px;"><img src="/media/images/no_photo_doctor.jpg" width="186" height="241" alt="" /></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="navigation" style="margin-left: 35px;">
                        <a href="javascript:void(0)" class="prev prev-navigation"></a>
                        <a href="javascript:void(0)" class="next next-navigation"></a>
                        <div class="carousel carousel-navigation">
                            <ul>
                                <?php if ($doctor_images): ?>
                                    <?php foreach($doctor_images as $doctor_image): ?>
                                        <li><img src="<?php echo $doctor_image->image->resize(101, 56)->path; ?>"></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div><!-- end class "stage" -->
            </div><!-- end class "connected-carousels" -->

        </div>
        <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::DOCTOR_PHOTOS_PHOTOS, $model->revision_number); ?>
    </div>
    <?php $this->block('registry/blocks/form-buttons'); ?>

</div>
<style>
    .carousel-navigation img {
        width: auto;
        height: 56px;
    }
    .carousel-stage li {
        text-align: center;
    }
    .carousel-stage img {
        height: 280px;
    }
</style>
