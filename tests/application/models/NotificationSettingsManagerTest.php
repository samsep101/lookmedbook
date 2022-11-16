<?php
	class NotificationSettingsManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var NotificationSettingsManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new NotificationSettingsManager();
		}

		/** 
 		 * todo: проверить тест 
		 * @covers NotificationSettingsManager::getListByAccountId
		 */
		function testGetListByAccountId(){

			$accounts = ModelManagerFactory::getByName('account')->getListWithLimit(10);

			foreach($accounts as $account){
				$notification_settingses = $this->object->getListByAccountId($account->getId());
				$this->assertTrue(is_array($notification_settingses));

				if ($notification_settingses)
					foreach($notification_settingses as $notification_settings){
						$this->assertTrue(is_object($notification_settings));
						$this->assertEquals($notification_settings->account_id, $account->getId());
					}
			}
		}

		/** 
 		 * todo: проверить тест 
		 * @covers NotificationSettingsManager::getListBySmsNotifyPhoneId
		 */
		function testGetListBySmsNotifyPhoneId(){

			$sms_notify_phones = ModelManagerFactory::getByName('sms_notify_phone')->getListWithLimit(10);

			foreach($sms_notify_phones as $sms_notify_phone){
				$notification_settingses = $this->object->getListBySmsNotifyPhoneId($sms_notify_phone->getId());
				$this->assertTrue(is_array($notification_settingses));

				if ($notification_settingses)
					foreach($notification_settingses as $notification_settings){
						$this->assertTrue(is_object($notification_settings));
						$this->assertEquals($notification_settings->sms_notify_phone_id, $sms_notify_phone->getId());
					}
			}
		}

		/** 
 		 * todo: проверить тест 
		 * @covers NotificationSettingsManager::getOneByAccountId
		 */
		function testGetOneByAccountId(){

			$notification_settingses = $this->object->getListWithLimit(20);

			$this->object->clearRegister();

			foreach($notification_settingses as $notification_settings){
				$test_object = $this->object->getOneByAccountId($notification_settings->account_id);
						$this->assertTrue(is_object($test_object));
				$this->assertEquals($notification_settings->getId(), $test_object->getId());
			}
		}

		/** 
 		 * todo: проверить тест 
		 * @covers NotificationSettingsManager::getOneByPhoneId
		 */
		function testGetOneByPhoneId(){

			$notification_settingses = $this->object->getListWithLimit(20);

			$this->object->clearRegister();

			foreach($notification_settingses as $notification_settings){
				$test_object = $this->object->getOneByPhoneId($notification_settings->phone_id);
						$this->assertTrue(is_object($test_object));
				$this->assertEquals($notification_settings->getId(), $test_object->getId());
			}
		}

		/** 
 		 * todo: реализовать тест 
		 * @covers NotificationSettingsManager::setNotificationSettingsByAccountId
		 */
		function testSetNotificationSettingsByAccountId(){

		}


		/** 
 		 * todo: реализовать тест 
		 * @covers NotificationSettingsManager::checkExistsByAccountId
		 */
		function testCheckExistsByAccountId(){

		}


	}
