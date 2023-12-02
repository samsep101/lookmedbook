<?php
    /**
     * @var View $this
     * @var CmsGeneratorConfig $dataModel
     * @var CmsGeneratorConfig $this->dataModel
     * @var bool $ajax
     * @var Acl $acl
     * @var string $addUrl
     * @var string $addTitle
     * @var string $params
     * @var string $destination
     * @var DynamicModel[] $data
     * @var string[] $fieldTitles
     * @var string[] $filter_values
     * @var int $total_count
     */
?>
<div id="generatorData">

    <p class="actionBar">
        <?php if(!$ajax): ?>
            <span class="button">
                <a class="taskIndexLink" href="/<?php echo $this->dataModel->getModelName(); ?>"><img class="cursorPointer" src="/media/admin/icons/clipboard-audit-24-ns.png" align="absmiddle" border="0" /></a>
                <a href="<?php echo ADMIN_FOLDER.'/'.$this->dataModel->getModelName();?>"><?php echo $dataModel->getListTitle(); ?></a>
            </span>
        <?php endif; ?>

        <?php if ($acl->hasRights($dataModel->getModelName(),'add')): ?>
            <span class="button">
                <img class="cursorPointer" src="/media/admin/icons/badge-circle-plus-24-ns.png" align="absmiddle" />
                <a href="<?php echo ADMIN_FOLDER.$addUrl; ?>?destination=<?php echo $destination; ?>&<?php echo $params; ?>"><?php echo $addTitle; ?></a>
            </span>
        <?php endif; ?>
    </p>

    <?php
        if($this->getAdditionalHTML) {
            echo $this->getAdditionalHTML;
        }
    ?>

    <div style="clear:both"></div>

    <?php if(Acl::userGrant($dataModel->getModelName().'_xls')){?>
        <p class="controls">
            <img src="/mhadmin/media/img/xls.gif" align="absmiddle" />&nbsp;<a href="/mhadmin/<?php echo $dataModel->getModelName(); ?>/xls/">Сохранить</a>
        </p>
    <?php }?>

    <?php if(isset($_controller) && $_controller == 'search_log') echo SearchLogAdminHelper::additionalData((isset($csrf) && $csrf) ? $csrf : null); ?>

    <?php $filters = $dataModel->getListFilters();?>
    <?php if ($filters && !$ajax): ?>
        <script type="text/javascript">
            $(document).ready(function(){
                var filter_controller = new ListFilterController();
                filter_controller.init();
            });
        </script>
        <div id="filter-block">
            <div class="title"><b>Фильтры</b></div>
            <?php foreach($filters['filters'] as $filter_row_name => $filter_row): ?>
                <div class="filter-row">
                    <span class="title">
                        <?php if (!is_int($filter_row_name)): ?>
                            <?php echo $filter_row_name.':'; ?>
                        <?php endif; ?>
                    </span>
                    <?php foreach($filter_row as $filter_name =>  $filter): ?>
                        <?php $filter_view = FilterTypeFactory::getByName($filter['type'], $filter_name, $filter); ?>
                        <?php if ($filter['title']): ?>
                            <?php echo $filter['title']; ?>
                        <?php endif; ?>
                        <?php $filter_value = (isset($filter_values[$filter_name])) ? $filter_values[$filter_name] : null; ?>
                        <?php echo $filter_view->getView($filter_value); ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <div class="filter-buttons-row">
                <input type="button" class="submit" value="Применить" />
                <input type="button" class="cancel" value="Сбросить" />
            </div>
        </div>
    <?php endif; ?>

    <?php $information_blocks = $dataModel->getListInformationBlocks(); ?>
    <?php if ($information_blocks):?>

        <div class="information-blocks-data">
        </div>

        <?php foreach ($information_blocks as $information_block):?>
            <script>
                $('.information-blocks-data').append('<img class="loader" src="/media/images/ajaxLoader.gif" />');
                $(document).ready(function() {
                    Ajax.Post('<?php echo $information_block['url']?>', {}, function (data) {
                        if (data.status == 0) {
                            $('.information-blocks-data').append(data.result.html);
                            $('.information-blocks-data .loader').remove();
                        }
                    });
                });
            </script>
        <?php endforeach;?>
    <?php endif;?>

    <?php if(count($data)): ?>

    <?php $total_count_options = $dataModel->getListTotalCount(); ?>

    <?php if ($total_count_options && $total_count_options['show']): ?>
        <div class="total-count-info">
            <b><?php echo $total_count_options['text']; ?></b>: <?php echo $total_count; ?>
        </div>
    <?php endif; ?>

    <?php if ($dataModel->getModelName()=='disease'):?>
        <form method="post" action="/disease/parseDiseasesAndDiseaseBlocks" >
            <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>
            <input class="make-xml" style="margin:10px 0 0 0;padding:5px;font-size: 15px" type="button" value="Импортировать данные"/>
        </form>
    <?php endif?>

    <?php if ($dataModel->getModelName()=='yandex_content_log'):?>
        <a class="classic-href" target="_blank" href="/test/getYandexContentToken"><input class="make-xml" style="margin:10px 0 0 0;padding:5px;font-size: 15px" type="button" value="Получить токен отправки текстов"/></a>
    <?php endif?>

