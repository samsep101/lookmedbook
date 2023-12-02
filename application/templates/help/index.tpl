<script>
    $(function() {
        $(document).ready(function(){
            var help_controller = new HelpPageController();
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
    <?php if (isset($_GET['help_query'])):?>
        <div class="illness-catalog-error">По Вашему запросу ничего не найдено.</div>
    <?php endif?>
    <div class="help-page flo" id="tabs">
        <div class="nav" style="display:none">
            <ul>
                <?php foreach ($rubrics as $rubric):?>
                    <li id="<?php echo $rubric->id; ?>"><a href="#tabs-<?php echo $rubric->id; ?>"><i></i><?php echo $rubric->title; ?></a></li>
                <?php endforeach?>
            </ul>
        </div>

        <?php foreach ($rubrics as $rubric):?>

            <div id="tabs-<?php echo $rubric->id; ?>">

                <ul class="sub-menu">
                    <?php foreach ($subrubrics as $subrubric):?>
                        <?php if ($subrubric->help_rubric_id == $rubric->id):?>
                            <li><a href="#sub-<?php echo $rubric->id; ?>-<?php echo $subrubric->id; ?>"><?php echo $subrubric->title; ?></a></li>
                        <?php endif?>
                    <?php endforeach?>
                </ul>
                <div class="help-cont">
                    <?php foreach ($subrubrics as $subrubric):?>
                        <?php if ($subrubric->help_rubric_id == $rubric->id):?>
                            <h2 id="sub-<?php echo $rubric->id; ?>-<?php echo $subrubric->id; ?>"><?php echo $subrubric->title; ?></h2>
                            <ul class="help-list">
                                <?php foreach ($materials as $material):?>
                                    <?php if ($material->help_subrubric_id == $subrubric->id):?>
                                        <li> <a class="help-target" href="#"><?php echo $material->title; ?></a>
                                            <div class="drop">
                                                <p><?php echo $material->content; ?></p>
                                            </div>
                                        </li>
                                    <?php endif?>
                                <?php endforeach?>
                            </ul>
                        <?php endif?>
                    <?php endforeach?>
                </div>
            </div>

        <?php endforeach?>

    </div>
</div>