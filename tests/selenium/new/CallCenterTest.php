<?php
    class CallCenterTest extends BaseSeleniumTest
    {
        public function testCallCenter1()//Создание обращения без привязки к аккаунту без записи
        {
            $this->logout();
            $this->Authorization();
            $this->open('/account');
            $this->waitForPageToLoad("30000");
            $this->click("css=a.create-appeal.btn-1");
            sleep(1);
            $this->type("css=input[name=\"phone_number\"]",$this->getPhoneNumber());
            $this->type("css=input[name=\"last_name\"]","test".time());
            $this->type("css=input[name=\"first_name\"]","test".time());
            $this->type("css=input[name=\"middle_name\"]","test".time());
            $this->type("css=textarea[name=\"title\"]","test".time());
            $this->select("appeal_type_id","value=".rand(1,2));
            $this->select("visit_source_id","value=".rand(1,3));
            $this->click("css=input[name=\"without_visit\"]");
            sleep(2);
            $this->assertEquals("Обращение сохранено", $this->getText("css=div.fancybox-inner > div"));
        }

        public function testCallCenter2()//Создание обращения без привязки к аккаунту c записью
        {
            $this->logout();
            $this->Authorization();
            $this->waitForPageToLoad("30000");
            $this->click("css=a.create-appeal.btn-1");
            sleep(1);
            $this->type("css=input[name=\"phone_number\"]",$this->getPhoneNumber());
            $this->type("css=input[name=\"last_name\"]","test".time());
            $this->type("css=input[name=\"first_name\"]","test".time());
            $this->type("css=input[name=\"middle_name\"]","test".time());
            $this->type("css=textarea[name=\"title\"]","test".time());
            $this->select("appeal_type_id","value=".rand(1,2));
            $this->select("visit_source_id","value=".rand(1,3));
            $this->click("css=input[name=\"with_visit\"]");
            sleep(2);
            $this->assertEquals("Обращение сохранено", $this->getText("css=div.fancybox-inner > div"));
        }

        public function testCallCenter3()//Создание обращения c привязкой к аккаунту без записи
        {
            $this->logout();
            $this->Authorization();
            $this->open('/account');
            $this->waitForPageToLoad("30000");
            $this->click("css=a.create-appeal.btn-1");
            sleep(1);
            $this->type("css=input[name=\"phone_number\"]",$this->getNumber());
            $this->typeKeys("css=input[name=\"phone_number\"]",$this->getNumber());
            sleep(1);
            $this->type("css=textarea[name=\"title\"]","test".time());
            $this->select("appeal_type_id","value=".rand(1,2));
            $this->select("visit_source_id","value=".rand(1,3));
            $this->click("css=input[name=\"without_visit\"]");
            sleep(2);
            $this->assertEquals("Обращение сохранено", $this->getText("css=div.fancybox-inner > div"));
        }

        public function testCallCenter4()//Создание обращения c привязкой к аккаунту c записью
        {
            $this->logout();
            $this->Authorization();
            $this->open('/account');
            $this->waitForPageToLoad("30000");
            $this->click("css=a.create-appeal.btn-1");
            sleep(1);
            $this->type("css=input[name=\"phone_number\"]",$this->getNumber());
            $this->typeKeys("css=input[name=\"phone_number\"]",$this->getNumber());
            sleep(1);
            $this->type("css=textarea[name=\"title\"]","test".time());
            $this->select("appeal_type_id","value=".rand(1,2));
            $this->select("visit_source_id","value=".rand(1,3));
            $this->click("css=input[name=\"with_visit\"]");
            sleep(2);
            $this->assertEquals("Обращение сохранено", $this->getText("css=div.fancybox-inner > div"));
        }

        public function Authorization() //метод для авторизации оператора call-центра
        {
            $this->click("css=a.btn-enter.reg-linking");
            sleep(2);
            $this->type("css=input[name=\"email\"]", "sergey.redko@actsystems.ru");
            $this->type("css=input[name=\"password\"]", "2280104");
            $this->click("css=input.btn-1.submit");
            $this->waitForPageToLoad("30000");
        }

        private function getPhoneNumber()//генерация случайного номера телефона
        {
            $numberPhone = '+7-495-';
            $count = 0;
            while ($count < 7)
        {
            $numberPhone.=rand(0,9);
            if ($count == 2 || $count == 4)
            {
                $numberPhone.='-';
            }
            $count++;
        }
            return $numberPhone;
        }

        private function getNumber()
        {
            return "+7-495-641-26-26";
        }
    }
?>