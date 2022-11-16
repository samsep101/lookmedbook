<?php if (count($specialties)):?>
    <?php foreach($specialties as $specialty): ?>
       <?php if ($specialty->id==30 or $specialty->id==34 or $specialty->id==39) 
       {
       ?>
        <option  value="<?php echo $specialty->getId(); ?>" <?php if ($specialty->gparent == 1) {?>style="font-weight: bold;"<?php }?>
            data-specialty_alias="<?php echo $specialty->alias; ?>"
            data-specialty_name="<?php echo $specialty->name; ?>"
            data-specialty_plural_name="<?php echo $specialty->plural_name; ?>"
            <?php if ($specialty->getId()==39)
             {
             ?>
             selected
             <?php
             }
             ?>
            >

            <?php echo StringHelper::startProposalWord($specialty->name); ?>
        </option>
        <?php
        }
        ?>
    <?php endforeach; ?>
<?php endif; ?>