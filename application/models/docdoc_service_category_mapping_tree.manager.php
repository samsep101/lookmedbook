<?php

class DocdocServiceCategoryMappingTreeManager extends ModelManager
{
    protected $table_name = 'docdoc_service_category_mapping';
    protected $model_name = 'DocdocServiceCategoryMappingTreeModel';

    public function getListBySearchParams(SearchParams $search_params)
    {
        $q = 'SELECT sc.id, sc.name, sc.alias, sc.parent_id, dscm.id AS rule_id, dscm.mapped_service_id, dscm.service_id, dsc.full_name AS service_name
                FROM `service_category` sc
                LEFT JOIN docdoc_service_category_mapping dscm ON sc.docdoc_id=dscm.mapped_service_id
                LEFT JOIN docdoc_service_category dsc ON dsc.docdoc_id=dscm.service_id
                WHERE 
                  sc.is_active = 1 AND sc.docdoc_id IS NOT NULL 
                  AND sc.docdoc_id != 0 AND sc.service_type="service"
                  AND (sc.docdoc_id NOT IN (SELECT service_id FROM docdoc_service_category_mapping) OR dscm.service_id = dscm.mapped_service_id)
                ORDER BY parent_id ASC, name ASC';

        $rows = $this->db->query($q);

        // TODO Здесь классический белый зверек, сделать нормально
        $tree = [];
        $mapping = [];
        $beforeRows = PHP_INT_MAX;

        while (!empty($rows)) {
            foreach ($rows as $rowId => $row) {
                $one = $row;
                $one['subslugs'] = [];
                $one['count'] = 0;
                $id = $one['id'];
                $parent_id = $one['parent_id'];

                unset($one['parent_id']);

                $ignoreService = [
                    'docdoc_id' => &$one['service_id'],
                    'name'      => &$one['service_name'],
                    'rule_id'   => &$one['rule_id'],
                ];

                unset($one['service_id'], $one['service_name']);

                if($parent_id > 0) {
                    if (!empty($mapping[$parent_id]) && !empty($mapping[$mapping[$parent_id]['root_id']])) {
                        $rootAlias = $mapping[$mapping[$parent_id]['root_id']]['alias'];
                        $one['full_slug'] = isset($one['full_slug']) ? $one['full_slug'] : $rootAlias.'/'.$one['alias'];
                        $one['root_id'] = $mapping[$parent_id]['root_id'];

                        if (isset($mapping[$parent_id]['subslugs'][$id])) {
                            $mapping[$parent_id]['subslugs'][$id]['ignore_services'][] = $ignoreService;
                        } else {
                            $one['ignore_services'][] = $ignoreService;
                            $mapping[$parent_id]['subslugs'][$id] = &$one;
                        }
                        $mapping[$id] = &$one;
                        unset($rows[$rowId]);
                    }
                } else {
                    $one['root_id'] = $id;
                    $one['ignore_services'][] = $ignoreService;
                    $tree[$id] = &$one;
                    $mapping[$id] = &$one;
                    unset($rows[$rowId]);
                }
                unset($one);
            }
            if ($beforeRows == count($rows)) {
                // Something unexpected
                break;
            }
            $beforeRows = count($rows);
        }

        foreach($tree as $id => $root){

            $root['count'] = count($root['subslugs']);
            $tree[$id] = $root;
        }

        return $tree;
    }

    /**
     * @return DocdocServiceCategoryModel[]
     */
    public function getNotMappedServices()
    {
        $sql = <<<SQL
        SELECT 
          dsc.id, dsc.docdoc_id, dsc.full_name 
        FROM 
          docdoc_service_category dsc INNER JOIN service_category sc ON dsc.docdoc_id=sc.docdoc_id  
          LEFT JOIN docdoc_service_category_mapping dscm ON dsc.docdoc_id=dscm.mapped_service_id WHERE dscm.id is null;
SQL;

        $rows = $this->db->query($sql);

        return $rows ? $this->initList($rows) : [];
    }

    public function deleteByMappedId($mappedId)
    {
        $this->orm_model->delete(['mapped_service_id' => $mappedId]);
    }

    /**
     * @param DocdocServiceCategoryMappingTreeModel $model
     * @return bool
     * @throws Exception
     */
    public function save(DynamicModel $model)
    {
        $success = $this->db->beginTransaction();
        try {
            if ($success) {
                $success &= parent::save($model);

                if ($success) {
                    /** @var DocdocServiceCategoryMappingTreeModel[] $rules */
                    $rules = $this->initList($this->orm_model->select()->where('mapped_service_id = ?', $model->service_id)->fetchAll());

                    if ($rules) {
                        foreach ($rules as $rule) {
                            $bufRule = new DocdocServiceCategoryMappingTreeModel();
                            $bufRule->service_id = $rule->service_id;
                            $bufRule->mapped_service_id = $model->mapped_service_id;
                            $bufRule->save();
                            $rule->delete();
                        }
                    }

                    /** @var ServiceCategoryManager $manager */
                    $manager = ModelManagerFactory::getByName('service_category');
                    $manager->deleteByDocDocId($model->service_id);
                }
            }
        } catch (\Exception $e) {
            $success = false;
        } catch (\Error $e) {
            $success = false;
        }

        if ($success) {
            $success &= $this->db->commitTransaction();
        }

        if (!$success) {
            $this->db->rollbackTransaction();
        }

        return $success;
    }
}
