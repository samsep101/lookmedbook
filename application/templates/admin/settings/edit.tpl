<script>
$(function() {
	$('.pager a, .actionBar, .generatorEditDiv').corner('5px');
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
			<a href="javascript:void(0)" onclick="ajax('settings/?ajax=1','generatorData')"><img class="cursorPointer" src="/media/admin/icons/clipboard-audit-24-ns.png" align="absmiddle" border="0" /></a>
			<a href="javascript:void(0)" onclick="ajax('settings/?ajax=1','generatorData')">Настройки</a>
		</span> 
</p>
<div style="clear:both"></div>

<form  id="settingsForm" action="settings/save" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>

<div id="tabs" class="tabs">

    <div id="tab-0">
	<table class="generatorTable" cellpadding="3" cellspacing="3">
		<tr>
			<td class="label"><?php echo $setting['name']; ?>:</td>
			<td>
				<?php
					$typeClass = ucfirst($setting['type']).'Type';
					if ($setting['type'] == 'file')
					{
						$typeSettings = array(
							'type'	=> 'file',
							'base_dir'	=> 'settings/'
						);
					} elseif ($setting['type'] == 'image') {
						$typeSettings = array(
							'type'	=> 'file',
							'base_dir'	=> 'settings/',
							'images' => array(	
								'small'		=> '50x0',		
								'middle'	=> '100x0',		
								'big'		=> '200x0',		
								'full'		=> '500x0',		
								'full2'		=> '650x0',		
							),
						);
					} else {
						$typeSettings = array(
							'type' => $setting['type'],
						);
					}
					$type = new $typeClass('value',$typeSettings,$setting['value']);
				?>
				<?php echo $type->getFormValue($setting['value']); ?>
			</td>
		</tr>
	</table>
	</div>
</div>
<input type="hidden" name="form[id]" value="<?php echo $setting['id']; ?>" />
<p><input type="button" value="Сохранить" id="submit_action" onclick="$('#settingsForm').submit()" />
<input type="button" onclick="ajax('settings/?ajax=1','generatorData')" value="Отменить" id="submit_action"></p>
</form>