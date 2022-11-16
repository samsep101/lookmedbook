
<script type="text/javascript">
    $(document).ready(function(){

        var _ = $('#edit-seo');

        _.on('click','[name="publish"]',function(){

            var data = _.find('form').serialize();

            $.ajax({
                method: "POST",
                url: "/registry/clinic/ajaxSaveSeoModerate",
                data: data,
                dataType: 'json',
                success: function(res){
                    //console.log(typeof(res));
                    if( typeof(res['result']['data']['title']) != "undefined" ){
                        _.find('#seo-title').html(res['result']['data']['title']);
                    }
                    if( typeof(res['result']['data']['description']) != "undefined" ){
                        _.find('#seo-description').html(res['result']['data']['description']);
                    }
                }
            });

        });
    });
</script>

<div class="fields-block pick-a-pic flo" id="edit-seo">
    <form>
    <input type="hidden" name="form[id]" value="<?=$seo->id?>" />
    <input type="hidden" name="form[clinic_id]" value="<?=$clinic_id?>" />

    <p>Параметры клиники</p>

    <div class="fields-block-inner pink-inner mb20">

        <table class="white-table w100">
            <tr>
                <td>Название клиники</td>
                <td><?=$clinic->name?></td>
            </tr>
            <tr>
                <td>Адрес</td>
                <td><?=$clinic->address?></td>
            </tr>
            <tr>
                <td>Метро</td>
                <td><?=$clinic->metro?></td>
            </tr>
        </table>

    </div>

    <div class="both"></div>

    <p>Настройки СЕО Адреса</p>

    <div class="fields-block-inner pink-inner mb20">

        <div class="full"><label class="full">Адрес для СЕО </label></div>
        <div class="both"></div>
        <div class="full">

            <div class="row-record-data w100">
                <?php echo $view_processor->getView ( 'seo_address' ); ?>
            </div>

        </div>
        <div class="full"><span>Оставьте поле пустым, чтобы использовать адрес клиники. Если в адресе есть город, область, республика и пр. удалите его. </span></div>

    </div>

    <div class="both"></div>

    <p>Настройки мета тега title</p>

    <div class="fields-block-inner pink-inner mb20">
        <div class="row-record">Текущий title : <p id="seo-title"><?=$this->seo_title?></p></div>
        <div class="row-record">
            <div class="col-50">
                <div class="row-record-data">
                    <?php  echo $view_processor->getView ( 'metro_to_title' ); ?>
                </div>

                <label class="full">Добавить <b>метро</b> в заголовок (title) </label>
                <div class="both"></div>
                Не нужно добавлять метро, если станция метро входит в название клиники

            </div>

            <div class="col-50">

                <div class="row-record-data">
                    <?php  echo $view_processor->getView ( 'address_to_title' ); ?>
                </div>

                <label class="full">Добавить <b>адрес</b> в заголовок (title) </label>
                <div class="both"></div>
                Не нужно добавлять адрес, если адрес входит в название клиники

            </div>
        </div>
        <div class="row-record">

            <div class="full"><label class="full">Текст заголовка (title), имеет высший приоритет</label></div>
            <div class="full">

                <div class="row-record-data w100">
                    <?php echo $view_processor->getView ( 'seo_title' ); ?>
                </div>

            </div>

        </div>
    </div>
    <div class="both"></div>

    <p>Настройки мета тега description</p>

    <div class="fields-block-inner pink-inner mb20">
        <div class="row-record" >Текущий description : <p id="seo-description"><?=$this->seo_description?></p></div>
        <div class="row-record">
            <div class="col-50">
                <div class="row-record-data">
                    <?php echo $view_processor->getView ( 'metro_to_description' ); ?>
                </div>

                <label class="full">Добавить <b>метро</b> в описание (description) </label>
                <div class="both"></div>
                Не нужно добавлять метро, если станция метро входит в название клиники

            </div>

            <div class="col-50">

                <div class="row-record-data">
                    <?php echo $view_processor->getView ( 'address_to_description' ); ?>
                </div>

                <label class="full">Добавить <b>адрес</b> в описание (description) </label>
                <div class="both"></div>
                Не нужно добавлять адрес, если адрес входит в название клиники

            </div>

        </div>


        <div class="row-record">

            <div class="full"><label class="full">Текст описания (description), имеет высший приоритет</label></div>
            <div class="both"></div>
            <div class="full">

                <div class="row-record-data w100">
                    <?php echo $view_processor->getView ( 'seo_descritpion' ); ?>
                </div>

            </div>

        </div>
    </div>
</form>
<?php $this->block('registry/blocks/form-buttons'); ?>
</div>