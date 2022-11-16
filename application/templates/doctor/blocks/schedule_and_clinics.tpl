<?php
/**
 * @var DoctorModel $doctor
 * @var int $specialty_id
 */
if (!isset($week_schedule))
    $week_schedule = 0;
if (!isset($search_page))
    $search_page = 0;
?>
<script type="text/javascript">
    $(document).ready(function () {
        schedule_controller = new ScheduleAndClinicsFormController('#doctor-big-card-<?php echo $doctor->getUniqueId(); ?>');
        schedule_controller.init();

<?php if ($doctor->clinic_id): ?>
            schedule_controller.selectClinic(<?php echo $doctor->clinic_id; ?>);
<?php endif; ?>
    });
</script>

<?php
$notWorkingWithDr = false;
if (!empty($current_account) && $current_account->is_call_centre_operator && $doctor->not_work) {
    $notWorkingWithDr = true;
}
?>

<div class="location-box">
    <ul class="tabs flo">

        <?php if ($specialty_id): ?>
            <?php $specialty_clinics = $doctor->getClinicsBySpecialtyId($specialty_id); ?>
        <?php else: ?>
            <?php $specialty_clinics = $doctor->clinics; ?>
        <?php endif; ?>

        <?php
        $number = 1;
        $clinic_numbers = array();
        ?>
        <?php if (isset($clinic_id)): ?>
            <?php $doctor_clinic = $doctor->getOneClinicbyId($clinic_id); ?>
            <?php $clinic_numbers[$clinic_id] = ''; ?>
            <li data-id="<?php echo $doctor_clinic->getId(); ?>" class="loc-<?php echo $number ?> clinic-<?php echo $doctor_clinic->getId(); ?>-button">
                <span>
                    <?php echo $number; ?>
                </span>
            </li>
        <?php else: ?>
            <?php foreach ($specialty_clinics as $clinic): ?>
                <?php
                if ($number == 1)
                    $clinic_numbers[$clinic->getId()] = '';
                else
                    $clinic_numbers[$clinic->getId()] = $number - 1;
                ?>
                <li data-id="<?php echo $clinic->getId(); ?>" class="loc-<?php echo $number ?> clinic-<?php echo $clinic->getId(); ?>-button ">
                    <span>
                        <?php echo $number; ?>
                    </span>
                </li>
                <?php $number++; ?>
            <?php endforeach; ?>
        <?php endif ?>
    </ul>


    <div class="box">
        <?php $section_number = 1; ?>
        <?php if (isset($clinic_id)): ?>
            <div class="section section-<?php echo $section_number; ?> visible flo">
                <div class="row">
                    <p class="name-center col-lg-12">
                        <strong>
                            <a <?php if (!Acc::isAuthed()): ?> href="javascript:void(0);" data-url="<?php echo ClinicPageLinkViewHelper::getLink($doctor_clinic); ?>" <?php else: ?> href=" <?php echo ClinicPageLinkViewHelper::getLink($doctor_clinic); ?>" <?php endif; ?>>
                                <?php echo $doctor_clinic->name; ?>
                            </a>
                        </strong>
                    </p>
                    <div class="location col-lg-6 col-md-6">
                        <!--<div class="trigger">
                        <?php echo $section_number; ?>
                        </div>-->
                        <p class="name-inf">
                            <?php if ($doctor_clinic->metro_station): ?>
                                <?php if ($doctor_clinic->metro_station->metro_branch): ?>
                                    <?php echo MetroBranchIconViewHelper::getImage($doctor_clinic->metro_station->metro_branch) ?>
                                <?php endif; ?>
                                <?php echo $doctor_clinic->metro_station->name; ?> <br  />
                            <?php endif; ?>

                            <?php $str_len = (isset($is_big_card) && $is_big_card) ? 60 : 30; ?>
                            <?php echo StringHelper::trimText($doctor_clinic->address, $str_len); ?>
                        </p>
                    </div>
                    <div class="price-inf col-lg-6 col-md-6">
                        <?php if (isset($specialty_id) && $specialty_id && isset($purpose_of_visit_id) && $purpose_of_visit_id): ?>
                            <?php $purpose_name = StringHelper::getPurposeOfVisitNameByPurposeOfVisitId($purpose_of_visit_id); ?>
                            <?php if ($purpose_name == 'Первичный прием'): ?>
                                <?php $visit_price = $doctor->getFirstVisitPrice($clinic->getId(), $specialty_id, $purpose_of_visit_id); ?>
                            <?php else: ?>
                                <?php $visit_price = $doctor->getSecondVisitPrice($clinic->getId(), $specialty_id, $purpose_of_visit_id); ?>
                            <?php endif; ?>

                            <?php if ($visit_price === '0'): ?>
                                <?php $visit_price = 'Бесплатно'; ?>
                            <?php elseif ($visit_price): ?>
                                <?php $visit_price = $visit_price . ' руб.'; ?>
                            <?php else: ?>
                                <?php $visit_price = ''; ?>
                            <?php endif; ?>

                            <?php if (intval($visit_price) > 0) { ?>
                                <p>
                                    <?php echo ($purpose_name) ? StringHelper::trimText($purpose_name, 40) : ''; ?>
                                    <strong><?php echo $visit_price; ?> </strong>
                                </p>
                            <?php } ?>
                        <?php else: ?>
                            <?php
                            if (isset($doctor->first_visit_price) && $doctor->first_visit_price > 0)
                                $first_visit_price = $doctor->first_visit_price;
                            else
                                $first_visit_price = $doctor->getFirstVisitPrice($clinic->getId(), $specialtyIDForDoctorCard);

                            if (isset($doctor->second_visit_price) && $doctor->second_visit_price > 0)
                                $second_visit_price = $doctor->second_visit_price;
                            else
                                $second_visit_price = $doctor->getSecondVisitPrice($clinic->getId(), $specialtyIDForDoctorCard);

                            if ($first_visit_price === '0')
                                $first_visit_price = ' Бесплатно';
                            elseif ($first_visit_price && !$doctor->min_price)
                                $first_visit_price = ': ' . $first_visit_price . ' руб.';
                            elseif ($first_visit_price && $doctor->min_price)
                                $first_visit_price = ': от ' . $first_visit_price . ' руб.';
                            else
                                $first_visit_price = '';

                            if ($second_visit_price === '0')
                                $second_visit_price = ' Бесплатно';
                            elseif ($second_visit_price && !$doctor->min_price)
                                $second_visit_price = ': ' . $second_visit_price . ' руб.';
                            elseif ($second_visit_price && $doctor->min_price)
                                $second_visit_price = ': от ' . $second_visit_price . ' руб.';
                            else
                                $second_visit_price = '';
                            ?>

                            <?php if ($first_visit_price) { ?>
                                <p>Первый визит<strong><?php echo $first_visit_price; ?></strong></p>
                            <?php } ?>
                            <?php if ($second_visit_price) { ?>
                                <p>Повторный визит<strong><?php echo $second_visit_price; ?></strong></p>
                            <?php } ?>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!$notWorkingWithDr) { ?>

                    <?php /* ?>
                      <?php if ($specialty_id): ?>
                      <?php $doctor_clinic_specialties = $doctor->getSuggestedSpecialtiesListBySpecialtyIdAndClinicId($specialty_id, $clinic->getId()); ?>
                      <?php else: ?>
                      <?php $doctor_clinic_specialties = $doctor->getSpecialtiesByClinicId($clinic->getId()); ?>
                      <?php endif; ?>
                      <?php $existing_schedule = $doctor->checkExistingDoctorScheduleByClinicIdAndClinicSpecialties($clinic->getId(), $doctor_clinic_specialties); ?>
                      <?php */ ?>

                    <?php $doctor_clinic_specialties = $doctor->getSpecialtiesByClinicId($clinic->getId()); ?>
                    <?php $existing_schedule = 1; ?>

                    <div class="location-box_schedule">
                        <ul class="tabs_schedule">

                        </ul>
                        <div class="box">
                            <?php foreach ($doctor_clinic_specialties as $specialty): ?>
                                <div class="section-in visible flo">
                                    <div class="schedule-extended <?php if (!$existing_schedule) { ?>rounded-single-schedule<?php } ?> schedule-clinic-<?= $doctor_clinic->getId() ?>">
                                        <ul class="schedule flo">

                                            <?php $monday = date("d.m.Y", strtotime("last Monday")); ?>
                                            <?php $days_count = ($week_schedule) ? 7 : 14; ?>

                                            <?php for ($day = 1; $day <= $days_count; $day++): ?>
                                                <?php $cur_day_time = strtotime($monday) + 86400 * ($day - 1); ?> <!-- текущий день недели -->

                                                <?php if ($existing_schedule): ?>
                                                    <?php $doctor_schedule = ModelManagerFactory::getByName('doctor_schedule')->getOneCurrentByDoctorIdAndClinicIdAndSpecialtyId($doctor->getId(), $doctor_clinic->getId(), $specialty->getId()); ?>

                                                    <?php if ($doctor_schedule): ?>
                                                        <?php $work_time = $doctor_schedule->getDoctorWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                    <?php else: ?>
                                                        <?php $work_time = $clinic->getClinicWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                    <?php endif; ?>
                                                    <li class="day active schedule-tim <?php echo $work_time ? 'day-var' : ''; ?>"><i><?php echo DayViewHelper::shortDay(date($cur_day_time)); ?></i>
                                                        <?php if ($work_time): ?>
                                                            <a onclick="
                                                                    var block = new RecordToTheDoctorBlockController(<?php echo $doctor->getId(); ?>, $(this));
                                                                    block.action_for_counters = 'day';
                                                                    block.init();
                                                               " href="#record-to-the-doctor-popup-<?php echo $doctor->getId(); ?>" class="record-day-pick" <?php if (!Acc::isAuthed()) { ?>data-url="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>"<?php } ?>>
                                                                   <?php echo $work_time; ?>
                                                            </a>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php else: ?>
                                                    <?php $work_time = $clinic->getClinicWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                    <li class="day active schedule-tim <?php echo $work_time ? 'day-var' : ''; ?>"><i><?php echo DayViewHelper::shortDay(date($cur_day_time)); ?></i>
                                                        <?php if ($work_time): ?>
                                                            <a onclick="
                                                                    var block = new RecordToTheDoctorBlockController(<?php echo $doctor->getId(); ?>, $(this));
                                                                    block.action_for_counters = 'day';
                                                                    block.init();
                                                               " href="#record-to-the-doctor-popup-<?php echo $doctor->getId(); ?>" class="record-day-pick" <?php if (!Acc::isAuthed()) { ?>data-url="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>"<?php } ?>>
                                                                   <?php echo $work_time; ?>
                                                            </a>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endif ?>
                                            <?php endfor; ?>
                                        </ul>
                                        <?php if (!$week_schedule): ?>
                                            <a class="prev-nav" href="javascript:void(0)"></a>
                                            <a class="next-nav" href="javascript:void(0)"></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>

                <?php } ?>
            </div>
        <?php else: ?>

            <?php foreach ($specialty_clinics as $clinic): ?>
                <div class="section section- <?php echo $section_number; ?> visible flo">
                    <div class="row">
                        <p class="name-center col-lg-12">
                            <strong>
                                <a href=" <?php echo ClinicPageLinkViewHelper::getLink($clinic); ?>">

                                    <?php echo $clinic->name; ?>
                                </a></strong>
                        </p>
                        <div class="location col-lg-7">

                            <?php if ($is_seo_page && $page_type != 'doctor') { ?>
                                <p class="name-inf" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                    <meta content="Клиника" itemprop="affiliation">

                                    <?php if ($clinic->metro_station) { ?>
                                        <?php if ($clinic->metro_station->metro_branch) { ?>
                                            <?php echo MetroBranchIconViewHelper::getImage($clinic->metro_station->metro_branch) ?>
                                        <?php } ?>
                                        <?php echo $clinic->metro_station->name; ?> <br  />
                                    <?php } ?>

                                    <?php $str_len = (isset($is_big_card) && $is_big_card) ? 60 : 30; ?>

                                    <span itemprop="street-address">
                                        <?php echo StringHelper::trimText($clinic->address, $str_len); ?>
                                    </span>
                                </p>
                            <?php } else { ?>
                                <p class="name-inf">
                                    <?php if ($clinic->metro_station) { ?>
                                        <?php if ($clinic->metro_station->metro_branch) { ?>
                                            <?php echo MetroBranchIconViewHelper::getImage($clinic->metro_station->metro_branch) ?>
                                        <?php } ?>
                                        <?php echo $clinic->metro_station->name; ?> <br  />
                                    <?php } ?>

                                    <?php $str_len = (isset($is_big_card) && $is_big_card) ? 60 : 30; ?>

                                    <?php echo StringHelper::trimText($clinic->address, $str_len); ?>
                                </p>
                            <?php } ?>

                        </div>
                        <div class="price-inf col-lg-5">
                            <?php if (isset($purpose_of_visit_id) && $purpose_of_visit_id): ?>
                                <?php $purpose_name = StringHelper::getPurposeOfVisitNameByPurposeOfVisitId($purpose_of_visit_id); ?>
                                <?php if ($purpose_name == 'Первичный прием'): ?>
                                    <?php $visit_price = $doctor->getFirstVisitPriceByClinicId($clinic->getId(), $specialty_id, $purpose_of_visit_id); ?>
                                <?php else: ?>
                                    <?php $visit_price = $doctor->getSecondVisitPriceByClinicId($clinic->getId(), $specialty_id, $purpose_of_visit_id); ?>
                                <?php endif; ?>

                                <?php if ($visit_price === '0'): ?>
                                    <?php $visit_price = 'Бесплатно'; ?>
                                <?php elseif ($visit_price): ?>
                                    <?php $visit_price = $visit_price . ' руб.'; ?>
                                <?php else: ?>
                                    <?php $visit_price = ''; ?>
                                <?php endif; ?>

                                <p>
                                    <?php echo ($purpose_name) ? StringHelper::trimText($purpose_name, 40) : ''; ?>
                                    <strong><?php echo $visit_price; ?> </strong>
                                </p>
                            <?php else: ?>
                                <?php
                                if (isset($doctor->first_visit_price) && $doctor->first_visit_price > 0)
                                    $first_visit_price = $doctor->first_visit_price;
                                else
                                    $first_visit_price = $doctor->getFirstVisitPrice($clinic->getId(), $specialtyIDForDoctorCard);

                                if (isset($doctor->second_visit_price) && $doctor->second_visit_price > 0)
                                    $second_visit_price = $doctor->second_visit_price;
                                else
                                    $second_visit_price = $doctor->getSecondVisitPrice($clinic->getId(), $specialtyIDForDoctorCard);

                                if ($first_visit_price === '0')
                                    $first_visit_price = ' Бесплатно';
                                elseif ($first_visit_price && !$doctor->min_price)
                                    $first_visit_price = ': ' . $first_visit_price . ' руб.';
                                elseif ($first_visit_price && $doctor->min_price)
                                    $first_visit_price = ': от ' . $first_visit_price . ' руб.';
                                else
                                    $first_visit_price = '';

                                if ($second_visit_price === '0')
                                    $second_visit_price = ' Бесплатно';
                                elseif ($second_visit_price && !$doctor->min_price)
                                    $second_visit_price = ': ' . $second_visit_price . ' руб.';
                                elseif ($second_visit_price && $doctor->min_price)
                                    $second_visit_price = ': от ' . $second_visit_price . ' руб.';
                                else
                                    $second_visit_price = '';
                                ?>
                                <?php if ($first_visit_price) { ?>
                                    <p>Первый визит<strong><?php echo $first_visit_price; ?></strong></p>
                                <?php } ?>
                                <?php if ($second_visit_price) { ?>
                                    <p>Повторный визит<strong><?php echo $second_visit_price; ?></strong></p>
                                <?php } ?>
                            <?php endif; ?>
                        </div>
                    </div>



                    <?php if (!$notWorkingWithDr) { ?>

                        <?php /* ?>
                          <?php if ($specialty_id): ?>
                          <?php $doctor_clinic_specialties = $doctor->getSuggestedSpecialtiesListBySpecialtyIdAndClinicId($specialty_id, $clinic->getId()); ?>
                          <?php else: ?>
                          <?php $doctor_clinic_specialties = $doctor->getSpecialtiesByClinicId($clinic->getId()); ?>
                          <?php endif; ?>
                          <?php $existing_schedule = $doctor->checkExistingDoctorScheduleByClinicIdAndClinicSpecialties($clinic->getId(), $doctor_clinic_specialties); ?>
                          <?php */ ?>

                        <?php $doctor_clinic_specialties = $doctor->getSpecialtiesByClinicId($clinic->getId()); ?>
                        <?php $existing_schedule = 1; ?>

                        <div class="location-box_schedule">
                            <ul class="tabs_schedule">

                            </ul>
                            <div class="box">
                                <?php foreach ($doctor_clinic_specialties as $specialty): ?>
                                    <div class="section-in visible flo">
                                        <div class="schedule-extended schedule-clinic-<?= $clinic->getId() ?> <?php if ($search_page || !$existing_schedule) { ?>rounded-single-schedule<?php } ?>">
                                            <ul class="schedule flo">

                                                <?php $monday = date("d.m.Y", strtotime("last Monday")); ?>
                                                <?php $days_count = ($week_schedule) ? 7 : 14; ?>

                                                <?php for ($day = 1; $day <= $days_count; $day++): ?>
                                                    <?php $cur_day_time = strtotime($monday) + 86400 * ($day - 1); ?> <!-- текущий день недели -->
                                                    <?php if ($existing_schedule): ?>
                                                        <?php $doctor_schedule = ModelManagerFactory::getByName('doctor_schedule')->getOneCurrentByDoctorIdAndClinicIdAndSpecialtyId($doctor->getId(), $clinic->getId(), $specialty->getId()); ?>

                                                        <?php if ($doctor_schedule): ?>
                                                            <?php $work_time = $doctor_schedule->getDoctorWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                        <?php else: ?>
                                                            <?php $work_time = $clinic->getClinicWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                        <?php endif; ?>
                                                        <li class="day active schedule-tim <?php echo $work_time ? 'day-var' : ''; ?>"><i><?php echo DayViewHelper::shortDay(date($cur_day_time)); ?></i>
                                                            <?php if ($work_time): ?>
                                                                <a onclick="
                                                                        var block = new RecordToTheDoctorBlockController(<?php echo $doctor->getId(); ?>, $(this));
                                                                        block.action_for_counters = 'day';
                                                                        block.init();
                                                                   " href="#record-to-the-doctor-popup-<?php echo $doctor->getId(); ?>" class="record-day-pick" <?php if (!Acc::isAuthed()) { ?>data-url="<?php echo DoctorPageLinkViewHelper::getLink($doctor); ?>"<?php } ?>>
                                                                       <?php echo $work_time; ?>
                                                                </a>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php else: ?>
                                                        <?php $work_time = $clinic->getClinicWorkTimeByDate(date('Y-m-d', $cur_day_time)); ?>
                                                        <li class="day active schedule-tim <?php echo $work_time ? 'day-var' : ''; ?>"><i><?php echo DayViewHelper::shortDay(date($cur_day_time)); ?></i>
                                                            <?php if ($work_time) { ?>

                                                            <?php } ?>
                                                        </li>
                                                    <?php endif ?>
                                                <?php endfor; ?>
                                            </ul>
                                            <?php if (!$week_schedule): ?>
                                                <a class="prev-nav" href="javascript:void(0)"></a>
                                                <a class="next-nav" href="javascript:void(0)"></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>

                    <?php } ?>

                </div>
                <?php $section_number++; ?>
            <?php endforeach; ?>
        <?php endif ?>
    </div>
</div>

<?php if ($notWorkingWithDr) { ?>
    <div class="clearfix"></div>
    <div class="not-work-message">
        НЕ РАБОТАЕМ
    </div>

<?php } ?>
