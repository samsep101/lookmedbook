<?php
    class LandingLoginDoctorPageTest extends BaseSeleniumTest
    {

        public function testPage()
        {
            //кнопка "Зарегистрироваться"
            $this->registration();

            //кнопка "Регистрируясь я принимаю условия использования"
            $this->registerButton();

            //кнопка "Зарегистрироваться"
            //$this->landingRegisterButton();

            //региcтрация
            $this->landingLogin();

        }

        protected function registerButton()
        {
            $doctor_manager = new DoctorManager();
            $doctors = $doctor_manager->getList();
            $this->doctor = $doctors[rand(0, count($doctors) - 1)];
            $this->logout();

            $this->open('/doctor/get?id=' . $this->doctor->getId() . '&landing=1');
            $this->assertTrue($this->isVisible('id=landing-login-popup'));
        }

        protected function landingRegisterButton()
        {
            $this->click('css=div.cont.reg-popup > p.intro > a.reg-link');
            $this->assertTrue($this->isVisible('id=landing-popup-registration'));
            $this->click('link=exact:Уже зарегистрированы? Авторизироваться');
            $this->assertTrue($this->isVisible('id=landing-login-popup'));
        }

        protected function landingLogin()
        {
            $login_email = 'd.karviga@gmail.com';
            $login_password = '123456';

            $this->validateLoginFields();

            $this->type("css=div.form.auth-form > div.row.flo > div.txt > input[name=\"landing_login_email\"]", $login_email);
            $this->type("css=div.form.auth-form > div.row.flo > div.txt > input[name=\"landing_login_password\"]", $login_password);
            $this->click("css=div.form.auth-form input#submit_landing_login");
            sleep(3);
            $this->assertTrue($this->isElementPresent('css=div.doctor-landing'));
        }

        protected function validateLoginFields()
        {
            $test_account_login = 'd.karvigagmail.com';
            $test_account_password = '1236';
            $this->type("css=div.form.auth-form > div.row.flo > div.txt > input[name=\"landing_login_email\"]", $test_account_login);
            $this->type("css=div.form.auth-form > div.row.flo > div.txt > input[name=\"landing_login_password\"]", $test_account_password);
            $this->click("css=div.form.auth-form input#submit_landing_login");
            $this->assertTrue($this->isVisible("css=label.error[for=\"landing_login_form\"]"));
            sleep(1);
        }
    }