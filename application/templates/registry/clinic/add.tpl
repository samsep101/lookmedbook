<script type="text/javascript">
    $(document).ready(function(){
        var add_clinic_controller = new AddClinicFormController();
        add_clinic_controller.init();
    });
</script>

<div class="moderated-form" id="information-form">
    <div class="fields-block flo">
        <div class="fields-block-inner white-inner">

            <div class="row-record">
                <label>Название клиники</label>
                <input type="text" style="width:500px;margin-top: 10px;" name="clinic_name" class="grey_placeholder"/>
                <br><span style="margin-left: 150px" class="ex_registry">Пример: Бионикс</span>
            </div>

            <div class="row-record">
                <label>Город</label>
                <select name="city" style="margin-top: 10px;">
                    <?php if($cities): ?>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?php echo $city->getId(); ?>"><?php echo $city->name; ?></option>
                            <?php if ($city->name == 'Москва'): ?>
                                <script>
                                    $('select[name="city"]').val("<?php echo $city->getId();?>").prop('selected',true);
                                </script>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="row-record">
                <label>Адрес</label>
                <input type="text" style="width:500px;margin-top: 10px;" name="address"/>
                <br><span style="margin-left: 150px" class="ex_registry">Пример: ул. Народная, 14с1</span>
            </div>
            <br>
            <div class="row-record">
                <label>Долгота</label>
                <input type="text" style="margin-top: 10px;" name="longitude" class="grey_placeholder"/>
                <br><span style="margin-left: 150px" class="ex_registry">Пример: 37.6501</span>
            </div>

            <div class="row-record">
                <label>Широта</label>
                <input type="text" style="margin-top: 10px;" name="latitude" class="grey_placeholder"/>
                <br><span style="margin-left: 150px" class="ex_registry">Пример: 55.7377</span>
            </div>
            <a href="https://api.yandex.ru/maps/tools/getlonglat/" target="_blank" style="font-size: 16px">Получение координат</a>

            <div class="row-record" style="margin-top: 40px">
                <label style="width: 370px">Кто предоставил информацию по брифу</label>
                <div class="in_row_record_data">

                    <div class="row-record">
                        <label>(Ф.И.О., должность)</label>
                        <div class="row-record-data">
                            <input type="text" name="user_name" style="width: 500px">
                        </div>
                    </div>

                    <div class="row-record">
                        <label>Телефон</label>
                        <div class="row-record-data">
                            <input type="text" name="user_phone">
                        </div>
                    </div>

                    <div class="row-record">
                        <label>E-mail</label>
                        <div class="row-record-data">
                            <input type="email" name="user_email">
                        </div>
                    </div>

                    <!--<div class="row-record">
                        <label>Новый пароль</label>
                        <div class="row-record-data">
                            <input type="password" name="user_password">
                        </div>
                    </div>-->
                </div>
            </div>

        </div>
    </div>

    <div class="buttons flo">
        <input class="btn-appoint" type="button" name="save" value="Сохранить">
    </div>
</div>

