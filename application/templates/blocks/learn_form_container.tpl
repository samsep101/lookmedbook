<div id="learn_form_container" style="display:none">
    <form action="/ajax/recordToTheVisit"
          method="POST"
          class="learnPopupForm linkMapper"
          rel=".learnFormResult"
          onComplete="recordComplete()"
          onsubmit="onRecordSubmit(this)">
        <div class="booking record-to-the-doctor-popup" style="display:block">
            <div class="all">
                <h3 class="h1">Получить информацию</h3>
                <div class="step-block-1 flo" style="display: none">
                    <!-- place for info where user want to visit -->
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
                <div class="row flo m-b-10">
                    <div class="text-shadow-input">
                        &nbsp;
                    </div>
                    <div class="shadow-input">
                        <div class="g-recaptcha-add_review"></div>
                    </div>
                </div>
                <div class="row flo m-b-20">
                    <div class="record_process_result"></div>
                    <div class="inner-top-info" style="text-align: center;font-size: 1.2em;">
                        Мы всегда рады вам помочь! <span class="info-phone"><?= HelpPhoneNumberViewHelper::getPhoneNumberLink() ?></span>
                    </div>
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
