<?php $this->block('disease/blocks/micro_markup'); ?>
<div class="inner">
	<div class="about-ilness-content flo">
		<div class="main-column">
            <?php $this->block('disease/blocks/adv_left_side'); ?>
            <div class="desease-phone">
	            <a class="actionLink" href="/action">Акции и спецпредложения</a>
            </div>
			<div class="main-cont flo">
                <div id="hero">

				<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb breadcrumb-custom">
					<li itemprop="itemListElement" itemscope
						itemtype="http://schema.org/ListItem">
						<a itemprop="item" href="/" class="breadcrumb__link">
							<span itemprop="name">Главная</span></a> -&nbsp;
						<meta itemprop="position" content="1" />
					</li>
					<li itemprop="itemListElement" itemscope
						itemtype="http://schema.org/ListItem">
						<a itemprop="item" href="/disease" class="breadcrumb__link">
							<span itemprop="name">Заболевания</span></a> -&nbsp;
						<meta itemprop="position" content="2" />
					</li>
					<li>
                    <span>
                        <span><?=$disease->title?></span></span>
					</li>
				</ol>

				<div class="illness-header flo">
					<h1 id="disease-title" data-id="<?php echo $disease->id; ?>" data-title="<?php echo $disease->title; ?>"><?php echo $disease->title; ?></h1>
					<?php if (Acc::isAuthed()) { ?>
						<a class="btn-bookmark btn-bookmark-illness"></a>
					<?php } ?>
					<?php if ($disease->alt_names) { ?>
						<p class="another"><span>...или:</span>
							<?php echo $disease->alt_names_string; ?>
						</p>
					<?php } ?>
				</div>

				<div class="illness-description">
					<?php $disease->content = preg_replace('/<br \/>/','',$disease->content);?>
					<?php $disease->content = preg_replace('/<br\/>/','',$disease->content);?>
					<div class="like_p"><?php echo html_entity_decode($disease->content,ENT_COMPAT,'UTF-8'); ?></div>
				</div>
                    <!-- old slickjumg -->
                    <!-- верхний тизер medshowtiz (был тут) -->
                    <?php if( $this->beforeblocks != "" ): ?>
                    <div id="before-disease">
                       <?php // var_dump($disease); ?>
                       <script type="text/javascript">
                       $(document).ready(function(){
                       var data = 'slug=' + '<?=$disease->alias?>' ;
                            $.ajax({
                                type    : 'POST',
                                url     : '/disease/ajaxGetBeforeBlock',
                                dataType: 'json',
                                data    : data,
                                success : function(res){
                                    $('#before-disease').html(res['result']);
                                }
                            });
                        });
                        </script>
                    </div>
                    <?php endif; ?>

                </div>
                <?php if(0 and !empty($disease->alias) and in_array($disease->alias, ['mezhpozvonochnaya-gryzha', 'osteohondroz-pozvonochnika'])) { ?>
					<div class="desease-banner-line">
						<div class="ortospy-banner"></div>
					</div>
				<?php } ?>

				<?php if ($disease_blocks) { include('get_desease_blocks.tpl'); } ?>

				<?php if ($disease->extended_content || $disease->sources) { include('get_desease_extend.tpl'); } ?>

                <?=$this->afterblocks?>

				<?php if ($disease_specialties) { include('get_desease_special.tpl'); } ?>

                <?php $this->block('disease/footer_adv'); ?>
			</div>
			<div id="cards-wrap"></div>
		</div>

		<div class="side-column" <?php /* ?>data-spy="affix" data-offset-top="100"<?php */ ?>>
			<?php if ($disease_specialties) { ?>
		<div class="info-box doing-box what-to-do" style="position: relative; z-index: 7000;width: 256px; left: 10px">
					<h3>У вас <span><?php echo mb_strtolower(trim($disease->title),'UTF-8');?>?
						</span></h3>
					<ol class="todo-list">
						<li>
							<?php if (!Acc::isAuthed()) { ?>
								<?php include('get_desease_doct_help_noauth.tpl');?>
							<?php } else { ?>
								<?php include('get_desease_doct_help_auth.tpl');?>
							<?php } ?>
						</li>
					</ol>
				</div>
			<?php } ?>

			<?php if ($disease->medicine) { ?>
				<div class="info-box">
					<h3>Медикаменты</h3>
					<div class="medicament-block">
						<p class="medicament-title"><?php echo $disease->medicine->name; ?></p>
						<a class="medicament-url" href="#">Список аптек</a>
						<?php if ($disease->medicine->image) { ?>
							<img src="<?php echo $disease->medicine->image->resize(234,200)->path; ?>" class="medicament-logo" alt="" />
						<?php } else { ?>
							<img src="/media/images/no-photo.gif" class="medicament-logo" alt="" />
						<?php } ?>
						<div class="center-align"><a class="medicament-btn" href="#">Купить онлайн <?php echo $disease->medicine->price; ?> P</a></div>
						<p class="medicament-info">Перед приемом лекарства проконсультируйтесь у врача</p>
					</div>
				</div>
			<?php } ?>

			<?php if(!empty($disease->alias) and in_array($disease->alias, ['varikoznaya-bolezn', 'hronicheskaya-venoznaya-nedostatochnost'])) {
				include('wikimed.tpl');
			} ?>

			<?php if(!empty($disease->alias) and in_array($disease->alias, ['mezhpozvonochnaya-gryzha', 'osteohondroz-pozvonochnika'])) { ?>
                           <div class="desease-banner-line">
                             <div class="ortospy-banner2" onclick="window.location='/clinic/ortospayn';return false;"></div>
                           </div>
                        <?php } ?>

			<?php include('get_desease_yandexdir.tpl'); ?>
			<div id="ban2"></div>
			<?php include('get_desease_secondopinions.tpl');?>

		</div>
	</div>
</div>

<div class="inner-2">
    <?php
    if ($hasDoctors) { ?>
        <div class="search-count-block <?php echo (isset($is_red) && $is_red) ? 'red' : ''; ?>">
            <p class="count">
                Мы нашли для Вас <span class="count-digit"><?= $totalDoctors ?></span> <span class="count-doctor"><?= $doctorWordForm ?></span> <span class="count-specialty"><?= $doctorsSpecialtyName ?></span>
            </p>
            <div class="divider-shadow"></div>
        </div>
        <div>
            <?php $this->block('doctor/card_big_list') ?>
            <?php foreach ($disease_specialties as $specialty) { ?>
                <a class="load-next-page view-more" href="<?= $specialty->specialtyUrl ?>">Перейти на страницу поиска врачей</a>
            <?php }
            ?>
        </div>
    <?php } ?>
  <sjdiv id="SlickJumpNativeAds-sm02hh"></sjdiv>
  <div id="disease-similar">
  </div>
</div>