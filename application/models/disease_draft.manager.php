<?php

class DiseaseDraftManager extends ModelManager
{
    protected $table_name = 'disease_draft';
    protected $model_name = 'DiseaseDraftModel';

    protected $suppressAfterSave = false;

    /**
     * @param DiseaseDraftModel $model
     */
    protected function create(DynamicModel $model)
    {
        $model->created_at = time();
        if (!$model->updated_at) {
            $model->updated_at = time();
        }
        if (!$model->status) {
            $model->status = DiseaseDraftModel::STATUS_EDITING;
        }
        parent::create($model);
    }

    /**
     * @param DiseaseDraftModel $model
     */
    protected function afterSave(DynamicModel $model)
    {
        parent::afterSave($model);

        if (!$this->suppressAfterSave) {
            $this->saveOrUpdateRelatedDisease($model);
        }
    }

    public function getOneByDiseaseId($diseaseId)
    {
        $sql = 'SELECT *
          FROM ' . $this->table_name . '
          WHERE disease_id = "' . (int)$diseaseId . '"';
        $db = Register::get('db');
        $data = $db->query($sql);

        return (isset($data[0])) ? $this->initOne($data[0]) : null;
    }

    protected function saveOrUpdateRelatedDisease(DiseaseDraftModel $model)
    {
        if ($model->disease_id) {
            /** @var DiseaseModel $disease */
            $disease = (ModelManagerFactory::getByName('disease')->getOneById($model->disease_id));
        }

        if (empty($disease)) {
            $disease = new DiseaseModel();
        }

        if ($this->cloneToRelatedDisease($model, $disease)) {
            $this->orm_model->update(['disease_id' => $disease->getId()], ['id' => $model->getId()]);

            return true;
        }

        return false;
    }
    public function log($content){
        $file = fopen('/home/vhost/mebook/www/l.txt', 'a');
        fwrite($file, $content);
        fclose($file);
    }

