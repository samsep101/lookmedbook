<?php
/**
 * @var View $this
 * @var ProductModel $product
 * @var int $category_id
 * @var ProductCategoryModel $product_category
 * @var ProductCategoryModel $parent_product_category
 */
?>

<script type="text/javascript">
    $(document).ready(function() {
        var product_search_controller = new ProductSearchController();
        product_search_controller.product_category = <?php echo isset($category_id) ? $category_id : "''"; ?>;
        product_search_controller.is_leader = 0;
        product_search_controller.by_page = 10;
        product_search_controller.pattern = '';
        product_search_controller.product_itself = <?php echo $product->getId(); ?>;
        product_search_controller.init();


        var product_card_view_controller = new ProductViewCardController(window.product_basket);
        product_card_view_controller.product_id = <?php echo($product->getId()); ?>;
		product_card_view_controller.price = <?php echo (float)$product->price; ?>;
		product_card_view_controller.max_count = <?php echo (float)$product->quantity; ?>;

        product_card_view_controller.init();
    });
</script>

<div class="magazine inner-2 flo">
    <ul class="way_line">
        <?php $this->block('shop/blocks/breadcrumbs'); ?>
    </ul>
    <div class="block_left" style="width: auto; float: none;">
        <div class="good flo">
            <div class="foto">
                <?php if($product->image_id && $product->is_image_confirmed): ?>
                    <?php $product_href = $product->image->path; ?>
                    <a class="screenshot" href="<?php echo $product_href; ?>" rel="<?php echo $product_href; ?>" onclick="event.preventDefault()">
                        <img src="<?php echo $product->image->resize(300, 256)->path; ?>"/>
                    </a>
                <?php else: ?>
                    <a href="javascript:void(0)">
                        <img src="/media/images/no_image_product.png"/>
                    </a>
                <?php endif; ?>
            </div>
            <div class="info">
                <h1>
                    <?php echo $product->full_name;?>
                </h1>
                <?php if(isset($product_category) && $product_category): ?>
                    <a class="back" href="/shop/catalog/<?php echo($product_category->alias); ?>">к списку лекарств</a>
                <?php else:?>
                    <a class="back" href="/shop/catalog">на главную</a>
                <?php endif; ?>

                <p class="name">
					<?php if($product->manufacturer): ?>
						<?php echo $product->manufacturer->name; ?>
					<?php endif; ?>
				</p>
                <?php /*
                    <div class="buy">
                        <div class="buy-left">
                        <span class="product_price"><span class="gprice"><?php  echo (float)$product->price;?></span>&nbsp;<span>р.</span></span>
                        <span class="showhide">x</span>
                        <ul class="numeric">
                            <li class="decrement">-</li>
                            <li class="count">1</li>
                            <li class="increment">+</li>
                        </ul>
                        </div>
                        <div class="buy-right">
                            <input class="btn-appoint" type="button" value="Купить"/>
                            <div class="goood_totalp">
                                <a href="/shop/basket">В корзине на сумму:</a><br/>
                                <span class="total_price"></span> <span>р.</span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="product-card-message">Перед применением, проконсультируйтесь с врачом</div>
                        <!--<div class="not_available">Нет в наличии</div>-->
                    </div>
                */?>
            </div>

            <?php
                $article = $this->before_article;
                if($article) : ?>
                    <link rel="stylesheet" href="/media/css/product-article.css?rnd=1" type="text/css">
                    <script type="text/javascript" src="/media/js/articles-spoiler.js?rnd=1"></script>
                    <div class="shop-product-article __container">
                        <?=$article?>
                        <div class="shop-product-article-showmore"><a class="__showmore" data-switch="Свернуть статью" href="javascript:void(0)">Читать далее...</a></div>
                    </div>
            <?php endif; ?>

            <?php if ($product->fill_information_status_id == FillInformationStatusModel::OK):?>
                <?php if ($product->zip_info || $product->composition || $product->dosage || $product->side_effects || $product->overdosage || $product->storage_condition || $product->pharma_effects || $product->indications || $product->contra_indications):?>
                    <div class="p_grey">
                        <p class="instruction">Инструкция</p>
                        <p class="info-from-vidal">Описание товара предоставлено <a target="_blank" href="http://vidal.ru">"Видаль"</a></p>
                    </div>
                <div id="open-left-nav">Меню</div>
                    <div class="good_info">
                        <ul class="left_nav">
                            <?php if ($product->zip_info || $product->composition):?><li><a href="javascript:void(0)">Состав и форма выпуска</a></li><?php endif;?>
                            <?php if ($product->dosage):?><li><a href="javascript:void(0)">Способ применения и дозы</a></li><?php endif;?>
                            <?php if ($product->side_effects):?><li><a href="javascript:void(0)">Побочные действия</a></li><?php endif;?>
                            <?php if ($product->overdosage):?><li><a href="javascript:void(0)">Передозировка</a></li><?php endif;?>
                            <?php if ($product->storage_condition):?><li><a href="javascript:void(0)">Условия хранения</a></li><?php endif;?>
                            <?php if ($product->pharma_effects):?><li><a href="javascript:void(0)">Фармакологическое действие</a></li><?php endif;?>
                            <?php if ($product->information->pharmacokinetics):?><li><a href="javascript:void(0)">Фармакокинектика</a></li><?php endif;?>
                            <?php if ($product->indications):?><li><a href="javascript:void(0)">Показания</a></li><?php endif;?>
                            <?php if ($product->contra_indications):?><li><a href="javascript:void(0)">Противопоказания</a></li><?php endif;?>
                            <?php if ($product->extend_information->interaction):?><li><a href="javascript:void(0)">Лекарственное взаимодействие</a></li><?php endif;?>
                            <?php if ($product->extend_information->lactation):?><li><a href="javascript:void(0)">Беременность и лактация</a></li><?php endif;?>
                            <?php if ($product->extend_information->special_information):?><li><a href="javascript:void(0)">Особые указания</a></li><?php endif;?>
                            <?php if ($product->extend_information->pharm_delivery):?><li><a href="javascript:void(0)">Условия отпуска из аптек</a></li><?php endif;?>
                        </ul>
                        <?php if ($product->zip_info || $product->composition):?>
                            <div class="goods_txt visible">
                                <h2 class="h-txt">Состав и форма выпуска</h2>
                                <div class="product-like-p">
                                    <?php echo HtmlTextViewHelper::getView($product->composition);?>
                                    <?php echo HtmlTextViewHelper::getView($product->zip_info);?>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($product->dosage):?>
                            <div class="goods_txt">
                                <h2 class="h-txt">Способ применения дозы</h2>
                                <div class="product-like-p">
                                    <?php echo HtmlTextViewHelper::getView($product->dosage);?>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($product->side_effects):?>
                            <div class="goods_txt">
                                <h2 class="h-txt">Побочные действия</h2>
                                <div class="product-like-p">
                                    <?php echo HtmlTextViewHelper::getView($product->side_effects);?>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($product->overdosage):?>
                            <div class="goods_txt">
                                <h2 class="h-txt">Передозировка</h2>
                                <div class="product-like-p">
                                    <?php echo HtmlTextViewHelper::getView($product->overdosage);?>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($product->storage_condition):?>
                            <div class="goods_txt">
                                <h2 class="h-txt">Условия хранения</h2>
                                <div class="product-like-p">
                                    <?php echo HtmlTextViewHelper::getView($product->storage_condition);?>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($product->pharma_effects):?>
                            <div class="goods_txt">
                                <h2 class="h-txt">Фармакологическое действие</h2>
                                <div class="product-like-p">
                                    <?php echo HtmlTextViewHelper::getView($product->pharma_effects);?>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($product->pharmacokinetics):?>
                            <div class="goods_txt">
                                <h2 class="h-txt">Фармакокинектика</h2>
                                <div class="product-like-p">
                                    <?php echo HtmlTextViewHelper::getView($product->pharmacokinetics);?>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($product->indications):?>
                            <div class="goods_txt">
                                <h2 class="h-txt">Показания</h2>
                                <div class="product-like-p">
                                    <?php echo HtmlTextViewHelper::getView($product->indications);?>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($product->contra_indications):?>
                            <div class="goods_txt">
                                <h2 class="h-txt">Противопоказания</h2>
                                <div class="product-like-p">
                                    <?php echo HtmlTextViewHelper::getView($product->contra_indications);?>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($product->extend_information->interaction):?>
                            <div class="goods_txt">
                                <h2 class="h-txt">Лекарственное взаимодействие</h2>
                                <div class="product-like-p">
                                    <?php echo HtmlTextViewHelper::getView($product->extend_information->interaction);?>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($product->extend_information->lactation):?>
                            <div class="goods_txt">
                                <h2 class="h-txt">Беременность и лактация</h2>
                                <div class="product-like-p">
                                    <?php echo HtmlTextViewHelper::getView($product->extend_information->lactation);?>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($product->extend_information->special_information):?>
                            <div class="goods_txt">
                                <h2 class="h-txt">Особые указания</h2>
                                <div class="product-like-p">
                                    <?php echo HtmlTextViewHelper::getView($product->extend_information->special_information);?>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if ($product->extend_information->pharm_delivery):?>
                            <div class="goods_txt">
                                <h2 class="h-txt">Условия отпуска из аптек</h2>
                                <div class="product-like-p">
                                    <?php echo HtmlTextViewHelper::getView($product->extend_information->pharm_delivery);?>
                                </div>
                            </div>
                        <?php endif;?>
                    </div>
                <?php endif;?>
            <?php endif;?>
        </div>
        <?php if(isset($product_category) && $product_category): ?>
            <h2><?php echo($product_category->name); ?>. Другие лекарства:</h2>
        <?php else: ?>
            <h2>Другие лекарства:</h2>
        <?php endif; ?>

        <a class="load-next-page view-more" href="javascript:void(0);" data-page="1"><i class="icon-loader"></i></a>
    </div>

    <?php /*
        <div class="block_right">
            <?php $this->block('shop/blocks/basket_info'); ?>
            <?php $this->block('shop/blocks/orders_phone'); ?>
            <?php $this->block('shop/blocks/orders'); ?>
            <?php $this->block('shop/blocks/payments'); ?>
            <?php $this->block('shop/blocks/choice'); ?>
        </div>
    */?>
</div>

<?php $this->block('blocks/adv/content_page_tiezerlady'); ?>