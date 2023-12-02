<?php $this->container = '#action-form'; ?>

<form action="/registry/clinic/actionSave?clinic_id=<?=$clinic_id?>" method="post" enctype="multipart/form-data">
    <div class="fields-block flo">
        <p>Акции</p>
        <div class="row-record">
            <label>Название</label>
            <div class="row-record-data" style="width:100%; max-width:785px;">
                <input type="text" name="form[name]" value="<?=$edit_action->name?>" style="width:100%;"><br>
            </div>
        </div>
        <div class="row-record">
            <label>Изображение прямоугольник (600*120 или больше с соблюдением пропорций)</label>
            <div class="row-record-data">
                <input type="file" name="icon_full_width" value="" style=""><br>
            </div>
        </div>
        <div class="row-record">
            <label>Изображение квадрат (200*200 или больше с соблюдением пропорций)</label>
            <div class="row-record-data">
                <input type="file" name="icon" value="" style=""><br>
            </div>
        </div>
        <div class="row-record">
            <label></label>
            <div class="education-parameter short-parameter">
                <label>Дата начала</label>
                <input type="text" name="form[date_from]" value="<?=($edit_action->date_from ? DateHelper::changeFormat($edit_action->date_from,'-') : date('d-m-Y'))?>" id="form_date_from">
                <input type="button" id="form_date_picker_date_from" value="Выбрать дату">
                <script>
                    Calendar.setup(
                            {

                                ifFormat:"%d-%m-%Y", daFormat:"%d-%m-%Y",
                                inputField: 'license_issue_date',
                                button: 'form_date_from',
                                date: '<?=date('d-m-Y')?>'
                            }
                    );
                    Calendar.setup(
                            {

                                ifFormat:"%d-%m-%Y", daFormat:"%d-%m-%Y",
                                inputField: 'license_issue_date',
                                button: 'form_date_picker_date_from',
                                date: '<?=date('d-m-Y')?>'
                                eventName: 'click'
                            }
                    );

                </script>
            </div>
            <div class="education-parameter short-parameter">
                <label>Дата окончания</label>
                <input type="text" name="form[date_to]" value="<?=($edit_action->date_to ? DateHelper::changeFormat($edit_action->date_to,'-') : date('d-m-Y'))?>" id="form_date_to">
                <input type="button" id="form_date_picker_date_to" value="Выбрать дату">
                <script>
                    Calendar.setup(
                            {

                                ifFormat:"%d-%m-%Y", daFormat:"%d-%m-%Y",
                                inputField: 'license_issue_date',
                                button: 'form_date_to',
                                date: '<?=date('d-m-Y')?>'
                            }
                    );
                    Calendar.setup(
                            {

                                ifFormat:"%d-%m-%Y", daFormat:"%d-%m-%Y",
                                inputField: 'license_issue_date',
                                button: 'form_date_picker_date_to',
                                date: '<?=date('d-m-Y')?>'
                            eventName: 'click'
                    }
                    );
                </script>
            </div>
        </div>
        <div class="row-record">
            <label>Описание</label>
            <div class="row-record-data">
                <textarea class="htmlarea" id="form_info" name="form[info]" style="width: 100%; height:350px;"><?=$edit_action->info?></textarea>
                <script type="text/javascript">
                    $(document).ready(function() {
                        CKEDITOR.replace( 'form_info',
                                {
                                    filebrowserBrowseUrl : '/media/js/ckeditor/ckfinder.html',
                                    filebrowserUploadUrl : '/media/js/ckeditor/core/connector/php/connector.php?command=QuickUpload&type=Files',

                                    filebrowserImageBrowseUrl : '/media/js/ckeditor/ckfinder.html?type=Images',
                                    filebrowserFlashBrowseUrl : '/media/js/ckeditor/ckfinder.html?type=Flash',

                                    filebrowserImageUploadUrl : '/media/js/ckeditor/core/connector/php/connector.php?command=QuickUpload&type=Images',
                                    filebrowserFlashUploadUrl : '/media/js/ckeditor/core/connector/php/connector.php?command=QuickUpload&type=Flash',

                                    filebrowserWindowWidth : '600',
                                    filebrowserWindowHeight : '600'
                                });

                        getValue(CKEDITOR.instances.form_info.getData());

                        function getValue(value) {
                            $("textarea#form_info").html(value);

                            setTimeout(function() {
                                getValue(CKEDITOR.instances.form_info.getData())
                            }, 1000);
                        };
                    });
                </script>
            </div>
        </div>
    <?php if ($specializations): ?>

        <p>Специализации</p>
        <div class="fields-block-inner white-inner">
            <div class="specialties-block" data-holder-for="specialization_to_clinic">
                <?php foreach ($specializations as $e):
                $specialization = $e->specialization;
                ?>
                <div class="clinic_specialty">
                    <label>
                        <div data-name="specialization_to_clinic" class="specialization_to_clinic">
                            <div>
                                <span style="background-image: none">
                                    <input type="checkbox" name="specialization_id[]" <?php if ($edit_action->hasSpecialization($specialization->getId())){?> checked="checked" <?php } ?>  value="<?php echo $specialization->getId() ?>" />
                                </span>
                                <?=$specialization->name; ?>
                            </div>
                        </div>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
        <div class="buttons flo">
            <!-- <input class="btn-appoint longest-button" type="submit" name="sent_back" value="Отправить на доработку" onclick="return false;">-->
            <input class="btn-1" type="submit" name="publish" value="Сохранить">
            <input class="btn-appoint" type="button" name="cancel" value="Отменить" onclick="window.location.reload()">
            <input type="hidden" name="edit_action_id" value="<?=$edit_action->id?>" />


        </div>
    </div>
</form>
<div class="actionsList">
    <?php foreach($clinic_actions as $e){
        /** @var ActionModel $e */
	?>
    <div class="oneAction">
        <div class="actionImage" style="height: 200px;">
            <div class="actionName"><?=$e->name?></div>
            <?php if ($e->image){ ?><img src="<?=$e->image->crop(200, 200)->path?>" width="200" height="200"><?php } ?>
        </div>
        <div class="actionEdit"><a class="link-edit" href="/registry/clinic/action?clinic_id=<?=$clinic_id?>&edit_action_id=<?=$e->id?>">редактировать</a></div>
        <div class="actionDelete"><a class="link-delete" href="/registry/clinic/actionDelete?clinic_id=<?=$clinic_id?>&delete_action_id=<?=$e->id?>">удалить</a></div>
    </div>
    <?php } ?>
</div>