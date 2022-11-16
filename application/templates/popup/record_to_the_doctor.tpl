<?php
	/**
	 * @var View $this
	 * @var ClinicModel $clinic_selected
	 * @var ScheduleModel[] $schedule_times
     * @var DoctorModel $doctor
	 */
?>
<?php if (Application::config('captcha.enable')) { ?>
    <script src='https://www.google.com/recaptcha/api.js?render=explicit' async defer></script>
<?php } ?>
<div id="record-to-the-doctor-popup-<?php echo $doctor->getId(); ?>" class="booking record-to-the-doctor-popup" style="display:block">
    <div class="all">
        <h1>Запись на прием</h1>

        <div class="step-block-1 flo">
            <div class="progress-bar flo">

                <div class="steps step-1">
                    <div class="bar current_bar">
                        <span class="label">Шаг 1. Выбор времени</span>
                    </div>
                </div>
                <div class="steps step-2">
                    <div class="bar after_current_bar">
                        <span class="label" style=" padding-left: 7px;">Шаг 2. Подтверждение данных</span>
                    </div>
                </div>
            </div>
            <div class="visit-target flo">
                <div class="avatar">
                    <?php echo DoctorAvatarViewHelper::viewOnCard($doctor, 74, 111); ?>
                </div>
                <div>
                    <div class="rating">
                        <?php echo RateViewHelper::view($doctor->rate); ?>
                    </div>
                    <?php if ($doctor->is_virtual): ?>
                        <p class="name"><?= StringHelper::startProposalWord($doctor->specialties[0]->name); ?><br>
                    <?php else: ?>
                        <p class="name"><?= $doctor->specialties_names; ?><br>
                    <?php endif; ?>
                        <a href="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>"><span><?php echo $doctor->full_name; ?></span></a>
                    </p>
                    <!--<div class="sel-box">
                        <?php if ($doctor->specialties): ?>
                            <?php $this->purposes = $doctor->getPurposeOfVisitListBySpecialtyId($doctor->specialties[0]->getId()); ?>
                            <?php $this->block('ajax/purposes_select'); ?>
                        <?php endif; ?>
                    </div>-->
                </div>
            </div>

            <?php if (isset($day)):?>

                <div class="info-box loc-single">
                    <div class="location-box">
                        <div class="box">
                            <div class="section section-1 visible flo">
                                <div class="location">
                                    <p class="name-inf"><strong><?php echo $clinic_selected->name?></strong> <br>
                                        <?php if ($clinic_selected->metro_station): ?>
                                        <?php if ($clinic_selected->metro_station->metro_branch->image_id): ?>
                                        <img src="<?php echo $clinic_selected->metro_station->metro_branch->image->resize(25,36)->path;?>" alt="<?php echo $clinic_selected->metro_station->metro_branch->name; ?>">
                                        <?php endif; ?>
                                        <?php echo $clinic_selected->metro_station->name; ?> <br  />
                                        <?php endif; ?>
                                        <?php echo $clinic_selected->address; ?>
                                    </p>
                                </div>
                                <div class="price-inf">
                                    <?php
                                        $first_visit_price = $doctor->getFirstVisitPriceByClinicId($clinic_selected->getId());
                                        if ($first_visit_price == '0') $first_visit_price = ' Бесплатно';
                                        elseif ($first_visit_price) $first_visit_price = ': '.$first_visit_price.' руб.';
                                        else $first_visit_price = '';

                                        $second_visit_price = $doctor->getSecondVisitPriceByClinicId($clinic_selected->getId());
                                        if ($second_visit_price == '0') $second_visit_price = ' Бесплатно';
                                        elseif ($second_visit_price) $second_visit_price = ': '.$second_visit_price.' руб.';
                                        else $second_visit_price = '';
                                    ?>
                                    <p>Первый визит<strong><?php echo $first_visit_price ?></strong></p>
                                    <p>Повторный визит<strong><?php echo $second_visit_price ?></strong></p>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="booking-note flo">
                        <h3><?php echo $day?></h3>
                        <span class="label">Время</span>
                        <div class="sel-box">
                            <select name="schedule_time" class="chzn-select" style="width:296px;">
                                <?php foreach ($schedule_times as $time): ?>
                                    <?php $time_from = date_create($time->dt_start);?>
                                    <?php $time_from = date_format($time_from, 'H:i');?>
                                    <?php if ($time_from >= date('H:i'))?>
                                    <option value="<?php echo $time->id?>"><?php echo $time_from?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

            <?php else:?>

                <div class="info-box">
                    <?php $this->record_flag = true; ?>
                    <?php $this->block('doctor/blocks/clinics'); ?>
                </div>
            <?php endif?>
            <div class="btns flo">
                <input type="button" value="Продолжить" class="btn-1 resume-btn">
                <div class="error-msg error-msg-1">Пожалуйста выбери время</div>
            </div>
        </div>
        <div class="step-block-2 flo">
            <div class="progress-bar flo">


                <div class="steps step-1">
                    <div class="bar after_current_bar">
                        <img src="/media/images/comit_step.png" style="">
                        <span class="label step_1_label">Шаг 1. Выбор времени</span>
                    </div>
                </div>
                <div class="steps step-2">
                    <div class="bar current_bar">
                        <span class="label">Шаг 2. Подтверждение данных</span>
                    </div>
                </div>

            </div>
            <div class="form-block">
                <div class="row flo">
                    <label class="lab">Кто идет на прием?</label>
                    <div class="relation">
                        <ul class="family_relation_inline radio-label-container">
                            <li class="radioBox family-type-main act">
                                <span></span> Я
                                <input type="hidden" value="1">
                            </li>
                            <li class="radioBox family-type-relative">
                                <span></span> Родственник
                                <input type="hidden">
                            </li>
                        </ul>
                        <div class="sel-box" style="display: none;">
                            <?php if ($family_relations): ?>
                            <select name="family_relation_status" data-placeholder="<?php echo $family_relations[1]->name; ?>" class="chzn-select">
                                <option value=""></option>
                                <?php for($i = 1; $i < count($family_relations); $i++): ?>
                                <option value="<?php echo $family_relations[$i]->getId(); ?>"><?php echo $family_relations[$i]->name; ?></option>
                                <?php endfor; ?>
                            </select>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <div class="row flo">
                    <label class="lab">ФИО пациента*</label>

                    <div class="txt">
                        <?php
                        if ($current_account)
                            $fio = $current_account->full_name;
                        else
                        $fio = '';
                        ?>
                        <input type="text" name="surname" placeholder="Иванов Иван Иванович" value="<?php echo $fio; ?>" />
                    </div>
                </div>

                <div class="row flo">
                    <label class="lab">Телефон пациента*</label>

                    <div class="txt">
                        <input type="text" class="mask" name="phone" placeholder="+7-___-___-__-__"
                               data-rule-required="true" data-msg-required="Введите номер телефона" value="<?php echo ($current_account && $current_account->phones) ? substr($current_account->phones[0]->phone, 1, 10) : ''; ?>" />
                    </div>
                </div>
				<span class="god-mode"></span>
                <?php if (!Acc::isAuthed()): ?>
                <div class="hidden row flo email-row">
                    <label class="lab">Email*</label>

                    <div class="txt">
                        <input type="text" class="mask" name="email" placeholder="example@email.com" />
                    </div>
                </div>
                <?php endif; ?>
                <!--
                <div class="row flo">
                    <label class="lab">Цель визита*</label>

                    <div class="sel-box">
                        <div class="record-purpose-list">
                            <?php if ($doctor->specialties): ?>
                                <?php $this->purposes = $doctor->getPurposeOfVisitListBySpecialtyId($doctor->specialties[0]->getId()); ?>
                                <?php $this->select_style = 'width: 290px;'; ?>
                                <?php $this->block('ajax/purposes_select'); ?>
                            <?php endif; ?>
                        </div>
                        <span class="price"></span>
                    </div>

                </div>
-->
                <div class="row flo">
                    <label class="lab">Комментарий</label>

                    <div class="txt">
                        <textarea class="popup-textarea" maxlength="1500" name="comment" placeholder="Здесь можно добавить пожелания касательно Вашей записи к врачу."></textarea>
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

                <div class="btns flo">
                    <input type="button" value="Записаться" class="btn-1 send-button">
                    <div class="error-msg error-msg-1">Пожалуйста выбери время</div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                showTopNumber();
            });
        </script>
        <?php foreach ($doctor->clinics as $clinic) {?>
          <div class="inner-top-info js--clinic-info js--clinic-info-<?= $clinic->getId() ?>" style="text-align: center;">Мы всегда рады вам помочь!
            <span class="info-phone"><?= $clinic->top_phone ? : HelpPhoneNumberViewHelper::getPhoneNumber($city) ?></span>
          </div>
        <?php } ?>
    </div>
</div>
