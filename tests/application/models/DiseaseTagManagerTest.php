<?php
    class DiseaseTagManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var DiseaseTagManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new DiseaseTagManager();
        }

        /**
         *
         * @covers DiseaseTagManager::getListByTag
         */
        function testGetListByTag()
        {
            $disease_tags = $this->object->getListWithLimit(20);
            $this->object->clearRegister();

            foreach ($disease_tags as $disease_tag) {
                $test_objects = $this->object->getListByTag($disease_tag->tag);
                $this->assertTrue(is_array($test_objects));

                if ($test_objects)
                    foreach ($test_objects as $test_object) {
                        $this->assertTrue(is_object($test_object));
                        $this->assertContains($disease_tag->tag, $test_object->tag);
                    }
            }
        }
    }
