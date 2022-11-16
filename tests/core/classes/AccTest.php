<?php
    if (!isset($_SESSION))
        $_SESSION = array();


    class AccTest extends PHPUnit_Framework_TestCase
    {
        protected static $shared_session;

        protected function setUp()
        {
            $_SESSION = self::$shared_session;
        }

        /**
         * Tears down the fixture, for example, closes a network connection.
         * This method is called after a test is executed.
         */
        protected function tearDown()
        {
            self::$shared_session = $_SESSION;
        }

        /**
         * @covers Acc::login
         */
        public function testLogin()
        {
            $account = new AccountModel();
            $account->setId(5);
            $account->login = 'misha';

            Acc::login($account);

            $this->assertEquals($_SESSION['__acc']['account']['login'], 'misha');
            $this->assertEquals($_SESSION['__acc']['account']['id'], 5);
        }

        /**
         * @covers  Acc::isAuthed
         * @depends testLogin
         */
        public function testSuccessIsAuthed()
        {
            $this->assertTrue(Acc::isAuthed());
        }

        /**
         * @covers  Acc::accountId
         * @depends testLogin
         */
        public function testAccountId()
        {
            $this->assertEquals(5, Acc::accountId());
        }

        /**
         * @covers  Acc::accountLogin
         * @depends testLogin
         */
        public function testAccountLogin()
        {
            $this->assertEquals('misha', Acc::accountLogin());
        }

        /**
         * @covers  Acc::logout
         * @covers  Acc::isAuthed
         * @covers  Acc::accountId
         * @covers  Acc::accountLogin
         *
         * @depends testLogin
         */
        public function testLogout()
        {
            Acc::logout();
            $this->assertFalse(Acc::isAuthed());
            $this->assertFalse(Acc::accountId());
            $this->assertFalse(Acc::accountLogin());
        }

    }
