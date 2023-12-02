<?php
    /* @var $doctor DoctorModel */
    if (!isset($is_small_card))
        $is_small_card = false;
    if (!isset($doctor_page))
        $doctor_page = false;
?>
<script language="JavaScript">
function click_on_doctor_record_button($this, doc_id) {
	var doctor_id = doc_id || 940;
	if (window.is_test == 1) {
		$this.attr('href','javascript:void(0)');
	} else {
		//if (SessionInfo.is_authed)
		//  {

			send('//ad.adriver.ru/cgi-bin/rle.cgi?sid=194132&sz=zapis&bt=55&pz=0&rnd=![rnd]');
//во втором копипасте функции был такой вариант send-а:
//		send('//ad.adriver.ru/cgi-bin/rle.cgi?sid=194132&amp;sz=zapis&amp;bt=55&amp;pz=0&amp;rnd=![rnd]')

		var block = new RecordToTheDoctorBlockController(doctor_id, $this, null);
		block.action_for_counters = 'button';
		block.init();

			//это изначально было закоменчено. без понятия, что эта фигня значит
			/*
			 } else {
			 var block = new LandingRegistrationPageController();
			 block.block_title = 'для записи к врачу';
			 block.block_over_textbox = 'Введите почту и продолжайте запись!';
			 block.action_for_counters = 'booking-reg';
			 block.success_registration_callback = function(data){
			 var block = new RecordToTheDoctorBlockController(
			doctor_id, $this, null);
			 block.action_for_counters = 'button';
			 $('.header #authorization-block-on-disease-page').html('<div class=\'header-user\' style=\'margin: 2px 44px 0 24px;\'><a href=\'/account/message\' class=\'header-usernotification\'> <span style=\'display: none;\' class=\'notification\' id=\'usernotification\'></span> </a><div class=\'header-userinfo\'><a href=\'/account/about\' class=\'header-userprofile\'>'+ SessionInfo.email +'</a><ul class=\'header-usermenu\'><li><a href=\'/account/about\'>Профиль</a></li><li><a href=\'/help\'>Помощь</a></li><li><a href=\'/account/logout\'>Выйти</a></li></ul></div></div>');
			 block.init();
			 };
			 block.init()
			 }*/
	}
}
</script>

<div class="btns flo" id="visit-remind-block-<?= $this->reviewId ?>">
  <?php if (isset($example_page)) { ?>
    <a href="javascript:void(0)" class="btn-appoint">Записаться</a>
    <a class="btn-bookmark doctor_bookmark">
        <i class="icon-add"></i><span class="txt">Добавить в закладки</span>
    </a>
  <?php } else if(!empty($single_doctor_page)) { ?>

  <?php if (!$doctor->clinic->visit_disallow):?>
	   <?=$doctor->getRecordButton()?>
  <?php endif; ?>
      <a class="btn review-link" href="#add-review-popup-<?= $this->reviewId ?>">Оставить отзыв</a>
      <a class="btn-bookmark doctor_bookmark">
          <i class="icon-add"></i><span class="txt">Добавить в закладки</span>
      </a>
    <?php } else { ?>
      <?php if ($doctor->clinic && !$doctor->clinic->visit_disallow):?>
		  <?=$doctor->getRecordButton(2)?>
      <?php endif; ?>
    <?php } ?>
</div>
