<?php
    //require_once 'WebDriver/WebDriver.php';
   // require_once 'WebDriver/ServiceFactory.php';
    class BaseSeleniumTest extends PHPUnit_Extensions_SeleniumTestCase
    {
        /**
         * @var AccountModel
         */
        protected $test_account;
        protected $test_account_login = 'd.karviga@gmail.com';
        protected $test_account_password = '2280104';

        protected $registration_email;
        protected $password;
        protected $repeat_password;

        protected $session;

        protected function setUp()
        {
                //'*firefox',
                //'*opera',
                //'*googlechrome C:\Program Files\Google\Chrome\Application\chrome.exe',

            $this->setBrowser("*firefox");
            //$this->setBrowserUrl("http://admin:21506@dev.lookmedbook.ru/");
            $this->setBrowserUrl(SITE_URL);

            $account_manager = new AccountManager();
            $this->test_account = $account_manager->getOneByEmail($this->test_account_login);
        }

        public function myLogin()
        {
            $this->open("/");
            $this->click("css=a.btn-enter.reg-linking");
            $this->type("css=input[name=\"email\"]", "arialover08@gmail.com");
            $this->type("css=input[name=\"password\"]", "snatch");
            $this->click("css=input.btn-1.submit");
            $this->waitForPageToLoad("30000");
        }


        public function call_centerLogin()
        {
            $this->open("/");
            $this->click("css=a.btn-enter.reg-linking");
            $this->type("css=input[name=\"email\"]", "sergey.redko@actsystems.ru");
            $this->type("css=input[name=\"password\"]", "2280104");
            $this->click("css=input.btn-1.submit");
            $this->waitForPageToLoad("30000");
        }

        public function registration()
        {
            $this->logout();
            $this->open("/");
            $this->click('css=button.smart-button');

            $this->validateEmail();

            $this->registration_email = StringGeneratorHelper::generate(10).'@test.ru';
            $this->type("css=div.form.reg-form > div.row.flo > div.txt > input[name=\"email\"]", $this->registration_email);
            $this->click('id=registration-popup');

            $this->validatePassword();

            $this->password = '123456';
            $this->repeat_password = $this->password;
            $this->type('id=password', $this->password);
            $this->type('id=repeat_registration_password', $this->repeat_password);
            sleep(1);
            $this->click("css=input.btn-1.submit_registration");
            sleep(4);
            $this->assertTrue($this->isVisible('id=success-popup'));
            $this->click('css=a.fancybox-item.fancybox-close');
            $this->assertFalse($this->isVisible('id=success-popup'));
        }

        protected function validateEmail()
        {
            $this->registration_email = 'd.karviga@gmail.com';

            $this->type("css=div.form.reg-form > div.row.flo > div.txt > input[name=\"email\"]", $this->registration_email);
            $this->click("css=input.btn-1.submit_registration");
            $this->assertTrue($this->isVisible("css=label.error[for=\"email\"]"));
            sleep(1);
        }

        protected function validatePassword()
        {
            $this->password = '12345';

            $this->type('id=password', $this->password);
            $this->click("css=input.btn-1.submit_registration");
            $this->assertTrue($this->isVisible("css=label.error[for=\"password\"]"));
            sleep(1);

            $this->password = '123456';
            $this->repeat_password = $this->password.'0';
            $this->type('id=password', $this->password);
            $this->type('id=repeat_registration_password', $this->repeat_password);
            $this->click("css=input.btn-1.submit_registration");
            $this->assertTrue($this->isVisible("css=label.error[for=\"password-field error\"]"));
            sleep(1);
        }


        public function login()
        {
            $this->logout();
            $this->open("/");
            $this->click("css=button.smart-button-2");

            $this->validateLogin();
            $this->test_account_login = 'd.karviga@gmail.com';
            $this->test_account_password = '123456';

            $this->type("css=div.form.auth-form > div.row.flo > div.txt > input[name=\"email\"]", $this->test_account_login);
            $this->type("css=div.form.auth-form > div.row.flo > div.txt > input[name=\"password\"]", $this->test_account_password);
            $this->click("css=div.form.auth-form input.submit");
            $this->waitForPageToLoad("30000");
            $this->assertTrue($this->isElementPresent("css=.main-page-tooltip"));
        }

        protected function shopLogin()
        {
            $this->open("/admin/security/login");
            $this->type("css=input[name=\"login\"]", "shop");
            $this->type("css=input[name=\"password\"]", "123456");
            $this->click("css=#signin");
            $this->waitForPageToLoad("30000");
        }

        protected function validateLogin()
        {
            $this->test_account_login = 'd.karvigagmail.com';
            $this->test_account_password = '1236';
            $this->type("css=div.form.auth-form > div.row.flo > div.txt > input[name=\"email\"]", $this->test_account_login);
            $this->type("css=div.form.auth-form > div.row.flo > div.txt > input[name=\"password\"]", $this->test_account_password);
            $this->click("css=div.form.auth-form input.submit");
            $this->assertTrue($this->isVisible("css=label.error[for=\"login_form\"]"));
            sleep(1);
        }

        public function logout()
        {
            $this->open("/account/logout");
        }

        public function mainManagerLogin()
        {
            $this->open("/admin/security/login");
            $this->type("name=login", "account");
            $this->type("name=password", "denis");
            $this->click("id=signin");
            $this->waitForPageToLoad();
        }

        public function adminLogin()
        {
            $this->open("/admin/security/login");
            $this->waitForPageToLoad();
            $this->type("name=login", "denis");
            $this->type("name=password", "denis");
            $this->click("id=signin");
            $this->waitForPageToLoad();
        }

        public function accountManagerLogin ()
        {
            $this->open("/admin/security/login");
            $this->type("name=login", "sergei");
            $this->type("name=password", "sergei");
            $this->click("id=signin");
            $this->waitForPageToLoad();
        }

        public function freelancerLogin ()
        {
            $this->open("/admin/security/login");
            $this->type("name=login", "freelancer");
            $this->type("name=password", "freelancer");
            $this->click("id=signin");
            $this->waitForPageToLoad();
        }

        public function waitForText($element, $text)
        {
            for ($second = 0; ; $second++) {
                if ($second >= 5) $this->fail("timeout");
                try {
                    if ($text == $this->getText($element))
                        break;
                } catch (Exception $e) {

                }
                sleep(1);
            }
        }
    }