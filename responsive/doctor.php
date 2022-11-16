<?php include 'common/header.php'; ?>


<div class="container">
    <ul class="breadcrumbs left-logo" itemscope itemtype="http://schema.org/BreadcrumbList">
        <li class="home" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a href="/" itemprop="item" class="breadcrumb__link"><i class="glyphicon glyphicon-home"></i></a>
            <span itemprop="name">Главная</span>
            <meta itemprop="position" content="1" />
        </li>
        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a href="/shop/catalog" itemprop="item" class="breadcrumb__link">
                <span itemprop="name">Лекарства</span>
            </a>
            <meta itemprop="position" content="2" />
        </li>
        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a href="/shop/catalog/lechenie-zabolevaniy-zhkt-i-pecheni" itemprop="item" class="breadcrumb__link">
                <span itemprop="name">Лечение заболеваний ЖКТ и печени</span>
            </a>
            <meta itemprop="position" content="3" />
        </li>
    </ul>
</div>


<?php include 'common/footer.php'; ?>