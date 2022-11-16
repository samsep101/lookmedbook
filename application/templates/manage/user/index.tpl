<?php
    /**
     * @var string $login
     * @var int $role_id
     * @var ClinicModel[] $clinics
     * @var int $clinic_id
     * @var int $city_id
     * @var UserModel $user
     * @var CityModel[] $cities
     */
?>
<div class="users-filter">
    <div>
        <form action="/manage/user" method="GET">
            Логин: <input type="text" name="login" value="<?php echo $login; ?>" />

            Тип:
            <select name="role_id">
                <option value="0">Все</option>
                <option value="<?php echo RoleModel::ACCOUNT_MANAGER; ?>"
                <?php if ($role_id == RoleModel::ACCOUNT_MANAGER): ?>
                selected="selected"
                <?php endif; ?>
                >Аккаунт-менеджер</option>
                <option value="<?php echo RoleModel::FREELANCE_MANAGER; ?>"
                <?php if ($role_id == RoleModel::FREELANCE_MANAGER): ?>
                selected="selected"
                <?php endif; ?>
                >Менеджер-фрилансер</option>
                <option value="<?php echo RoleModel::ACCOUNT_REGISTRY; ?>"
                    <?php if ($role_id == RoleModel::ACCOUNT_REGISTRY): ?>
                        selected="selected"
                    <?php endif; ?>
                    >Представитель клиники</option>
            </select>

            <?php echo $this->block('registry/manage/blocks/simple_city_filter');?>

            Клиника:
            <select name="clinic_id" class="clinic-option-list" style="width: 250px">
                <option value="0">Все</option>
                <?php foreach($clinics as $clinic): ?>
                <option value="<?php echo $clinic->getId(); ?>"
                <?php if ($clinic_id == $clinic->getId()): ?>
                selected="selected"
                <?php endif; ?>
                ><?php echo $clinic->name; ?></option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Применить" />
        </form>
    </div>
</div>

<input class="btn-appoint block-button" type="submit" value="Добавить" onclick="window.location='/manage/user/create';" />

<?php if ($users): ?>
<?php $counter = 1;?>
    <table class="styled-table block users-list">
        <thead>
            <th>Логин</th>
            <th>Тип</th>
            <th>Клиники</th>
            <th></th>
        </thead>
        <?php foreach($users as $user): ?>
            <tr>
                <td><?php echo $user->login; ?></td>
                <td><?php echo $user->role->name; ?></td>
                <td>
                    <?php if ($user->clinics): ?>
                        <?php foreach($user->clinics as $clinic): ?>
                            <?php echo $clinic->name; ?> <br />
                        <?php endforeach; ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td>
                    <a href="/manage/user/edit?user_id=<?php echo $user->getId(); ?>">редактировать</a>
                </td>
                <?php if (Acl::isAuthed(RoleModel::ACCOUNT_MANAGER) && $counter == 1):?>
                    <td>
                        Мой аккаунт
                    </td>
                <?php endif;?>
            </tr>
        <?php $counter++;?>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    по данным параметрам ничего не найдено
<?php endif; ?>