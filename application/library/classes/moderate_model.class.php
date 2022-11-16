<?php
    class ModerateModel extends DynamicModel
    {
        public function publishRevision()
        {
            $manager = $this->getManager();
			$field = $this->getManager()->getModerateEntityName().'_id';
            $this->beforePublish();
            $manager->publishRevision($this->{$field}, $this);
        }

        protected function beforePublish()
        {

        }

        /**
         * @return EntityModerateModelManager
         */
        public function getManager()
        {
            return parent::getManager();
        }
    }