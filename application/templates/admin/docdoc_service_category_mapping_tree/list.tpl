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

global $memory_allocation_costil1;
$memory_allocation_costil1 = 1;

?>
<div id="generatorData">

    <p class="actionBar">
        <?php if(!$ajax): ?>
            <span class="button">
                <a class="taskIndexLink" href="/<?= $this->dataModel->getModelName(); ?>"><img class="cursorPointer" src="/media/admin/icons/clipboard-audit-24-ns.png" align="absmiddle" border="0" /></a>
                <a href="<?= ADMIN_FOLDER.'/'.$this->dataModel->getModelName();?>"><?= $dataModel->getListTitle(); ?></a>
            </span>
        <?php endif; ?>

        <?php if ($acl->hasRights($dataModel->getModelName(),'add')): ?>
            <span class="button">
                <img class="cursorPointer" src="/media/admin/icons/badge-circle-plus-24-ns.png" align="absmiddle" />
                <a href="<?= ADMIN_FOLDER.$addUrl; ?>?destination=<?= $destination; ?>&<?= $params; ?>"><?= $addTitle; ?></a>
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
            <img src="/mhadmin/media/img/xls.gif" align="absmiddle" />&nbsp;<a href="/mhadmin/<?= $dataModel->getModelName(); ?>/xls/">Сохранить</a>
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
                            <?= $filter_row_name.':'; ?>
                        <?php endif; ?>
                    </span>
                    <?php foreach($filter_row as $filter_name =>  $filter): ?>
                        <?php $filter_view = FilterTypeFactory::getByName($filter['type'], $filter_name, $filter); ?>
                        <?php if ($filter['title']): ?>
                            <?= $filter['title']; ?>
                        <?php endif; ?>
                        <?php $filter_value = (isset($filter_values[$filter_name])) ? $filter_values[$filter_name] : null; ?>
                        <?= $filter_view->getView($filter_value); ?>
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
            Ajax.Post('<?= $information_block['url']?>', {}, function (data) {
              if (data.status == 0) {
                $('.information-blocks-data').append(data.result.html);
                $('.information-blocks-data .loader').remove();
              }
            });
          });
        </script>
    <?php endforeach;?>
    <?php endif;?>

    <?php
    $serviceTreeManager = new DocdocServiceCategoryMappingTreeManager();
    $notMappedServices = $serviceTreeManager->getNotMappedServices();

    ?>
    <a href="" class="spoiler-links">Показать/скрыть незамапленные услуги</a>
    <div class="spoiler-body">
        <form id="not-mapped-services-list" action="/admin/<?= $dataModel->getModelName(); ?>/edit_not_mapped/?destination=<?= $destination; ?>" method="POST">
            <input type="hidden" name="csrf" value=<?= isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>
            <table class="list">
                <thead>
                <th></th>
                <th>Услуги, не участвующие в мэппинге</th>
                </thead>
                <tbody>
                <?php foreach ($notMappedServices as $service) { ?>
                    <tr>
                        <td>
                            <input class="input_check" type="checkbox" name="not_mapped_list[]" onclick="unchecked($(this));"
                                   value="<?= $service->docdoc_id ?>"/>
                        </td>
                        <td>
                            <?= $service->full_name; ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <?php if ($acl->hasRights($dataModel->getModelName(),'delete_list')){?>
                <div class="clear"><!-- --></div>
                <input type="button" value="Не мэппить выделенные услуги (использовать, как есть)" onclick="$('#not-mapped-services-list').submit()"/>
                <br />
            <?php }?>
        </form>
    </div>

    <?php if($data): ?>

        <?php $total_count_options = $dataModel->getListTotalCount(); ?>

        <?php if ($total_count_options && $total_count_options['show']): ?>
            <div class="total-count-info">
                <b><?= $total_count_options['text']; ?></b>: <?= $total_count; ?>
            </div>
        <?php endif; ?>

        <?php if ($dataModel->getModelName()=='disease'):?>
            <form method="post" action="/disease/parseDiseasesAndDiseaseBlocks" >
                <input type="hidden" name="csrf" value=<?= isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>
                <input class="make-xml" style="margin:10px 0 0 0;padding:5px;font-size: 15px" type="button" value="Импортировать данные"/>
            </form>
        <?php endif?>

        <?php if ($dataModel->getModelName()=='yandex_content_log'):?>
            <a class="classic-href" target="_blank" href="/test/getYandexContentToken"><input class="make-xml" style="margin:10px 0 0 0;padding:5px;font-size: 15px" type="button" value="Получить токен отправки текстов"/></a>
        <?php endif?>

        <form id="<?= $dataModel->getModelName(); ?>form" action="/admin/<?= $dataModel->getModelName(); ?>/delete_list/?destination=<?= $destination; ?>" method="POST" onsubmit="return confirm('Вы действительно хотите удалить эти записи?');return false;">
            <input type="hidden" name="csrf" value=<?= isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>


            <table class="list <?php if ($dataModel->isSortableList()): ?>sortable<?php endif; ?>">
                <thead>

                <?php if ($acl->hasRights($dataModel->getModelName(),'delete_list')): ?>
                    <th width="30px;"><input class="status_check" type="checkbox" onclick="checked_all($(this));"/></th>
                <?php endif; ?>

                <th>Услуга, отображаемая на сайте</th>
                <th>Услуга-двойник</th>

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

                </thead>
                <tbody data-model="<?= $dataModel->getModelName(); ?>">

                <?php
                $renderMappingTree = function ($subslugs, $level = 0) use (&$renderMappingTree, $dataModel, $acl, $buttons, $indexField, $destination) {
                    foreach ($subslugs as $subone) { ?>
                        <tr id="key[<?= $subone['mapped_service_id']; ?>][]" data-id="<?= $subone['mapped_service_id']; ?>" >
                            <?php if ($acl->hasRights($dataModel->getModelName(),'delete_list')): ?>
                                <td>
                                    <?php if (isset($subone['mapped_service_id'])) : ?>
                                    <input class="input_check"  type="checkbox" name="delete_list[]" onclick="unchecked($(this));" value="<?= $subone['mapped_service_id']; ?>"/>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                            <td class="field-mapped_service_id ?>" style="padding-left: <?= 30 * $level ?>px">
                                <?= $subone['name']; ?>
                            </td>

                            <td class="field-service_id ?>">
                                <?php foreach ($subone['ignore_services'] as $ignoreService) {
                                    echo "{$ignoreService['name']}</br>";
                                } ?>
                            </td>
                            <?php if ($acl->hasRights($dataModel->getModelName(),'edit')){?>
                                <td width="25px;" style="text-align:center;">
                                <?php foreach ($subone['ignore_services'] as $ignoreService) :
                                    $page_region = (isset($_controller) && $_controller == 'visit') ? '' : '#key[' .$ignoreService['rule_id'] .'][]';
                                    if (isset($ignoreService['rule_id'])) : ?>
                                    <a href="<?= ADMIN_FOLDER.'/'.$dataModel->getModelName(); ?>/edit/?<?= $indexField; ?>=<?= $ignoreService['rule_id']; ?>&destination=<?= ($destination) ? $destination : urlencode($_SERVER['REQUEST_URI'] .$page_region); ?>"><img title="Редактировать" border="0" class="edit-image" src="/media/admin/icons/pencil-16-ns.png"/></a>
                                    </br>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </td>
                            <?php } ?>

                            <?php if ($acl->hasRights($dataModel->getModelName(),'delete')){?>
                                <td width="25px;" style="text-align:center;">
                                <?php foreach ($subone['ignore_services'] as $ignoreService) :
                                    if (isset($ignoreService['rule_id'])) : ?>
                                    <a href="<?= ADMIN_FOLDER; ?>/<?= $dataModel->getModelName(); ?>/delete/?<?= $indexField; ?>=<?= $ignoreService['rule_id']; ?>&destination=<?= $destination; ?>" onclick="return confirm('Вы действительно хотите удалить эту запись?');"><img title="Удалить" border="0" src="/media/admin/icons/badge-square-cross-16-ns.png"/></a>
                                    </br>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </td>
                            <?php }?>
                        </tr>
                        <?php
                        if (!empty($subone['subslugs'])) {
                            $renderMappingTree($subone['subslugs'], $level + 1);
                        }
                    }
                };
                ?>
                <?php $renderMappingTree($data) ?>
                </tbody>
            </table>
            <br />

            <?php if ($acl->hasRights($dataModel->getModelName(),'delete_list')){?>
                <div class="clear"><!-- --></div>
                <input type="button" value="Удалить выделенные" id="submit_action" onclick="$('#<?= $dataModel->getModelName(); ?>form').submit()" />
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

    <?php else: ?>
        <p>Пока нет данных.</p>
    <?php endif; ?>

</div>