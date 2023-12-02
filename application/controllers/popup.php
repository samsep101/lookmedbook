<?php
	class PopupController extends BaseController
	{
		public function __construct()
		{
			$this->layout = 'ajax';
			parent::__construct();
		}

		public function login()
		{

		}

		public function registration()
		{

		}

        public function appeal()
        {
            if(!$this->current_account || !$this->current_account->is_call_centre_operator)
                JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);

            /**
             * @var SpecialtyManager $specialty_manager
             * @var AppealTypeManager $appeal_type_manager
             * @var VisitSourceManager $visit_source_manager
             * @var TargetCallManager $target_call_manager
             */
            $specialty_manager = ModelManagerFactory::getByName('specialty');
            $appeal_type_manager = ModelManagerFactory::getByName('appeal_type');
            $visit_source_manager = ModelManagerFactory::getByName('visit_source');
            $target_call_manager = ModelManagerFactory::getByName('target_call');

            $this->view->specialties = $specialty_manager->getSortedList('name');
            $this->view->appeal_types = $appeal_type_manager->getList();
            $this->view->visit_sources = $visit_source_manager->getList();
            $this->view->target_calls = $target_call_manager->getList();

            $html = $this->renderInString('popup/appeal');

            JsonResponse::result($html);
        }
	}