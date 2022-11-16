<?php
    /**
     * @var View $this
     * @var ProductCategoryModel $product_category
     * @var ProductCategoryModel $parent_product_category
     * @var ProductCategoryModel $root_product_category
     * @var ProductCategoryModel[] $product_categories
     * @var ProductCategoryModel[] $parent_product_category_children
     * @var array $product_categories_ids
     * @var string $pattern
     * @var int $by_page
     * @var bool $is_leader
     * @var bool $show_total_count
     */

     $Articles_Viewer = new Articles_Viewer();
?>

<script type="text/javascript">
    $(document).ready(function() {
        var productSearchController = new ProductSearchController();
        productSearchController.product_category = <?php echo isset($product_category) ? $product_category->getId() : 0; ?>;
        productSearchController.is_leader = <?php echo isset($is_leader) ? $is_leader : 0; ?>;
        productSearchController.by_page = <?php echo isset($by_page) ? $by_page : 10; ?>;
        productSearchController.pattern = <?php echo isset($pattern) ? "'$pattern'" : "'*'"; ?>;
        productSearchController.product_itself = '';
        productSearchController.init();

        var carousel_controller = new CarouselController(1100);
        carousel_controller.init();
    });
</script>

<div class="container">
    <?php $this->pattern = isset($pattern) ? $pattern : "'*'"; ?>
    <?php $this->block('shop/blocks/live_search'); ?>

    <?php if(isset($product_category) && $product_category): ?>
        <?php $this->product_category = $product_category; ?>
        <?php $this->parent_product_category = $parent_product_category; ?>
        <ul class="way_line m0">
        <?php $this->block('shop/blocks/breadcrumbs'); ?>
        </ul>
    <?php else:  ?>
        <?php if(!isset($pattern) || !$pattern): ?>
            <h2 style="color: #818080">Каталог лекарств</h2>
        <?php else: ?>
            <hr class="separator_h"/>
        <?php endif; ?>
    <?php endif; ?>
    
        <?php if(isset($product_category) && $product_category): ?>
            <?php if(isset($parent_product_category) || $product_categories): ?>
                <div class="bg_gradient catalog_more_h flo">
            <?php endif; ?>
            <h3>
                <?php if(isset($parent_product_category) && $parent_product_category): ?>
                    <a class="category" href="<?php echo ProductCategoryLinkViewHelper::getLink($parent_product_category); ?>">
                        <?php echo $parent_product_category->name; ?>
                    </a>
                <?php elseif((isset($product_categories) && $product_categories)): ?>
                    <a class="category" href="<?php echo ProductCategoryLinkViewHelper::getLink($product_category); ?>">
                        <?php echo $product_category->name; ?>
                    </a>
                <?php endif; ?>
                <?php if(!isset($parent_product_category) && isset($product_categories) && $product_categories): ?>
                    <a class="back" href="/shop/catalog">к списку лекарств</a>
                <?php endif; ?>
                <?php if(isset($parent_product_category) && $parent_product_category && $product_category): ?>
                    <a class="back" href="<?php echo ProductCategoryLinkViewHelper::getLink($parent_product_category); ?>">
                        к списку категорий
                    </a>
                <?php endif; ?>
            </h3>
            <?php if(!isset($parent_product_category) && isset($product_categories) && $product_categories): ?>
                <ul class="catalog_more_ul">
                    <?php foreach($product_categories as $item): ?>
                        <li>
                            <a href="<?php echo ProductCategoryLinkViewHelper::getLink($item); ?>">
                                <?php echo $item->name; ?> (<?php echo (isset($show_total_count) && $show_total_count) ? $item->products_total_count : $item->products_count; ?>)
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if(isset($parent_product_category) && $parent_product_category && $product_category): ?>
                <ul class="catalog_more_ul">
                    <?php foreach($parent_product_category_children as $item): ?>
                        <li <?php echo ($item->getId() == $product_category->getId()) ? 'class="active"' : ''; ?>>
                            <a href="<?php echo ProductCategoryLinkViewHelper::getLink($item); ?>">
                                <?php echo $item->name; ?> (<?php echo (isset($show_total_count) && $show_total_count) ? $item->products_total_count : $item->products_count; ?>)
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if(isset($parent_product_category) || $product_categories): ?>
                </div>
            <?php endif; ?>

        <?php
        // подключение статей
        //debug($product_category);
        if(!empty($product_category) AND in_array($product_category->id, [
                1434,
                //1439, // Лечение ОРВИ и гриппа
                //1942, // Афобазол
                1943,
                1959,
            ])) : ?>
                <link rel="stylesheet" href="/media/css/product-article.css?rnd=<?= Articles_Viewer::RND?>" type="text/css">
                <script type="text/javascript" src="/media/js/articles-spoiler.js?rnd=<?= Articles_Viewer::RND?>"></script>
                <div class="shop-product-article catalog __container">
                    <?=$Articles_Viewer->showCatalogArticle($product_category->id)?>
                    <div class="shop-product-article-showmore"><a class="__showmore" data-switch="Свернуть статью" href="javascript:void(0)">Читать далее...</a></div>
                </div>
        <?php endif; ?>

            <?php if(isset($product_category) && $product_category): ?>
                    <h4>
                        <a class="category" href="<?php echo ProductCategoryLinkViewHelper::getLink($product_category); ?>">
                            <?php echo $product_category->name; ?>
                        </a>
                        <?php if(isset($product_category) && $product_category && !$product_categories && !$parent_product_category): ?>
                            <a class="back" href="/shop/catalog">к списку лекарств</a>
                        <?php endif; ?>
                    </h4>
                <?php endif; ?>

                <a class="load-next-page view-more" href="javascript:void(0);" data-page="1"><i class="icon-loader"></i></a>
                <?php if ($product_category->description): ?>
                    <div class="bg_gradient about_good">
                        <p class="h-txt"><?php echo $product_category->name; ?></p>
                        <p class="txt"><?php echo $product_category->description; ?></p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="bg_gradient catalog_list_container">
                    <?php if(!isset($pattern) && isset($root_product_category) && $root_product_category): ?>
                        <?php $this->product_category = $root_product_category; ?>
                        <?php $this->product_categories = $product_categories; ?>
                        <?php $this->block('shop/catalog/product_categories'); ?>
                    <?php else: ?>
                        <ul class="catalog_list" style="height: 55px">
                            <li class="search_pattern"><?php echo 'Результаты поиска по запросу ' .'"' .$pattern .'"'; ?></li>
                            <h3><a class="back" href="/shop/catalog">вернуться в каталог</a></h3>
                        </ul>
                    <?php endif; ?>
                </div>

                <?php if(!isset($pattern)): ?>
                    <h2 style="color: #818080">Популярные лекарства</h2>
                <?php endif; ?>
                <a class="load-next-page view-more" href="javascript:void(0);" data-page="1"><i class="icon-loader"></i></a>
            <?php endif; ?>
    </div>

    <?php /*
        <div class="block_right">
            <?php $this->block('shop/blocks/basket_info'); ?>
            <?php $this->block('shop/blocks/orders_phone'); ?>
            <?php $this->block('shop/blocks/orders'); ?>
            <?php $this->block('shop/blocks/payments'); ?>
            <?php $this->block('shop/blocks/choice'); ?>
        </div>
    */ ?>
    <?php /*
        <?php if(!isset($product_category)): ?>
            <div class="product-license-block">
                <p class="all-elements">
                    <a class="show-all" href="javascript:void(0)">Лицензия и реквизиты</a>
                </p>
                <div class="product-license-container">
                    <div class="line"></div>
                    <p class="license-text">
                        <span class="bigger">ООО «Аптечный сервис»</span><br/><br/>
                        Р/с 40702810800760003208 в ОАО "Московский кредитный банк" г. Москва<br/>
                        к/с 30101810300000000659,<br/>
                        БИК 044585659<br/>
                        ИНН/КПП:  7725719998 / 771401001<br/>
                        Юридический адрес: 127137, г.Москва, ул. Правды, д.24, стр.5<br/><br/>
                        Лицензия: № ФС-99-02-003264 от 15.08.2013 г.
                    </p>

                    <div class="connected-carousels">
                        <div class="stage">
                            <div class="carousel carousel-stage">
                                <ul>
                                    <?php $i = 1; ?>
                                    <?php while($i < 5): ?>
                                        <li>
                                            <a data-fancybox-type="iframe" href="/shop/catalog/getLicenseImages">
                                                <img src="/media/images/license_<?php echo $i .'.jpg'; ?>" alt="" style="width: 110px;"/>
                                            </a>
                                        </li>
                                        <?php $i++; ?>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="line"></div>
                </div>
            </div>
        <?php endif; ?>
    */ ?>
</div>

<script>
    $(function() {
        $('[data-jcarousel]').each(function() {
            var el = $(this);
            el.jcarousel(el.data());
        });

        $('[data-jcarousel-control]').each(function() {
            var el = $(this);
            el.jcarouselControl(el.data());
        });

        $(".main-cont .schedule-extended ul").each(function(e){
            $(this).parent('.schedule-extended').addClass('schedule-extended-'+e);
            $(this).nextAll('a').addClass('nav-'+e);
            $('.main-cont .location-box .tabs li').on('click', function(){
                $('.main-cont .schedule-extended-' + e + ' ul').carouFredSel({
                    auto: false,
                    prev: '.main-cont .prev-nav.nav-'+ e,
                    next: '.main-cont .next-nav.nav-'+ e,
                    scroll:{items:1},
                    circular: false,
                    infinite:false
                });
            });
            $('.main-cont .location-box .tabs li:first-child').trigger('click');
        });

      $(".connected-carousels .carousel-stage li a").fancybox({
        maxWidth	: 660,
        maxHeight	: 1100,
        fitToView	: false,
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
      });
    });
</script>