<script>
    $(function() {
        $(document).ready(function(){
            var help_controller = new HelpSearchResultsPageController();
            help_controller.init();
        });
    });
</script>

<div class="inner-3">
    <h1 class="help-title">Помощь</h1>
    <div class="search-block s-block-var flo">
        <form action="/help/searchResults" method="GET">
            <input type="text" class="txt" autocomplete="off" name="help_query" placeholder="Введите вопрос" />
            <input type="submit" class="btn-1" value="Искать" />
            <ul class="drop-menu help-drop-menu">
            </ul>
        </form>
    </div>
    <div class="about-ilness-content flo">
        <div class="ilness-result">
            <h1>Результаты</h1>
            <ol class="illness-results-list">
                <?php foreach ($materials as $material):?>
                <li> <a class="help-result" ><?php echo $material->title; ?></a>
                    <div class="drop">
                        <p><?php echo $material->content; ?></p>
                    </div>
                </li>
                <?php endforeach?>
            </ol>
            <?php if (isset($next_button)):?>
            <?php echo $next_button; ?>
            <?php endif?>
        </div>
    </div>
</div>