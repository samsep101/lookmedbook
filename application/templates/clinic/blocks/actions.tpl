<div class="actionsList">
    <?php foreach($actions as $e):?>
    <?php if ($e->name){ ?>
	<div align="center">
        <div class="oneAction"  style="width: 600px; margin-left: 193px;height: 160px;">
            <div class="actionImage">
                <div class="actionName" style="width: 600px"><a onclick="recordController.showForm(0,<?=$this->__values['clinic']->id?>,0); return false" href="<?php /* =$e->getLink() */?>"><?=$e->name?></a></div>
				<?php if ($e->get_image_full_width()){?>
                    <a href="#" class="actions-show-form" onclick="recordController.showForm(0,<?=$this->__values['clinic']->id?>,0); return false;">
                        <img src="<?=$e->get_image_full_width()->crop(600, 120)->path?>" width="600" height="120">
                    </a>
				<?php } ?>
            </div>
            <!-- <div class="actionText"><?=$e->info?></div> -->
        </div>
	</div>
    <?php } ?>
    <?php endforeach; ?>
</div>