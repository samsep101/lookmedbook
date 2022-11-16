<?php
class AnalyticCounterManager extends ModelManager
{
    protected $table_name = 'analytic_counter';
    protected $model_name = 'AnalyticCounterModel';

    public function getOneByAnalyticCounterTypeIdAndCityId($analytic_counter_type_id, $city_id)
    {
        $sql = 'SELECT analytic_counter.*
                FROM analytic_counter
                WHERE analytic_counter_type_id = '.(int)$analytic_counter_type_id.'
                    AND city_id = '.(int)$city_id;

        $data = $this->db->query($sql);
        return (isset($data[0])) ? $this->initOne($data[0]) : '';
    }
}
