<?php
    class DiseaseBlockManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var DiseaseBlockManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new DiseaseBlockManager();
        }

        /**
         * @covers DiseaseBlockManager::getActiveListByDiseaseId
         */
        function testGetActiveListByDiseaseId()
        {

            $diseases = ModelManagerFactory::getByName('disease')->getListWithLimit(20);

            foreach ($diseases as $disease) {
                $disease_blocks = $this->object->getActiveListByDiseaseId($disease->getId());
                $this->assertTrue(is_array($disease_blocks));

                if ($disease_blocks)
                    foreach ($disease_blocks as $disease_block) {
                        $this->assertTrue((bool)$disease_block->is_active);
                        $this->assertTrue(is_object($disease_block));
                        $this->assertEquals($disease_block->disease_id, $disease->getId());
                    }
            }
        }

    }
