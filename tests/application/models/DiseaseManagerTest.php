<?php
	class DiseaseManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var DiseaseManager
		 */
		protected $object;
        protected $by_page = 10;
        protected $page = 1;

		protected function setUp(){
			$this->object = new DiseaseManager();
		}

		/**
		 * @covers DiseaseManager::getActiveListByTitleOrAltName
		 */
		function testGetActiveListByTitleOrAltName()
        {
            $diseases = $this->object->getListWithLimit(20);
            $this->object->clearRegister();
            foreach ($diseases as $diseas)
            {
                $tests_objects = $this->object->getActiveListByTitleOrAltName($diseas->title,$this->by_page,$this->page);
                $this->assertTrue(is_array($tests_objects));

                if ($tests_objects)
                    foreach ($tests_objects as $test_object)
                    {
                        $this->assertTrue(is_object($test_object));
                        $this->assertEquals($test_object->getId(), $diseas->getId());
                    }
            }
		}

		/**
		 * @covers DiseaseManager::getListByDiseaseTagId
		 */
		function testGetListByDiseaseTagId(){

			$disease_tags = ModelManagerFactory::getByName('disease_tag')->getListWithLimit(10);

			foreach($disease_tags as $disease_tag){
                $test_tags = ModelManagerFactory::getByName('disease_tag')->getListByTag($disease_tag->tag);
				$diseases = $this->object->getListByDiseaseTagId($test_tags,10,1);
				$this->assertTrue(is_array($diseases));

				if ($diseases)
					foreach($diseases as $disease){
						$this->assertTrue(is_object($disease));
                        $disease_to_disease_tag = ModelManagerFactory::getByName('disease_to_disease_tag')->checkExistsByDiseaseIdAndDiseaseTagId($disease->getId(),$disease_tag->getId());
                        $this->assertEquals(1,$disease_to_disease_tag);
					}
			}
		}

        /**
         * @covers DiseaseManager::getSelectedListByAccountId
         */
        function testGetSelectedListByAccountId()
        {
            $accounts = ModelManagerFactory::getByName('account')->getListWithLimit(20);

            foreach ($accounts as $account) {
                $tests_objects = $this->object->getSelectedListByAccountId($account->getId());
                $this->assertTrue(is_array($tests_objects));

                if ($tests_objects)
                    foreach ($tests_objects as $test_object) {
                        $this->assertTrue(is_object($test_object));

                        $my_disease_manager = new MyDiseaseManager();
                        $my_disease = $my_disease_manager->getOneByDiseaseIdAndAccountId($test_object->getId(),$account->getId());
                        $this->assertTrue(is_object($my_disease));
                        $this->assertEquals($my_disease->disease_id, $test_object->getId());
                    }
            }
        }

	}
