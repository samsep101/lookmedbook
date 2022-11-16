<div class="specializationDiseasesArea">
    <div class="sdaSpecializationDiseases">
        <div class="sdaTitle">
            <div class="sdaTitleContainer">
                <span class="sdaTitlePart sdaLeft"></span>
                <span class="sdaTitlePart sdaRight"></span>
                Другие заболевания по направлению <?php echo $diseases_specialization['specialization']; ?>:
            </div>
        </div>
        <div class="sdaDiseaseBlocks">
            <?php
            $counter = 1;
            foreach($diseases_specialization['diseases_group'] AS $disease_item) { ?>
                <?php if($counter == 1) { ?>
                <div class="sdaDiseaseBlock">
                    <ul>
                <?php } ?>
                        <li>
                            <a href="<?php echo "/disease/{$disease_item['alias']}"; ?>" title="<?php echo $disease_item['title']; ?>"><?php echo $disease_item['title']; ?></a>
                        </li>
                <?php if($counter % 2 == 0) { ?>
                    </ul>
                </div>
                <div class="sdaDiseaseBlock">
                    <ul>
                <?php } elseif(count($diseases_specialization['diseases_group']) == $counter) { ?>
                    </ul>
                </div>
                <?php } ?>
            <?php
            $counter++;
            }
            ?>
            <div class="clearfix"></div>
        </div>
<!--#
        <div id="SC_TBlock_252951" class="SC_TBlock">loading...</div>
        <script type="text/javascript">
            (sc_adv_out = window.sc_adv_out || []).push({
                id : "252951",
                domain : "n.ladyclick.ru"
            });
        </script>
        <script type="text/javascript" src="//st-n.ladyclick.ru/js/adv_out.js"></script>

    </div>
-->
</div>