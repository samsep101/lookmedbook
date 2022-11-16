<?php
	class DoctorArticleManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var DoctorArticleManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new DoctorArticleManager();
		}

		/**
		 * @covers DoctorArticleManager::getListByDoctorId
		 */
		function testGetListByDoctorId(){

			$doctors = ModelManagerFactory::getByName('doctor')->getListWithLimit(10);

			foreach($doctors as $doctor){
				$doctor_articles = $this->object->getListByDoctorId($doctor->getId());
				$this->assertTrue(is_array($doctor_articles));

				if ($doctor_articles)
					foreach($doctor_articles as $doctor_article){
						$this->assertTrue(is_object($doctor_article));
						$this->assertEquals($doctor_article->doctor_id, $doctor->getId());
					}
			}
		}

		/**
		 * @covers DoctorArticleManager::getListByDoctorArticleTypeId
		 */
		function testGetListByDoctorArticleTypeId(){

			$doctor_article_types = ModelManagerFactory::getByName('doctor_article_type')->getListWithLimit(10);

			foreach($doctor_article_types as $doctor_article_type){
				$doctor_articles = $this->object->getListByDoctorArticleTypeId($doctor_article_type->getId());
				$this->assertTrue(is_array($doctor_articles));

				if ($doctor_articles)
					foreach($doctor_articles as $doctor_article){
						$this->assertTrue(is_object($doctor_article));
						$this->assertEquals($doctor_article->doctor_article_type_id, $doctor_article_type->getId());
					}
			}
		}

	}
