<?php
    /**
     * @var int $city_id
     * @var CityModel[] $cities
     */
?>

Город:
<select name="city_id" style="width: 200px">
    <option value="0">Все</option>
    <?php if ($cities):?>
    <?php foreach ($cities as $city):?>
        <option value="<?php echo $city->getId();?>" <?php if (isset($city_id) && $city_id == $city->getId()){?>selected="selected"<?php }?>><?php echo $city->name;?></option>
        <?php endforeach;?>
    <?php endif;?>
</select>