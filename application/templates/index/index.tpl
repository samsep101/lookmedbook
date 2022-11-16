<?php
	/**
	 * @var View $this
	 * @var bool $show_popup
	 * @var bool $show_popup_forgot_pass
	 * @var DoctorModel[] $doctors
	 * @var CityModel $city
	 * @var DistrictModel[] $districts
	 * @var SpecialtyModel $specialty
	 */
?>
<?php if (isset($_GET['email_is_confirmed'])) $show_popup = 1; else $show_popup = 0; ?>
<?php if (isset($_GET['is_change_password'])) $show_popup_forgot_pass = 1; else $show_popup_forgot_pass = 0; ?>
<script>
    $(document).on('content-ready', function(){
        var controller = new IndexPageController(<?php echo $show_popup;?>, <?php echo $show_popup_forgot_pass; ?>);
        controller.init();
        var header_controller = new HeaderController();
        header_controller.init();
        var disease_controller = new DiseaseSearchPageController('disease-search');
        disease_controller.init();
    });
</script>
<style>
    .dd-button{
        width: 100% !important;
        font-size: 15px !important;
    }
</style>
<div class="inner-home-main">
    <div class="center">
        <div class="left_search">
            <div class="search-form">
                <ul class="tabs flo">
                    <li class="active">
                        <span id="find_doctor_tab"><i></i>Найти врача</span>
                    </li>
                    <li>
                        <span id="find_clinic_tab"><i></i>Найти клинику</span>
                    </li>
                </ul>

                <div class="section visible">
                    <?php echo $this->show_title = false; ?>
                    <?php $this->home_page = 1; ?>
                    <?php $this->block('doctor/blocks/search-form'); ?>
                </div>
                <div class="section find-clinic search-form-main-page-container">
                    <?php $this->block('clinic/blocks/search-form'); ?>
                </div>
            </div>

            <div class="abs abs2">Выберите врача<br/>
                <small>или клинику</small></div>
            <div class="abs abs3">Выберите<span class="flo"></span> специализацию<br/>
                <small>и цель визита</small></div>
            <div class="abs abs4">Найди лучшего<br/>
                <small>специалиста</small></div>
    </div>
        <div class="right_owl">
            <p class="h-text">Online сервис записи к врачу и в клинику</p>
            <span class="abs abs1">
                <small>Пользуйтесь сайтом <?= SITE_NAME ?><br/>на любом устройстве!</small>
            </span>
        </div>
        <p class="clear_line"></p>
    </div>
</div>
<div class="footer-top-line"></div>

<div id="footer">

    <?php if(!empty($doctors)) { ?>
        <?php $this->doctors = $doctors; ?>
        <?php $this->block('/doctor/blocks/slider_cards_doctors'); ?>
    <?php } ?>

</div><!-- #footer -->
<?php if($city->getId() == 2) { ?>
<div class="about_dis">
    <div class="center">
        <p class="h-txt"><a href="/disease">Читайте о заболеваниях на понятном языке</a></p>
        <?php $this->block('index/_video_ad') ?>

        <div class="search-block search-block-mainpage flo">
            <form action="/search/results" method="GET">
                <input type="text" class="txt illness-search-input" autocomplete="off" name="query" placeholder="Введите название заболевания" />
                <input type="submit" class="btn-6 illness-search-submit" data-action-for-counters="disease" value="Искать" />
                <ul class="drop-menu">
    </ul>
            </form>
        </div>
    </div>
</div>
<?php } ?>
<?php if($city->getId() == 2) { ?>
    <div class="learn-more">
<?php } else { ?>
        <div class="learn-more not-disease-about">
            <div class="wave-separator"></div>
<?php } ?>
    <div class="learn-txt">
        <p class="h-txt">
            Вы представитель<span class="flo"></span> клиники или доктор?
        </p>
        <p class="l_txt">
            <?php echo SITE_NAME; ?> — это лучший способ для того,<br/>
            чтобы найти лучших пациентов и рассказать
            <span class="flo"></span>о себе.
            <br/><br/>
            С помощью нашего сервиса к вам придет гораздо<br/>
            больше пациентов!
        </p>
        <a href="/example">Узнать больше</a>
    </div>
    <div class="line-rgba"></div>
</div>
<div class="thisis">
    <div class="line-shadow">
    </div>
    <div class="thisiscenter">
        <h1><?php echo SITE_NAME; ?> - это online сервис записи к врачу и в клинику</h1>
        <ul class="list-about">
            <li>
                <img class="thisis-li1" src="/media/images/blank.png"/>
                <p>Наш сервис - это возможность
                    для человека получить всю
                    медицинскую информацию
                    в одном месте
                </p>
            </li>
            <li>
                <img class="thisis-li2" src="/media/images/blank.png"/>
                <p>Вы можете хранить историю своих визитов в личном кабинете, а также добавлять в избранное интересные Вам клиники и врачей
                </p>
            </li>
            <li>
                <img class="thisis-li3" src="/media/images/blank.png"/>
                <p>Вы можете получать информацию о заболеваниях на доступном  языке, понятном для обычных людей
                </p>
            </li>
            <li>
                <img class="thisis-li4" src="/media/images/blank.png"/>
                <p>Мы прилагаем все усилия, чтобы наши пациенты были здоровы и довольны. Нам всегда можно позвонить и задать любой вопрос, который волнует Вас
                </p>
            </li>
        </ul>
        <div class="h-txt h-txt-blue">
                <span>
                Пользуясь <?php echo SITE_NAME; ?>,<br/>
                Вы получаете настоящий сервис!</span>
            <a class="btn-1 btn-doctor" href="/doctor">Найти врача</a>
        </div>
    </div>
</div>
        <br>
<?php echo $this->block('index/_bottom_ad');?>
<?php echo $this->block('index/specialties_groups');?>







