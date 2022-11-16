<?php
    class HelpMaterialManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var HelpMaterialManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new HelpMaterialManager();
        }

        /**
         * @covers HelpMaterialManager::getActiveListBySubrubricId
         */
        function testGetActiveListBySubrubricId()
        {
            $subrubrics = ModelManagerFactory::getByName('help_subrubric')->getListWithLimit(10);

            foreach ($subrubrics as $subrubric) {
                $help_materials = $this->object->getActiveListBySubrubricId($subrubric->getId());
                $this->assertTrue(is_array($help_materials));

                if ($help_materials)
                    foreach ($help_materials as $help_material) {
                        $this->assertTrue(is_object($help_material));
                        $this->assertEquals($help_material->help_subrubric_id, $subrubric->getId());
                    }
            }
        }

        /**
         * @covers HelpMaterialManager::getSubrubricIdById
         */
        function testGetSubrubricIdById()
        {
            $help_materials = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($help_materials as $help_material)
            {
                $test_object = $this->object->getSubrubricIdById($help_material->getId());
                $this->assertTrue(is_object($help_material));
                $this->assertEquals($help_material->help_subrubric_id, $test_object);
            }
        }

        /**
         * @covers HelpMaterialManager::getSubrubricIdById
         */
        function testFailGetSubrubricIdById()
        {
                $test_object = $this->object->getSubrubricIdById(null);
                $this->assertFalse(is_object($test_object));
                $this->assertNull($test_object);
        }


    }