    protected function cloneToRelatedDisease(DiseaseDraftModel $diseaseDraftModel, DiseaseModel $diseaseModel)
    {

        $this->log(print_r($diseaseModel, 1));
        $this->log(print_r($diseaseDraftModel, 1));

        $diseaseModel->title = $diseaseDraftModel->title;
        $diseaseModel->genitive_name = $diseaseDraftModel->genitive_name;
        $diseaseModel->prepositional_name = $diseaseDraftModel->prepositional_name;
        $diseaseModel->content = $diseaseDraftModel->content;
        $diseaseModel->extended_content = $diseaseDraftModel->extended_content;
        $diseaseModel->sources = $diseaseDraftModel->sources;
        $diseaseModel->is_active = $diseaseDraftModel->status == DiseaseDraftModel::STATUS_DONE;
        $diseaseModel->date_update = date("Y-m-d H:i:s", $diseaseDraftModel->updated_at);

        $diseaseModel->save();

        if (!$diseaseModel->getId()) {
            return false;
        }

        /** @var DiseaseAltNameManager $diseaseAltNameManager */
        $diseaseAltNameManager = ModelManagerFactory::getByName('disease_alt_name');
        $diseaseAltNameManager->deleteByDiseaseId($diseaseModel->id);

        if ($diseaseDraftModel->alternative_names) {
            $alternativeNames = str_replace('.', '', $diseaseDraftModel->alternative_names);
            $alternativeNames = explode(",", $alternativeNames);
            foreach ($alternativeNames as $alternativeName) {
                $diseaseAltName = new DiseaseAltNameModel();
                $diseaseAltName->alt_name = trim($alternativeName);
                $diseaseAltName->disease_id = $diseaseModel->getId();
                $diseaseAltName->save();
            }
        }

        /** @var SpecialtyToDiseaseManager $specialtyToDiseaseManager */
        $specialtyToDiseaseManager = ModelManagerFactory::getByName('specialty_to_disease');
        $specialtyToDiseaseManager->deleteByDiseaseId($diseaseModel->getId());
        foreach ($diseaseDraftModel->specialties as $specialtyToDiseaseDraftModel) {
            $specialtyToDiseaseModel = new SpecialtyToDiseaseModel();
            $specialtyToDiseaseModel->specialty_id = $specialtyToDiseaseDraftModel->specialty_id;

            $specialtyToDiseaseModel->disease_id = $diseaseModel->getId();
            $specialtyToDiseaseModel->is_adult = $specialtyToDiseaseDraftModel->is_adult;
            $specialtyToDiseaseModel->is_male = $specialtyToDiseaseDraftModel->is_male;
            $specialtyToDiseaseModel->is_female = $specialtyToDiseaseDraftModel->is_female;
            $specialtyToDiseaseModel->is_children = $specialtyToDiseaseDraftModel->is_children;
            $specialtyToDiseaseModel->is_newborn = $specialtyToDiseaseDraftModel->is_newborn;
            $specialtyToDiseaseModel->is_pregnant = $specialtyToDiseaseDraftModel->is_pregnant;
            $specialtyToDiseaseModel->main_flag = $specialtyToDiseaseDraftModel->main_flag;
            $specialtyToDiseaseModel->save();
        }

        /** @var DiseaseBlockManager $diseaseBlockManager */
        $diseaseBlockManager = ModelManagerFactory::getByName('disease_block');
        $diseaseBlockManager->deleteByDiseaseId($diseaseModel->getId());

        foreach ($diseaseDraftModel->contentBlocks as $contentBlock) {
            $diseaseBlock = new DiseaseBlockModel();
            $diseaseBlock->disease_id = $diseaseModel->getId();

            $diseaseBlock->disease_block_type_id = $contentBlock->disease_block_type_id;
            $diseaseBlock->content = $contentBlock->content;
            $diseaseBlock->is_active = $contentBlock->is_active;
            $diseaseBlock->male_flag = $contentBlock->is_male;
            $diseaseBlock->female_flag = $contentBlock->is_female;
            $diseaseBlock->adult_flag = $contentBlock->is_adult;
            $diseaseBlock->children_flag = $contentBlock->is_children;
            $diseaseBlock->newborn_flag = $contentBlock->is_newborn;
            $diseaseBlock->pregnant_flag = $contentBlock->is_pregnant;

            $diseaseBlock->save();
        }

        /** @var DiseaseTagManager $diseaseTagManager */
        $diseaseTagManager = ModelManagerFactory::getByName('disease_tag');
        /** @var DiseaseToDiseaseTagManager $diseaseToDiseaseTagManager */
        $diseaseToDiseaseTagManager = ModelManagerFactory::getByName('disease_to_disease_tag');
        $diseaseToDiseaseTagManager->deleteByDiseaseId($diseaseModel->getId());

        $tags = explode(",", $diseaseDraftModel->tags);
        foreach ($tags as $tag) {
            $tag = trim($tag);
            $diseaseTag = $diseaseTagManager->createIfNotExists($tag);
            $diseaseToDiseaseTag = new DiseaseToDiseaseTagModel();
            $diseaseToDiseaseTag->disease_id = $diseaseModel->getId();
            $diseaseToDiseaseTag->disease_tag_id = $diseaseTag->getId();
            $diseaseToDiseaseTag->save();
        }

        return true;
    }

    public function saveOrUpdateRelatedDraftByDisease(DiseaseModel $model)
    {
        $oldSuppressAfterSave = $this->suppressAfterSave;
        try {
            $this->suppressAfterSave = true;
            if ($model->getId()) {
                /** @var DiseaseDraftModel $disease */
                $diseaseDraft = $this->getOneByDiseaseId($model->id);
            }

            if (empty($diseaseDraft)) {
                $diseaseDraft = new DiseaseDraftModel();
            }

            return $this->cloneToRelatedDiseaseDraft($model, $diseaseDraft);
        } finally {
            $this->suppressAfterSave = $oldSuppressAfterSave;
        }
    }

