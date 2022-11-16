<?php
	class UserManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var UserManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new UserManager();
		}

		/**
		 * @covers UserManager::getOneByLoginAndPassword
		 */
		function testGetOneByLoginAndPassword(){
            $users = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($users as $user) {
                $test_object = $this->object->getOneByLoginAndPassword($user->login, $user->password);
                $this->assertTrue(is_object($test_object));
                $this->assertEquals($user->getId(), $test_object->getId());
            }
		}
	}
