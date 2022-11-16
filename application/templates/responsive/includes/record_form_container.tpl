<!-- record default form ------>
<script type="text/javascript">
    function initializeGrecaptcha() {
        console.log('[Google Recaptcha] Initialize');
//        grecaptcha.render('g-recaptcha-add_review', {
//            'sitekey': '6LelcycTAAAAACs6URiEq3D1rLkKudTxC3D1Skj5'
//        })
    }
</script>
<script src='https://www.google.com/recaptcha/api.js?render=explicit&onload=initializeGrecaptcha' async defer></script>
<div id="record_form_container" style="display:none">
    <form action="/ajax/recordToTheVisit" method="POST" class="recordPopupForm linkMapper" rel=".recordFormResult" onComplete="recordComplete()" onsubmit="if (!$(this).find('input[name=full_name]').val() || !$(this).find('input[name=phone]').val()){alert('Вы не заполнили поля имя или телефон'); $(this).find('.doSubmit').val('false');}else{$(this).find('.doSubmit').val('');}">
      <div class="booking record-to-the-doctor-popup" style="display:block">
          <div class="all">
              <h3 class="h1">Запись на прием</h3>
              <div class="step-block-1 flo" style="display: none">
                  <!-- place for info where user want to visit -->
              </div>
              <div class="row flo m-b-10">
                  <div class="text-shadow-input">
                      Когда нужно к врачу:
                  </div>
                  <div class="shadow-input">
                      <input class="datepicker" type="text" name="visit_start" placeholder="01.01.2016">
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
                      <input type="text" name="full_name" placeholder="Иван">
                  </div>
              </div>
              <div class="row flo m-b-10">
                  <div class="text-shadow-input">
                      Ваш телефон:
                  </div>
                  <div class="shadow-input">
                      <input type="text" id="inputPhone" class="inputPhone" name="phone" placeholder="+7 (___) ___-__-__">
                  </div>
              </div>
              <div class="row flo m-b-10">
                  <div class="text-shadow-input">
                      Ваш email:
                  </div>
                  <div class="shadow-input">
                      <input type="text" name="email" placeholder="example@email.ru">
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
                      Мы всегда рады вам помочь! <span class="info-phone"><?= HelpPhoneNumberViewHelper::getPhoneNumber() ?></span>
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

<!-- record default form ------>