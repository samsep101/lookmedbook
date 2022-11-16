<div class="container">
    <div class="row show_cards">
        <div class="col-lg-12 more_patient">
            <h2>Больше пациентов с <?php echo SITE_NAME; ?></h2>
            <p class="txt">
                Ежедневно 10 миллионов человек<br>
                ищут медицинскую помощь в Интернете.
                <br><br>
                Каждый третий готов записаться on-line на прием к врачу.<br>
                И это ваши пациенты.
            </p>
        </div>
        <div class="col-lg-12 show_cards_line"></div>
        <div class="col-lg-12 need_patient">
            <h2>Каждому врачу нужен свой пациент</h2>
            <p class="txt">
                Мы помогаем клиникам предоставить наиболее интересную
                и необходимую информацию для пациентов.
                <br><br>
                При этом мы помогаем пациентам не пропустить информацию.
            </p>
        </div>
        <div class="col-lg-12 show-page">
            <div class="col-lg-6 column">
                <p class="blue-txt">
                    Павел ищет клинику, в которой он сможет получить<br>
                    консультацию сразу у нескольких специалистов.
                    <br><br>
                    На <?php echo SITE_NAME; ?> мы показываем направления<br>
                    и специалистов клиники.
                </p>
            </div>
            <div class="col-lg-6 column">
                <p class="blue-txt">
                    У Маши ребенку около 1 года. Она ищет педиатра, у которого<br>
                    есть свои дети. Такому врачу Маша доверяет больше.
                    <br><br>
                    На <?php echo SITE_NAME; ?> мы предоставляем не только общую<br>
                    информацию, но и ту, которая вызовет больший интерес<br>
                    и доверие у пациента.
                </p>
            </div>
            <div class="clear-line"></div>
            <div class="column">
                <img src="/media/images/home_page/show-page-1.png" style="margin-top: 13px;">
            </div>
            <div class="column">
                <img src="/media/images/home_page/show-page-2.png">
            </div>
            <div class="clear-line"></div>
            <div class="column">
                <!--<a href="/clinic/LookMedBook-primer-zapolneniya">Посмотреть пример страницы "О клинике"</a>-->
                <a href="/clinic">Посмотреть страницы "О клинике"</a>
            </div>
            <div class="column">
                <!--<a href="/doctor/ivanovanm">Посмотреть пример страницы "О враче"</a>-->
                <a href="/doctor">Посмотреть страницы "О враче"</a>
            </div>
        </div>
        <div class="col-lg-12 show_cards_line"></div>
        <div class="col-lg-12 online_patient">
            <h2>On-line запись</h2>
            <p class="txt">
                Наша on-line регистратура записывает на прием в вашу клинику<br>
                без выходных, перерывов на обед и даже ночью
            </p>
            <img src="/media/images/home_page/check-day.png">
        </div>
        <div class="col-lg-12 show_cards_line"></div>
        <div class="col-lg-12 work_with_us">
            <h2>Как начать с нами работать?</h2>
            <p class="txt">
                Напишите нам на почту <a class="blue-txt" href="mailto:sales@lookmedbook.ru">sales@lookmedbook.ru</a>
                <br><br>
                или позвоните по телефону <span class="blue-txt"><?= HelpPhoneNumberViewHelper::getPhoneNumber() ?></span>
            </p>
        </div>
    </div>
    <div class="show_cards_line"></div>
    <div class="online_patient">
        <h2>On-line запись</h2>
        <p class="txt">
            Наша on-line регистратура записывает на прием в вашу клинику<br>
            без выходных, перерывов на обед и даже ночью
        </p>
        <img src="/media/images/home_page/check-day.png">
    </div>
    <div class="show_cards_line"></div>
    <div class="work_with_us">
        <h2>Как начать с нами работать?</h2>
        <p class="txt">
            Напишите нам на почту <a class="blue-txt" href="mailto:sales@lookmedbook.ru">sales@lookmedbook.ru</a>
            <br><br>
            <?= HelpPhoneNumberViewHelper::renderPhoneBlock('или позвоните по телефону <span class="blue-txt">{%phone%}</span>');
            ?>
        </p>
    </div>
</div>
