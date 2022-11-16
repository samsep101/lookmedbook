<?php
    class AuthorizationTest extends BaseSeleniumTest
    {
        public function testAuthorizationCorrect()//PopUp на главной странице
        {
            $login = array(
                $this->getLogin(),
                $this->getLoginPhoneNumber(),
                $this->getLoginPhoneNumberWithoutPlus(),
            );

            foreach($login as $log)
            {
                $this->open("/");
                $this->click("css=a.btn-enter.reg-linking");
                sleep(2);
                $this->type("css=input[name=\"email\"]", $log);
                $this->type("css=input[name=\"password\"]", "2280104");
                $this->click("css=input.btn-1.submit");
                $this->waitForPageToLoad("30000");
                $this->logout();
            }
        }

        public function test_AuthorizationHeaderButtons()//авторизация через кнопку "Войти" на страницах
        {
            $pages = array(
                '/example',
                //'/help',
                '/about',
                '/doctor',
                '/clinic',
                '/disease',
                '/disease/zheltaya-lihoradka/male',
                '/analysis',
                '/shop/catalog',
                '/shop/basket',
            );

            $login = array(
                $this->getLogin(),
                $this->getLoginPhoneNumber(),
                $this->getLoginPhoneNumberWithoutPlus(),
            );

            foreach($pages as $page)
            {
                foreach($login as $log)
                {
                    $this->open($page);
                    $this->waitForPageToLoad("30000");
                    sleep(2);
                    $this->click("css=a.btn-enter.reg-linking");
                    sleep(2);
                    $this->type("css=input[name=\"email\"]", $log);
                    $this->type("css=input[name=\"password\"]", "2280104");
                    $this->click("css=input.btn-1.submit");
                    $this->waitForPageToLoad("30000");
                    $this->click("link=Выйти");
                }
            }
        }

        public function test_AuthorizationHeaderButtonRegistration()//авторизация через ссылку "Войти" на
            //попапе Регистрации
        {
            $pages = array(
                '/example',
                //'/help',
                '/about',
                '/doctor',
                '/clinic',
                '/disease',
                '/disease/zheltaya-lihoradka/male',
                '/analysis',
                '/shop/catalog',
                '/shop/basket',
            );

            $login = array(
                $this->getLogin(),
                $this->getLoginPhoneNumber(),
                $this->getLoginPhoneNumberWithoutPlus(),
            );

            foreach($pages as $page)
            {
                foreach ($login as $log)
                {
                    $this->open($page);
                    $this->click("link=Зарегистрироваться");
                    sleep(2);
                    $this->click("id=login-popup-link");
                    sleep(3);
                    $this->type("css=div.form.auth-form > div.row.flo > div.txt > input[name=\"email\"]", $log);
                    $this->type("css=div.form.auth-form > div.row.flo > div.txt > input[name=\"password\"]", "2280104");
                    $this->click("css=input.btn-1.submit");
                    $this->waitForPageToLoad("30000");
                    $this->logout();
                }
            }
        }

        public function testAuthorizationWithLinkInDesease()//страница заболевания, блок "О LookMedBook"
        {
            $login = array(
                $this->getLogin(),
                $this->getLoginPhoneNumber(),
                $this->getLoginPhoneNumberWithoutPlus(),
            );

            foreach ($login as $log)
            {
                $this->open("/disease/zheltaya-lihoradka/male");
                $this->waitForPageToLoad("30000");
                $this->click("css=ul.list > a.btn-reg");
                sleep(2);
                $this->click("id=landing-login-link");
                sleep(2);
                $this->type("css=input[name=\"email\"]", $log);
                $this->type("css=input[name=\"password\"]", "2280104");
                $this->click("css=input.btn-1.submit");
                $this->waitForPageToLoad("30000");
                $this->logout();
            }
        }

        public function testAuthorizationAddBookmark()//авторизация через кнопку "Добавить в закладки
                                                      //на старнице клиник и страницы врачей"
        {
            $pages = array(
              '/clinic',
              '/doctor',
            );

            $login = array(
                $this->getLogin(),
                $this->getLoginPhoneNumber(),
                $this->getLoginPhoneNumberWithoutPlus(),
            );

            foreach ($pages as $page)
            {
                foreach($login as $log)
                {
                    $this->logout();
                    $this->open($page);
                    sleep(2);
                    $count = $this->getXpathCount('//a[contains(@class, "btn-bookmark")]'); //получаем число кнопок "Добавить в закладки" на странице
                    $this->assertGreaterThan(0, $count);
                    $test_item = rand(1, $count);
                    $this->click('xpath=(//a[contains(@class, "btn-bookmark")])[position()='.$test_item.']');
                    sleep(2);
                    $this->click('//a[contains(@id, "landing-login-link")]');
                    sleep(2);
                    $this->type("css=input[name=\"email\"]", $log);
                    $this->type("css=input[name=\"password\"]", "2280104");
                    $this->click("css=input.btn-1.submit");
                    $this->waitForPageToLoad("30000");
                    $this->logout();
                }
            }
        }

        //негативные кейсы

        public function testAuthorizationIncorrect1()//незарегистрированный email и правильный пароль
        {
            $this->open("/");
            $this->click("css=a.btn-enter.reg-linking");
            sleep(2);
            $this->type("css=input[name=\"email\"]", "sergey.redko1@actsystems.ru");
            $this->type("css=input[name=\"password\"]", "2280104");
            $this->click("css=input.btn-1.submit");
            sleep(2);
            $this->assertEquals("Указанный адрес еще не зарегистрирован", $this->getText("css=label.error"));
        }

        public function testAuthorizationIncorrect2() //незаполненное поле "Email"
        {
            $this->open("/");
            $this->click("css=a.btn-enter.reg-linking");
            sleep(2);
            $this->type("css=input[name=\"email\"]", "");
            $this->type("css=input[name=\"password\"]", "2280104");
            $this->click("css=input.btn-1.submit");
            sleep(2);
            $this->assertEquals("Поле обязательно для заполнения", $this->getText("css=label.error"));
        }

        public function testAuthorizationIncorrect3() //незаполненное поле "Пароль"
        {
            $this->open("/");
            $this->click("css=a.btn-enter.reg-linking");
            sleep(2);
            $this->type("css=input[name=\"email\"]", $this->getLogin());
            $this->type("css=input[name=\"password\"]", "");
            $this->click("css=input.btn-1.submit");
            sleep(2);
            $this->assertEquals("Неверный пароль", $this->getText("css=label.error"));
        }

        public function testAuthorizationIncorrecr4() // длина пароля меньше 6 символов
        {
            $this->open("/");
            $this->click("css=a.btn-enter.reg-linking");
            sleep(2);
            $this->type("css=input[name=\"email\"]", $this->getLogin());
            $this->type("css=input[name=\"password\"]", "22801");
            $this->click("css=input.btn-1.submit");
            sleep(2);
            $this->assertEquals("Неверный пароль", $this->getText("css=label.error"));
        }

        private function getLogin()
        {
            return "sergey.redko@actsystems.ru";
        }

        private function getLoginPhoneNumber()
        {
            return "+74956412626";
        }

        private function getLoginPhoneNumberWithoutPlus()
        {
            return "84956412626";
        }
    }