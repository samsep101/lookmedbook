	<div id="tabs">
		<div class="illness-nav-wrap">
			<div class="illness-nav" data-spy="affix">
				<div class="nav">
					<ul>
						<?php foreach ($disease_tabs_flags as $key=>$value) { ?>
							<?php if ($value) { ?>
								<li data-tab-name="<?php echo $key; ?>" class="tab-people tab-<?php echo $key; ?> <?php echo $key == $card ? 'ui-state-active' : ''; ?>">
                                    <a href="<?php echo DiseasePageLinkViewHelper::getLink($disease); ?>/<?php echo $key; ?><?php if ($disease_green_btn) echo "?dis=new3"; ?>" id="<?php echo $key; ?>"><?php echo DiseaseTabNameViewHelper::getNameByTabFlag($key); ?></a>
                                </li>
							<?php } ?>
						<?php } ?>
						<li data-tab-name="actions" class="tab-people tab-actions">
							<a href="/action" id="">Акции</a>
						</li>
					</ul>
				</div>
				<div id="MyBigAjaxElement"></div>
				<!--add class "carousel" to sub-nav for sliding-->

				<?php foreach ($disease_tabs_flags as $key=>$value) { ?>

					<?php if ($value) { ?>
						<?php if ($key != $card) {
						    continue;
                        } ?>

						<div class="sub-nav sub-nav-<?php echo $key; ?>" id="<?php echo $key; ?>">
							<?php $sub_counter = 0; ?>
							<ul>
								<?php foreach ($disease_blocks as $block) { ?>

									<?php $field_name = $key.'_flag';?>
									<?php $field_anchor = 'b'.$block->id;?>

									<?php if ($block->$field_name == 1) { ?>
										<li><a
													data-section-id="<?php echo $block->disease_block_type_id?>"
													class="section-name content-active-<?php echo DiseaseBlockAliasViewHelper::getAlias($block->disease_block_type_id); ?>"
													data-section-name="content-active-<?php echo DiseaseBlockAliasViewHelper::getAlias($block->disease_block_type_id); ?>"
                                                    data-t="<?php echo $field_anchor; ?>"
													href="<?php echo DiseasePageLinkViewHelper::getLink($disease); ?>#<?php echo $field_anchor; ?>"><?php if(isset($block->title_block) && $block->title_block) echo $block->title_block; else echo $block->disease_block_type->name; ?></a></li>
										<?php $sub_counter++;?>
									<?php } ?>
								<?php } ?>

							</ul>
							<?php if ($sub_counter>=7) { ?>
								<script type="text/javascript">
									$(document).ready(function(){
										first_tab = '<?php echo $card; ?>';
										$('.sub-nav-<?php echo $key; ?>').addClass('carousel');
										$('.sub-nav-'+first_tab+'.carousel ul').each(function(){
											if($(this).find('li').length >= 7) {
												$(this).carouFredSel({
													auto: false,
													prev: '.prev',
													next: '.next',
													scroll:{items:1},
													circular: false,
													infinite:false
												});
											}
										});
									});
								</script>
								<a class="prev" href="#"></a> <a class="next" href="#"></a>
							<?php } else { ?>
							<?php $sub_width = $sub_counter*101; ?>
								<script>
									$(document).ready(function(){
										$('.sub-nav-<?php echo $key; ?>').removeClass('carousel');
										$('#<?php echo $key; ?>.sub-nav ul').css('width', '<?php echo $sub_width; ?>');
									});
								</script>
							<?php } ?>
						</div>
					<?php } ?>
				<?php } ?>

			</div>
		</div>
		<br>

		<div class="content read">
			<?php $this->block('disease/blocks/disease_blocks_content'); ?>
		</div>
		<!--<div class="content">
			<div class="section section-help flo" style="background: url(/media/images/section_cont_shadow.png) no-repeat;"> <span class="info-title">Текст понятен?</span>
				<div class="info-buttons"></div>
				<div class="disease-only-info">
					<p class="info-line">ИНФОРМАЦИЯ ДЛЯ ОЗНАКОМЛЕНИЯ</p>
					<p class="info-line">Необходима консультация с врачом</p>
				</div>
			</div>
		</div>-->
	</div>
