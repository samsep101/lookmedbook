<?php
	/**
	 * @var View $this
	 * @var Acl $acl
	 * @var array $tabs
	 * @var CmsGeneratorConfig $this->dataModel
	 */
?>
<script>
$(function() {

	$('.generatorEditDiv h4').click(function(){
		if($($(this).next()).css('display') == 'none'){
			$($(this).next()).show(250);
		} else {
			$($(this).next()).hide();
		}
	});
    $(document).on('click', '.generatorEditDiv .section-header', function(){
        var _ = $(this);
        var id = _.closest('.generatorEditDiv').attr('id');
        if(_.hasClass('open')){
            $('#'+ id +'-container').slideUp(150);
            _.removeClass('open');
        } else {
            _.addClass('open');
            $('#'+ id +'-container').slideDown(150);
        }
    });
});
</script>
<?php //ini_set("memory_limit", "256M");?>
<script language="JavaScript" src="/media/js/admin/form_edit.js"></script>

<p class="actionBar">
		<span class="button">
			<a href="javascript:void(0)" onclick="ajax('/<?php echo $this->dataModel->getModelName(); ?>/?ajax=1','generatorData')"><img class="cursorPointer" src="/media/admin/icons/clipboard-audit-24-ns.png" align="absmiddle" border="0" /></a>
			<a href="<?php echo ADMIN_FOLDER.'/'.$this->dataModel->getModelName(); ?>"><?php echo $this->dataModel->getListTitle(); ?></a>
		</span> 
	<?php  if ($acl->hasRights($this->dataModel->getModelName(),'add')){?>
		<span class="button">
			<img class="cursorPointer" src="/media/admin/icons/badge-circle-plus-24-ns.png" align="absmiddle" />
			<a href="<?php echo ADMIN_FOLDER.'/'.$this->dataModel->getModelName(); ?>/add/"><?php echo $this->dataModel->getAddTitle(); ?></a>
		</span> 
	<?php }?>
</p>
<div style="clear:both"></div>

<form action="<?php echo ADMIN_FOLDER; ?>/<?php echo $this->dataModel->getModelName();?>/edit/?id=<?php echo $indexValue; ?>&destination=<?php echo $destination; ?>" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>

	<?php echo $this->dataModel->getEditTooltip()?>
	<?php $this->block('admin/blocks/validation-errors'); ?>
	<?php $tab_num = 1; ?>
	<?php foreach ($tabs as $tabId=>$tabName): ?>

	<div id="tab-<?php echo $tabId; ?>" class="generatorEditDiv">
			<h4><?php echo $tabName?></h4>
			<div <?php echo ($tab_num != 1) ? 'style="display:none;"' : ''; ?>>
		<br />
		<table class="generatorTable" cellpadding="3" cellspacing="3">
			<?php foreach ($tabFields[$tabName] as $fieldName=>$field): ?>
				<?php if ($field->hasLayout): ?>
				<tr>
						<?php if($this->dataModel->checkUserFilter($fieldName) && Acl::userGrant($this->dataModel->getModelName().'_list_my')): ?>
							<input name="form[<?php echo $fieldName; ?>]" type="
							hidden" value="<?php echo Acl::userId(); ?>" />
						<?php else: ?>
						<?php
							$form_value = $model;
							$path = explode('.', $fieldName);

							foreach($path as $v)
							{
								if($form_value)
									$form_value = $form_value->{$v};
								else
									break;

								}
						?>
							<td class="label"><b><?php echo $this->dataModel->getFieldLabel($fieldName);?></b>:</td>
							<td><?php echo $field->getFormValue($form_value, $model);?></td>
						<?php endif; ?>
				</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		</table>
		</div>
	</div>
		<?php $tab_num++; ?>
	<?php endforeach; ?>
</div>

<input type="hidden" name="form[<?php echo $indexField; ?>]" value="<?php echo $indexValue; ?>" />
<input type="hidden" name="current" id="current" value="<?=$this->model->id?>">
<p>
	<input type="button" onclick="$($($(this).parent()).parent()).submit()" value="Сохранить" id="submit_action">
	<input type="button" onclick="window.location.reload()" value="Отменить" id="cancel_action">
    <?=$this->dataModel->displayButtons()?>
