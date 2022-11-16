<?php $this->container = '#clinic-photos-form'; ?>
<script type="text/javascript">
    $(document).ready(function(){
        var form_controller = new ClinicPhotosController();
        form_controller.setContainer('#clinic-photos-form');
        form_controller.init('<?php echo $model_name; ?>', <?php echo $entry_id; ?>);
        form_controller.clinic_id = <?php echo $entry_id; ?>;

        <?php if (!RegistryAccessHelper::hasAccessToEdit($model->moderate_status_id)): ?>
            form_controller.lock();
        <?php endif; ?>
    });
</script>

<?php $this->block('registry/blocks/moderate_status'); ?>

<div class="moderated-form" id="clinic-photos-form">
    <div class="fields-block-for-photos flo">
        <p class="pink">Фотографии клиники</p>
        <div class="block-photos flo">
            <div class="photo-add">
                <input type="file" id="add_main_doctor_photo" value="Добавить фото"/>
                <p class="pink">Размер фотографии не менее <br> 74 x 31</p>
                <div id="avatar-progress-block"></div>
                <div class="clinic-avatar-image card-image" id="clinic-avatar-container">
                    <?php if ($model->card_image): ?>
                    <img src="<?php echo $model->card_image->crop(74, 31)->path; ?>" />
                    <?php endif; ?>
                    <input class="hidden" name="form[card_image_id]" value="<?php echo $model->card_image_id; ?>"/>
                    <div class="image-block">
                    </div>
                </div>
            </div>
            <div class="doctor-card-preview">
                <h2>Как это выглядит на <?php echo SITE_NAME; ?></h2>
                <?php $this->block('registry/clinic/blocks/card_big'); ?>
            </div>
            <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_AVATAR, $model->revision_number); ?>
            <div class="photo-add photo-add-more">
                <input type="file" id="add_clinic_photo" value="Добавить фото"/>
                <p class="pink">Размер фотографии 660 x 360</p>
                <div id="photo-progress-block"></div>

                <div class="content">
                    <div class="connected-carousels">
                        <div class="stage">
                            <div class="carousel carousel-stage clinic-carousel" data-holder-for="image_to_clinic">
                                <ul>
                                    <?php if ($clinic_images): ?>
                                    <?php foreach($clinic_images as $clinic_image): ?>
                                        <li data-name="image_to_clinic">
                                            <img src="<?php echo $clinic_image->image->crop(660, 360)->path; ?>">
                                            <input class="hidden" name="image_id" value="<?php echo $clinic_image->image_id; ?>"/>
                                        </li>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <img class="delete-when-add-new" src="/media/images/no-photo2.gif" alt="" />
                                    <?php endif; ?>
                                </ul>
                                <a href="javascript:void(0);" class="prev prev-stage"><span></span></a>
                                <a href="javascript:void(0);" class="next next-stage"><span></span></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_PHOTOS, $model->revision_number); ?>
        </div>
        <?php $this->block('registry/blocks/form-buttons'); ?>
    </div>
</div>
<style>
    .carousel-stage img{
        max-height: 450px;
    }
    .carousel-stage img {
        width: 660px;
    }
    .carousel-navigation img {
        width: 101px;
        height: 56px;;
    }
    .connected-carousels .carousel {
        height: 360px;
    }
</style>

<script>
    $(function() {
        $('[data-jcarousel]').each(function() {
            var el = $(this);
            el.jcarousel(el.data());
        });

        $('[data-jcarousel-control]').each(function() {
            var el = $(this);
            el.jcarouselControl(el.data());
        });
    });
</script>