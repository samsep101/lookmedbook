<?php
    $roots = $this->roots;
?>
<?php if(!empty($roots)) : ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h4>Медицинские услуги в областях</h4>
        <ul class="tree-roots">
            <?php foreach($roots as $root) : ?>
            <li class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <a href="/uslugi/<?=$root['slug']?>"><?=$root['name']?></a>
                <span><?=$root['count']?></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>