</p>

<?php if($extra = $this->dataModel->getExtra()): ?>

	<?php $extra_id = 0; ?>
	<?php foreach($extra as $name => $v): ?>

        <?php if(!empty($v['type'])) :
            $id = implode('-', [$v['type'], $name]);
        ?>
            <div id="<?=$id?>" class="generatorEditDiv">
                <div class="section-header open"><?=$v['title']; ?></div>
                <div id="<?=$id.'-container'?>">
                    <?php if(!empty($v['view'])) :
                        // выполняем вьюху
                        echo call_user_func_array($v['view'], [$this]);
                    endif;?>
                </div>
            </div>
        <?php else :
            if(!Acl::userGrant($v['table'].'_list')): continue; endif;
        ?>
            <div id="extra-<?php echo $extra_id; ?>" class="generatorEditDiv">
                <h4><?php echo $v['title']; ?></h4>
                <div id="extra-<?php echo $extra_id; ?>-container">

                </div>
            </div>
            <script type="text/javascript">
                <?php
                    $where = '';
                    $hide_fields = array();
                    $params = array();
                    if (isset($v['field'])){
                        $where = '&where['.$v['field'].']='.$model->getId();
                        $hide_fields[] = 'hide_fields[]='.$v['field'];
                        $params[] = 'params['.$v['field'].']='.$model->getId();
                        $sort_by = array();
                        if(isset($v['sort_by']) && $v['sort_by'] && is_array($v['sort_by']))
                        {
                            $sort_counter = 0;
                            foreach($v['sort_by'] as $sort_item)
                            {
                                $sort_by[] = 'sort_by['.$sort_counter.'][field]='.$sort_item['field'].'&sort_by['.$sort_counter.'][desc]='.$sort_item['desc'];
                            }

                            $sort_by = '&'.join('&',$sort_by);
                        }
                    }

                    $arr = array();
                    if (isset($v['fields'])){
                        foreach($v['fields'] as $field_name1 => $field_name2)
                        {
                            $arr[] = 'where['.$field_name1.']='.$model->{$field_name2};
                            $hide_fields[] = 'hide_fields[]='.$field_name2;
                            $params[] = 'params['.$field_name1.']='.$model->{$field_name2};
                        }
                        $where = '&'.join('&',$arr);

                    }

                    $params = '&'.join('&', $params);
                    $hide_fields = '&'.join('&', $hide_fields);

                    $ajax_destination = 'destination=/admin/'.$this->dataModel->getModelName().'/edit?id='.$model->getId();

                    /*if ($destination)
                        $ajax_destination .= urlencode('&').'destination='.$destination;*/

                    $ajax_destination = '&'.$ajax_destination;
                ?>

                ajax('/admin/<?php echo $v['table']; ?>?ajax=1<?php echo $ajax_destination; ?><?php echo $where; ?><?php echo $params; ?><?php echo $hide_fields ?><?php echo (isset($sort_by) && $sort_by) ? $sort_by : ''; ?>', 'extra-<?php echo $extra_id; ?>-container');
                function loadExtra<?php echo $extra_id; ?>Page(page)
                {
                    if (page == undefined) page = 1;

                    $(document).on('click', '#extra-<?php echo $extra_id; ?>-container .pager a', function(){
                        if (!$(this).data('page'))
                            return;

                        ajax('/admin/<?php echo $v['table']; ?>?ajax=1<?php echo $where; ?><?php echo $ajax_destination; ?><?php echo $params; ?><?php echo $hide_fields; ?>&page='+$(this).data('page')<?php echo (isset($sort_by) && $sort_by) ? $sort_by : ''; ?>, 'extra-<?php echo $extra_id; ?>-container');

                        $(document).scrollTo($('#extra-<?php echo $extra_id; ?>'));
                        return false;
                    });
                }

                loadExtra<?php echo $extra_id; ?>Page();

            </script>
        <?php endif; ?>
		<?php $extra_id++; ?>
	<?php endforeach; ?>
<?php endif; ?>


</form>