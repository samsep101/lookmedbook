<?php foreach($specialties_groups as $specialty_group): ?>
    <div class="specialty-group<?php if(!empty($page_type)) echo ' doctor-specialties'; ?>">
        <div class="group-name">
            <?php echo $specialty_group['name']; ?>
        </div>
        <ul>
            <?php
                foreach($specialty_group['groups'] as $letter_groups):
                $first = 1;
            ?>
                <?php foreach($letter_groups as $specialty_item): ?>
                    <?php if (!$specialty || $specialty_item->getId() != $specialty->getId()): ?>

                    <li>
                        <a <?php if($first) echo 'class="first-specialty"'; ?> href="<?php echo SeoLinkViewHelper::getSpecialtyPageLink($specialty_item, $address_object); ?>" title="<?php echo $specialty_item->name; ?>"><span class="specialty-name-firs-letter"><?php echo $specialty_item->firstLetter; ?></span><span class="specialty-name-other-part"><?php echo $specialty_item->otherPart; ?></span></a>
                    </li>
                    <?php
                        $first = 0;
                        endif;
                    ?>
                <?php endforeach; ?>
                <?php if(count($letter_groups) != 0) echo '&nbsp;'; ?>
            <?php endforeach; ?>
        </ul>
        <div class="clearfix"></div>
    </div>
<?php endforeach; ?>