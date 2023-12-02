<?php

use app\library\helpers\Morpher;

class ServiceCategorySimpleModel extends SimpleModel {
    protected $table = 'service_category';
    protected $table_district = 'district';
    protected $m2m_clinics = 'service_to_clinic';

    public function getTree() {
        /** @var Memcache $memcache */
        $memcache = Register::get('memcache');
        $tree = $memcache->get("tree" . $this->cityID);
        if ($tree !== false) {
            return $tree;
        }

        $q = $this->replace([
            '{services}' => $this->table,
            '{relations}' => $this->m2m_clinics,
            '{city}' => $this->cityID,
        ], 'SELECT sc.id, sc.alias, sc.name, sc.genitive_name, parent_id, linked_service_id, SUM(IF(c.city_id = {city}, 1, 0)) as total
                FROM `{services}` sc
                LEFT JOIN `{relations}` s2c ON sc.id = s2c.service_id
                LEFT JOIN `clinic` c ON s2c.clinic_id = c.id
                WHERE sc.is_active = 1
                GROUP BY sc.id
                ORDER BY parent_id ASC, name ASC');

        $rows = $this->db->query($q);

        // TODO Здесь классический белый зверек, сделать нормально
        $tree = [];
        $mapping = [];
        $rowsToLink = [];
        $beforeRows = PHP_INT_MAX;
        $serviceIds = [];
        foreach ($rows as $rowId => $row) {
            $serviceIds[] = $row['id'];
        }
//error_log(print_r($serviceIds, true));

        $indexControl = new ElasticSearchServicePriceIndexControl();
        // get all from elastic by cityId
        $criteria = new ServicePriceSearchCriteria();
        $criteria->city_id = $this->cityID;
        $criteria->by_page = 100;
        $criteria->page = 0;

        $services = [];
        do {
            $criteria->page++;
            $services += $indexControl->search($criteria);
        } while ($criteria->by_page*$criteria->page < $indexControl->getTotalHits());

        foreach ($rows as $rowId => &$row) {
            $key = $row['id'] . '_' . $this->cityID;
            if (isset($services[$key])) {
                $row['min_price'] = $services[$key]->min_price;
            } else {
                $row['min_price'] = null;
            }

        }
        unset($row);

        while (!empty($rows)) {
            foreach ($rows as $rowId => $row) {
                $one = $row;
                $one['subslugs'] = [];
                $one['count'] = 0;
                $id = $one['id'];
                $parent_id = $one['parent_id'];

                if (isset($one['linked_service_id'])) {
                    if (isset($mapping[$one['linked_service_id']])) {
                        $this->applyLinkData($one, $mapping[$one['linked_service_id']]);
                    } else {
                        $rowsToLink[] = &$one;
                    }
                }

                unset($one['parent_id']);
//if ($id == 4862) error_log(print_r($one, true));
                if($parent_id > 0) {
                    if (!empty($mapping[$parent_id]) && !empty($mapping[$mapping[$parent_id]['root_id']])) {
                        if (!isset($one['min_price']) && isset($mapping[$parent_id]['min_price'])) {
                            $one['min_price'] = $mapping[$parent_id]['min_price'];
                            $one['total'] = $mapping[$parent_id]['total'];
                        }

                        $rootAlias = $mapping[$mapping[$parent_id]['root_id']]['alias'];
                        $one['full_slug'] = isset($one['full_slug']) ? $one['full_slug'] : $rootAlias.'/'.$one['alias'];
                        $one['root_id'] = $mapping[$parent_id]['root_id'];
                        $mapping[$parent_id]['subslugs'][$id] = &$one;
                        $mapping[$id] = &$one;
                        unset($rows[$rowId]);
                    }
                } else {
                    $one['root_id'] = $id;
                    $tree[$id] = &$one;
                    $mapping[$id] = &$one;
                    unset($rows[$rowId]);
                }
                unset($one);
            }
            if ($beforeRows == count($rows)) {
                // Something unexpected
//error_log(print_r($rows, true));
                error_log('Error while building service tree');
                break;
            }
            $beforeRows = count($rows);
        }

        foreach ($rowsToLink as &$row) {
            if (isset($mapping[$row['linked_service_id']])) {
                $this->applyLinkData($row, $mapping[$row['linked_service_id']]);
            }
            unset($row);
        }


        foreach($tree as $id => $root){

            $root['count'] = count($root['subslugs']);
            $this->fillName($root);
            $tree[$id] = $root;
        }

        $memcache->set("tree{$this->cityID}", $tree, MEMCACHE_COMPRESSED,  1800);

        return $tree;

    }

    protected function applyLinkData(&$model, $linkedModel)
    {
        $model['full_slug'] = !empty($linkedModel['full_slug']) ? $linkedModel['full_slug'] : $linkedModel['alias'];
        $model['min_price'] = $linkedModel['min_price'];
        $model['total'] = $linkedModel['total'];
    }

    protected function fillName(&$data)
    {
        if (isset($data) && empty($data['genitive_name'])) {
            if (extension_loaded('morpher')) {
                $data['genitive_name'] = Morpher::inflect($data['name'], 'rod');
            } else {
                $data['genitive_name'] =  WordDeclination::getInstance()->toGenitive($data['name']) ?: $data['name'];
            }
        }
    }


    public function getById($id) {

        $q = str_replace(['{table}', '{id}'], [
            $this->table,
            intval($id)
        ], 'SELECT id, alias, name, parent_id, description, full_description, specialty_id, top_phone, top_phone_text FROM {table} WHERE id = {id} LIMIT 1');

        $data = $this->db->get($q);
        $this->fillName($data);
        return $data;
    }

    public function getByAlias($alias) {

        $q = str_replace(['{table}', '{alias}'], [
            $this->table,
            $this->escape($alias)
		], 'SELECT id, alias, service_type, name, genitive_name, parent_id, description, full_description, specialty_id, top_phone, top_phone_text, linked_service_id FROM {table} WHERE alias = {alias} LIMIT 1');

        $data = $this->db->get($q);
        $this->fillName($data);
		return $data;
    }

    public function getClinicsCount($service_id) {

        return $this->total($this->m2m_clinics, '`service_id` = ' . (int)$service_id);
    }

    public function getClinicCountRoots($service_id) {

        $q = $this->replace([
            '{t.relations}' => $this->m2m_clinics,
            '{service_id}' => (int)$service_id,
            '{city_id}' => $this->cityID,
        ], 'SELECT COUNT(DISTINCT(s2c.clinic_id)) as total
                FROM {t.relations} s2c
                    INNER JOIN clinic c ON s2c.clinic_id = c.id
                WHERE s2c.service_id = {service_id}
                    AND c.city_id = {city_id}');

        return intval($this->db->get($q)['total']);
    }

    public function getDistricts() {

        return $this->get_where_orderby($this->table_district, 'city_id = '.$this->cityID);
    }

}

/* END CLASS: ServicesModel extends SimpleModel */
