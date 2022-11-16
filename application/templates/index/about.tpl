<script type="text/javascript">
    $(document).ready(function() {
        <?php
            /**
            * @var CityManager $city_manager
            */
         ?>

        <?php $city_manager = ModelManagerFactory::getByName('city'); ?>
        <?php $city_id = $city_manager->getIdByName('Москва') ?>
        var controller = new AboutPageController(<?php echo $city_id; ?>);
        controller.init();
    })
</script>

<div class="inner-3 about_us flo">
    <h1>О проекте</h1>
    <h2><?php echo SITE_NAME; ?> – это бесплатный интернет-сервис, помогающий людям организовать свое здоровье.</h2>
</div>

<div class="about_us">
    <p class="line_p line_p-white">Наша миссия – помогать людям, предоставляя лучший сервис.</p>
</div>

<div class="inner-3 about_us flo">
    <div class="white_bl white_bl-l">
        <p>На нашем сервисе Вы всегда сможете найти:</p>
        <ul class="list">
            <li>Подходящую именно Вам <a href="/clinic">клинику</a> или <a href="/doctor">врача</a>;</li>
            <li>Записаться на прием online;</li>
            <li>Информацию о возможностях клиники и услугах;</li>
            <li>Информацию о компетенции врача и его опыте;</li>
            <li>Узнать, где можно сдать <a href="/analysis">анализы</a> именно в Вашем городе.</li>
        </ul>
    </div>

    <div class="owl">
        <p>Органайзер здоровья</p>
        <p class="bottom"><?php echo SITE_NAME; ?></p>
    </div>

    <div class="white_bl white_bl-r">
        <p>Мы подготовили информацию о каждом заболевании, и что важно:</p>
        <ul class="list">
            <li>Все тексты о <a href="/disease">заболеваниях</a> подготовлены врачами и имеют четкую структуру</li>
            <li>Мы адаптировали все медицинские термины и наши тексты понятны любому человеку;</li>
            <li>Мы описываем все возможные современные (российские и западные) варианты лечения и диагностики, а также рекомендуем, к каким специалистам стоит обратиться, если случилась беда.</li>
        </ul>
    </div>

    <p class="line_p">Все, что мы создаем на проекте, делается для людей, поэтому:</p>

    <ul class="list_info">
        <li>Вы всегда можете позвонить нам, и мы поможем подобрать <a href="/clinic">клинику</a> и нужную процедуру или просто ответить на интересующие вопросы.</li>
        <li>В личном кабинете сайта Вы можете отслеживать записи, получать уведомления и напоминания о процедурах и визитах к врачу.</li>
    </ul>
</div>

<div class="about_us">
    <p class="line_p line_p-white line_p2">Нам важно, чтобы Вы получили настоящий сервис и обратились к нам вновь!</p>
</div>

<div class="inner-3 about_us flo">
    <div class="contacts">
        <p class="h-txt">Контакты</p>
        <ul>
            <?= HelpPhoneNumberViewHelper::renderPhoneBlock(<<<HTML
                <li>
                    <p class="h-txt">Телефон:</p>
                    <p class="txt">{%phone%}</p>
                </li>
HTML
); ?>
            <li>
                <p class="h-txt">E-mail:</p>
                <p class="txt"><a href="mailto:info@lookmedbook.ru">info@lookmedbook.ru</a></p>
            </li>
        </ul>
    </div>
    <div class="address">
        <span class="index">Мы находимся по<br/> адресу:</span>
        <span class="txt"><?php echo JUR_ADDRESS_FULL; ?></span>
    </div>
</div>
