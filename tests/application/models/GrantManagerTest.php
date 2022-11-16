<?php
	class GrantManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var GrantManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new GrantManager();
		}

		/**
		 * @covers GrantManager::getListByRoleId
		 */
		function testGetListByRoleId(){

			$roles = ModelManagerFactory::getByName('role')->getListWithLimit(10);

			foreach($roles as $role){
				$grants = $this->object->getListByRoleId($role->getId());
				$this->assertTrue(is_array($grants));

				if ($grants)
					foreach($grants as $grant){
						$this->assertTrue(is_object($grant));
						$this->assertEquals($grant->role_id, $role->getId());
					}
			}
		}

	}