    protected function cloneToRelatedDiseaseDraft(DiseaseModel $diseaseModel, DiseaseDraftModel $diseaseDraftModel)
    {
        $diseaseDraftModel->disease_id = $diseaseModel->getId();
        $diseaseDraftModel->name = $diseaseModel->title;
        $diseaseDraftModel->genitive_name = $diseaseModel->genitive_name;
        $diseaseDraftModel->prepositional_name = $diseaseModel->prepositional_name;
        $diseaseDraftModel->content = $diseaseModel->content;
        $diseaseDraftModel->extended_content = $diseaseModel->extended_content;
        $diseaseDraftModel->sources = $diseaseModel->sources;
        $diseaseDraftModel->updated_at = strtotime($diseaseModel->date_update);
        $diseaseDraftModel->status = $diseaseModel->is_active ? DiseaseDraftModel::STATUS_DONE : DiseaseDraftModel::STATUS_NOT_IN_WORK;

        $diseaseDraftModel->alternative_names = $diseaseModel->alt_names_string;
        $diseaseDraftModel->tags = implode(',', $diseaseModel->tags);

        $diseaseDraftModel->save();

        if (!$diseaseDraftModel->getId()) {
            return false;
        }

        /** @var SpecialtyToDiseaseDraftManager $specialtyToDiseaseDraftManager */
        $specialtyToDiseaseDraftManager = ModelManagerFactory::getByName('specialty_to_disease_draft');
        $specialtyToDiseaseDraftManager->deleteByDiseaseDraftId($diseaseDraftModel->getId());

        /** @var SpecialtyToDiseaseManager $specialtyToDiseaseManager */
        $specialtyToDiseaseManager = ModelManagerFactory::getByName('specialty_to_disease');
        $specialties = $specialtyToDiseaseManager->getListByDiseaseId($diseaseModel->getId());
        foreach ($specialties as $specialty) {
            $specialtyToDiseaseDraftModel = new SpecialtyToDiseaseDraftModel();
            $specialtyToDiseaseDraftModel->specialty_id = $specialty->specialty_id;

            $specialtyToDiseaseDraftModel->disease_draft_id = $diseaseDraftModel->getId();
            $specialtyToDiseaseDraftModel->is_adult = $specialty->is_adult;
            $specialtyToDiseaseDraftModel->is_male = $specialty->is_male;
            $specialtyToDiseaseDraftModel->is_female = $specialty->is_female;
            $specialtyToDiseaseDraftModel->is_children = $specialty->is_children;
            $specialtyToDiseaseDraftModel->is_newborn = $specialty->is_newborn;
            $specialtyToDiseaseDraftModel->is_pregnant = $specialty->is_pregnant;
            $specialtyToDiseaseDraftModel->main_flag = $specialty->main_flag;
            $specialtyToDiseaseDraftModel->save();
        }

        /** @var DiseaseDraftBlockManager $diseaseDraftBlockManager */
        $diseaseDraftBlockManager = ModelManagerFactory::getByName('disease_draft_block');
        $diseaseDraftBlockManager->deleteByDiseaseDraftId($diseaseDraftModel->getId());

        /** @var DiseaseBlockManager $diseaseBlockManager */
        $diseaseBlockManager = ModelManagerFactory::getByName('disease_block');
        $blocks = $diseaseBlockManager->getActiveListByDiseaseId($diseaseModel->getId());
        foreach ($blocks as $contentBlock) {
            $diseaseBlock = new DiseaseDraftBlockModel();
            $diseaseBlock->disease_draft_id = $diseaseDraftModel->getId();

            $diseaseBlock->disease_block_type_id = $contentBlock->disease_block_type_id;
            $diseaseBlock->content = $contentBlock->content;
            $diseaseBlock->title = $contentBlock->title;
            $diseaseBlock->is_active = $contentBlock->is_active;
            $diseaseBlock->is_male = $contentBlock->male_flag;
            $diseaseBlock->is_female = $contentBlock->female_flag;
            $diseaseBlock->is_adult = $contentBlock->adult_flag;
            $diseaseBlock->is_children = $contentBlock->children_flag;
            $diseaseBlock->is_newborn = $contentBlock->newborn_flag;
            $diseaseBlock->is_pregnant = $contentBlock->pregnant_flag;

            $diseaseBlock->save();
        }

        return true;
    }

}