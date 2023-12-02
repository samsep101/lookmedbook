<script>
    $(document).ready(function(){
        var controller = new UserCreateController();
        controller.init();
    });
</script>
<table class="styled-table">
    <tr>
        <td>Логин</td>
        <td><input type="text" name="login" /></td>
    </tr>
    <tr>
        <td>Пароль</td>
        <td><input type="password" id="password" name="password" /></td>
    </tr>
    <tr>
        <td>Пароль повторно</td>
        <td><input type="password" name="password2" /></td>
    </tr>
    <tr>
        <td>Тип</td>
        <td>
            <select name="role_id">
                <?php if(Acl::userRole() == RoleModel::ACCOUNT_SUPER_MANAGER): ?>
                <option value="<?php echo RoleModel::ACCOUNT_MANAGER; ?>">Аккаунт-менеджер</option>
                <?php endif; ?>
                <option value="<?php echo RoleModel::FREELANCE_MANAGER; ?>">Менеджер-фрилансер</option>
                <option value="<?php echo RoleModel::ACCOUNT_REGISTRY; ?>">Представитель клиники</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Клиника</td>
        <td>
            <select name="clinic_id">
                <option value="0">-</option>
                <?php foreach($clinics as $clinic): ?>
                <option value="<?php echo $clinic->getId(); ?>"><?php echo $clinic->name; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="submit" name="save" value="Добавить" />
        </td>
    </tr>


</table>