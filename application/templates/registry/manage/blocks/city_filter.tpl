<?php
    /**
     * @var CityModel[] $cities
     * @var int $registry_city
     * @var string $instance
     * @var string $page_name
     */
?>

<div style="margin:20px;">
    Город:
    <select name="city_id" data-page-name="<?php if (isset($page_name) && $page_name) echo $page_name;?>" style="width: 250px">
        <option value="0">Все</option>
        <?php if (isset($instance) && $instance == 'doctor' && Acl::userRole() != RoleModel::FREELANCE_MANAGER):?>
            <option value="100000" <?php if ($registry_city == 100000){?>selected<?php }?>>Врачи без привязки к клиникам</option>
        <?php endif;?>
        <?php if ($cities):?>
        <?php foreach ($cities as $city):?>
            <option value="<?php echo $city->getId();?>" <?php if ($registry_city == $city->getId()){?>selected<?php }?>><?php echo $city->name;?></option>
            <?php endforeach;?>
        <?php endif;?>
    </select>
</div>