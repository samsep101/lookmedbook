<?php

    $manager = ModelManagerFactory::getByName('clinic');

    require_once ABS_ROOT.'/application/models/relations.simplemodel.php';
    $model = new RelationsSimpleModel();

    $current_model_id = $this->model->id;
    $current_model_class = get_class($this->model);

    switch ($current_model_class) {
        // если текущая модель на редактировании - услуги, то берем связи услуга- клиники
        case 'ServiceCategoryModel':
            // триггер добавления связи js событие
            $trigger = 'service_to_clinic';
            // список связей для выборки клиник
            $relations = $model->getServicesRelationsClinics($current_model_id);
            $relations_select = $model->getSelect('specialization', 'id', 'name');
            break;
        default:
            $relations = false;
            break;
    }

    $clinics = [];
    if(!empty($relations)){
        $clinics = $manager->getClinics($relations);
    }

?>

<?php if(!empty($clinics)) : ?>

    <table class="list">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Адрес</th>
            <th style="width: 45%">Тип</th>
            <th>Управление</th>
        </tr>
        <?php foreach($clinics as $clinic) : ?>
        <tr data-clinic-id="<?=$clinic->id?>" data-linkto="<?=$current_model_id?>">
            <td><?=$clinic->id?></td>
            <td><a href="/admin/clinic/edit?id=<?=$clinic->id?>"><?=$clinic->name?></a></td>
            <td><?=$clinic->address?></td>
            <td>
                <?php
                    $types = array_combine(explode(',', $clinic->type_id), explode(',', $clinic->type_name));
                    foreach($types as $id => $name) :
                ?>
                <a href="/admin/clinic_type/edit/?id=<?=$id?>"><?=$name?></a>
                <?php endforeach; ?>
            </td>
            <td>
                <a href="/admin/clinic/edit?id=<?=$clinic->id?>">
                    <img title="Редактировать" border="0" class="edit-image" src="/media/admin/icons/pencil-16-ns.png">
                </a>
                <a href="#" data-trigger="<?=$trigger?>_delete">
                    <img title="Удалить связь" border="0" src="/media/admin/icons/badge-square-cross-16-ns.png">
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

<?php else : ?>
    <div class="alert alert-info">Связей пока нет.</div>

    <?php if(!empty($relations_select)) : ?>
    <div class="tools-panel">
        <ul class="form">
            <li>
                <label>Связать с клиниками через специализацию:</label>
                <select name="apply_relations_to_clinic" data-onchange="<?=$trigger?>_auto_relations" data-linkto="<?=$current_model_id?>">
                    <option value="_init">- выберите специализацию -</option>
                    <?php foreach($relations_select as $k => $v) : ?>
                    <option value="<?=$k?>"><?=$v?></option>
                    <?php endforeach; ?>
                </select>
            </li>
        </ul>
    </div>
    <?php endif;?>

<?php endif; ?>

    <div class="tools-panel">
        <ul class="form">
            <li>
                <label>Связать с клиникой:</label>
                <input type="text" name="autocomplete_clinic" placeholder="Введите название клиники" data-ac="clinic" data-linkto="<?=$current_model_id?>">
            </li>
        </ul>

        <input data-trigger="<?=$trigger?>" type="button" value="Добавить связь">
    </div>