<form id="<?php echo $dataModel->getModelName(); ?>form" action="/admin/<?php echo $dataModel->getModelName(); ?>/delete_list/?destination=<?php echo $destination; ?>" method="POST" onsubmit="return confirm('Вы действительно хотите удалить эти записи?');return false;">
    <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>


<table class="list <?php if ($dataModel->isSortableList()): ?>sortable<?php endif; ?>">
	<thead>

            <?php if ($acl->hasRights($dataModel->getModelName(),'delete_list')): ?>
                <th width="30px;"><input class="status_check" type="checkbox" onclick="checked_all($(this));"/></th>
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

		<?php $buttons = $dataModel->getListButtons(); ?>
		<?php if ($buttons): ?>
			<?php foreach($buttons as $button): ?>
				<th>&nbsp;</th>
			<?php endforeach; ?>
		<?php endif; ?>

        <?php
        $linkGenerator = $dataModel->getListLinkGenerator();
        if ($linkGenerator) {
            $links = $dataModel->getListLinks();
            if ($links) {
                foreach($links as $link) {
                    echo "<th>&nbsp;</th>";
                }
            }
        } else {
            $links = [];
        } ?>
	</thead>
	<tbody data-model="<?php echo $dataModel->getModelName(); ?>">
	<?php foreach ($data as $row): ?>
		<?php  $dataModel->setValues($row);?>
		<tr id="key[<?php echo $row->getId(); ?>][]" data-id="<?php echo $row->getId(); ?>" >
                <?php if ($acl->hasRights($dataModel->getModelName(),'delete_list')): ?>
                    <td><input class="input_check"  type="checkbox" name="delete_list[]" onclick="unchecked($(this));" value="<?php echo $row->getId(); ?>"/></td>
                <?php endif; ?>
			<?php foreach ($dataModel->getListFields() as $field): ?>
                <?php if ($hide_fields && in_array($field->fieldName, $hide_fields)) continue; ?>
					<?php
					$form_value = $row;
					$path = explode('.', $field->fieldName);

					foreach($path as $v)
					{
						$form_value = $form_value->{$v};
						if(!$form_value)
						{
							break;
						}
					}
					?>
                <td class="field-<?php echo str_replace('.', '_', $field->fieldName); ?>">
                    <?php echo $field->getViewValue($form_value, $row); ?>
                    <?php if(($dataModel->getModelName() == 'visit') && ($field->fieldName == 'visit_number')): ?>
                        <?php switch($row->visit_channel_id):
                                case VisitChannelModel::SITE: ?>
                                <img src="/media/images/site.png" style="float:right;">
                                <?php break;
                                case VisitChannelModel::YANDEX: ?>
                                <img src="/media/images/Ya.png" style="float:right;">
                                <?php break;
                                case VisitChannelModel::APPEAL: ?>
                                <img src="/media/images/call.png" style="float:right;">
                                <?php break;
                                case VisitChannelModel::MOBILE: ?>
                                <img src="/media/images/app.png" style="float:right;">
                                <?php break;
                                case VisitChannelModel::LANDING: ?>
                                <img src="/media/images/landing/landing-visit.png" style="float:right;">
                                <?php break;
                                case VisitChannelModel::WIDGET: ?>
                                <img src="/media/images/widget.png" style="float:right;">
                               <?php break; ?>
                        <?php endswitch; ?>
                    <?php endif; ?>

                </td>
			<?php endforeach; ?>

			<?php if ($acl->hasRights($dataModel->getModelName(),'edit')){?>
                <?php $page_region = (isset($_controller) && $_controller == 'visit') ? '' : '#key[' .$row->getId() .'][]'; ?>
				<td width="25px;" style="text-align:center;"><a href="<?php echo ADMIN_FOLDER.'/'.$dataModel->getModelName(); ?>/edit/?<?php echo $indexField; ?>=<?php echo $row->getId(); ?>&destination=<?php echo ($destination) ? $destination : urlencode($_SERVER['REQUEST_URI'] .$page_region); ?>"><img title="Редактировать" border="0" class="edit-image" src="/media/admin/icons/pencil-16-ns.png"/></a></td>
			<?php } ?>

			<?php if ($acl->hasRights($dataModel->getModelName(),'delete')){?>
				<td width="25px;" style="text-align:center;"><a href="<?php echo ADMIN_FOLDER; ?>/<?php echo $dataModel->getModelName(); ?>/delete/?<?php echo $indexField; ?>=<?php echo $row->getId(); ?>&destination=<?php echo $destination; ?>" onclick="return confirm('Вы действительно хотите удалить эту запись?');"><img title="Удалить" border="0" src="/media/admin/icons/badge-square-cross-16-ns.png"/></a></td>
			<?php }?>

			<?php if ($buttons): ?>
				<?php foreach($buttons as $button): ?>
					<td><img src="<?php echo $button['img']; ?>" class="button <?php echo $button['class']; ?>" title="<?php echo $button['title']; ?>" data-id="<?php echo $row->getId(); ?>" /></td>
				<?php endforeach; ?>
			<?php endif; ?>

            <?php if ($links): ?>
                <?php foreach($links as $link): ?>
                    <td><a href="<?= $linkGenerator($row) ?>" target="_blank"><img src="<?= $link['img']; ?>" class="button <?= $link['class']; ?>" title="<?= $link['title']; ?>" data-id="<?= $row->getId(); ?></a>" /></td>
                <?php endforeach; ?>
            <?php endif; ?>
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

