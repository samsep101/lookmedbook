<?php
    /**
     * @var int $my_account
     * @var ClinicModel[] $user_clinics
     */
?>
<script>
    $(document).ready(function(){
        var controller = new UserEditPageController();
        controller.user_id = <?php echo $user->getId(); ?>;
        controller.init();
    });
</script>
<div class="edit-form block">
    <table class="styled-table">
        <tr>
            <td>Логин</td>
            <td><input type="text" name="login" value="<?php echo $user->login; ?>" /></td>
        </tr>
        <tr>
            <td>Пароль</td>
            <td><input type="password" id="password" name="password" value="" /></td>
        </tr>
        <tr>
            <td>Пароль повторно</td>
            <td><input type="password" name="password2" value="" /></td>
        </tr>
        <tr>
            <td>Тип</td>
            <td><select name="role_id">
                <?php if(Acl::userRole() == RoleModel::ACCOUNT_SUPER_MANAGER || (Acl::userRole() == RoleModel::ACCOUNT_MANAGER && isset($my_account) && $my_account == 1)): ?>
                <option value="<?php echo RoleModel::ACCOUNT_MANAGER; ?>"
                <?php if ($user->role_id == RoleModel::ACCOUNT_MANAGER): ?>
                selected="selected"
                <?php endif; ?>
                >Аккаунт-менеджер</option>
                <?php endif; ?>
                <option value="<?php echo RoleModel::FREELANCE_MANAGER; ?>"
                <?php if ($user->role_id == RoleModel::FREELANCE_MANAGER): ?>
                selected="selected"
                <?php endif; ?>
                >Менеджер-фрилансер</option>
                <option value="<?php echo RoleModel::ACCOUNT_REGISTRY; ?>"
                <?php if ($user->role_id == RoleModel::ACCOUNT_REGISTRY): ?>
                selected="selected"
                <?php endif; ?>
                >Представитель клиники</option>
            </select>
            </td>
        </tr>
        <tr>
            <td colspan="2"> <input type="submit" name="save" value="Сохранить" /></td>
        </tr>
    </table>
</div>

<div class="block new_clinic_block">
    <select name="clinic_id" style="width: 300px">
        <option value="0">-</option>
        <?php foreach($clinics as $clinic): ?>
        <option value="<?php echo $clinic->getId(); ?>"><?php echo $clinic->name; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Добавить новую клинику" />
</div>

<form method="GET" action="/manage/user/edit">
    <?php echo $this->block('registry/manage/blocks/simple_city_filter');?>
    <input type="hidden" name="user_id" value="<?php if (isset($user) && $user) echo $user->getId();?>">
    <input type="submit" value="Применить">
</form>

<?php if ($user_clinics): ?>
    <table class="block styled-table user-clinics-list">
        <thead>
        <th>
            Название
        </th>
        <th></th>
        </thead>
        <tbody class="clinics-list">
            <?php $this->block('manage/account/blocks/clinic_results'); ?>
        </tbody>
    </table>
<?php endif; ?>
