<?php
    class DiseaseToDiseaseTagManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var DiseaseToDiseaseTagManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new DiseaseToDiseaseTagManager();
        }

        /**
         * @covers DiseaseToDiseaseTagManager::checkExistsByDiseaseIdAndDiseaseTagId
         */
        function testCheckExistsByDiseaseIdAndDiseaseTagId()
        {
            $disease_to_disease_tags = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($disease_to_disease_tags as $disease_to_disease_tag)
            {
                $test_object = $this->object->checkExistsByDiseaseIdAndDiseaseTagId($disease_to_disease_tag->disease_id, $disease_to_disease_tag->disease_tag_id);

                if ($test_object)
                    $this->assertTrue($test_object);
            }
        }


    }
