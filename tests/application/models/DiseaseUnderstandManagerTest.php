<?php
    class DiseaseUnderstandManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var DiseaseUnderstandManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new DiseaseUnderstandManager();
        }

        /**
         * @covers DiseaseUnderstandManager::checkExistsByDiseaseIdAndAccountId
         */
        function testCheckExistsByDiseaseIdAndAccountId()
        {
            $disease_understands = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($disease_understands as $disease_understand) {
                $test_object = $this->object->checkExistsByDiseaseIdAndAccountId($disease_understand->disease_id, $disease_understand->account_id);

                if ($test_object)
                    $this->assertTrue($test_object);
            }
        }


    }
