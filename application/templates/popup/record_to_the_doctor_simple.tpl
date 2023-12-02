<?php
	/**
	 * @var View $this
	 * @var ClinicModel $clinic_selected
	 * @var ScheduleModel[] $schedule_times
	 */
?>
<div id="record-to-the-doctor-popup-<?php echo $doctor->getId(); ?>" class="booking record-to-the-doctor-popup" style="display:block; width: 400px !important; margin-left: 20px;">
    <div class="all" align="center">
        <h1>Запись на прием к врачу</h1>
        <div style="margin-bottom: 20px;">
        	<a href="/doctor/<?php echo $doctor->alias; ?>" style="font-size: 22px; color: #D93476; text-decoration: none;"><?php echo $doctor->last_name . ' ' . $doctor->first_name . ' ' . $doctor->second_name; ?></a>
        </div>
        <p>Заполните форму и клиника свяжется с вами в течении следующих 8 минут для подтверждения записи.</p>
        <div class="flo">
            <div class="form-block">
            	<div class="row flo" style="display: none;">
	                <div class="relation">
	                    <ul class="family_relation_inline radio-label-container">
	                        <li class="radioBox family-type-main act">
	                            <input type="hidden" value="1">
	                        </li>
	                    </ul>
	                </div>
	            </div>
                <div class="row flo">
                    <div class="txt">
                        <?php
                        if ($current_account)
                            $fio = $current_account->full_name;
                        else
                        $fio = '';
                        ?>
                        <input type="text" name="surname" placeholder="Ваше имя*" value="<?php echo $fio; ?>" />
                    </div>
                </div>

                <div class="row flo">
                    <div class="txt">
                        <input type="text" class="mask" name="phone" placeholder="+7-___-___-__-__"
                               data-rule-required="true" data-msg-required="Введите номер телефона" value="<?php echo ($current_account && $current_account->phones) ? substr($current_account->phones[0]->phone, 1, 10) : ''; ?>" />
                    </div>
                </div>
				<span class="god-mode"></span>
				<?php if (!Acc::isAuthed()): ?>
                <div class="row flo email-row">
                    <div class="txt">
                        <input type="text" class="mask" name="email" placeholder="Ваш e-mail*" />
                    </div>
                </div>
                <?php endif; ?>
     
                <div class="row flo">
                    <div class="txt">
                        <textarea class="popup-textarea" maxlength="1500" name="comment" placeholder="Добавьте удобный район и дату приёма"></textarea>
                    </div>
                </div>

                <div class="flo">
                    <input type="button" value="Отправить заявку" class="btn-1 send-button">
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                showTopNumber();
            });
        </script>
        <div class="inner-top-info" style="text-align: center;">Мы всегда рады вам помочь!
            <span class="info-phone"><?php echo HelpPhoneNumberViewHelper::getPhoneNumber($city); ?></span>
        </div>
    </div>
</div>
