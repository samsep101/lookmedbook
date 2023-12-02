<div class="row-record">
    <label class="pad-a-bit">График работы врача</label>
    <input class="day-range-schedule-pick" id="date-range" type="text" placeholder="с__по__"/>
</div>

<div class="row-record">
    <label>Время приема одного пациента</label>
    <input class="min-number" type="text" name="visit_slot_time" />
    <label>мин</label>
</div>

<div id="tabs-schedule">
    <ul class="schedule-type">
        <li data-name="days"><a href="#tabs-schedule-2"  class="days-ow-week-tab">Дни недели</a></li>
        <li data-name="numbers"><a href="#tabs-schedule-1"  class="odd-day">Четн./нечетн.</a></li>
    </ul>
    <div id="tabs-schedule-2">
        <div id="first-week-calendar" class="calendar-work head-none"></div>

        <input class="btn-appoint long_but add-week" type="submit" value="Добавить неделю">

        <div class="second-week">
            <div  id="second-week-calendar" class="calendar-work head-none second-week"></div>
            <div class="radio-label-container">
                <div class="radio-week-control">
                    <div class="head-label first-label">
                        <div class="radioBox act"><span></span>1-ая неделя - 1-ая в году
                            <input type="hidden" value="1">
                        </div>
                    </div>
                    <div class="head-label second-label">
                        <div class="radioBox"><span></span>1-ая неделя - 1-ая в месяц
                            <input type="hidden" value="0">
                        </div>
                    </div>
                </div>
            </div>
            <input class="btn-appoint long_but remove-week" type="submit" value="Удалить неделю">
        </div>
    </div>

    <div id="tabs-schedule-1">
        <div id="even-numbers-container">
            <?php
            $time_block_data = array(
                'title' => 'Четные числа'
            );
            ?>
            <?php $this->block('registry/doctor/blocks/time_set', $time_block_data); ?>
        </div>
        <div id="odd-numbers-container">
            <?php
                $time_block_data = array(
                    'title' => 'Нечетные числа'
            );
            ?>
            <?php $this->block('registry/doctor/blocks/time_set', $time_block_data); ?>
        </div>

    </div>
</div>

