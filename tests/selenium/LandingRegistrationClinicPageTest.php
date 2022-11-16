<?php
    class LandingRegistrationClinicPageTest extends BaseSeleniumTest
    {

        public function testPage()
        {
            //кнопка "Зарегистрироваться"
            $this->registerButton();
            //кнопка "Регистрируясь я принимаю условия использования"
            //$this->privacyBytton();

            //кнопка "Уже зарегистрированы? Авторизироваться"
            //$this->landingAuthButton();

            //региcтрация
            $this->landingRegistration();
        }

        protected function registerButton()
        {
            $clinic_manager = new ClinicManager();
            $clinics = $clinic_manager->getList();
            $clinic = $clinics[rand(0, count($clinics) - 1)];

            $this->logout();
            $this->open('/clinic/get?id=' . $clinic->getId() . '&landing=1');
            $this->assertTrue($this->isVisible('id=landing-popup-registration'));
        }

        protected function privacyBytton()
        {
            $this->click('css=div.cont.reg-popup > div.form.reg-form > div.btns.flo > a.privacy-txt.reg-link');
            $this->assertTrue($this->isVisible('id=landings-terms-popup'));
            $this->click('css=#landings-terms-popup > div.btns.flo > a.btn-2.reg-link');
            $this->assertTrue($this->isVisible('id=landing-popup-registration'));
        }

        protected function landingAuthButton()
        {
            $this->click('link=exact:Уже зарегистрированы? Авторизироваться');
            $this->assertTrue($this->isVisible('id=landing-login-popup'));
            $this->click('css=div.cont.reg-popup > p.intro > a.reg-link');
            $this->assertTrue($this->isVisible('id=landing-popup-registration'));
        }

        protected function landingRegistration()
        {
            $registration_email = 'd.karviga@gmail.com';
            $this->validateEmailField();

            $registration_email = StringGeneratorHelper::generate(10) . '@test.ru';
            $this->type("css=div.form.reg-form > div.row.flo > div.txt > input[name=\"landing_registration_email\"]", $registration_email);
            $this->click('id=submit_landing_registration');
            sleep(1);
            $this->assertFalse($this->isVisible('id=success-popup'));
        }

        protected function validateEmailField()
        {
            $registration_email = 'd.karviga@gmail.com';

            $this->type("css=div.form.reg-form > div.row.flo > div.txt > input[name=\"landing_registration_email\"]", $registration_email);
            $this->click('id=submit_landing_registration');

            $this->assertTrue($this->isVisible("css=label.error[for=\"landing_registration_email\"]"));
            sleep(1);
        }
    }