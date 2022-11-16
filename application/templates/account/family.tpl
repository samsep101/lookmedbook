<script type="text/javascript">
    $(document).ready(function () {
        var controller = new PersonalRoomFamilyController();
        controller.init();
    });
</script>

<div class="inner-3">
    <div class="cab-page flo">
        <?php $this->active_top_menu = 'profile'; ?>
        <?php $this->block('blocks/personal-room-top-menu'); ?>

        <?php $this->active_left_menu = 'family'; ?>
        <?php $this->block('blocks/personal-room-left-menu'); ?>

        <div class="cab-cont cab-family">
            <h2>Семья</h2>
            <div class="account-relations">

            </div>
            <div class="about-form form">
                <h2>Связать меня с моей семьей:</h2>
                <form id="cab-form" action="#" method="post">
                    <div class="row flo">
                        <label class="lab">ФИО*</label>
                        <div class="data-box">
                            <div class="txt txt-surname">
                                <input type="text" name="last_name" placeholder="Фамилия">
                            </div>
                            <div class="txt txt-surname">
                                <input type="text" name="first_name" placeholder="Имя">
                            </div>
                            <div class="txt txt-surname2">
                                <input type="text" name="middle_name" placeholder="Отчество">
                            </div>
                        </div>
                    </div>
                    <div class="row flo">
                        <label class="lab">Номер телефона</label>
                        <div class="data-box">
                            <div class="txt">
                                <input type="text" class="mask" name="phone" placeholder="+7-___-___-__-__">
                            </div>
                        </div>
                    </div>
                    <div class="row flo">
                        <label class="lab">E-mail*</label>
                        <div class="data-box">
                            <div class="txt">
                                <input type="e-mail" name="email" placeholder="Введите e-mail">
                            </div>
                        </div>
                    </div>
                    <div class="row flo">
                        <label class="lab">Отношения*</label>
                        <div class="data-box">
                            <div class="sel-box">
                                <select name="relation" data-placeholder="Выберите связь" class="chzn-select add-relation-status" style="width:260px;">
                                    <?php foreach($relations as $relation):?>
                                        <?php if ($relation->name!='Я'):?>
                                            <option value="<?php echo $relation->id; ?>"><?php echo $relation->name; ?></option>
                                        <?php endif?>
                                    <?php endforeach?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="btns flo">
                        <input class="btn-1" id="add-relation-button" type="button" value="Отправить запрос">
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>