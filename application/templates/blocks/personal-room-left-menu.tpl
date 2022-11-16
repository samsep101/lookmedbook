<?php if (isset($active_left_menu)): ?>
    <ul class="sub-menu">
        <li <?php echo ($active_left_menu == 'about') ? 'class="active"' : ''; ?> > <a href="/account/about">О себе</a> </li>
        <li <?php echo ($active_left_menu == 'message') ? 'class="active"' : ''; ?>><a href="/account/message">Сообщения</a></li>
        <li <?php echo ($active_left_menu == 'family') ? 'class="active"' : ''; ?>><a href="/account/family">Семья</a></li>
        <li <?php echo ($active_left_menu == 'trust') ? 'class="active"' : ''; ?>><a href="/account/trust">Доверие</a></li>
        <li <?php echo ($active_left_menu == 'reviews') ? 'class="active"' : ''; ?>><a href="/account/reviews">Отзывы</a></li>
        <li <?php echo ($active_left_menu == 'options') ? 'class="active"' : ''; ?>><a href="/account/options">Настройки</a></li>
    <!--<img src="/media/images/color-banner-3.gif" alt="">
    <img src="/media/images/color-banner-1.gif" alt="">
    <img src="/media/images/color-banner-2.gif" alt="">-->
    </ul>
<?php endif; ?>



<?php if (isset($active_left_disease_menu)): ?>
    <ul class="sub-menu sub-menu-var">
        <li <?php echo ($active_left_disease_menu == 'read') ? 'class="active"' : ''; ?>><a href="/account/my_disease">К прочтению</a></li>
        <li <?php echo ($active_left_disease_menu == 'archive') ? 'class="active"' : ''; ?>><a href="/account/my_disease/archive">Архив</a></li>
    </ul>
<?php endif; ?>