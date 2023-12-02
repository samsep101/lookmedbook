<?php

    $manager = ModelManagerFactory::getByName('doctor');

    require_once ABS_ROOT.'/application/models/relations.simplemodel.php';
    $model = new RelationsSimpleModel();

    $current_model_id = $this->model->id;
    $current_model_class = get_class($this->model);

    switch ($current_model_class) {
        // если текущая модель на редактировании - услуги, то берем связи услуга- клиники
        case 'ServicesCategoriesModel':
            // триггер добавления связи js событие
            $trigger = 'services_to_doctor';
            // список связей для выборки клиник
            $relations_clinics = $model->getServicesRelationsClinics($current_model_id);

            break;
        default:
            $relations_clinics = false;
            break;
    }

    $doctors = [];
    if(!empty($relations_clinics)){
        $doctors = $manager->getByClinics($relations_clinics);
    }

    $relations_doctors = $model->getServicesRelationsDoctors($current_model_id);

?>

<?php if(!empty($doctors)) : /*id' => '123823',
      'first_name' => 'Николай ',
      'second_name' => 'Валерьевич',
      'last_name' => 'Чередниченко',
      'full_lower_name' => 'николай  валерьевич чередниченко',
      'alias' => 'cherednichenkonv',
      'type_id' => NULL,
      'type_name' => NULL,*/?>

    <table class="list" id="doctors-table" data-linkto="<?=$current_model_id?>">
        <tr>
            <th style="width: 20px"></th>
            <th>ID</th>
            <th>ФИО</th>
            <th>Тип</th>
            <th style="width: 100px">Управление</th>
        </tr>
        <?php foreach($doctors as $doc) :
            $fullname = implode(' ', array_map('trim', [$doc->last_name, $doc->first_name, $doc->second_name]));
        ?>
        <tr data-doc-id="<?=$doc->id?>">
            <td>
                <?php if(in_array($doc->id, $relations_doctors)) : ?>
                <input type="checkbox" class="__selected" checked name="selected[]" value="<?=$doc->id?>">
                <?php else : ?>
                <input type="checkbox" class="__selected" name="selected[]" value="<?=$doc->id?>">
                <?php endif; ?>
            </td>
            <td><?=$doc->id?></td>
            <td>
                <a href="/admin/doctor/edit?id=<?=$doc->id?>">
                    <img title="Редактировать" border="0" class="edit-image" src="/media/admin/icons/pencil-16-ns.png">
                </a>
                <a href="/admin/doctor/edit?id=<?=$doc->id?>"><?=$fullname?></a>
                (<a href="/doctor/<?=$doc->alias?>">просмотр на сайте</a>)</td>
            <td>
                <?php if(!empty($doc->type_name)) : ?>
                <span><?=$doc->type_name?></span>
                <?php else : ?>
                <span>не указан</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if(in_array($doc->id, $relations_doctors)) : ?>
                <a href="#" data-trigger="<?=$trigger?>_delete">
                    <img title="Удалить связь" border="0" src="/media/admin/icons/badge-square-cross-16-ns.png">
                </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <input data-trigger="<?=$trigger?>" type="button" value="Сохранить связи с врачами">

<?php else : ?>
    <div class="alert alert-info">Связей пока нет.</div>
<?php endif; ?>