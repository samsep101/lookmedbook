<?php

/**
 * @property int $id
 * @property string $name
 * @property string $genitive_name
 * @property string $prepositional_name
 * @property string $alternative_names
 * @property string $tags
 * @property string $content
 * @property string $extended_content
 * @property string $sources
 * @property string $status
 * @property int $disease_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $author_id
 * @property int $editor_id
 *
 * @property-read string $statusName
 * @property-read DiseaseModel $disease
 * @property-read SpecialtyToDiseaseDraftModel[] $specialties
 * @property-read DiseaseDraftBlockModel[] $contentBlocks
 */
class DiseaseDraftModel extends DynamicModel
{
    const STATUS_NOT_IN_WORK = 0;
    const STATUS_COPYWRITING = 1;
    const STATUS_MEDICAL_EDITING = 2;
    const STATUS_EDITING = 3;
    const STATUS_VERIFICATION = 4;
    const STATUS_RATING = 5;
    const STATUS_DONE = 6;

    protected static $statuses = [
        self::STATUS_NOT_IN_WORK => 'Не в работе',
        self::STATUS_COPYWRITING => 'Копирайтинг',
        self::STATUS_MEDICAL_EDITING => 'Медицинская редактура',
        self::STATUS_EDITING  => 'Редактура',
        self::STATUS_VERIFICATION => 'Проверка',
        self::STATUS_RATING => 'Оценка',
        self::STATUS_DONE => 'Готов',
    ];

    public static function getStatuses()
    {
        return static::$statuses;
    }

    public function _field_statusName()
    {
        return isset(static::$statuses[$this->status]) ? static::$statuses[$this->status] : null;
    }

    /**
     * @return SpecialtyToDiseaseDraftModel[]
     */
    public function _field_specialties()
    {
        /** @var SpecialtyToDiseaseDraftManager $manager */
        $manager = ModelManagerFactory::getByName('specialty_to_disease_draft');
        $specialties = $manager->getByDiseaseDraftId($this->id);
        return $specialties;
    }

    public function _field_title(){
return 123;
	}
    /**
     * @return DiseaseDraftBlockModel[]
     */
    public function _field_contentBlocks()
    {
        /** @var DiseaseDraftBlockManager $manager */
        $manager = ModelManagerFactory::getByName('disease_draft_block');
        $contentBlocks = $manager->getByDiseaseDraftId($this->id);
        return $contentBlocks;
    }

    /**
     * @return DiseaseModel|null
     */
    public function _field_disease()
    {
        if ($this->disease_id) {
            /** @var DiseaseManager $manager */
            $manager = ModelManagerFactory::getByName('disease');
            return $manager->getOneById($this->disease_id);
        }

        return null;
    }
}
