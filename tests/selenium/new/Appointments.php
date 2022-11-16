<?php
    class AppointmentsTest extends BaseSeleniumTest
    {
        /*
         * Кейсы записи на прием для неавторизованных пользователей
         * */

        public function testAppointments1() //запись на прием неавторизованного пользователя с подтвержденным телефоном
                                            //и подтвержденным email'ом
        {
            $pages = array(
                '/',
                '/doctor',
                '/clinic/medanna',
            );

            foreach($pages as $page)
            {
                $this->logout();
                $this->open($page);
                sleep(2);
                $count = $this->getXpathCount('//a[contains(@class, "btn-appoint")]'); //получаем число элементов, с классом btn-appoint
                $this->assertGreaterThan(0, $count);
                $test_item = rand(1, $count);
                $this->click('xpath=(//a[contains(@class, "btn-appoint")])[position()='.$test_item.']'); //клик по рандомной кнопке
                $this->waitForElementPresent('css=.record-to-the-doctor-popup');//ожидание нашего попапа
                $slots_count = $this->getXpathCount('//li[contains(@class, "time-li")]'); //количество слотов на попапе
                $slot_item = rand(1, $slots_count);//выбераем рандомный слот
                $this->click('xpath=(//li[contains(@class, "time-li")])[position()='.$slot_item.']');//кликаем по слоту
                $this->click('css=.record-to-the-doctor-popup .resume-btn');//кликаем на кнопку "Записаться"
                $this->type("css=input[name=\"surname\"]", "Редько Сергей Викторович");
                $this->type("css=input[name=\"phone\"]", "+7-495-641-26-26");
                $this->type("css=input[name=\"email\"]", "sergey.redko@actsystems.ru");
                $this->click('css=.record-to-the-doctor-popup .god-mode');
                $this->click('css=.record-to-the-doctor-popup .send-button');
                sleep(1);
                $this->type("css=input[name=\"password\"]", "2280104");
                $this->click('css=.record-to-the-doctor-popup .send-button');
                sleep(3);
                $this->assertEquals("Поздравляем, вы успешно записались на прием. Ждите подтверждения!", $this->getText("css=div.fancybox-inner div"));
            }
        }

        public function testAppointments2()//запись на прием неавторизованного пользователя с подтвержденным телефоном
                                            //и неподтвержденным email'ом
        {
            $pages = array(
                '/',
                '/doctor',
                '/clinic/medanna',
            );

            foreach($pages as $page)
            {
                $this->logout();
                $this->open($page);
                sleep(2);
                $count = $this->getXpathCount('//a[contains(@class, "btn-appoint")]'); //получаем число элементов, с классом btn-appoint
                $this->assertGreaterThan(0, $count);
                $test_item = rand(1, $count);
                $this->click('xpath=(//a[contains(@class, "btn-appoint")])[position()='.$test_item.']'); //клик по рандомной кнопке
                $this->waitForElementPresent('css=.record-to-the-doctor-popup');//ожидание нашего попапа
                $slots_count = $this->getXpathCount('//li[contains(@class, "time-li")]'); //количество слотов на попапе
                $slot_item = rand(1, $slots_count);//выбераем рандомный слот
                $this->click('xpath=(//li[contains(@class, "time-li")])[position()='.$slot_item.']');//кликаем по слоту
                $this->click('css=.record-to-the-doctor-popup .resume-btn');//кликаем на кнопку "Записаться"
                $this->type("css=input[name=\"surname\"]", "Редько Сергей Викторович");
                $this->type("css=input[name=\"phone\"]", "+7-495-641-26-26");
                $this->type("css=input[name=\"email\"]", $this->getLogin());
                $this->click('css=.record-to-the-doctor-popup .god-mode');
                $this->click('css=.record-to-the-doctor-popup .send-button');
                sleep(2);
                $this->assertEquals("help@lookmedbook.com", $this->getText("css=div.fancybox-inner > div > a"));
            }
        }

        public function testAppointments3()//запись на прием неавторизованного пользователя с не подтвержденным телефоном
                                           //и неподтвержденным email'ом
        {
            $pages = array(
                '/',
                '/doctor',
                '/clinic/medanna',
            );

            foreach($pages as $page)
            {
                $this->logout();
                $this->open($page);
                $count = $this->getXpathCount('//a[contains(@class, "btn-appoint")]'); //получаем число элементов, с классом btn-appoint
                $this->assertGreaterThan(0, $count);
                $test_item = rand(1, $count);
                $this->click('xpath=(//a[contains(@class, "btn-appoint")])[position()='.$test_item.']'); //клик по рандомной кнопке
                $this->waitForElementPresent('css=.record-to-the-doctor-popup');//ожидание нашего попапа
                $slots_count = $this->getXpathCount('//li[contains(@class, "time-li")]'); //количество слотов на попапе
                $slot_item = rand(1, $slots_count);//выбераем рандомный слот
                $this->click('xpath=(//li[contains(@class, "time-li")])[position()='.$slot_item.']');//кликаем по слоту
                $this->click('css=.record-to-the-doctor-popup .resume-btn');//кликаем на кнопку "Записаться"
                $this->type("css=input[name=\"surname\"]", "Редько Сергей Викторович");
                $this->type("css=input[name=\"phone\"]", "+7-495-".$this->getNumber()."-26-26");
                $this->type("css=input[name=\"email\"]", $this->getLogin());
                $this->click('css=.record-to-the-doctor-popup .god-mode');
                $this->click('css=.record-to-the-doctor-popup .send-button');
                sleep(2);
                $this->type('//input[contains(@id, "confirm_code")]',"5555");
                $this->click("css=input[name=\"submit-confirm-code\"]");
                $this->click('css=.record-to-the-doctor-popup .send-button');
                sleep(2);
                $this->assertEquals("Поздравляем, вы успешно записались на прием. Ждите подтверждения!", $this->getText("css=div.fancybox-inner > div"));
            }
        }

        /*
         * Кейсы записи на прием для авторизованного пользователя
         * */

        public function testAppointments4()//запись на прием авторизованного пользователя с подтвержденным телефоном
        {
            $pages = array(
                '/doctor',
                '/clinic/medanna',
            );

            foreach($pages as $page)
            {
                $this->logout();
                $this->open('/');
                $this->Authorization();
                $this->open($page);
                $count = $this->getXpathCount('//a[contains(@class, "btn-appoint")]'); //получаем число элементов, с классом btn-appoint
                $this->assertGreaterThan(0, $count);
                $test_item = rand(1, $count);
                $this->click('xpath=(//a[contains(@class, "btn-appoint")])[position()='.$test_item.']'); //клик по рандомной кнопке
                $this->waitForElementPresent('css=.record-to-the-doctor-popup');//ожидание нашего попапа
                $slots_count = $this->getXpathCount('//li[contains(@class, "time-li")]'); //количество слотов на попапе
                $slot_item = rand(1, $slots_count);//выбераем рандомный слот
                $this->click('xpath=(//li[contains(@class, "time-li")])[position()='.$slot_item.']');//кликаем по слоту
                $this->click('css=.record-to-the-doctor-popup .resume-btn');//кликаем на кнопку "Записаться"
                $this->type("css=input[name=\"surname\"]", "Редько Сергей Викторович");
                $this->type("css=input[name=\"phone\"]", "+7-495-641-26-26");
                $this->click('css=.record-to-the-doctor-popup .god-mode');
                $this->click('css=.record-to-the-doctor-popup .send-button');
                sleep(3);
                $this->assertEquals("Поздравляем, вы успешно записались на прием. Ждите подтверждения!", $this->getText("css=div.fancybox-inner > div"));
            }
        }

        public function testAppointments5()//запись на прием авторизованного пользователя с не подтвержденным телефоном
        {
            $pages = array(
                '/doctor',
                '/clinic/medanna',
            );

            foreach($pages as $page)
            {
                $this->logout();
                $this->open('/');
                $this->testAuthorization();
                $this->open($page);
                $count = $this->getXpathCount('//a[contains(@class, "btn-appoint")]'); //получаем число элементов, с классом btn-appoint
                $this->assertGreaterThan(0, $count);
                $test_item = rand(1, $count);
                $this->click('xpath=(//a[contains(@class, "btn-appoint")])[position()='.$test_item.']'); //клик по рандомной кнопке
                $this->waitForElementPresent('css=.record-to-the-doctor-popup');//ожидание нашего попапа
                $slots_count = $this->getXpathCount('//li[contains(@class, "time-li")]'); //количество слотов на попапе
                $slot_item = rand(1, $slots_count);//выбераем рандомный слот
                $this->click('xpath=(//li[contains(@class, "time-li")])[position()='.$slot_item.']');//кликаем по слоту
                $this->click('css=.record-to-the-doctor-popup .resume-btn');//кликаем на кнопку "Записаться"
                $this->type("css=input[name=\"surname\"]", "Редько Сергей Викторович");
                $this->type("css=input[name=\"phone\"]", "+7-495-641-26-26");
                $this->click('css=.record-to-the-doctor-popup .god-mode');
                $this->click('css=.record-to-the-doctor-popup .send-button');
                sleep(3);
                $this->assertEquals("Поздравляем, вы успешно записались на прием. Ждите подтверждения!", $this->getText("css=div.fancybox-inner > div"));
            }
        }

        private function getLogin()
        {
            return 'tester'.time().'@test.ru';
        }

        private function getNumber()
        {
            return $i = rand(100,999);
        }

        public function Authorization()//Авторизация пользователя
        {
            $this->click("css=a.btn-enter.reg-linking");
            sleep(3);
            $this->type("css=input[name=\"email\"]", "sergey.redko@actsystems.ru");
            $this->type("css=input[name=\"password\"]", "2280104");
            $this->click("css=input.btn-1.submit");
            $this->waitForPageToLoad("30000");
        }
    }
?>