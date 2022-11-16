<?php
    /**
     * @var string $active_top_menu
     */
?>

<script type="text/javascript">
    $(document).ready(function() {
        if(navigator.platform == 'MacIntel') {
            $(".cab-page").width('972px');
        }
    });
</script>
<h1 class="help-title">Личный кабинет</h1>
<div class="nav">
    <ul>
        <li <?php echo ($active_top_menu == 'profile') ? 'class="ui-state-active"' : ''; ?>> <a href="/account/about"><i></i>Профиль</a></li>
        <li <?php echo ($active_top_menu == 'visits') ? 'class="ui-state-active"' : ''; ?>><a href="/account/doctorsVisitsComing"><i></i>Запись к врачу</a></li>
        <li <?php echo ($active_top_menu == 'my_doctors') ? 'class="ui-state-active"' : ''; ?>><a href="/account/my_doctor"><i></i>Мои врачи</a></li>
        <li <?php echo ($active_top_menu == 'my_clinics') ? 'class="ui-state-active"' : ''; ?>><a href="/account/my_clinic"><i></i>Мои клиники</a></li>
        <li <?php echo ($active_top_menu == 'my_diseases') ? 'class="ui-state-active"' : ''; ?>><a href="/account/my_disease"><i></i>Список заболеваний</a></li>
        <li <?php echo ($active_top_menu == 'orders') ? 'class="ui-state-active"' : ''; ?>><a href="/account/orders"><i></i>Заказы лекарств</a></li>
    </ul>
</div>
