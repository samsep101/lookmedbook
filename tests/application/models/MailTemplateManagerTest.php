<?php
    class MailTemplateManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var MailTemplateManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new MailTemplateManager();
        }

        /**
         * @covers MailTemplateManager::getMailByCodeAndTokens
         */
        function testGetMailByCodeAndTokens()
        {
            $mail_templates = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($mail_templates as $mail_template)
            {
                $test_object = $this->object->getOneByCodeAndTokens($mail_template->code);
                $this->assertEquals($test_object->code, $mail_template->code);
            }
        }


    }
