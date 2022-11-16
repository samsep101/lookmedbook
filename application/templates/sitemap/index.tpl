<?php
/**
 * @var SpecialtyModel[]      $doctorSpecialties
 * @var SpecializationModel[] $clinicSpecializations
 */
?>
<div class="inner">
    <div class="search-block flo">
    </div>
    <div class="ilness-list flo">
        <ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">
            <li itemprop="itemListElement" itemscope
                itemtype="http://schema.org/ListItem">
                    <span itemprop="item">
                        <span itemprop="name"><h1>Карта сайта</h1></span></span>
                <meta itemprop="position" content="1"/>
            </li>
        </ol>
        <div class="sitemap">
            <ul class="list-group">
                <li>
                    <strong><a href="/">Главная</a></strong>
                </li>
                <li>
                    <strong>Все врачи</strong>

                    <ul class="list">
                        <?php foreach ($doctorSpecialties as $doctorSpecialty) : ?>
                            <li>
                                <a href="/sitemap/doctor/<?= $doctorSpecialty->id ?>"><?= StringHelper::upperCaseFirstSymbol($doctorSpecialty->name) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li>
                    <strong>Клиники</strong>
                    <ul class="list">
                        <?php foreach ($clinicSpecializations as $clinicSpecialization) : ?>
                            <li>
                                <a href="/sitemap/clinic/<?= $clinicSpecialization->id ?>"><?= StringHelper::upperCaseFirstSymbol($clinicSpecialization->name) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
