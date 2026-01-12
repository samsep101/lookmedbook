<?php

/**
 * @property string $title
 * @property int $id
 * @property int $disease_draft_id
 * @property int $disease_block_type_id
 * @property string $content
 * @property bool $is_active
 * @property string $title_block
 * @property bool $is_adult
 * @property int $order
 * @property bool $is_male
 * @property bool $is_female
 * @property bool $is_children
 * @property bool $is_newborn
 * @property bool $is_pregnant
 * @property bool $main_flag
 * @property int $created_at
 * @property int $updated_at
 * @property int $author_id
 * @property int $editor_id
 *
 * @property-read string $diseaseBlockType
 */
class DiseaseDraftBlockModel extends DynamicModel
{

    const DISEASE_BLOCK_SYMPTOMS = 1;
    const DISEASE_BLOCK_INCUBATION_PERIOD  = 2;
    const DISEASE_BLOCK_FORMS = 3;
    const DISEASE_BLOCK_CAUSES = 4;
    const DISEASE_BLOCK_DIAGNOSTIC = 5;
    const DISEASE_BLOCK_TREATMENT = 6;
    const DISEASE_BLOCK_COMPLICATIONS_CONSEQUENCES = 7;
    const DISEASE_BLOCK_PREVENTION = 8;
    const DISEASE_BLOCK_ADDITIONAL_INFO = 9;

    protected static $diseaseBlockTypes = [
        self::DISEASE_BLOCK_SYMPTOMS => 'Симптомы',
        self::DISEASE_BLOCK_INCUBATION_PERIOD => 'Инкубационный период',
        self::DISEASE_BLOCK_FORMS => 'Формы',
        self::DISEASE_BLOCK_CAUSES => 'Причины',
        self::DISEASE_BLOCK_DIAGNOSTIC => 'Диагностика',
        self::DISEASE_BLOCK_TREATMENT => 'Лечение',
        self::DISEASE_BLOCK_COMPLICATIONS_CONSEQUENCES => 'Осложнения и последствия',
        self::DISEASE_BLOCK_PREVENTION => 'Профилактика',
        self::DISEASE_BLOCK_ADDITIONAL_INFO => 'Дополнительно',
    ];

    public static function getDiseaseBlockTypes()
    {
        return static::$diseaseBlockTypes;
    }

    public function _filed_title() {
	    return 'статья2';
    }
    public function _field_diseaseBlockType()
    {
        return isset(static::$diseaseBlockTypes[$this->disease_block_type_id])
            ? static::$diseaseBlockTypes[$this->disease_block_type_id]
            : null;
    }
}
