<?php
    /**
     * @var View $this
     * @var CityModel|null $city
     */
?>

<header class="header">
    <div class="inner flo">
        <a class="logo" href="<?php if($city->alias) { echo '/';} else echo SITE_URL.'/'; ?>">
            <img class="main-logo" src="/media/images/blank.png" alt=""/></a>

        <div class="landing-header-links">
            <a href="#how-to-doctor">Запись в клинику</a>
            <a href="#when-to-doctor">Когда обратиться?</a>
            <a href="#how-it-works">Как мы работаем?</a>
            <a href="#reviews">Отзывы</a>

            <?= HelpPhoneNumberViewHelper::renderPhoneBlock(<<<HTML
            <div class="phone-block phone-with-time">
                <span class=""> {%phone%}</span> </span>
                <span class="small-time">с 7:00 до 22:00 </span>
            </div>
HTML
); ?>
        </div>
    </div>
</header>