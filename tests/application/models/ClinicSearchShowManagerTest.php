<?php
    class ClinicSearchShowManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var ClinicSearchShowManager
         */
        protected $object;
        protected $hash = 'hash';
        protected $fail_hash = 'fail hash';
        protected $limit = 20;
        protected $shows = 4;

        protected function setUp()
        {
            $this->object = new ClinicSearchShowManager();
            $this->deleteAndCreateClinicSearchShow();
        }

        private function deleteAndCreateClinicSearchShow()
        {
            $this->object->deleteByHash($this->hash);

            $clinic_search_show = new ClinicSearchShowModel();
            $clinic_search_show->hash = $this->hash;
            $clinic_search_show->balls = 1;
            $clinic_search_show->disableValidation();

            ModelManagerFactory::getByName('clinic_search_show')->save($clinic_search_show);
        }

        /**
         * @covers ClinicSearchShowManager::getMaxBallsByHash
         */
        function testGetMaxBallsByHash()
        {
            $clinic_search_shows = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($clinic_search_shows as $clinic_search_show) {
                $test_object = $this->object->getMaxBallsByHash($clinic_search_show->hash);

                foreach ($clinic_search_shows as $test_clinic_search_show) {
                    $this->assertGreaterThanOrEqual($test_object->result, $test_clinic_search_show->balls);
                }
            }

        }

        /**
         * @covers ClinicSearchShowManager::getOneByHash
         */
        function testGetOneByHash()
        {
            $clinic_search_shows = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($clinic_search_shows as $clinic_search_show) {
                $test_object = $this->object->getOneByHash($clinic_search_show->hash);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($clinic_search_show->hash, $test_object->hash);
            }
        }

        /**
         * @covers ClinicSearchShowManager::getOneByHash
         */
        function testFailGetOneByHash()
        {
            $test_object = $this->object->getOneByHash($this->fail_hash);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

        /**
         * @covers ClinicSearchShowManager::deleteByHash
         */
        function testDeleteByHash()
        {
            $this->object->deleteByHash($this->hash);
            $test_object = $this->object->getOneByHash($this->hash);
            $this->assertNull($test_object);
        }


    }
