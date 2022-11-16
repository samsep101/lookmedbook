<?php
    /**
     * @var int $counter_number
     * @var int $counter_page_index
     * @var SpecialtyModel $specialty
     */
?>
<script type="text/javascript">
    $(document).ready(function(){
        var landing_page_controller = new LandingPageController();
        landing_page_controller.counter_number = <?php echo $counter_number;?>;
        landing_page_controller.specialty_alias = '<?php echo $specialty->alias;?>';
        landing_page_controller.counter_page_index = '<?php echo $counter_page_index;?>';
        landing_page_controller.init();
    });
</script>

<div class="checkbox-header">
    <div class="landing-center">
        <div class="landing-h-text header-about">
            <?php echo SITE_NAME; ?> - удобный сервис записи к врачу и в клинику
        </div>
        <div class="landing-checkboxes">
            <div class="landing-checkbox">
                <span>Бесплатно оказываем </span>
                <span>услуги по подбору врачей и</span>
                <span>клиник</span>
            </div>
            <div class="landing-checkbox next">
                <span>Сотрудничаем с передовыми </span>
                <span>клиниками Москвы и  </span>
                <span>Московской области </span>
            </div>
            <div class="landing-checkbox next">
                <span>Мы эксперты в области</span>
                <span>оказания медицинских</span>
                <span>услуг</span>
            </div>
        </div>
    </div>
</div>

<div class="call-order-block">
    <div class="landing-center">
        <a class="landing-h-text" id="how-to-doctor">Мы нашли для Вас в Москве 900 врачей <span class="bolder"><?php if (isset($specialty)) echo $specialty->lp_genitive_name_plural;?></span></a>
        <div class="order-tooltip">
            <ul>
                <li>
                    <i>
                        <img width="83" height="77" alt="" src="/media/images/landing/doctor-tooltip.png">
                    </i>
                    <span>Запишем Вас на прием к<br>лучшему специалисту </span>
                </li>
                <li>
                    <i>
                        <img alt="" src="/media/images/landing/map-tooltip.png">
                    </i>
                    <span>Подберем клинику<br>рядом с домом в удобное для<br>Вас время</span>
                </li>
                <li>
                    <i>
                        <img width="61" height="58" alt="" src="/media/images/landing/wallet-tooltip.png">
                    </i>
                    <span>Проконсультируем<br>относительно стоимости<br>приема врача</span>
                </li>
            </ul>
        </div>
        <div class="order-form">
            <div class="order-form-inner">
                <div class="order-form-inner-header">
                    Запись к <span class="bolder"><?php if (isset($specialty)) echo $specialty->lp_dative_name;?></span><br> онлайн в 1 клик
                </div>
                <div class="order-form-inner-italic">
                    Подберем лучшего специалиста в<br> удобное для вас время
                </div>
                <input type="text" placeholder="Ваше имя*" name="first_name" data-action="Name">
                <input type="text" placeholder="Ваш телефон*" name="phone_number" data-action="Phone">
                <input type="hidden" name="specialty_id" value="<?php if (isset($specialty) && $specialty) echo $specialty->getId();?>">
                <textarea placeholder="Желаемое место и время  приема" name="comment" data-action="Comment"></textarea>
                <div class="ax_paragraph">
                    <a class="btn-1 btn-doctor order-form-submit" href="javascript:void(0);" data-action="SimpleBookFinishButton">Записаться на прием к <?php if (isset($specialty)) echo $specialty->lp_dative_name;?></a>
                </div>
                <img class="left-arrow" alt="" src="/media/images/landing/arrow-left.png">
            </div>
            <div class="underorder"></div>
        </div>
    </div>
