<?php
    class MyClinicManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var MyClinicManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new MyClinicManager();
        }

        /**
         * @covers MyClinicManager::getOneByClinicIdAndAccountId
         */
        function testGetOneByClinicIdAndAccountId()
        {
            $my_clinics = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($my_clinics as $my_clinic) {
                $test_object = $this->object->getOneByClinicIdAndAccountId($my_clinic->clinic_id, $my_clinic->account_id);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($my_clinic->getId(), $test_object->getId());
            }
        }

        /**
         * @covers MyClinicManager::getOneByClinicIdAndAccountId
         */
        function testFailGetOneByClinicIdAndAccountId()
        {
            $test_object = $this->object->getOneByClinicIdAndAccountId(null, null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);

        }

        /**
         * @covers MyClinicManager::getListByAccountId
         */
        function testGetListByAccountId()
        {
            $accounts = ModelManagerFactory::getByName('account')->getListWithLimit(10);

            foreach ($accounts as $account) {
                $my_clinics = $this->object->getListByAccountId($account->getId());
                $this->assertTrue(is_array($my_clinics));

                if ($my_clinics)
                    foreach ($my_clinics as $my_clinic) {
                        $this->assertTrue(is_object($my_clinic));
                        $this->assertEquals($my_clinic->account_id, $account->getId());
                    }
            }
        }

        /**
         * @covers MyClinicManager::checkExistsByClinicIdAndAccountId
         */
        function testCheckExistsByClinicIdAndAccountId()
        {
            $my_clinics = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($my_clinics as $my_clinic) {
                $test_object = $this->object->checkExistsByClinicIdAndAccountId($my_clinic->clinic_id, $my_clinic->account_id);

                if ($test_object)
                    $this->assertTrue($test_object);
            }
        }

        /**
         * @covers MyClinicManager::getOneByClinicId
         */
        function testGetOneByClinicId()
        {
            $my_clinics = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($my_clinics as $my_clinic) {
                $test_object = $this->object->getOneByClinicId($my_clinic->getId());
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($my_clinic->getId(), $test_object->getId());
            }
        }

        /**
         * @covers MyClinicManager::getOneByClinicId
         */
        function testFailGetOneByClinicId()
        {
            $test_object = $this->object->getOneByClinicId(null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

    }
