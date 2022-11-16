<footer class="footer footer-landing <?php echo $addicted_class;?>">
    <div class="footer-top-blue-line"></div>
    <div class="inner">
        <section>
            <div class="ax_paragraph">
                <img src="/media/images/eyes_owl_line_tr.png">
            </div>
            <div class="ax_paragraph">
                <span><?php echo SITE_NAME; ?> - удобный сервис записи </span>
                <span>к врачу и в клинику </span>
            </div>
            <div class="ax_paragraph">

            </div>
            <div class="ax_paragraph">
                <span>&copy 2014 Все права защищены </span>
            </div>
        </section>
        <section class="next">
            <div class="ax_paragraph">
                <a class="btn-1 btn-doctor order-link order-link-bottom" href="#how-to-doctor" data-action="SimpleBookStartButton">Записаться на прием к <?php if (isset($specialty)) echo $specialty->lp_dative_name;?></a>
            </div>
            <div class="ax_paragraph">
                <span class="big-phone"><?= HelpPhoneNumberViewHelper::getPhoneNumber() ?> </span>
                <span class="small-time">с 7:00 до 22:00 </span>
            </div>
            <div class="ax_paragraph">
                <span class="mail-to"><a href="mailto:info@lookmedbook.ru" >info@lookmedbook.ru </a></span>
            </div>
        </section>
        <section class="next">
            <div class="ax_paragraph contacts-header">
                <span>Услуги по записи на прием в клиники </span>
                <span>оказывает: </span>
            </div>
            <div class="ax_paragraph last">
                <span>ООО "М-Софт Медикал Имаджинг" </span>
                <span>ИНН: 7728632135 </span>
                <span>КПП: 771701001 </span>
                <span>Юр. адрес: <?php echo JUR_ADDRESS_FULL; ?></span>
            </div>
        </section>
    </div>
</footer>