<?php

    /**
     * Class AppealModel
     *
     * @property int $id
     * @property string $first_name
     * @property string $last_name
     * @property string $middle_name
     * @property string $full_name
     * @property string $phone_number
     * @property int $appeal_type_id
     * @property AppealTypeModel $appeal_type
     * @property int $visit_source_id
     * @property VisitSourceModel $visit_source
     * @property string $title
     * @property int $specialty_id
     * @property SpecialtyModel $specialty
     * @property int $is_with_visit
     * @property datetime $dt_create
     * @property int $account_id
     * @property int $city_id
     * @property AccountModel $account
     *
     * @property int $visit_id
     * @property int $target_call_id
     * @property TargetCallModel $target_call
     */
    class AppealModel extends DynamicModel
    {

        protected function _field_full_name()
        {
            $str = '';

            if($this->last_name)
                $str .= $this->last_name.' ';

            if($this->first_name)
                $str .= $this->first_name.' ';

            if($this->middle_name)
                $str .= $this->middle_name;

            $str = trim($str);

            return $str;
        }

        /**
         * @return VisitModel|null
         */
        public function getVisit()
        {
            return $this->_field_visit();
        }

        protected function _field_visit()
        {
            /**
             * @var VisitManager $visit_manager
             */
            $visit_manager = ModelManagerFactory::getByName('visit');

            return $visit_manager->getOneByAppealId($this->getId());
        }

        protected function _field_target_call()
        {
            if(isset($this->target_call) && $this->target_call) {
                return $this->target_call;
            }

            /**
             * @var TargetCallManager $target_call_manager
             * @var TargetCallModel $target_call
             */
            $target_call_manager = ModelManagerFactory::getByName('target_call');
            $target_call = $target_call_manager->getOneById($this->target_call_id);
            $this->target_call = ($target_call) ? $target_call : null;

            return $this->target_call;
        }
    }