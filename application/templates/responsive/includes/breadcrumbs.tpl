<?php $bc = $this->breadcrumbs;
    if(!empty($bc)) : ?>
<div class="container">
    <ul class="breadcrumbs left-logo" itemscope itemtype="http://schema.org/BreadcrumbList">

        <?php foreach($bc as $i => $item) : ?>
        <li class="<?=$item[2]?>" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <?php if($item[1]) : ?>
            <a href="<?=$item[1]?>" itemprop="item" class="breadcrumb__link">
                <?php if($item[2] == 'home') : ?>
                    <i class="glyphicon glyphicon-home"></i>
                <?php endif; ?>
                <span itemprop="name"><?=$item[0]?></span>
                <i class="glyphicon glyphicon-chevron-right"></i>
            </a>
            <?php else : ?>
                <span itemprop="name"><?=$item[0]?></span>
            <?php endif; ?>
            <meta itemprop="position" content="<?=$i+1?>" />
        </li>
        <?php endforeach; ?>

    </ul>
</div>
<?php endif; ?>