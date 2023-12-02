 <div class="other-links bottom-what-to-do">
	<div class="info-box doing-box not-hide">
		<h3 class="attention-block">Что делать при <span><?php echo trim($disease->prepositional_name);?>?</span></h3>
				<?php if (!Acc::isAuthed()) { ?>
					<?php include('get_desease_doct_help_noauth2.tpl');?>
				<?php } else { ?>
					<?php include('get_desease_doct_help_auth2.tpl');?>

				<?php } ?>
	</div>
</div>
