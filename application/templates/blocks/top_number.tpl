<?php
/**
 * @var MetroStationModel|StreetModel|RegionModel|DistrictModel|CityModel|null $address_object
 * @var CityModel|null $city
 * @var bool $doctors_page
 * @var bool $doctor_page
 * @var bool $is_seo_page
 * @var SpecialtyModel|null $specialty
 * @var ClinicModel|null $clinic
 */
?>
<?php if (isset($doctors_page) && $doctors_page):?>
    <div class="top_number refactor">
    <?php if (isset($is_seo_page) && $is_seo_page): ?>
        <?php echo SeoBreadcrumbsViewHelper::getViewForSpecialtyPages($specialty, $address_object, 0, array('tnInnerStyle')); ?>
    <?php endif; ?>
        <h1 class="number_left refactor">
            <?= SeoTextViewHelper::getTopNumberH1($specialty, $address_object) ?>
        </h1>
        <?= HelpPhoneNumberViewHelper::renderPhoneBlock(<<<HTML
            <div class="number_right refactor">
                <span class="refactor">Поможем подобрать врача</span><br />
                <span class="number_set refactor">{%phone%}</span><br />
                <span class="work_time">с 07 до 22:</span>
            </div>
HTML
); ?>
    </div>
<?php elseif (isset($doctor_page) && $doctor_page):?>
    <?php assert(isset($doctor) && $doctor instanceof DoctorModel) ?>
    <div class="top_number refactor default_top_number">
        <?php if (isset($is_seo_page) && $is_seo_page): ?>
            <?php echo SeoBreadcrumbsViewHelper::getViewForSpecialtyPages($specialty, $address_object); ?>
        <?php endif; ?>
        <div class="number_left refactor-number-left-styles">
            <?php
                $data_return = SiteUriHelper::returnToSearchForm();
                if (!empty($data_return['count']) && !empty($data_return['link'])) {
            ?>
                <a class="back-to-search doctor-top-page" href="<?php echo $data_return['link']; ?>"> Вернуться к результатам поиска <span>(<?php echo $data_return['count'] . ' ' . SpecialtyHelper::getDoctorWordForm($data_return['count']); ?>)</span></a>
            <?php } elseif(SiteUriHelper::previousPageIsClinicPage()) { ?>
                <a class="back-to-search doctor-top-page" href="<?php echo $_SERVER['HTTP_REFERER']; ?>"> Вернуться на страницу клиники </a>
            <?php } ?>
        </div>
        <?php foreach ($doctor->clinics as $i => $clinic) { ?>
            <div class="number_right refactor clinic-top-phone"
                 data-clinic-id="<?= $clinic->id ?>"
                 style="display: <?= $i ? 'none' : 'block' ?>;"
            >
            <?php if ($clinic->top_phone) { ?>
                <span class="refactor"><?= $clinic->top_phone_text ?></span><br />
                <span class="number_set refactor"><?= $clinic->top_phone ?></span><br />
            <?php } else { ?>
                <span class="refactor">Поможем подобрать специалиста с 07 до 22:</span><br />
                <span class="number_set refactor"><?= HelpPhoneNumberViewHelper::getPhoneNumber($city) ?></span><br />
            <?php } ?>
            </div>
        <?php } ?>

    </div>
<?php else:?>
    <?php
    $isShowNumber = empty($clinic) || $clinic && in_array($clinic->city_id, Application::config('global.available_city_ids'));
    $isShowNumber = $isShowNumber && empty($hidePhone);
    if ($isShowNumber) : // костыль для сокрытия номера телефона для городов
    ?>
    <div class="top_number">
        <?php if (empty($topPhoneText)) { ?>
            <div class="number_left">Есть вопросы? Не нашел нужного специалиста?</div>
        <?php } ?>
        <?php if (!empty($topPhoneTitle)) { ?>
            <div class="number_left"><?= $topPhoneTitle ?></div>
        <?php } ?>
        <?php
            if(!empty($topPhone)) {
                $phone = $topPhone;
            } else {
                $phone = HelpPhoneNumberViewHelper::getPhoneNumber($city);
            }
            if(!empty($phone)) {
        ?>

            <div class="number_right">
              <?php if (!empty($topPhoneText)) { ?>
                <?= $topPhoneText ?>
              <?php } else { ?>
                <span>Звони, мы поможем</span><br />
              <?php } ?>
              <span class="number_set"><?= $phone; ?></span><br />
              <?php if (!empty($topPhoneFooter)) { ?>
                <?= $topPhoneFooter ?><br />
              <?php } ?>
            </div>

        <?php
            }
        ?>
    </div>
    <?php endif; ?>
<?php endif;?>
