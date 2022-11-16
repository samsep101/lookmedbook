<?php if (!isset($visit_id) && !isset($doctor_id) && !isset($clinic_id)): ?>
    <script>
        window.location = '/account/doctorsVisitsPast';
    </script>
<?php endif; ?>
<script type="text/javascript">
    $(document).ready(function(){
        var controller = new AddReviewBlockController(<?php echo $visit_id;?>,<?php echo $doctor_id;?>,<?php echo $clinic_id;?>);
        controller.init();
    });

</script>

<a href="javascript:void(0)" id="add-review-link" style="display: none">Добавить отзыв</a>

<div id="add-review-form">

    <div id="add-review-step1" class="add-review">
        <label class="blue">Оцените врача:</label><br><br>

        <label>Кабинет</label>
        <select id="cabinet" class="select-rating">
            <option value="1">1 звезда</option>
            <option value="2">2 звезды</option>
            <option value="3">3 звезды</option>
            <option value="4" selected>4 звезды</option>
            <option value="5">5 звезд</option>
        </select><br><br>

        <label>Время ожидания</label>
        <select id="waiting_time" class="select-rating">
            <option value="1">1 звезда</option>
            <option value="2">2 звезды</option>
            <option value="3" selected>3 звезды</option>
            <option value="4">4 звезды</option>
            <option value="5">5 звезд</option>
        </select><br><br>

        <label>Отношение к пациенту</label>
        <select id="relationship" class="select-rating">
            <option value="1">1 звезда</option>
            <option value="2">2 звезды</option>
            <option value="3">3 звезды</option>
            <option value="4">4 звезды</option>
            <option value="5" selected>5 звезд</option>
        </select><br><br>

        <label>Соответствие цене</label>
        <select id="value_for_money" class="select-rating">
            <option value="1">1 звезда</option>
            <option value="2" selected>2 звезды</option>
            <option value="3">3 звезды</option>
            <option value="4">4 звезды</option>
            <option value="5">5 звезд</option>
        </select><br><br>

        <label>Диагноз и лечение мне ясны</label>
        <select id="diagnosis_is_clear" class="select-rating">
            <option value="1">1 звезда</option>
            <option value="2">2 звезды</option>
            <option value="3" selected>3 звезды</option>
            <option value="4">4 звезды</option>
            <option value="5">5 звезд</option>
        </select><br><br>

        <label class="blue">Оцените клинику:</label><br><br>

        <label>Сервис в регистратуре</label>
        <select id="service_at_the_reception" class="select-rating">
            <option value="1">1 звезда</option>
            <option value="2">2 звезды</option>
            <option value="3">3 звезды</option>
            <option value="4" selected>4 звезды</option>
            <option value="5">5 звезд</option>
        </select><br><br>
        <span style="display: block">
            <input type="button" value="Пожаловаться <?php echo SITE_NAME; ?>" id="complaint" style="display: none">
            <input type="button" value="Далее" id="step1-submit" style="margin-left: 300px">
        </span>

    </div>

    <div id="add-review-step2" class="add-review" style="display: none">
        <label class="blue">Оцените врача:</label><br><br>

        <label>Если врач понадобиться другу, посоветуете?</label><br>
        <span id="advice-doctor-buttons">
            <input type="button" value="Да" id="yes-advice-doctor">&nbsp; <input type="button" value="Нет" id="not-advice-doctor">
        </span>
        <input type="hidden" id="advice-doctor" value="">
        <br><br>

        <label class="blue">Оцените клинику:</label><br><br>

        <label>Если клиника понадобиться другу, посоветуете?</label><br>
        <span id="advice-clinic-buttons">
            <input type="button" value="Да" id="yes-advice-clinic">&nbsp; <input type="button" value="Нет" id="not-advice-clinic">
        </span>
        <input type="hidden" id="advice-clinic">
        <br><br>

        <span style="margin-left: 300px; display: block">
            <a id="step2-skip" href="javascript:void(0)" class="skip">пропустить &rArr; </a>
            <input type="button" value="Далее" id="step2-submit">
        </span>

    </div>

    <div id="add-review-step3" class="add-review" style="display: none">
        <label class="blue">Оцените врача:</label><br><br>
        <label>Напишите отзыв</label><br>
        <textarea id="doctor_review"></textarea><br>

        <span style="margin-left: 300px; display: block">
            <a id="step3-skip" href="javascript:void(0)" class="skip">пропустить &rArr; </a>
            <input type="button" value="Далее" id="step3-submit">
        </span>

    </div>

    <div id="add-review-step4" class="add-review" style="display: none">
        <label class="blue">Оцените клинику:</label><br><br>
        <label>Напишите отзыв</label><br>
        <textarea id="clinic_review"></textarea><br>

        <span style="margin-left: 300px; display: block">
            <a id="step4-skip" href="javascript:void(0)" class="skip">пропустить &rArr; </a>
            <input type="button" value="Далее" id="step4-submit">
        </span>

    </div>

    <div id="add-review-step5" class="add-review" style="display: none">
        <label class="blue">Оцените врача:</label><br><br>
        <label>Хотите что-то сказать персонально <?php echo SITE_NAME; ?> ?</label><br>
        <textarea id="private_review"></textarea><br>

        <span style="margin-left: 300px; display: block">
            <input type="button" value="Готово" id="step5-submit">
        </span>

    </div>

</div>