<div class="info-card doctor-big-card flo" id="doctor-big-card-<?php echo $doctor->getUniqueId(); ?>">
    <div class="avatar_buttons">
        <div class="avatar">
            <?php echo DoctorAvatarViewHelper::viewOnCard($doctor, 74, 111, '', $do_not_show_url = true); ?>
            <?php if (($doctor->advice_rate)||($doctor->about)||($doctor->education)||($doctor->cabinet_rate)||($doctor->waiting_time_rate)||($doctor->relationship_rate)||($doctor->value_for_money_rate)||($doctor->diagnosis_is_clear_rate)): ?>
            <div class="card-popup flo"><span class="corn"></span>
                <?php if (($doctor->cabinet_rate)||($doctor->waiting_time_rate)||($doctor->relationship_rate)||($doctor->value_for_money_rate)||($doctor->diagnosis_is_clear_rate)):?>
                    <div class="aside">
                        <h5>Рейтинг</h5>

                        <div class="rating-item">
                            <p>Кабинет</p>
                            <?php echo RateViewHelper::view($doctor->cabinet_rate); ?>
                        </div>
                        <div class="rating-item">
                            <p>Время ожидания</p>
                            <?php echo RateViewHelper::view($doctor->waiting_time_rate); ?>
                        </div>
                        <div class="rating-item">
                            <p>Отношение к пациенту</p>
                            <?php echo RateViewHelper::view($doctor->relationship_rate); ?>
                        </div>
                        <div class="rating-item">
                            <p>Соответствие цене</p>
                            <?php echo RateViewHelper::view($doctor->value_for_money_rate); ?>
                        </div>
                        <div class="rating-item">
                            <p>Диагноз и дальнейшее лечение ясны</p>
                            <?php echo RateViewHelper::view($doctor->diagnosis_is_clear_rate); ?>
                        </div>
                    </div>
                    <?php endif?>
                <?php if (($doctor->advice_rate)||($doctor->about)||($doctor->education)):?>
                    <div class="cont">
                        <div class="inner-cont">
                            <?php if ($doctor->advice_rate): ?>
                            <div class="advice">
                                <?php echo RateViewHelper::viewAdvise($doctor->advice_rate); ?>
                                <span>Посоветуют друзьям</span>
                            </div>
                            <?php endif; ?>

                            <?php if ($doctor->about): ?>
                            <h5>Опыт / Компетенции</h5>

                            <p><?php echo $doctor->about;?></p>
                            <?php endif; ?>

                            <?php if ($doctor->education): ?>
                            <h5>Образование</h5>

                            <p>
                                <?php foreach ($doctor->education as $place):?>
                                <?php echo $place->name; ?><br>
                                <?php endforeach?>
                            </p>
                            <?php endif; ?>
                        </div>
                        <a class="more-link">Узнать больше</a>
                    </div>
                    <?php endif?>
            </div>
            <?php endif; ?>
        </div>

        <div class="btns flo">
            <a class="btn-appoint">Записаться</a>

            <?php if ($doctor->my_doctor): ?>
            <a class="btn-bookmark doctor_bookmark btn-bookmark-added doctor_bookmark<?php echo $doctor->getId(); ?>">
                <i class="icon-add"></i>
                <span class="txt txt-added">В закладках</span>
            </a>
            <?php else: ?>
            <a class="btn-bookmark doctor_bookmark doctor_bookmark<?php echo $doctor->getId(); ?>">
                <i class="icon-add"></i>
                <span class="txt">Добавить в закладки</span>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="descr">
        <div class="fixed_title" style="height: 75px;">
            <div class="name">
                <a href="javascript:void(0)">
                    <span class="post">
                        <?php echo $doctor->specialties_names; ?>
                    </span>
                </a>

                <div class="rating">
                    <?php echo RateViewHelper::view($doctor->rate); ?>
                    <div class="comments-count">
                        <a class="showTip el">
                            <?php echo ($doctor->reviews_count) ? StringHelper::getCorrectSuffixForReview($doctor->reviews_count) : '';?>
                        </a>
                    </div>
                    <div class="tooltip-block">
                        <?php if ($doctor->last_review): ?>
                        <div class="tooltip"><img class="corn" src="/media/images/tooltip_corn.png" alt=""/>
                            <p><?php echo StringHelper::trim($doctor->last_review->text, 70); ?></p>
                        </div>
                        <?php elseif(isset($current_account) && $current_account && $uncommented_visit = $doctor->getOneLastUncommentedVisitByAccountId($current_account->getId())): ?>
                        <a class="review-link rev-popup-open"><span></span>Оставить отзыв</a>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="javascript:void(0)">
                    <p class="doctorname_clear"><?php echo $doctor->full_name; ?></p>
                </a>
            </div>
        </div>

        <div class="info-box">
            <?php $week_schedule = 1; ?>
            <?php $specialty_id = (isset($specialty_id)) ? $specialty_id : null; ?>
            <div class="location-box">
                <ul class="tabs flo">

                    <?php if ($specialty_id):?>
                        <?php $specialty_clinics = $doctor->getClinicsBySpecialtyId($specialty_id);?>
                    <?php else:?>
                        <?php $specialty_clinics = $doctor->clinics;?>
                    <?php endif;?>

                    <?php
                    $number = 1;
                    $clinic_numbers = array();
                    ?>
                    <?php if (isset($clinic_id) && $clinic_id):?>
                        <?php $doctor_clinic = $doctor->getOneClinicbyId($clinic_id); ?>
                        <?php $clinic_numbers[$clinic_id] = '';?>
                        <li data-id="<?php echo $doctor_clinic->getId();?>" class="loc-<?php echo $number ?> clinic-<?php echo $doctor_clinic->getId(); ?>-button">
                            <span>
                                <?php echo $number; ?>
                            </span>
                        </li>
                    <?php else:?>
                        <?php foreach($specialty_clinics as $clinic): ?>
                            <?php
                            if ($number == 1)
                                $clinic_numbers[$clinic->getId()] = '';
                            else
                                $clinic_numbers[$clinic->getId()] = $number-1;
                            ?>
                            <li data-id="<?php echo $clinic->getId();?>" class="loc-<?php echo $number ?> clinic-<?php echo $clinic->getId(); ?>-button">
                            <span>
                                <?php echo $number; ?>
                            </span>
                            </li>
                            <?php $number++; ?>
                        <?php endforeach; ?>
                    <?php endif?>
                </ul>

                <div class="box">
                    <?php $section_number = 1; ?>
                    <?php if (isset($clinic_id) && $clinic_id):?>
                        <div class="section section-<?php echo $section_number; ?> visible flo">
                            <p class="name-center">
                                <strong>
                                    <?php if (!Acc::isAuthed()):?>
                                    <a href="javascript:void(0);" data-url="<?php echo ClinicPageLinkViewHelper::getLink($doctor_clinic); ?>">
                                        <?php else:?>
                                        <a>
                                            <?php endif;?>
                                            <?php echo $doctor_clinic->name; ?>
                                        </a>
                                </strong>
                            </p>
                            <div class="location">
                                <!--<div class="trigger">
                                    <?php echo $section_number; ?>
                                </div>-->
                                <p class="name-inf">

                                    <?php if ($doctor_clinic->metro_station): ?>
                                        <?php if ($doctor_clinic->metro_station->metro_branch): ?>
                                            <?php echo MetroBranchIconViewHelper::getImage($doctor_clinic->metro_station->metro_branch)?>
                                            <?php endif; ?>
                                        <?php echo $doctor_clinic->metro_station->name; ?> <br  />
                                        <?php endif; ?>

                                    <?php echo StringHelper::trim($doctor_clinic->address); ?>
                                </p>
                            </div>
                            <div class="price-inf">
                                <?php if (isset($specialty_id) && $specialty_id && isset($purpose_of_visit_id) && $purpose_of_visit_id ): ?>
                                <?php $visit_price = $doctor->getFirstVisitPriceByClinicId($clinic_id,$specialty_id,$purpose_of_visit_id); ?>
                                <?php $purpose_name = StringHelper::getPurposeOfVisitNameByPurposeOfVisitId($purpose_of_visit_id); ?>

                                <?php if ($visit_price == '0'): ?>
                                    <?php $visit_price = 'Бесплатно'; ?>
                                    <?php elseif($visit_price): ?>
                                    <?php $visit_price = $visit_price.' руб.';?>
                                    <?php else: ?>
                                    <?php $visit_price = ''; ?>
                                    <?php endif; ?>

                                <p>
                                    <?php echo ($purpose_name) ? StringHelper::trimText($purpose_name, 40) : ''; ?>
                                    <strong><?php echo $visit_price; ?> </strong>
                                </p>
                                <?php else: ?>
                                <?php
                                $first_visit_price = $doctor->getFirstVisitPriceByClinicId($clinic_id);
                                if ($first_visit_price == '0') $first_visit_price = ' Бесплатно';
                                elseif ($first_visit_price) $first_visit_price = ': '.$first_visit_price.' руб.';
                                else $first_visit_price = '';

                                $second_visit_price = $doctor->getSecondVisitPriceByClinicId($clinic_id);
                                if ($second_visit_price == '0') $second_visit_price = ' Бесплатно';
                                elseif ($second_visit_price) $second_visit_price = ': '.$second_visit_price.' руб.';
                                else $second_visit_price = '';
                                ?>
                                <p>Первый визит<strong><?php echo $first_visit_price;?></strong></p>
                                <p>Повторный визит<strong><?php echo  $second_visit_price; ?></strong></p>
                                <?php endif; ?>
                            </div>

                            <?php if ($specialty_id):?>
                                <?php $doctor_clinic_specialties = $doctor->getSuggestedSpecialtiesListBySpecialtyIdAndClinicId($specialty_id, $clinic->getId());?>
                            <?php else:?>
                                <?php $doctor_clinic_specialties = $doctor->getSpecialtiesByClinicId($clinic->getId());?>
                            <?php endif;?>
                            <?php $existing_schedule = $doctor->checkExistingDoctorScheduleByClinicIdAndClinicSpecialties($clinic->getId(), $doctor_clinic_specialties);?>

                            <div class="location-box_schedule">
                                <ul class="tabs_schedule">
                                    <?php if ($existing_schedule):?>
                                        <?php $specialty_counter=1;?>
                                        <?php foreach ($doctor_clinic_specialties as $specialty):?>
                                            <li <?php if ($specialty_counter == 1){?>class="active"<?php }?>><span><?php if (mb_strlen($specialty->name,'UTF-8')>15) { echo StringHelper::startProposalWord(mb_substr($specialty->name,0,15,'UTF-8'));?>... <?php } else {echo StringHelper::startProposalWord($specialty->name);}?></span></li>
                                            <?php $specialty_counter++;?>
                                        <?php endforeach?>
                                    <?php endif;?>
                                </ul>
                                <div class="box">
                                    <?php foreach ($doctor_clinic_specialties as $specialty):?>
                                    <div class="section-in visible flo">
                                        <div class="schedule-extended schedule-clinic-<?= $clinic->getId() ?> <?php if (!$existing_schedule) {?>rounded-single-schedule<?php }?>">
                                            <ul class="schedule flo">

                                                <?php $monday = date("d.m.Y", strtotime("Monday this week")); ?>
                                                <?php $days_count = ($week_schedule) ? 7 : 14; ?>

                                                <?php for ($day = 1; $day <= $days_count; $day++): ?>
                                                    <?php $cur_day_time = strtotime($monday) + 86400*($day-1); ?> <!-- текущий день недели -->
                                                    <?php if ($existing_schedule):?>
                                                        <?php $doctor_schedule = ModelManagerFactory::getByName('doctor_schedule')->getOneCurrentByDoctorIdAndClinicIdAndSpecialtyId($doctor->getId(), $clinic_id, $specialty->getId()); ?>

                                                        <?php if ($doctor_schedule): ?>
                                                            <?php $work_time = $doctor_schedule->getDoctorWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                        <?php else:?>
                                                            <?php $work_time = $clinic->getClinicWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                        <?php endif;?>
                                                        <li class="day active schedule-tim <?php echo $work_time ? 'day-var' : '' ; ?>"><i><?php echo DayViewHelper::shortDay(date($cur_day_time));?></i>
                                                            <?php if ($work_time): ?>
                                                                <a class="record-day-pick" <?php if (!Acc::isAuthed()) {?>data-url="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>"<?php }?>>
                                                                    <?php echo $work_time;?>
                                                                </a>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php else:?>
                                                        <?php $work_time = $clinic->getClinicWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                        <li class="day active schedule-tim <?php echo $work_time ? 'day-var' : '' ; ?>"><i><?php echo DayViewHelper::shortDay(date($cur_day_time));?></i>
                                                            <?php if ($work_time): ?>
                                                                <a class="record-day-pick" <?php if (!Acc::isAuthed()) {?>data-url="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>"<?php }?>>
                                                                    <?php echo $work_time;?>
                                                                </a>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php endif?>
                                                <?php endfor; ?>
                                            </ul>
                                            <?php if (!$week_schedule): ?>
                                            <a class="prev-nav" href="javascript:void(0)"></a>
                                            <a class="next-nav" href="javascript:void(0)"></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endforeach?>
                                </div>
                            </div>
                        </div>
                    <?php else:?>
                        <?php foreach($specialty_clinics as $clinic):?>
                            <div class="section section-<?php echo $section_number; ?> visible flo">
                                <p class="name-center">
                                    <strong>
                                        <a>
                                            <?php echo $clinic->name; ?>
                                        </a></strong>
                                </p>
                                <div class="location">
                                    <p class="name-inf">
                                        <?php if ($clinic->metro_station): ?>
                                        <?php if ($clinic->metro_station->metro_branch): ?>
                                            <?php echo MetroBranchIconViewHelper::getImage($clinic->metro_station->metro_branch)?>
                                            <?php endif; ?>
                                        <?php echo $clinic->metro_station->name; ?> <br  />
                                        <?php endif; ?>

                                        <?php echo StringHelper::trim($clinic->address); ?>
                                    </p>
                                </div>
                                <div class="price-inf">
                                    <?php if (isset($specialty_id) && $specialty_id && isset($purpose_of_visit_id) && $purpose_of_visit_id ): ?>
                                    <?php $visit_price = $doctor->getFirstVisitPriceByClinicId($clinic->getId(),$specialty_id,$purpose_of_visit_id); ?>
                                    <?php $purpose_name = StringHelper::getPurposeOfVisitNameByPurposeOfVisitId($purpose_of_visit_id); ?>

                                    <?php if ($visit_price == '0'): ?>
                                        <?php $visit_price = 'Бесплатно'; ?>
                                        <?php elseif($visit_price): ?>
                                        <?php $visit_price = $visit_price.' руб.';?>
                                        <?php else: ?>
                                        <?php $visit_price = ''; ?>
                                        <?php endif; ?>

                                    <p>
                                        <?php echo ($purpose_name) ? StringHelper::trimText($purpose_name, 40) : ''; ?>
                                        <strong><?php echo $visit_price; ?> </strong>
                                    </p>
                                    <?php else: ?>
                                    <?php
                                    $first_visit_price = $doctor->getFirstVisitPriceByClinicId($clinic->getId());
                                    if ($first_visit_price == '0') $first_visit_price = ' Бесплатно';
                                    elseif ($first_visit_price) $first_visit_price = ': '.$first_visit_price.' руб.';
                                    else $first_visit_price = '';

                                    $second_visit_price = $doctor->getSecondVisitPriceByClinicId($clinic->getId());
                                    if ($second_visit_price == '0') $second_visit_price = ' Бесплатно';
                                    elseif ($second_visit_price) $second_visit_price = ': '.$second_visit_price.' руб.';
                                    else $second_visit_price = '';
                                    ?>
                                    <p>Первый визит<strong><?php echo $first_visit_price;?></strong></p>
                                    <p>Повторный визит<strong><?php echo  $second_visit_price; ?></strong></p>
                                    <?php endif; ?>
                                </div>

                                <?php if ($specialty_id):?>
                                    <?php $doctor_clinic_specialties = $doctor->getSuggestedSpecialtiesListBySpecialtyIdAndClinicId($specialty_id, $clinic->getId());?>
                                <?php else:?>
                                    <?php $doctor_clinic_specialties = $doctor->getSpecialtiesByClinicId($clinic->getId());?>
                                <?php endif;?>
                                <?php $existing_schedule = $doctor->checkExistingDoctorScheduleByClinicIdAndClinicSpecialties($clinic->getId(), $doctor_clinic_specialties);?>

                                <div class="location-box_schedule">
                                    <ul class="tabs_schedule">
                                        <?php if ($existing_schedule):?>
                                            <?php $specialty_counter=1;?>
                                            <?php foreach ($doctor_clinic_specialties as $specialty):?>
                                                <li <?php if ($specialty_counter == 1){?>class="active"<?php }?>><span><?php if (mb_strlen($specialty->name,'UTF-8')>15) { echo StringHelper::startProposalWord(mb_substr($specialty->name,0,15,'UTF-8'));?>... <?php } else {echo StringHelper::startProposalWord($specialty->name);}?></span></li>                                <?php $specialty_counter++;?>
                                                <?php $specialty_counter++;?>
                                            <?php endforeach?>
                                        <?php endif?>
                                    </ul>
                                    <div class="box">
                                        <?php foreach ($doctor_clinic_specialties as $specialty):?>
                                        <div class="section-in visible flo">
                                            <div class="schedule-extended schedule-clinic-<?= $clinic->getId() ?> <?php if (!$existing_schedule) {?>rounded-single-schedule<?php }?>">
                                                <ul class="schedule flo">

                                                    <?php $monday = date("d.m.Y", strtotime("last Monday")); ?>
                                                    <?php $days_count = ($week_schedule) ? 7 : 14; ?>

                                                    <?php for ($day = 1; $day <= $days_count; $day++): ?>
                                                        <?php $cur_day_time = strtotime($monday) + 86400*($day-1); ?> <!-- текущий день недели -->
                                                        <?php if ($existing_schedule):?>
                                                            <?php $doctor_schedule = ModelManagerFactory::getByName('doctor_schedule')->getOneCurrentByDoctorIdAndClinicIdAndSpecialtyId($doctor->getId(), $clinic->getId(), $specialty->getId()); ?>

                                                            <?php if ($doctor_schedule): ?>
                                                                <?php $work_time = $doctor_schedule->getDoctorWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                            <?php else:?>
                                                                <?php $work_time = $clinic->getClinicWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                            <?php endif;?>
                                                            <li class="day active schedule-tim <?php echo $work_time ? 'day-var' : '' ; ?>"><i><?php echo DayViewHelper::shortDay(date($cur_day_time));?></i>
                                                                <?php if ($work_time): ?>
                                                                    <a class="record-day-pick" <?php if (!Acc::isAuthed()) {?>data-url="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>"<?php }?>>
                                                                        <?php echo $work_time;?>
                                                                    </a>
                                                                <?php endif; ?>
                                                            </li>
                                                        <?php else:?>
                                                            <?php $work_time = $clinic->getClinicWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                            <li class="day active schedule-tim <?php echo $work_time ? 'day-var' : '' ; ?>"><i><?php echo DayViewHelper::shortDay(date($cur_day_time));?></i>
                                                                <?php if ($work_time): ?>
                                                                    <a class="record-day-pick" <?php if (!Acc::isAuthed()) {?>data-url="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>"<?php }?>>
                                                                        <?php echo $work_time;?>
                                                                    </a>
                                                                <?php endif; ?>
                                                            </li>
                                                        <?php endif?>
                                                    <?php endfor; ?>
                                                </ul>
                                                <?php if (!$week_schedule): ?>
                                                <a class="prev-nav" href="javascript:void(0)"></a>
                                                <a class="next-nav" href="javascript:void(0)"></a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endforeach?>
                                    </div>
                                </div>
                            </div>
                            <?php $section_number++;?>
                        <?php endforeach; ?>
                    <?php endif?>
                </div>
            </div>
        </div>
    </div>
</div>
