<?php echo $this->block('blocks/help-search-block'); ?>

<div id="content" style="padding:20px; width:95%">
						<h1 class="title">Результаты</h1>
						<div class="searchResult">
						
						<?php if (isset($_REQUEST['query'])){?>
							<p style="font-size:11px;">Для: "<?php echo strip_tags($query); ?>".
							
							<?php if (!$err){?>
								<ul class="searchResultUl">
									<?php $i=1;?>
									<?php foreach($results as $key=>$result){?>
									<li>
										<b><?php echo $i++; ?>.</b> <?php echo $result['search_content']; ?>
										<br />
										<span class="searchSmall"><a href="<?php echo $result['url']; ?>"><?php echo $_SERVER['SERVER_NAME']; ?><?php echo $result['url']; ?></a></span>
									</li>	
									<?php }?>
								</ul>
							<?php }elseif ($err == 'wrong_query'){?>
								<br />Строка запроса должна содержать не менее 3 символов, не считая пробелы.</p>
							<?php }elseif ($err == 'not_found'){?>
								<br />К сожалению, по Вашему запросу ничего не найдено.</p>
							<?php }?>
						<?php }?>
							</ul>
						</div>
</div>