</div>
<div class="long-grey-block">
    <div class="landing-center">
        <div class="its-doctor-time">
            <a class="landing-h-text" id="when-to-doctor"> Когда следует обратиться к <?php if (isset($specialty)) echo $specialty->lp_dative_name;?>? </a>
            <div class="its-doctor-time-text">
                <?php $this->block('landing/blocks/attention'); ?>
            </div>
            <img class="its-doctor-time-image" width="57" height="54" alt="" src="/media/images/landing/attention.png">
        </div>
    </div>

    <div class="landing-center symptoms-block <?php if (isset($specialty) && $specialty->getId() == SpecialtyModel::OTOLARYNGOLOGIST) {?>short-symptom-list<?php }?>">
        <div class="white-symptom-block">
            <div class="white-symptom-header">
                Записаться на прием к <span class="bolder"><?php if (isset($specialty)) echo $specialty->lp_dative_name;?></span> необходимо при появлении любого из следующих симптомов:
            </div>
            <div class="double-list">
                <?php $this->block('landing/blocks/symptoms'); ?>
            </div>
            <div class="white-symptom-footer">
                <span class="white-symptom-footer-text white-symptom-footer-part">Запись к <?php if (isset($specialty)) echo $specialty->lp_dative_name;?> по телефону</span>
                <img class="white-symptom-footer-phone-img white-symptom-footer-part" width="41" height="40" alt="" src="/media/images/landing/phone.png">
                <!--<span class="white-symptom-footer-phone">8 (495) 787-39-53</span>-->
                <div class="white-symptom-footer-phone white-symptom-footer-part phone-with-time">
                    <span class="big-phone">8 (495) 215-09-07 </span>
                    <span class="small-time">с 9:00 до 21:00 </span>
                </div>
            </div>
        </div>
    </div>

    <div class="landing-center">
        <a class="landing-h-text how-it-works" id="how-it-works"> Как мы работаем? </a>
        <div class="bubbles-block">
            <div class="bubble">
                <img class="" width="85" height="82" alt="" src="/media/images/landing/bubble-in-1.png">
                <div class="bubble-text">
                    <span class="simple-text-span">Вы оставляете заявку на</span>
                    <span class="simple-text-span">запись к врачу на сайте</span>
                    <span class="simple-text-span">или по телефону</span>
                </div>
            </div>
            <div class="simple-arrow"><img class="bubble-arrow bubble-arrow-down" alt="" src="/media/images/landing/arrow-down.png"></div>
            <div class="bubble">
                <img class="" width="89" height="88" alt="" src="/media/images/landing/bubble-in-2.png">
                <div class="bubble-text">
                    <span class="simple-text-span">Мы подбираем лучшего</span>
                    <span class="simple-text-span">специалиста, учитывая</span>
                    <span class="simple-text-span">Ваши пожелания</span>
                </div>
            </div>
            <div class="simple-arrow"><img class="bubble-arrow bubble-arrow-up" alt="" src="/media/images/landing/arrow-up.png"></div>
            <div class="bubble">
                <img class="" width="78" height="73" alt="" src="/media/images/landing/bubble-in-3.png">
                <div class="bubble-text">
                    <span class="simple-text-span">Вы получаете</span>
                    <span class="simple-text-span">подтверждение о записи в</span>
                    <span class="simple-text-span">виде звонка от клиники</span>
                </div>
            </div>
        </div>
        <div class="landing-heading-line">
            <div class="button-container">
                <a class="btn-1 btn-doctor order-link order-link-center" href="#how-to-doctor" data-action="SimpleBookStartButton">Записаться на прием к <?php if (isset($specialty)) echo $specialty->lp_dative_name;?></a>
            </div>
        </div>
    </div>
</div>
<div class="white-review-block">
    <div class="landing-center">
        <a class="landing-h-text" id="reviews"> Отзывы о  <?php echo SITE_NAME; ?> </a>
        <div class="reviews-block">
            <div class="single-review">
                <div class="author">
                    <img class="" width="112" height="116" alt="" src="/media/images/landing/review-man.png">
                    <span class="">Александр Владимиров</span>
                </div>
                <div class="review-text">
                    <img class="review-triangle" alt="" src="/media/images/landing/review-triangle.png">
                    Оптимальный сервис для делового человека. Все очень быстро и просто. Сотрудники колл-центра чуткие и внимательные, а главное – терпеливые. И врача они ищут не просто хорошего, а именно для тебя.<br><br>
                    Раньше приходилось обзванивать множество центров, чтобы просто попасть на прием к специалисту в удобное время. С <?php echo SITE_NAME; ?> это дело пяти минут. Теперь, когда надо к врачу, просто захожу на <?php echo SITE_NAME; ?> – и вопрос решен. Всем советую!
                </div>
            </div>
            <div class="single-review">
                <div class="author">
                    <img class="" width="112" height="116" alt="" src="/media/images/landing/user_female.png">
                    <span class="">Валентина  Горькова</span>
                </div>
                <div class="review-text">
                    <img class="review-triangle" alt="" src="/media/images/landing/review-triangle.png">
                    Мои друзья и я теперь знаем: если надо к врачу, то только через <?php echo SITE_NAME; ?>. Не раз пробовали и убедились, что это, во-первых, экономия времени, во-вторых - нервов. А, в-третьих, гарантия, что попадешь к тому специалисту, к которому хотел попасть.<br><br>
                    Записаться проще простого, и специалисты колл-центра всегда выслушают, помогут с выбором врача. Здорово, что наконец-то появился такой нужный проект ДЛЯ ЛЮДЕЙ!
                </div>
            </div>
        </div>
    </div>
</div>