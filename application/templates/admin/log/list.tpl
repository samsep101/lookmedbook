<div id="generatorData">

    <p class="actionBar">
        <?php if(!$ajax): ?>
            <span class="button">
                <a class="taskIndexLink" href="/<?php echo $this->dataModel->getModelName(); ?>"><img class="cursorPointer" src="/media/admin/icons/clipboard-audit-24-ns.png" align="absmiddle" border="0" /></a>
                <a href="<?php echo ADMIN_FOLDER.'/'.$this->dataModel->getModelName(); ?>"><?php echo $dataModel->getListTitle(); ?></a>
            </span>
        <?php endif; ?>

        <?php if ($acl->hasRights($dataModel->getModelName(),'add')): ?>
            <span class="button">
                <img class="cursorPointer" src="/media/admin/icons/badge-circle-plus-24-ns.png" align="absmiddle" />
                <a href="<?php echo ADMIN_FOLDER.$addUrl; ?>?destination=<?php echo $destination; ?>&<?php echo $params; ?>"><?php echo $addTitle; ?></a>
            </span>
        <?php endif; ?>
    </p>

    <form id="export_log_form" action="/admin/index/log/exportCSVWithAccountActivity">
        <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>
        <input type="submit" value="Выгрузить в *.csv" style="float: right">
    </form>


    <div style="clear:both"></div>

    <?php if(Acl::userGrant($dataModel->getModelName().'_xls')){?>
        <p class="controls">
            <img src="/mhadmin/media/img/xls.gif" align="absmiddle" />&nbsp;<a href="/mhadmin/<?php echo $dataModel->getModelName(); ?>/xls/">Сохранить</a>
        </p>
    <?php }?>


    <?php if(count($data)){?>

    <?php  $filters = $dataModel->getFilters();?>

    <?php /* Автофильтры */?>
    <?php /*if(count($filters)){?>
        <form method="POST" id="filterForm">
            <table cellpadding="2" cellspacing="2">
                <tr>
                    <td><img src="/mhadmin/media/img/filter.png" /></td>

                <?php foreach ($filters as $filter => $title){?>
                        <?php $fileterValues = array();?>
                        <?php foreach ($data as $row){?>
                            <?php  $dataModel->setValues($row);?>
                            <?php foreach ($dataModel->getListFields() as $field){?>
                                <?php if($field->getFieldName() == $filter){?>
                                    <?php if($row[$filter] != 0){?>
                                        <?php $fileterValues[$row[$filter]] = $field->getViewValue()?>
                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        <?php }?>

                        <?php if(count($fileterValues) > 0){?>
                            <td>
                            <?php if((int) $_GET[$filter] and count($fileterValues)==1){?>
                                <?php foreach($fileterValues as $filterKey=>$filterVal){?>
                                    <span class="grey"><?php echo $title; ?>:</span> <?php echo $filterVal; ?> <img src="/mhadmin/media/img/icons/badge-circle-cross-16-ns.png" class="cursorPointer" align="absmiddle" onclick="filterDel('<?php echo $filter; ?>')" />
                                    <input type="hidden" name="<?php echo $filter; ?>" value="<?php echo $filterKey; ?>" />
                                <?php }?>
                            <?php } else {?>
                                <span class="grey"><?php echo $title; ?>:</span> <select style="width:150px;" name="<?php echo $filter; ?>" onchange="$('#filterForm').submit();">
                                    <option value=''>...</option>
                                    <?php foreach($fileterValues as $filterKey=>$filterVal){?>
                                        <?php if((int) $filterKey){?>
                                            <?php if((int) $_GET[$filter] == (int) $filterKey){?>
                                            <option value="<?php echo $filterKey; ?>" selected><?php echo $filterVal; ?></option>
                                            <?php } else {?>
                                            <option value="<?php echo $filterKey; ?>"><?php echo $filterVal; ?></option>
                                            <?php }?>
                                        <?php }?>
                                    <?php }?>
                                </select>
                            <?php }?>
                            </td>
                        <?php }?>
                <?php }?>
                </tr>
            </table>
            <br />
        </form>
        <script>
                $(document).ready(function() {
                    $('#filterForm').ajaxForm({
                        'success': function(data) {
                            url = '';
                            $('#filterForm').find('select, input').each(function(){
                                if($(this).val()){
                                    url = url+'&'+$(this).attr('name')+'='+$(this).val();
                                }
                            });
                            ajax('/<?php echo $dataModel->getModelName(); ?>/?ajax=1'+url,'<?php echo $dataModel->getModelName(); ?>Content');
                        }
                    });
                });

                function filterDel(name){
                    url = '';
                    $('#filterForm').find('select, input').each(function(){
                        if($(this).val() && $(this).attr('name') != name){
                            url = url+'&'+$(this).attr('name')+'='+$(this).val();
                        }
                    });
                    ajax('/<?php echo ADMIN_FOLDER; ?>/<?php echo $dataModel->getModelName();?>/?ajax=1'+url,'<?php echo $dataModel->getModelName(); ?>Content');
                }
        </script>
    <?php }*/?>

    <?php if ($dataModel->getModelName()=='disease'):?>
        <form method="post" action="/disease/parseDiseasesAndDiseaseBlocks" >
            <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>
            <input class="make-xml" style="margin:10px 0 0 0;padding:5px;font-size: 15px" type="button" value="Импортировать данные"/>
        </form>
    <?php endif?>

<form id="<?php echo $dataModel->getModelName(); ?>form" action="/admin/<?php echo $dataModel->getModelName(); ?>/delete_list/?destination=<?php echo $destination; ?>" method="POST" onsubmit="return confirm('Вы действительно хотите удалить эти записи?');return false;">
    <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>

<table class="list">
	<thead>
	<tr>

            <?php if ($acl->hasRights($dataModel->getModelName(),'delete_list')): ?>
                <th width="30px;"><input class="status_check" type="checkbox" onclick="checked_all();"/></th>
            <?php endif; ?>


		<?php foreach ($fieldTitles as $fieldTitle){?>
			<th><?php echo $fieldTitle; ?></th>
		<?php }?>

		<?php if ($acl->hasRights($dataModel->getModelName(),'edit')){?>
			<th>&nbsp;</th>
		<?php }?>

		<?php if ($acl->hasRights($dataModel->getModelName(),'delete')){?>
			<th>&nbsp;</th>
		<?php }?>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($data as $row): ?>
		<?php  $dataModel->setValues($row);?>
		<tr id="key[<?php echo $row->getId(); ?>][]">
                <?php if ($acl->hasRights($dataModel->getModelName(),'delete_list')): ?>
                    <td><input class="input_check"  type="checkbox" name="delete_list[]" value="<?php echo $row->getId(); ?>"/></td>
                <?php endif; ?>

			<?php foreach ($dataModel->getListFields() as $field): ?>
                <?php if ($field->fieldName == $hide_field) continue; ?>
				<td><?php echo $field->getViewValue($row->{$field->fieldName}); ?></td>
			<?php endforeach; ?>

			<?php if ($acl->hasRights($dataModel->getModelName(),'edit')){?>
				<td width="25px;" style="text-align:right;"><a href="<?php echo ADMIN_FOLDER.'/'.$dataModel->getModelName(); ?>/edit/?<?php echo $indexField; ?>=<?php echo $row->getId(); ?>&destination=<?php echo $destination; ?>"><img title="Редактировать" border="0" src="/media/admin/icons/pencil-16-ns.png"/></a></td>
			<?php } ?>

			<?php if ($acl->hasRights($dataModel->getModelName(),'delete')){?>
				<td width="25px;" style="text-align:right;"><a href="<?php echo ADMIN_FOLDER; ?>/<?php echo $dataModel->getModelName(); ?>/delete/?<?php echo $indexField; ?>=<?php echo $row->getId(); ?>&destination=<?php echo $destination; ?>" onclick="return confirm('Вы действительно хотите удалить эту запись?');"><img title="Удалить" border="0" src="/media/admin/icons/badge-square-cross-16-ns.png"/></a></td>
			<?php }?>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<br />

        <?php if ($acl->hasRights($dataModel->getModelName(),'delete_list')){?>
            <div class="clear"><!-- --></div>
            <input type="button" value="Удалить выделенные" id="submit_action" onclick="$('#<?php echo $dataModel->getModelName(); ?>form').submit()" />
            <br />
        <?php }?>
</form>
<br />
<?php if($this->pages_num>1): ?>
	<div class="pager">
		<?php echo PagingViewHelper::paging(ADMIN_FOLDER.'/'.$dataModel->getModelName()."/?page=:page:",$this->pages_num,$this->page,'generatorData')?>
	</div>
<?php endif; ?>

<?php } else {?>

    <?php if ($dataModel->getModelName()=='disease'):?>
        <form method="post" action="/disease/parseDiseasesAndDiseaseBlocks" >
            <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>
            <input class="make-xml" style="margin:10px 0 0 0;padding:5px;font-size: 15px" type="button" value="Импортировать данные"/>
        </form>
    <?php endif?>

	<p>Пока нет данных.</p>
<?php }?>

</div>
