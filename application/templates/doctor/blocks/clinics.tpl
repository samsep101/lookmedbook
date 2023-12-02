<div class="location-box">
    <ul class="tabs flo">
        <?php
            $number = 1;
            $clinic_numbers = array();
         ?>

        <?php foreach($doctor->clinics as $clinic):?>
            <?php
                if ($number == 1)
                    $clinic_numbers[$clinic->getId()] = '';
                else
                    $clinic_numbers[$clinic->getId()] = $number-1;
            ?>
            <li data-id="<?php echo $clinic->getId();?>" class="loc-<?php echo $number ?> clinic-<?php echo $clinic->getId(); ?>-button">
                <span><?php echo $number; ?></span>
            </li>
        <?php $number++; ?>
        <?php endforeach; ?>
    </ul>
    <div class="box">
        <?php $section_number = 1; ?>
        <?php foreach($doctor->clinics as $clinic):?>
        <div class="section section-<?php echo $section_number; ?> visible flo">
            <p class="name-center"><strong><?php echo $clinic->name; ?></strong></p>
            <div class="location">
                <!--<div class="trigger">
                    <?php echo $section_number; ?>
                </div>-->
                <p class="name-inf">
                    <?php if ($clinic->metro_station): ?>
                        <?php if ($clinic->metro_station->metro_branch): ?>
                            <?php echo MetroBranchIconViewHelper::getImage($clinic->metro_station->metro_branch)?>
                        <?php endif; ?>
                    <?php echo $clinic->metro_station->name; ?> <br  />
                    <?php endif; ?>
                    <?php echo $clinic->address; ?>
                </p>
            </div>
            <div class="price-inf">

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

                <p>
                    <?php echo (!isset($record_flag)) ? '<strong>Первый визит</strong>' : 'Первый визит'; ?>
                    <?php echo (isset($record_flag)) ? '<span>'.$first_visit_price.'</span>' : $first_visit_price; ?>
                </p>
                <p>
                    <?php echo (!isset($record_flag)) ? '<strong>Повторный визит</strong>' : 'Повторный визит'; ?>
                    <?php echo (isset($record_flag)) ? '<span>'.$second_visit_price.'</span>' : $second_visit_price; ?>
                </p>
            </div>

            <div class="schedule-extended schedule-clinic-<?= $clinic->getId() ?> time-scroll">
                <p class="visit-note">Выберите время и дату приема</p>
                <ul class="shedule-var flo">
                    <?php $time = time(); ?>
                    <?php for ($i = 1; $i <= 30; $i++): ?>
                    <li class="dday dday-var active">
                        <div class="day" data-date="<?php echo date('Y-m-d', $time+($i-1)*86400); ?>">
                            <div>
                                <i <?php echo (date('D',$time + ($i -1)*86400) == "Sun" || date('D',$time + ($i -1)*86400) == "Sat") ? 'style="color:red;"' : '' ; ?>>
                                <?php echo (date($time + ($i -1)*86400) == date($time)) ? "Сегодня" : DayViewHelper::shortDay($time + ($i-1)*86400); ?>
                                </i>
                                <span <?php echo (date('D',$time + ($i -1)*86400) == "Sun" || date('D',$time + ($i -1)*86400) == "Sat") ? 'style="color:red;"' : '' ; ?>>
                                <?php echo DateViewHelper::date($time + ($i -1)*86400, 'day_and_month'); ?>
                                </span>
                            </div>
                        </div>
                    </li>
                    <?php endfor; ?>
                </ul>
                <a class="prev-nav" href="#"></a> <a class="next-nav" href="#"></a>
            </div>

            <?php
            	// @TODO Для вывода 1 расписания
            	$doctor_clinic_specialties = $doctor->getSpecialtiesByClinicId($clinic->getId());
            ?>

            <div class="time-scroll">
                <?php $specialty_counter = 0;?>
                <?php foreach ($doctor_clinic_specialties as $specialty):?>
	                <?php if ($specialty_counter == 0) {?>
	                <!--<p class="post"><?php echo $specialty->name; ?></p>-->
	                <div class="scroll-pane flo">
	                    <?php for($i = 1; $i <= 30; $i++): ?>
	                    <ul class="time clinic-<?php echo $section_number-1; ?> specialty-<?php echo $specialty_counter; ?> dday-var active day-<?php echo date('Y-m-d', $time+($i-1)*86400); ?>">
	                        <li class="unactive">
	                        </li>
	                    </ul>
	                    <?php endfor; ?>
	                </div>
	                <?php $specialty_counter++;?>
	                <?php }?>
                <?php endforeach?>
            </div>
        </div>
        <?php $section_number++;?>
        <?php endforeach; ?>
    </div>
</div>