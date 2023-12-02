<?php $this->block('registry/blocks/moderate_status'); ?>

<div class="fields-block pick-a-pic flo">
    <p>Описание клиники</p>
    <div class="fields-block-inner white-inner">
        <?php $this->container = '#description-form'; ?>
        <?php $this->block('registry/blocks/moderate-form-js-controller');  ?>
        <div class="moderated-form" id="description-form">
            <p>Напишите краткое описание Вашей клиники. Интересные факты, Ваша специализация. Это поможет нашим пользователям выбрать именно Вас!</p>
            <div style="width: 590px; float: left;"><?php echo $view_processor->getView('about'); ?></div>
            <ul class="refinement">
                <li>! Структура описания:</li>
                <li>1. Укажите информацию о местоположении</li>
                <li>2. Укажите информацию о специализации</li>
                <li> ... </li>
            </ul>
          
            <?php echo ModerateCommentViewHelper::getView($entry_id, ModerateCommentTypeModel::CLINIC_DESCRIPTION, $model->revision_number); ?>
            <?php $this->block('registry/blocks/form-buttons'); ?>
        </div>
    </div>
</div>

