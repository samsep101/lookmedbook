<?php if (Application::config('captcha.enable')) { ?>
    <script src='https://www.google.com/recaptcha/api.js?render=explicit' async defer></script>
<?php } ?>
<div id="record_form_container" style="display:none">
    <form action="/ajax/recordToTheVisit"
          method="POST"
          class="recordPopupForm linkMapper"
          rel=".recordFormResult"
          onComplete="recordComplete()"
          onsubmit="onRecordSubmit(this)">
    <div class="booking record-to-the-doctor-popup" style="display:block">
        <div class="all">
            <h3 class="h1">Запись на прием</h3>
            <div class="info-placeholder"></div>
            <div class="step-block-1 flo" style="display: none">
                <!-- place for info where user want to visit -->
            </div>
            <div class="row flo m-b-10">
                <div class="text-shadow-input">
                    Когда нужно к врачу:
                </div>
                <div class="shadow-input">
                    <input type="date" name="visit_start" placeholder="01.01.2016">
                </div>
            </div>
            <div class="row flo m-b-10">
                <div style="width: 155px;float:left;">&nbsp;</div>
                <div class="shadow-checkbox">
                    <div class="chekBox act"><span></span> <em>после работы</em>
                        <input type="hidden" name="after_work" value="1">
                    </div>
                    <!-- <input type="checkbox" name="after_work" value="1"> - после работы -->
                </div>
            </div>
            <div class="row flo m-b-10">
                <div class="text-shadow-input">
                    Ваше имя:
                </div>
                <div class="shadow-input">
                    <input type="text" name="full_name" placeholder="Иван" required>
                </div>
            </div>
            <div class="row flo m-b-10">
                <div class="text-shadow-input">
                    Ваш телефон:
                </div>
                <div class="shadow-input">
                    <input type="tel" id="inputPhone" class="inputPhone" name="phone" placeholder="+7 (___) ___-__-__" required>
                </div>
            </div>
            <div class="hidden row flo m-b-10">
                <div class="text-shadow-input">
                    Ваш email:
                </div>
                <div class="shadow-input">
                    <input type="email" name="email" placeholder="example@email.ru">
                </div>
            </div>
            <?php if (Application::config('captcha.enable')) { ?>
                <div class="row flo m-b-10">
                    <div class="text-shadow-input">
                        &nbsp;
                    </div>
                    <div class="shadow-input">
                        <div class="g-recaptcha-add_review"></div>
                    </div>
                </div>
            <?php } ?>
            <?php $topPhone = isset($topPhone) ? $topPhone : '' ?>
            <div class="row flo m-b-20 record-form-info-phone <?= $topPhone ? '' : 'hidden' ?>">
                <div class="record_process_result"></div>
                <div class="inner-top-info" style="text-align: center;font-size: 1.2em;">
                    Мы всегда рады вам помочь! <span class="info-phone"><?= $topPhone ?></span>
                </div>
            </div>
            <div class="row flo m-b-10" style="text-align: center;">
              <a class="reg-link show_license" href="javascript:void(0);">Пользовательское соглашение</a>
            </div>
            <div class="row flo m-b-10 a-c">
                <input style="width:200px;" type="submit" class="btn-1 resume-btn js-hide-on-record-complete" value="Записаться">
            </div>
        </div>
    </div>
        <input type="hidden" name="clinic_id">
        <input type="hidden" name="doctor_id">
        <input type="hidden" name="disease_id">
        <input type="hidden" class="doSubmit">
    </form>
    <div class="recordFormResult" style="display: none"></div>
    <div class="recordFormSuccess" style="display: none">Вы успешно записаны!</div>
    <div class="recordFormFail" style="display: none">Запись не удалась!</div>
</div>
