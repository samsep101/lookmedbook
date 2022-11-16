<?php
    class DoctorSearchShowManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var DoctorSearchShowManager
         */
        protected $object;
        protected $hash = 'hash';
        protected $fail_hash = 'fail hash';
        protected $limit = 20;
        protected $shows = 4;

        protected function setUp()
        {
            $this->object = new DoctorSearchShowManager();
            $this->deleteAndCreateDoctorSearchShow();
        }

        private function deleteAndCreateDoctorSearchShow()
        {
            $this->object->deleteByHash($this->hash);

            $doctor_search_show = new DoctorSearchShowModel();
            $doctor_search_show->hash = $this->hash;
            $doctor_search_show->balls = 1;
            $doctor_search_show->disableValidation();

            ModelManagerFactory::getByName('doctor_search_show')->save($doctor_search_show);
        }

        /**
         * @covers DoctorSearchShowManager::getMaxBallsByHash
         */
        function testGetMaxBallsByHash()
        {
            $doctor_search_shows = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($doctor_search_shows as $doctor_search_show) {
                $test_object = $this->object->getMaxBallsByHash($doctor_search_show->hash);

                foreach ($doctor_search_shows as $test_doctor_search_show) {
                    $this->assertGreaterThanOrEqual($test_object->result, $test_doctor_search_show->balls);
                }
            }
        }

        /**
         * @covers DoctorSearchShowManager::getOneByHash
         */
        function testGetOneByHash()
        {
            $doctor_search_shows = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($doctor_search_shows as $doctor_search_show) {
                $test_object = $this->object->getOneByHash($doctor_search_show->hash);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($doctor_search_show->hash, $test_object->hash);
            }
        }

        /**
         * @covers DoctorSearchShowManager::getOneByHash
         */
        function testFailGetOneByHash()
        {
            $test_object = $this->object->getOneByHash($this->fail_hash);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

        /**
         * @covers DoctorSearchShowManager::deleteByHash
         */
        function testDeleteByHash()
        {
            $this->object->deleteByHash($this->hash);
            $test_object = $this->object->getOneByHash($this->hash);
            $this->assertNull($test_object);
        }



    }
