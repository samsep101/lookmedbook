<?php

class SpecialtyToDiseaseDraftManager extends ModelManager
{
    protected $table_name = 'specialty_to_disease_draft';
    protected $model_name = 'SpecialtyToDiseaseDraftModel';

    public function create(SpecialtyToDiseaseDraftModel $model)
    {
        $model->created_at = time();
        $model->updated_at = time();
        parent::create($model);
    }


    /**
     * @param $diseaseDraftId
     * @return SpecialtyToDiseaseDraftModel[]
     */
    public function getByDiseaseDraftId($diseaseDraftId)
    {
        $data = $this->orm_model->select()->where('disease_draft_id = ?', $diseaseDraftId)->fetchAll();
        return $data ? $this->initList($data) : [];
    }

    public function deleteByDiseaseDraftId($diseaseDraftId)
    {
        $this->orm_model->delete('disease_draft_id = ' . (int)$diseaseDraftId);
    }
}
