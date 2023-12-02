<?php

    $slice_count = 4;

    $tree = $this->tree;
    foreach($tree as $id => $one){
        if(!empty($one['subslugs'])){
            $one['showmore_slugs'] = array_slice($one['subslugs'], $slice_count, null, true);
            $one['subslugs'] = array_slice($one['subslugs'], 0, $slice_count, true);
        }
        $tree[$id] = $one;
    }
    $this->tree = $tree;
    unset($tree);
?>

<link rel="stylesheet" href="/media/uslugi/styles.css" type="text/css">
<link rel="stylesheet" href="/media/uslugi/media.css" type="text/css">
<script type="text/javascript" src="/media/uslugi/services.js"></script>
<link rel="stylesheet" href="/media/css/product-article.css?rnd=<?= Articles_Viewer::RND?>" type="text/css">
<script type="text/javascript" src="/media/js/articles-spoiler.js?rnd=<?= Articles_Viewer::RND?>"></script>



<div class="services-events">

    <?=$this->renderInString('responsive/includes/breadcrumbs', false)?>
    <?php if(!empty($page)) : ?>

        <div class="container">
            <?php if($page == 'slug') : echo $this->renderInString('uslugi/blocks/page_slug', false); endif;?>
            <?php if($page == 'category') : echo $this->renderInString('uslugi/blocks/page_slug', false); endif;?>
        </div>

    <?php else : ?>
        <div class="container page-h1 ff-regular hidden-map-fullscreen">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h1><?=$h1?></h1>
                </div>
            </div>
        </div>

        <div class="container tree-services hidden-map-fullscreen">
            <div class="row">
                <?php foreach($this->tree as $one) : ?>
                <div class="column col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="ts-header ff-medium"><a href="/uslugi/<?=$one['alias']?>"><?=$one['name']?></a><span><?=($one['count'] > 0) ? $one['count'] : ''?></span></div>
                    <?php if($one['count'] > 0) : ?>

                        <ul>
                            <?php foreach($one['subslugs'] as $subone) : ?>
                            <li><a href="/uslugi/<?=$subone['full_slug']?>"><?=$subone['name']?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if(!empty($one['showmore_slugs'])) : ?>
                            <div class="showmore">
                                <ul class="spoiler">
                                    <?php foreach($one['showmore_slugs'] as $subone) : ?>
                                    <li><a href="/uslugi/<?=$subone['full_slug']?>"><?=$subone['name']?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                                <a href="javascript:void(0)" data-fliptext="скрыть" data-showmore=".spoiler">показать все</a>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>

                </div>
                <?php endforeach; ?>
            </div>
        </div>

    <?php endif; ?>

    <div class="container hidden-map-fullscreen" style="padding-top:15px;">
        <?=$this->renderInString('uslugi/blocks/roots', false);?>
    </div>

</div>

<script>
    $(function(){
       $(document).on('click', '[data-showmore]', function(e){
           e.preventDefault();
           var _ = $(this);
           if(_.hasClass('open')){
               _.removeClass('open');
               _.parent().find(_.data('showmore')).slideUp(200);
               _.text(_.data('flipold'));
           } else {
               _.addClass('open');
               _.parent().find(_.data('showmore')).slideDown(200);
               _.data('flipold', _.text());
               _.text(_.data('fliptext'));
           }
       });
    });
</script>