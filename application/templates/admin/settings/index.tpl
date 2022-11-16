<div id="generatorData">
<script>
$(function() {
	$('.pager a, .actionBar, .generatorEditDiv').corner('5px');
	sel($('#generatorData .taskIndexLink'));
	$('.generatorEditDiv h4').click(function(){
		if($($(this).next()).css('display') == 'none'){
			$($(this).next()).show(250);
		} else {
			$($(this).next()).hide();
		}
	});
});
</script>

<p class="actionBar">
		<span class="button">
			<a class="taskIndexLink" href="javascript:void(0)" onclick="ajax('<?php echo $this->dataModel->getModelName(); ?>/?ajax=1','generatorData')"><img class="cursorPointer" src="/media/admin/icons/clipboard-audit-24-ns.png" align="absmiddle" border="0" /></a>
			<a href="javascript:void(0)" onclick="ajax('<?php echo $this->dataModel->getModelName(); ?>/?ajax=1','generatorData')"><?php echo $dataModel->getListTitle(); ?></a>
		</span> 
</p>
<div style="clear:both"></div>


<div id="tabs" class="tabs">
<?php /*
	<ul>
		<?php  $i = 0;?>
		<?php foreach ($groups as $groupName=>$groupSettings){?>
			<li><a href="#tab-<?php echo $i; ?>"><?php echo $groupName; ?></a></li>
			<?php  $i++;?>
		<?php }?>
	</ul>
*/?>	
	<?php  $i = 0;?>
	<?php foreach ($groups as $groupName=>$groupSettings){?>
		<div id="tabSettings-<?php echo $i; ?>" class="generatorEditDiv">
		<h4><?php echo $groupName; ?></h4>
		    <div <?=(isset($tabs) && count($tabs) != 1)?'style="display:none;"':'';?>>
				<table width="100%" cellspacing="0" cellpadding="0" class="list">
					
					<tbody>
					<?php foreach ($groupSettings as $settings){?>
						<?php if($settings['code'] != 'work_access'){?>
						<tr>
							<td><span class="grey"><?php echo $settings['name']; ?>:</span>
							<?php if ($settings['type'] == 'text'){?>
							<i>... текст ...</i>
							<?php }elseif ($settings['type'] == 'htmlarea'){?>
							<i>... html-код ...</i>
							<?php }elseif ($settings['type'] == 'firstprice'){
							
								switch($settings['value'])
								{
									case 0:
										echo "Цена в магазине";
										break;
									case 1:
										echo "Цена по безналу";
										break;
								}
							
							}elseif ($settings['type'] == 'secondprice'){
							
								switch($settings['value'])
								{
									case 0:
										echo "Не выводить";
										break;
									case 1:
										echo "Цена в кредит";
										break;
									case 2:
										echo "Цена в рассрочку";
										break;
								}
							
							}else{?>
							<?php echo $settings['value']; ?>
							<?php }?>
							<a  href="javascript:void(0)" onclick="ajax('settings/edit/?id=<?php echo $settings['id']; ?>&ajax=1','generatorData')"><img title="Редактировать" border="0" src="/media/admin/icons/pencil-16-ns.png" border="0"/></a>
							</td>
						</tr>
						<?php }?>
					<?php }?>
					</tbody>
				</table>
			</div>
		</div>
		<?php  $i++;?>
	<?php }?>
</div>


</div>
