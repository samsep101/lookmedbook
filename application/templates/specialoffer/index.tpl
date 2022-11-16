<?php
?>
<div class="relative-right-owl"></div>
<h1>Хочу скидку на консультацию<br />к специалисту в Москве:</h1>
<div class="relative-doctor-select">Выберите специалиста</div>
<form id="subscribe-form" method="post">
    <div class="doctor-selector">
        <div class="row">
            <div class="col-xs-4">
                <label class="doctor-selector__item">
                    <input type="radio" class="doctor-selector__control" name="specialty" value="Уролог" />
                    <span class="doctor-selector__container">
                        <img src="/media/landing/images/urolog-portrait.png" />
                        <span class="doctor-selector__icon"></span>
                    </span>
                    <p>Уролог</p>
                </label>
            </div>
            <div class="col-xs-4">
                <label class="doctor-selector__item">
                    <input type="radio" class="doctor-selector__control" name="specialty" value="Венеролог" />
                    <span class="doctor-selector__container">
                        <img src="/media/landing/images/venerolog-portrait.png" />
                        <span class="doctor-selector__icon"></span>
                    </span>
                    <p>Венеролог</p>
                </label>
            </div>
            <div class="col-xs-4">
                <label class="doctor-selector__item">
                    <input type="radio" class="doctor-selector__control" name="specialty" value="Гинеколог" />
                    <span class="doctor-selector__container">
                        <img src="/media/landing/images/ginekolog-portrait.png" />
                        <span class="doctor-selector__icon"></span>
                    </span>
                    <p>Гинеколог</p>
                </label>
            </div>
        </div>
    </div>
    <h2>Как с Вами связаться?</h2>
    <div class="block-subscribe">
        <div class="block-subscribe__info js--form-info"><?= $formInfoContent ?></div>
        <input type="tel" name="phone" class="block-subscribe__input" placeholder="Номер телефона" />
        <button class="block-subscribe__button">Отправить</button>
    </div>
</form>
<div class="relative-phone-info">Анонимность гарантируем</div>
<div class="block-advantages">
    <div class="fixed-w">
        <div class="block-advantages__content row">
            <div class="col-xs-4">
                <img src="/media/landing/images/hospital.png" />
                <p class="block-advantages__text">Более<br />
                    <span class="font-64">1000</span><br />
                    клиник<br />в Москве
                </p>
            </div>
            <div class="col-xs-4">
                <img src="/media/landing/images/doctor.png" />
                <p class="block-advantages__text">Более<br />
                    <span class="font-64">10000</span><br />
                    квалифицированных<br /> врачей в Москве
                </p>
            </div>
            <div class="col-xs-4">
                <img src="/media/landing/images/examination.png" />
                <p class="block-advantages__text">Мы найдем<br />
                    для вас <br />
                    <span class="font-64">лучшего</span><br />
                    специалиста
                </p>
            </div>
        </div>
    </div>
</div>
<script src="/media/landing/js/specialoffer.js?v=<?= RELEASE_NUMBER ?>"></script>
