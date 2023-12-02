<div class="content">
	<div class="inner">
		<h1 style="text-align:center; max-width: 825px; margin: 0 auto;">Мы рады предложить вашему вниманию акции и спецпредложения от наших партнеров. Все подробности об акциях вы можете получить, оставив нам заявку или позвонив по телефону <?= HelpPhoneNumberViewHelper::getPhoneNumberLink() ?></h1>
		<div class="actionsList" style="width: 100%">
		   <?php foreach($actions as $e):?>
		   <?php if ($e->name){ ?>
			<div align="center">
				<div class="oneAction" style="max-width: 600px; margin: 0 auto; height: 160px;">
					<div class="actionImage">
						<div class="actionName" style="width: 600px"><a href="<?=$e->getLink()?>"><?=$e->name?></a></div>
						<a href="<?=$e->getLink()?>">
						<?php if ($e->get_image_full_width()){
						    $croppedImage = $e->get_image_full_width()->crop(600, 120);
						    if ($croppedImage) {?>
                                <img src="<?=$croppedImage->path?>" width="600" height="120">
                            <?php } ?>
						<?php } ?>
						</a>
					</div>
					<!-- <div class="actionText"><?=$e->info?></div> -->
				</div>
			</div>
			<?php } ?>
			<?php endforeach; ?>
		</div>
	</div>
</div>