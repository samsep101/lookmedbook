<?php
	class UniversityManagerTest extends PHPUnit_Framework_TestCase {

		/**
		 * @var UniversityManager
		 */
		protected $object;

		protected function setUp(){
			$this->object = new UniversityManager();
		}

        /**
         * @covers UniversityManager::getListByDoctorId
         */
        function testGetListByDoctorId()
        {
            $doctors = ModelManagerFactory::getByName('doctor')->getListWithLimit(20);

            foreach ($doctors as $doctor) {
                $tests_objects = $this->object->getListByDoctorId($doctor->getId());
                $this->assertTrue(is_array($tests_objects));

                if ($tests_objects)
                    foreach ($tests_objects as $test_object) {
                        $this->assertTrue(is_object($test_object));

                        $doctor_education_manager = new DoctorEducationManager();
                        $my_university = $doctor_education_manager->getOneByDoctorIdAndUniversityId($doctor->getId(), $test_object->getId());
                        $this->assertTrue(is_object($my_university));

                        $this->assertEquals($my_university->university_id, $test_object->getId());
                    }
            }
        }


	}
