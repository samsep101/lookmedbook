<?php
    /**
     * @var View $this
     * @var SpecialtyModel[] $specialties
     * @var AppealTypeModel[] $appeal_types
     * @var VisitSourceModel[] $visit_sources
     * @var TargetCallModel[] $target_calls
     * @var TargetCallModel $target_call
     */
?>
<div class="popup-handling">
    <div class="phone-name">
        <label>Телефон:</label> <input class="mask" type="text" name="phone_number" placeholder="+7-___-___-__-__"/>
        <label>Фамилия:</label> <input type="text" name="last_name" placeholder="Фамилия"/>
        <label>Имя:</label> <input type="text" name="first_name" placeholder="Имя"/>
        <label>Отчество:</label> <input type="text" name="middle_name" placeholder="Отчество"/>
    </div>
    <div class="another-info">
        <label>Тип:</label>
        <select name="appeal_type_id">
            <?php foreach($appeal_types as $appeal_type): ?>
            <option value="<?php echo $appeal_type->getId(); ?>"><?php echo $appeal_type->name; ?></option>
            <?php endforeach; ?>
        </select>
        <label>Откуда пришел:</label>
        <select name="visit_source_id">
            <?php foreach($visit_sources as $visit_source): ?>
            <option value="<?php echo $visit_source->getId(); ?>"><?php echo $visit_source->name; ?></option>
            <?php endforeach; ?>
        </select>
        <label>Телефон:</label>
        <select name="target_call_id">
            <option value="0">Телефон клиента</option>
            <?php foreach($target_calls as $target_call): ?>
                <option value="<?php echo $target_call->getId(); ?>"><?php echo $target_call->phone; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="bottom-block">
        <label class="forarea">Тема:</label>
        <textarea name="title" rows="3" placeholder="Хирург новогиреево"></textarea>
        <label class="forspecialty">Специализация:</label>
        <select name="specialty_id">
            <option value="0"></option>
            <?php foreach($specialties as $specialty): ?>
                <option value="<?php echo $specialty->getId(); ?>"><?php echo $specialty->name; ?></option>
            <?php endforeach; ?>
        </select>
        <input name="with_visit" class="with-visit btn-appoint" type="button" value="С записью"/>
        <input name="without_visit" class="without-visit btn-1" type="button" value="Без записи"/>
        <input name="cancel" class="cancel-appeal btn-appoint" type="button" value="Отмена" style="margin-left: 10px;"/>
    </div>
</div>