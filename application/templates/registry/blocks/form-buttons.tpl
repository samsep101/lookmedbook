<?php
/**
 * @var ClinicModel $clinic
 */
?>
<div class="buttons flo">
    <?php if (Acl::isAuthed(RoleModel::ACCOUNT_MANAGER) || Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)) : ?>
        <?php if (isset($add_flag)): ?>
            <!--<input class="btn-appoint long_but" type="submit" name="save" value="Сохранить" onclick="return false;">-->
        <?php else: ?>
            <!-- <input class="btn-appoint longest-button" type="submit" name="sent_back" value="Отправить на доработку" onclick="return false;">-->
        <?php endif; ?>
        <?php if (isset($add_flag) && $add_flag): ?>
            <input class="btn-1" type="submit" name="save" value="Сохранить" onclick="return false;"/>
        <?php else: ?>
            <input class="btn-1" type="submit" name="publish" value="Сохранить" onclick="return false;"/>
        <?php endif; ?>
        <input class="btn-appoint" type="submit" name="cancel" value="Отменить" onclick="return false;"/>

        <?php if (isset($model) && get_class($model) == 'ModerateDoctorInformationModel' && isset($menu_active) && ($menu_active == 'information')): ?>
            <input class="btn-appoint" type="submit" name="delete_doctor" value="Удалить" data-id="<?php echo $model->doctor_id;?>" onclick="return false;"/>
        <?php endif; ?>

        <?php if (isset($model) && get_class($model) == 'ModerateClinicInformationModel' && isset($menu_active) && ($menu_active == 'information')): ?>
            <input class="btn-appoint" type="submit" name="delete_clinic" value="Удалить" data-id="<?php echo $model->clinic_id;?>" onclick="return false;"/>
        <?php endif; ?>

        <?php if (isset($clinic) && ($clinic->is_region)): ?>
            <input class="btn-1" type="submit" name="delete_clinic" value="Удалить" onclick="return false;"/>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY) || Acl::isAuthed(RoleModel::FREELANCE_MANAGER)) : ?>
        <?php if (isset($clinic) && ($clinic->clinic_status_id != ClinicStatusModel::PUBLISHED) && ($clinic->clinic_status_id != ClinicStatusModel::PROBLEM) && $clinic->is_region): ?>
            <input class="btn-appoint long_but" type="submit" name="publish" value="Опубликовать" onclick="return false;" />
            <input class="btn-1" type="submit" name="problem" value="Есть проблема" onclick="return false;" />
        <?php endif; ?>
        <input class="btn-appoint long_but" type="submit" name="save" value="Сохранить" onclick="return false;" />
        <input class="btn-appoint" type="submit" name="cancel" value="Отменить" onclick="return false;" />
        <?php if (isset($clinic) && !$clinic->is_region): ?>
            <input class="btn-1" type="submit" name="moderate" value="Отправить на проверку" onclick="return false;" />
        <?php endif; ?>
    <?php endif; ?>
</div>