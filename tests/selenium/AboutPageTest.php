<?php
    class AboutPageTest extends BaseSeleniumTest
    {
        protected $nick;
        protected $first_name;
        protected $last_name;
        protected $middle_name;
        protected $sex;
        protected $phone;
        protected $email;

        public function testPage()
        {
            $this->login();

            $this->open('/account/about');

            $this->saveInfo();
        }

        protected function checkNotSaveChangeInfo()
        {
            $this->nick = StringGeneratorHelper::generate(8);
            $this->type("name=nick", $this->nick);
            $this->click('link=О себе');
            sleep(2);
            $this->assertTrue($this->isVisible("id=modal_window_popup"));
            $this->clickAndWait('id=ask_section_no_butoon');
            sleep(2);
            $this->assertFalse($this->isElementPresent('id=modal_window_popup'));
        }

        protected function checkSaveChangeInfo()
        {
            $this->nick = StringGeneratorHelper::generate(8);
            $this->type("name=nick", $this->nick);
            $this->click('link=Сообщения');
            sleep(2);
            $this->assertTrue($this->isVisible("id=modal_window_popup"));
            $this->clickAndWait('id=ask_section_save_butoon');
            sleep(2);
            $this->assertTrue($this->isElementPresent('css=div.cab-cont.cab-messages'));
        }

        protected function saveInfo()
        {
            $this->validateNick();

            //проверяем подтверждение сохранить инфу
            $this->checkSaveChangeInfo();

            $this->open('/account/about');

            $this->checkNotSaveChangeInfo();

            //todo: проверку всех полей
            /*$this->nick = 'Nick';
            $this->type("name=nick", $this->nick);

            // поля ФИО
            $this->first_name = 'Firstname';
            $this->last_name = 'Lastname';
            $this->middle_name = 'Middlename';

            $this->type("name=last_name", $this->last_name);
            $this->type("name=first_name", $this->first_name);
            $this->type("name=middle_name", $this->middle_name);

            //выбор пола
            $this->click("id=male_gender");
            $this->assertTrue($this->isElementPresent("css=span#male_gender[class=\"man selected\"]"));
            $this->assertFalse($this->isElementPresent("css=span#female_gender[class=\"selected\"]"));

            $this->click("id=female_gender");
            $this->assertTrue($this->isElementPresent("css=span#female_gender[class=\"woman selected\"]"));
            $this->assertFalse($this->isElementPresent("css=span#male_gender[class=\"selected\"]"));



            //добавление телефона
            $this->validatePhone();
            $this->phone = '+777-777-77-77';
            $this->click("css=span.add-phone");
            //$this->assertTrue($this->isElementPresent('css=input.mask[class=\"new_phone\"]'));
            $this->click('css=input.mask.new_phone');
            $this->type("css=input.mask.new_phone", $this->phone);

            //email
            $this->validateEmail();

            $this->email = 'd.karviga@gmail.com';
            $this->type("name=email", $this->email);

            $this->click("css=div.about-form #save_info_button");
            sleep(5);
            $this->assertTrue($this->isVisible("css=#success-popup"));
            $this->click("css=a.fancybox-item.fancybox-close");
            $this->assertFalse($this->isVisible("css=#success-popup"));*/
        }

        protected function validateNick()
        {
            $this->nick = 'Nic';

            $this->type("name=nick", $this->nick);
            $this->click("css=div.about-form #save_info_button");
            $this->assertTrue($this->isVisible("css=label.error[for=\"nick\"]"));
        }

        protected function validatePhone()
        {
            $this->phone = '+71234567';

            $this->click("css=span.add-phone");
            $this->assertTrue($this->isElementPresent('css=input.new_phone'));
            $this->type("css=input.mask.new_phone", $this->phone);
            sleep(3);
            $this->click("css=div.about-form #save_info_button");
            sleep(1.5);
            $this->assertTrue($this->isVisible("css=label.error[for=\"undefined\"]"));
        }

        protected function validateEmail()
        {
            $this->email = 'testgmail.com';

            $this->type("name=email", $this->email);
            sleep(1);
            $this->click("css=div.about-form #save_info_button");
            $this->assertTrue($this->isVisible("css=label.error[for=\"email\"]"));
        }
    }