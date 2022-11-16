<?php
    class RegistrationPageTest extends BaseSeleniumTest
    {

        public function testPage()
        {
            //кнопка "Зарегистрироваться"
            $this->registerButton();

            //кнопка "Регистрируясь я принимаю условия использования"
            $this->privacyBytton();

            //региcтрация
            $this->registration();

        }

        protected function registerButton()
        {
            $this->open("/");
            $this->click('css=button.smart-button');
            $this->assertTrue($this->isVisible('id=registration-popup'));
        }

        protected function privacyBytton()
        {
            $this->click('css=a.privacy-txt.reg-link');
            $this->assertTrue($this->isVisible('id=terms-popup'));
            $this->click('link=Назад');
            $this->assertTrue($this->isVisible('id=registration-popup'));
        }
    }