<?php
    class SpecialtyToDiseaseManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var SpecialtyToDiseaseManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new SpecialtyToDiseaseManager();
        }

        /**
         * @covers SpecialtyToDiseaseManager::getOneByDiseaseIdAndMainFlag
         */
        function testGetOneByDiseaseIdAndMainFlag()
        {
            $diseases = ModelManagerFactory::getByName('disease')->getListWithLimit(20);

            foreach($diseases as $disease){
                $specialty_to_disease = $this->object->getOneByDiseaseIdAndMainFlag($disease->getId());
                $this->assertTrue(is_object($specialty_to_disease));
                $this->assertTrue((bool)$specialty_to_disease->main_flag);
                $this->assertEquals($specialty_to_disease->disease_id, $disease->getId());

            }
        }

        /**
         * @covers SpecialtyToDiseaseManager::getOneByDiseaseIdAndMainFlag
         */
        function testFailGetOneByDiseaseIdAndMainFlag()
        {
            $test_object = $this->object->getOneByDiseaseIdAndMainFlag(null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

    }