<?php if(isset($filter_values) && $filter_values): ?>
    <?php if(strpos($_SERVER['QUERY_STRING'], '&') !== false): ?>
        <?php $filter_string = $_SERVER['QUERY_STRING']; ?>
        <?php while($filter_string[0] != '&'): ?>
            <?php $filter_string = substr($filter_string, 1); ?>
        <?php endwhile; ?>
    <?php endif; ?>
<?php else: ?>
    <?php $filter_string = ''; ?>
<?php endif; ?>

<?php if($this->pages_num > 1): ?>
	<div class="pager">
		<?php echo PagingViewHelper::paging(ADMIN_FOLDER.'/'.$dataModel->getModelName()."/?page=:page:".$filter_string,$this->pages_num,$this->page,'generatorData')?>
	</div>
<?php endif; ?>

<?php else: ?>

    <?php if ($dataModel->getModelName()=='disease'):?>
        <form method="post" action="/disease/parseDiseasesAndDiseaseBlocks" >
            <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>
            <input class="make-xml" style="margin:10px 0 0 0;padding:5px;font-size: 15px" type="button" value="Импортировать данные"/>
        </form>
    <?php endif?>

    <?php if ($dataModel->getModelName()=='yandex_content_log'):?>
        <a class="classic-href" target="_blank" href="/test/getYandexContentToken"><input class="make-xml" style="margin:10px 0 0 0;padding:5px;font-size: 15px" type="button" value="Получить токен отправки текстов"/></a>
    <?php endif?>

	<p>Пока нет данных.</p>
<?php endif; ?>

</div>
