<?php
/** @var SpecializationModel|null $specialization */
?>
<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">
    <li itemprop="itemListElement" itemscope
        itemtype="http://schema.org/ListItem">
        &nbsp;
        &nbsp;
        <a itemprop="item" href="/" class="breadcrumb__link">
            <span itemprop="name">Главная</span></a> -&nbsp;
        <meta itemprop="position" content="1" />
    </li>
    <?php if ($specialization) { ?>
        <li itemprop="itemListElement" itemscope
            itemtype="http://schema.org/ListItem">
            <a itemprop="item" href="/clinic" class="breadcrumb__link">
                <span itemprop="name">Клиники</span></a> -&nbsp;
            <meta itemprop="position" content="2" />
        </li>
        <li itemprop="itemListElement" itemscope
            itemtype="http://schema.org/ListItem">
            <span itemprop="item">
                <span itemprop="name"><?= htmlentities($specialization->name) ?></span>
            </span>
            <meta itemprop="position" content="3" />
        </li>
    <?php } else { ?>
        <li itemprop="itemListElement" itemscope
            itemtype="http://schema.org/ListItem">
            <span itemprop="item" href="/clinic">
                <span itemprop="name">Клиники</span></span>
            <meta itemprop="position" content="2" />
        </li>
    <?php } ?>
</ol>
