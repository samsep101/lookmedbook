<ul>
    <?php if(Acl::userId()){?>
        <?php $controllers = Acl::getUserControllers()?>
        <?php foreach($controllers as $key => $value){?>
            <li class="menuCat"><a href="/admin/<?php echo $key; ?>" class="<?php echo $key; ?> nl" ><span><?php echo $value; ?></span></a></li>
        <?php }?>
		<?php if (Acl::userRole() == RoleModel::ESHOP_MANAGER): ?>
			<li class="menuCat"><a href="/admin/statistic/shop_manager" class="nl" ><span>Отчет по контент-менеджерам</span></a></li>
		<?php endif; ?>
    <?php }?>
</ul>