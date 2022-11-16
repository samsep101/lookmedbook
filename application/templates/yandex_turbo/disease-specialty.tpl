<div>
    <h3>Что делать при <?= trim($disease->prepositional_name); ?>?</h3>
    <ol>
        <li>
            Выбрать подходящего врача
            <a href="<?= $specialty->specialtyUrl ?>"><?= $specialty->name; ?></a>
        </li>
        <li>Сдать анализы</li>
        <li>Получить от врача схему лечения</li>
        <li>Выполнить все рекомендации</li>
    </ol>

    <a href="<?= $specialty->specialtyUrl ?>">
        <?= "Найти врача {$specialty->genitive_name}" ?>
    </a>
</div>