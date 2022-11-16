<div class="numbers-content active-numbers flo">
    <div class="numbers-timing">
        <div class="numbers-row-record uneven">
            <div class="chekBox act">
                <span></span>
                <?php echo $title; ?>
                <input type="hidden"  name="is_selected" value="1">
            </div>
        </div>
        <div class="numbers-fields">
            <div class="numbers-row-record">
                <label>Время приема врача</label>
                <div class="numbers-record-data">
                    <p>c</p><input type="text" name="start_time" placeholder="__:__"/><p>по</p><input type="text" name="end_time" placeholder="__:__"/>
                </div>
            </div>
            <div class="numbers-row-record">
                <label>Перерыв</label>
                <div class="numbers-record-data">
                    <p>c</p><input type="text" name="break.start_time" placeholder="__:__"/><p>по</p><input type="text" name="break.end_time" placeholder="__:__"/>
                </div>
            </div>
            <div class="numbers-row-record radio-label-container">
                <label>Принимает в</label>
                <div class="radio-week-control">
                    <div class="head-label first-label">
                        <div class="radioBox act"><span></span>клинике
                            <input type="hidden" value="1">
                        </div>
                    </div>
                    <div class="head-label second-label">
                        <div class="radioBox"><span></span>на дому
                            <input type="hidden" value="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="numbers-accept">
        <label>Кроме:</label>
        <div>
            <p>ПН</p><p>ВТ</p><p>СР</p><p>ЧТ</p><p>ПТ</p><p>СБ</p><p>ВС</p>
        </div>
        <div class="days-checkboxes">
            <div class="chekBox monday">
                <span></span>
                <input data-day-name="monday" type="hidden"  name="is_selected" value="1">
            </div>
            <div class="chekBox tuesday">
                <span></span>
                <input data-day-name="tuesday" type="hidden"  name="is_selected" value="1">
            </div>
            <div class="chekBox wednesday">
                <span></span>
                <input data-day-name="wednesday" type="hidden"  name="is_selected" value="1">
            </div>
            <div class="chekBox thursday">
                <span></span>
                <input data-day-name="thursday" type="hidden"  name="is_selected" value="1">
            </div>
            <div class="chekBox friday">
                <span></span>
                <input data-day-name="friday" type="hidden"  name="is_selected" value="1">
            </div>
            <div class="chekBox saturday">
                <span></span>
                <input data-day-name="saturday" type="hidden"  name="is_selected" value="1">
            </div>
            <div class="chekBox sunday">
                <span></span>
                <input data-day-name="sunday" type="hidden"  name="is_selected" value="1">
            </div>
        </div>
    </div>
</div>
                    