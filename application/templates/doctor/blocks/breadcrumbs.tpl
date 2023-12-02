<?php
/** @var SpecialtyModel|null $specialty */
?>
<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">
  <li itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem">
    <a itemprop="item" href="/" class="breadcrumb__link">
      <span itemprop="name">Главная</span></a> -&nbsp;
    <meta itemprop="position" content="1"/>
  </li>
    <?php if ($specialty) { ?>
      <li itemprop="itemListElement" itemscope
          itemtype="http://schema.org/ListItem">
        <a itemprop="item" href="/doctor" class="breadcrumb__link">
          <span itemprop="name">Врачи</span></a> -&nbsp;
        <meta itemprop="position" content="2"/>
      </li>
      <li>
            <span href="/doctor/<?= htmlentities($specialty->alias) ?>">
                <span><?= htmlentities($specialty->name) ?></span>
            </span>
      </li>
    <?php } else { ?>
      <li itemprop="itemListElement" itemscope
          itemtype="http://schema.org/ListItem">
            <span itemprop="item" href="/doctor">
                <span itemprop="name">Врачи</span></span>
        <meta itemprop="position" content="2"/>
      </li>
    <?php } ?>
</ol>
