<?php
/**
 * @var CityModel|null   $city
 * @var ClinicModel|null $clinic
 */
$isShowNumber = empty($clinic) || $clinic && in_array($clinic->city_id, Application::config('global.available_city_ids'));
$isShowNumber = $isShowNumber && empty($hidePhone);
if ($isShowNumber) { // костыль для сокрытия номера телефона для городов
    ?>
    <div class="header-number">
        <?php
        if (!empty($topPhone)) {
            $phone = $topPhone;
        } else {
            $phone = HelpPhoneNumberViewHelper::getPhoneNumber($city);
        }
        if (!empty($phone)) {
            if (!empty($topPhoneText)) {
                echo $topPhoneText;
            } else { ?>
                <span class="header-number__text">Нужна помощь? Звоните!</span><br/>
            <?php } ?>
            <span class="header-number__icon glyphicon glyphicon-earphone"></span>&nbsp;<span class="header-number__phone"><?= $phone; ?></span><br/>
            <?php
        }
        ?>
    </div>
    <?php
}
