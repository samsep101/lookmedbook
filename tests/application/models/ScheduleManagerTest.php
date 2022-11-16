<?php
    class ScheduleManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var ScheduleManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new ScheduleManager();
        }

        private function deleteAndCreateShedule()
        {
            $this->object->deleteByEmail($this->email);

            $account = new AccountModel();
            $account->full_name = $this->full_name;
            $account->email = $this->email;
            $account->nick = $this->nick;
            $account->email_confirm_code = $this->email_confirm_code;
            $account->password_hash = PasswordHashGenerator::generate($this->password);
            $account->disableValidation();

            ModelManagerFactory::getByName('account')->save($account);
        }

        /**
         * todo: реализовать тест
         * @covers ScheduleManager::setNotBusyStatusByVisitId
         */
        function testSetNotBusyStatusByVisitId()
        {

        }


        /**
         * todo: реализовать тест
         * @covers ScheduleManager::getOneFirstByDoctorIdAndDate
         */
        function testGetOneFirstByDoctorIdAndDate()
        {

        }


        /**
         * todo: реализовать тест
         * @covers ScheduleManager::getOneLastByDoctorIdAndDate
         */
        function testGetOneLastByDoctorIdAndDate()
        {

        }


        /**
         * todo: реализовать тест
         * @covers ScheduleManager::checkWeekVisitsByDoctorIdAndTimeRange
         */
        function testCheckWeekVisitsByDoctorIdAndTimeRange()
        {

        }


        /**
         * todo: реализовать тест
         * @covers ScheduleManager::checkWeekendVisitsByDoctorId
         */
        function testCheckWeekendVisitsByDoctorId()
        {

        }


    }
