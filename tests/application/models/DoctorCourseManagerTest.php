<?php
    class DoctorCourseManagerTest extends PHPUnit_Framework_TestCase
    {
        /**
         * @var DoctorCourseManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new DoctorCourseManager();
        }

        /**
         * @covers DoctorCourseManager::getListByDoctorId
         */
        function testGetListByDoctorId()
        {

            $doctors = ModelManagerFactory::getByName('doctor')->getListWithLimit(10);

            foreach ($doctors as $doctor) {
                $doctor_courses = $this->object->getListByDoctorId($doctor->getId());
                $this->assertTrue(is_array($doctor_courses));

                if ($doctor_courses)
                    foreach ($doctor_courses as $doctor_course) {
                        $this->assertTrue(is_object($doctor_course));
                        $this->assertEquals($doctor_course->doctor_id, $doctor->getId());
                    }
            }
        }

    }
