<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title><?php echo $this->company; ?></title>
  <?php echo $this->block('registry/blocks/head'); ?>
</head>
<body>

<?php $param = (Acl::isAuthed(RoleModel::ACCOUNT_MANAGER) && isset($clinic_id)) ? '?clinic_id=' . $clinic_id : ''; ?>

<div class="registry-wrap">
  <div class="header-registry" style="overflow: hidden;">
    <div>
      <div style="float:right; margin-top:20px;">

        <ul class="horizontal registry-top-menu">
          <?php if (Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY)) { ?>
            <li><a href="/registry/doctor<?php echo $param; ?>">Врачи</a></li>
            <li><a href="/registry/clinic/information<?php echo $param; ?>">О клинике</a></li>
            <li><a href="/admin/security/logout">Выйти</a></li>
          <?php } ?>

          <?php if ( Acl::isAuthed(RoleModel::ACCOUNT_MANAGER) || Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)
            || Acl::isAuthed(RoleModel::FREELANCE_MANAGER) ) { ?>
            <li><a href="/registry/manage">Главная</a></li>
            <?php if (!Acl::isAuthed(RoleModel::FREELANCE_MANAGER)) { ?>
              <li><a href="/manage/user">Пользователи</a></li>
              <li><a href="/manage/account">Аккаунты</a></li>
            <?php } ?>
            <?php if (Acl::isAuthed(RoleModel::ACCOUNT_MANAGER) || Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)) { ?>
              <li><a href="/registry/manage/statuses">Статусы</a></li>
              <li><a href="/registry/manage/region_check">Регионы</a></li>
              <li><a href="/registry/manage/clinics">Клиники</a></li>
              <li><a href="/registry/manage/doctors">Врачи</a></li>
            <?php } ?>
            <li><a href="/admin/security/logout">Выйти</a></li>
          <?php } ?>
        </ul>
      </div>
      <img src="/media/images/registry/logo.png" class="logo" style="float:left; margin-right: 50px;"/>
      <div class="title">Регистратура</div>
    </div>
  </div>
  <?php if ($referer): ?>
    <a class="prev-page-link" href="<?php echo $referer; ?>">Вернуться на предыдущую страницу</a>
  <?php endif; ?>
  <div class="content registry-content">
    <?php if (isset($menu_type) && $menu_type == 'clinic'): ?>
      <span style="font-size: 18px;color: #000000;"><?php echo $clinic->name; ?></span>
      <?php $this->block('registry/blocks/clinic-menu'); ?>
    <?php endif; ?>
    <?php if (isset($menu_type) && $menu_type == 'doctor'): ?>
      <?php $this->block('registry/blocks/doctor-menu'); ?>
    <?php endif; ?>

    <?php $this->content(); ?>

  </div>
  <?php if (debug): ?>
    <!--
    <div class="debug">
        Время выполнения метода: <?php echo $action_time; ?><br />
        Рендеринг: <?php echo(microtime(TRUE) - $start_render_time); ?>
    </div>
    -->
  <?php endif; ?>
</div>
</body>
</html>
