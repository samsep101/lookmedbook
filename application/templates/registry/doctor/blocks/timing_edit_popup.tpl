<div class="numbers-content active-numbers  flo">
    <div class="numbers-timing schedule-edit-popup">
        <h2>График работы врача в</h2>
        <h3 class="nameday"></h3>
        <div class="numbers-row-record radio-label-container radio-menu">
            <div class="radio-week-control radio-edit">
                <div class="head-label first-label">
                    <div class="radioBox schedule-changing act"><span></span>Изменение расписания
                        <input type="hidden" value="1">
                    </div>
                </div>
                <div class="head-label second-label">
                    <div class="radioBox holidays"><span></span>Отпуск
                        <input type="hidden" value="0">
                    </div>
                </div>
                <div class="head-label third-label">
                    <div class="radioBox not-working"><span></span>Не работает
                        <input type="hidden" value="0">
                    </div>
                </div>
            </div>
        </div>
        <div id="schedule-changing" class="numbers-field schedule-changing">
            <div class="numbers-row-record">
                <label>Время приема врача</label>
                <div class="numbers-record-data">
                    <p>c</p><input type="text" placeholder="__:__" name="start_time" value="" /><p>по</p><input type="text" name="end_time" placeholder="__:__" value="" />
                </div>
            </div>
            <div class="numbers-row-record">
                <label>Перерыв</label>
                <div class="numbers-record-data">
                    <p>c</p><input type="text" name="break.start_time" placeholder="__:__" value="" /><p>по</p><input name="break.end_time" type="text" placeholder="__:__" value="" />
                </div>
            </div>
            <div class="numbers-row-record radio-label-container">
                <label>Принимает в</label>
                <div class="radio-week-control">
                    <div class="head-label first-label">
                        <div class="radioBox act"><span></span>клинике
                            <input type="hidden" name="visit_type.clinic" value="1">
                        </div>
                    </div>
                    <div class="head-label second-label">
                        <div class="radioBox"><span></span>на дому
                            <input type="hidden" name="visit_type.home" value="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="not-working" class="numbers-field flo">

                <label>Для специальности:</label>
                <div class="chekBox-container">
                    <div class="chekBox">
                        <span></span>Невролог
                        <input type="hidden"  name="is_selected" value="0">
                    </div>
                </div>
                <div class="chekBox-container">
                    <div class="chekBox">
                        <span></span>Терапевт
                        <input type="hidden"  name="is_selected" value="0">
                    </div>
                </div>

        </div>

        <div class="buttons flo">
            <input class="btn-appoint" type="submit" name="save" value="Сохранить" onclick="return false;">
            <input class="btn-1" type="submit" name="close" value="Отменить" onclick="return false;">
        </div>
    </div>
</div>