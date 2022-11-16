<?php

	/**
	 * Class WidgetSiteManager
	 * @method WidgetSiteModel getOneById
	 * @method WidgetSiteModel[] getList
	 */
	class WidgetSiteManager extends ModelManager
	{
		protected $table_name = "widget_site";
		protected $model_name = "WidgetSiteModel";

        protected function beforeSave(DynamicModel $model)
        {
            /**
             * @var WidgetSiteModel $model
             */
            if(!$model->identifier)
            {
                $model->identifier = sha1($model->getId().StringGeneratorHelper::generate(40));
            }
        }

        /**
         * @param $identifier
         * @return WidgetSiteModel
         */
        public function getOneByIdentifier($identifier)
        {
            $data = $this->orm_model->select()->where('identifier = ?', $identifier)->fetchOne();
            return $this->initOne($data);
        }

        protected function afterSave(DynamicModel $model)
        {
            /**
             * @var WidgetSiteModel $model
             */
            $code_generator = new WidgetSettingsCodeGenerator();
            $code_generator->generate($model);
        }
	}