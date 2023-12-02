<?php
?>

<?php if ($this->service['description'] || $this->service_category['description'] != "") : ?>
    <div class="clearfix"></div>
    <h2><b><?= $this->service['name'] ?></b> назначается врачом специалистом, часто в комплексе с другими исследованиями.
        <br/>Рекомендуем Вам записаться на прием к врачу, чтобы определить необходимые для диагностики исследования.</h2>
    <br/>
<?php else : ?>
    <h2><b><?= $this->service['name'] ?></b> назначается врачом специалистом, часто в комплексе с другими исследованиями.</h2>
    <div class="clearfix"></div>
    <h2>Рекомендуем Вам записаться на прием к врачу, чтобы определить необходимые для диагностики исследования.</h2>
    <br/>
<?php endif; ?>

<div id="our-doctors">
    <div class="clinic-list row clinic-search-result">

        <?php $this->block('doctor/card_big_list') ?>

        <div class="clearfix"></div>
    </div>
</div>