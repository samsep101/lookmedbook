<?php


class DiseaseDraftBlockManager extends ModelManager
{
    protected $table_name = 'disease_draft_block';
    protected $model_name = 'DiseaseDraftBlockModel';


    public function create(DiseaseDraftBlockModel $model)
    {
        $model->created_at = time();
        $model->updated_at = time();
        parent::create($model);
    }

    /**
     * @param $diseaseDraftId
     * @return DiseaseDraftBlockModel[]
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
