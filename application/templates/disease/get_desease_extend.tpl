<div class="other-links">
	<ul>
		<?php if ($disease->sources) { ?>
			<li> <span class="collapsed">Авторы</span>
				<div class="drop-box drop-box--opened">
					<?php $disease->sources = preg_replace('/<br \/>/','',$disease->sources);?>
					<?php $disease->sources = preg_replace('/<br\/>/','',$disease->sources);?>
					<p><?php echo html_entity_decode($disease->sources,ENT_COMPAT,'UTF-8'); ?></p>
				</div>
			</li>
		<?php } ?>
		<?php if ($disease->extended_content) { ?>
			<li> <span>Расширенное описание</span>
				<div class="drop-box">
					<?php $disease->extended_content = preg_replace('/<br \/>/','',$disease->extended_content);?>
					<?php $disease->extended_content = preg_replace('/<br\/>/','',$disease->extended_content);?>
					<p><?php echo html_entity_decode($disease->extended_content,ENT_COMPAT,'UTF-8'); ?></p>
				</div>
			</li>
		<?php } ?>
	</ul>
</div>