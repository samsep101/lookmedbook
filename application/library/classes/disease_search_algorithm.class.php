<?php

	class DiseaseSearchAlgorithm
	{
		private $redirect_url = '';

		private $next_page_flag = false;

		/**
		 * @return boolean
		 */
		public function getNextPageFlag()
		{
			return $this->next_page_flag;
		}

		/**
		 * @return string
		 */
		public function getRedirectUrl()
		{
			return $this->redirect_url;
		}

		public function search(DiseaseSearchCriteria $criteria)
		{
			$criteria->get_extra_item = true;

			/**
			 * @var DiseaseManager $disease_manager
			 */
			$disease_manager = ModelManagerFactory::getByName('disease');

			$diseases = $disease_manager->getListByModelSearchCriteria($criteria);

			if((count($diseases) == 1))
			{
				$disease = $diseases[0];
				if((mb_strtolower($disease->title, 'UTF-8') == mb_strtolower($criteria->name, 'UTF-8')))
				{
					$this->redirect_url = '/disease/get?id=' . $diseases[0]->id;
				}
			}

			if(count($diseases) == 0)
			{
				$criteria->tag = $criteria->name;
				$criteria->name = null;

				$diseases = $disease_manager->getListByModelSearchCriteria($criteria);
			}

			$this->next_page_flag = (isset($diseases[$criteria->by_page]));

			unset($diseases[$criteria->by_page]);

			return $diseases;
		}
	}