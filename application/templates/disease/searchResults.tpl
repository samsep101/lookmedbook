<script type="text/javascript">
    $(document).ready(function () {
        var controller = new DiseaseSearchResultsPageController();
        controller.init();
    });
</script>

<div class="inner">
    <div class="search-block flo">

        <form action="/search/results" method="GET">
            <input type="text" class="txt illness-search-input" autocomplete="off" name="query" value="<?php echo strip_tags($query); ?>" placeholder="Найти заболевание" />

            <input type="submit" class="btn-1 illness-search-submit" value="Искать" />
            <ul class="drop-menu">
            </ul>
        </form>
    </div>
    <div class="about-ilness-content flo">
        <div class="ilness-result">
            <h1>Результаты</h1>
            <ol class="illness-results-list">
                <?php foreach ($diseases as $disease):?>
                <li>
                    <div class="into">
                        <h2><a href="<?php echo DiseasePageLinkViewHelper::getLink($disease); ?>"><?php echo $disease->title; ?></a></h2>
                        <?php echo html_entity_decode(mb_substr($disease->content,0,170,'UTF-8'),ENT_COMPAT,'UTF-8'); ?>...
                        <p class="more"><a href="<?php echo DiseasePageLinkViewHelper::getLink($disease); ?>">Подробнее</a></p>
                    </div>
                </li>
                <?php endforeach?>
            </ol>
            <?php if (isset($next_button)):?>
            <?php echo $next_button; ?>
            <?php endif?>
        </div>

        <?php if (isset($medicine)):?>
        <div class="side-column" data-spy="affix" data-offset-top="197">
            <div class="info-box">
                <h3>Медикаменты</h3>
                <div class="medicament-block">
                    <p class="medicament-title"><?php echo $medicine->name; ?></p>
                    <a class="medicament-url" href="#">Список аптек</a>
                    <?php if ($medicine->image):?>
                    <img src="<?php echo $medicine->image->resize(234,200)->path; ?>" class="medicament-logo" alt="" />
                    <?php  else:?>
                    <img src="/media/images/no-photo.gif" class="medicament-logo" alt="" />
                    <?php endif?>
                    <div class="center-align"><a class="medicament-btn" href="#">Купить онлайн <?php echo $medicine->price; ?> P</a></div>
                    <p class="medicament-info">Перед приемом лекарства проконсультируйтесь у варача</p>
                </div>
            </div>
        </div>
        <?php endif?>
    </div>
</div>