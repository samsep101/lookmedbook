<?php
    class HelpSubrubricManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var HelpSubrubricManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new HelpSubrubricManager();
        }


        /**
         * @covers HelpSubrubricManager::getActiveListByRubricId
         */
        function getActiveListByRubricId()
        {
            $help_rubrics = ModelManagerFactory::getByName('help_rubric')->getListWithLimit(10);

            foreach ($help_rubrics as $help_rubric) {
                $help_materials = $this->object->getActiveListByRubricId($help_rubric->getId());
                $this->assertTrue(is_array($help_materials));

                if ($help_materials)
                    foreach ($help_materials as $help_material) {
                        $this->assertTrue(is_object($help_material));
                        $this->assertEquals($help_material->help_subrubric_id, $help_rubric->getId());
                    }
            }
        }

        /**
         * @covers HelpSubrubricManager::getRubricIdById
         */
        function testGetRubricIdById()
        {
            $help_subrubrics = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($help_subrubrics as $help_subrubric)
            {
                $test_object = $this->object->getRubricIdById($help_subrubric->getId());
                $this->assertEquals($help_subrubric->help_subrubric_id, $test_object);
            }
        }


    }
