<?php
	class MyDoctorManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var MyDoctorManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new MyDoctorManager();
		}

        /**
         * @covers MyDoctorManager::checkExistsByDoctorIdAndAccountId
         */
        function testCheckExistsByDoctorIdAndAccountId()
        {
            $my_doctors = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($my_doctors as $my_doctor) {
                $test_object = $this->object->checkExistsByDoctorIdAndAccountId($my_doctor->doctor_id, $my_doctor->account_id);

                if ($test_object)
                    $this->assertTrue($test_object);
            }
        }

        /**
         * @covers MyDoctorManager::getOneByDoctorIdAndAccountId
         */
        function testGetOneByDoctorIdAndAccountId()
        {
            $my_doctors = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($my_doctors as $my_doctor) {
                $test_object = $this->object->getOneByDoctorIdAndAccountId($my_doctor->doctor, $my_doctor->account_id);
                $this->assertTrue(is_object($my_doctor));
                $this->assertEquals($my_doctor->getId(), $test_object->getId());
            }
        }

        /**
         * @covers MyDoctorManager::getOneByDoctorIdAndAccountId
         */
        function testFailGetOneOneByDoctorIdAndAccountId()
        {
            $test_object = $this->object->getOneByDoctorIdAndAccountId(null,null);
            $this->assertFalse(is_object($test_object));
            $this->assertNull($test_object);
        }

		/**
		 * @covers MyDoctorManager::getListByAccountId
		 */
		function testGetListByAccountId(){

			$accounts = ModelManagerFactory::getByName('account')->getListWithLimit(10);

			foreach($accounts as $account){
				$my_doctors = $this->object->getListByAccountId($account->getId());
				$this->assertTrue(is_array($my_doctors));

				if ($my_doctors)
					foreach($my_doctors as $my_doctor){
						$this->assertTrue(is_object($my_doctor));
						$this->assertEquals($my_doctor->account_id, $account->getId());
					}
			}
		}

	}
