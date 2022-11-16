<?php

    class PasswordHashGeneratorTest extends PHPUnit_Framework_TestCase
    {

        /**
         * Sets up the fixture, for example, opens a network connection.
         * This method is called before a test is executed.
         */
        protected function setUp()
        {

        }

        /**
         * Tears down the fixture, for example, closes a network connection.
         * This method is called after a test is executed.
         */
        protected function tearDown()
        {

        }

        /**
         * @covers       PasswordHashGenerator::generate
         * @dataProvider passwordsProvider
         */
        public function testGenerate($string)
        {
            $this->assertTrue(is_string(PasswordHashGenerator::generate($string)));
        }

        public function passwordsProvider()
        {
            return array(
                array('12344'),
                array('fgrttrhrt'),
            );
        }
    }
