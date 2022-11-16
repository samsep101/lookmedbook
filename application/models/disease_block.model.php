<?php

/**
 * @property int $id
 * @property int $disease_id
 * @property DiseaseModel $disease
 * @property int $disease_block_type_id
 * @property DiseaseBlockTypeModel $disease_block_type
 * @property string $content
 * @property int $is_active
 * @property int $male_flag
 * @property int $female_flag
 * @property int $adult_flag
 * @property int $children_flag
 * @property int $newborn_flag
 * @property int $pregnant_flag
 */
class DiseaseBlockModel extends DynamicModel
{
    private static $typesWithExtendableName = [
        DiseaseDraftBlockModel::DISEASE_BLOCK_SYMPTOMS,
        DiseaseDraftBlockModel::DISEASE_BLOCK_TREATMENT,
        DiseaseDraftBlockModel::DISEASE_BLOCK_PREVENTION,
    ];

    /**
     * @return bool
     */
    public function hasExtendableName()
    {
        return in_array($this->disease_block_type_id, self::$typesWithExtendableName, false);
    }
}
