<?php
    /*
    * @var CityModel|null $city
     */
?>
<div class="inner-404 flo">
<div class="oops">
    <h1 class="text-bold">Видимо что-то случилось...</h1>
</div>
    <img src="/media/images/blank.png" alt="404" id="logo-404"/>
    <div class="register">
        <h1 class="text-bold"><p>Мы не нашли страницу, которую Вы искали...</p>
            Но мы можем помочь:</h1>

        <a class="reg-linking-btn-404 btn-1" href="<?php if($city && $city->isUsed()) { echo '/doctor';} else echo SITE_URL.'/doctor'; ?>" data-action-for-counters="find-doctor" data-category-for-counters="find-doctor" data-url='/doctor'>
            Найти врача
        </a>
        <a class="reg-linking-btn-404 btn-1" href="<?php if($city && $city->isUsed()) { echo '/clinic';} else echo SITE_URL.'/clinic'; ?>" data-action-for-counters="find-clinic" data-category-for-counters="find-clinic" data-url='/clinic'>
            Найти клинику
        </a>
    </div>
</div>