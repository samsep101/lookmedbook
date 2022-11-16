<?php
    class MyDiseaseManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var MyDiseaseManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new MyDiseaseManager();
        }

        /**
         * @covers MyDiseaseManager::checkExistsByDiseaseIdAndAccountId
         */
        function testCheckExistsByDiseaseIdAndAccountId()
        {
            $my_diseases = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($my_diseases as $my_disease) {
                $test_object = $this->object->checkExistsByDiseaseIdAndAccountId($my_disease->disease_id, $my_disease->account_id);

                if ($test_object)
                    $this->assertTrue($test_object);
            }
        }


        /**
         * @covers MyDiseaseManager::getOneByDiseaseIdAndAccountId
         */
        function testGetOneByDiseaseIdAndAccountId()
        {
            $my_diseases = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($my_diseases as $my_disease) {
                $test_object = $this->object->getOneByDiseaseIdAndAccountId($my_disease->disease_id, $my_disease->account_id);
                $this->assertTrue(is_object($my_disease));
                $this->assertEquals($my_disease->getId(), $test_object->getId());
            }
        }

        /**
         * @covers MyDiseaseManager::getOneByDiseaseIdAndAccountId
         */
        function testFailGetOneByDiseaseIdAndAccountId()
        {
            $test_object = $this->object->getOneByDiseaseIdAndAccountId(null,null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

        /**
         *
         * @covers MyDiseaseManager::getListByAccountId
         */
        function testGetListByAccountId()
        {
            $accounts = ModelManagerFactory::getByName('account')->getListWithLimit(10);

            foreach ($accounts as $account) {
                $my_diseases = $this->object->getListByAccountId($account->getId());
                $this->assertTrue(is_array($my_diseases));

                if ($my_diseases)
                    foreach ($my_diseases as $my_disease) {
                        $this->assertTrue(is_object($my_disease));
                        $this->assertEquals($my_disease->account_id, $account->getId());
                    }
            }
        }

    }
