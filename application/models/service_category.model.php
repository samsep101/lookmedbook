<?php

use app\library\helpers\Morpher;

/**
	 * @property int $id
     * @property string $service_type
	 * @property string $alias
	 * @property string $name
     * @property string $genitive_name
	 * @property int $is_active
	 * @property int $priority
     * @property int $created_at
     * @property int $updated_at
     * @property int $docdoc_id
     * @property int $parent_id
     * @property ServiceCategoryModel|null $parent
     * @property int linked_service_id
     * @property int $specialty_id
     * @property string $top_phone
     * @property string $top_phone_text
     *
	 *
	 */
	class ServiceCategoryModel extends DynamicModel
	{
	    public $priority = 0;

	    const TYPE_DIAGNOSTIC = 'diagnostic';
	    const TYPE_SERVICE = 'service';

	    public function getGenitiveName()
        {
            if ($this->genitive_name) {
                return $this->genitive_name;
            }
            if (extension_loaded('morpher')) {
                return Morpher::inflect($this->name, 'rod');
            }
            return WordDeclination::getInstance()->toGenitive($this->name);
        }

        protected function _field_parent()
        {
            if (!$this->parent_id) {
                return null;
            }

            /**
             * @var ServiceCategoryManager $manager
             */
            $manager = ModelManagerFactory::getByName('service_category');
            $this->parent = $manager->getOneById($this->parent_id);

            return $this->parent;
        }
	}
