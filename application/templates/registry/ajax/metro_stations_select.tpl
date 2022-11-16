<?php
    /**
     * @var View $this
     * @var MetroStationModel[] $metro_stations
     * @var int $city_id
     */
?>

<?php if($metro_stations): ?>
    <?php $cache_id = 'metro_station_id_' .$city_id; ?>
    <?php if (!$cache->start($cache_id,  'metro_station_block')): ?>
        <select name="form[metro_station_id]">
            <option value="0">выберите станцию метро</option>
            <?php foreach($metro_stations as $metro_station): ?>
                <option value="<?php echo $metro_station->getId(); ?>"><?php echo $metro_station->name_with_city_name; ?></option>
            <?php endforeach; ?>
        </select>
        <?php $cache->end(); ?>
    <?php endif; ?>
<?php endif; ?>