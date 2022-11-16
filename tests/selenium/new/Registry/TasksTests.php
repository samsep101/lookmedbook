<?php
class TaskTests extends BaseSeleniumTest
{
    public function test_1 ()
    {
        $this->open("/clinic/120-na-80");
        $this->waitForPageToLoad();
        $this->assertEquals("«120 на 80», Москва, ул Народная, 14с1 - «LookMedBook»", $this->getTitle());
        $this->open("http://admin:21506@ufa.dev.lookmedbook.ru/clinic/120-na-80");
        $this->waitForText("css=p", "Мы не нашли страницу, которую Вы искали...");
    }

    public function test_2 ()
    {
        $this->open("http://admin:21506@aaa.dev.lookmedbook.ru");
        $this->waitForPageToLoad();
        $this->assertEquals("", $this->getText("id=logo-404"));
        $this->open("http://admin:21506@aa344343da.dev.lookmedbook.ru");
        $this->waitForPageToLoad();
        $this->assertEquals("", $this->getText("id=logo-404"));
        $this->open("http://admin:21506@a-frfaa.dev.lookmedbook.ru");
        $this->waitForPageToLoad();
        $this->assertEquals("", $this->getText("id=logo-404"));
        $this->open("http://admin:21506@aaвуцаукуакуa.dev.lookmedbook.ru");
        $this->waitForPageToLoad();
        $this->assertEquals("", $this->getText("id=logo-404"));
        $this->open("http://admin:21506@a1-aa.dev.lookmedbook.ru");
        $this->waitForPageToLoad();
        $this->assertEquals("", $this->getText("id=logo-404"));
        $this->open("http://admin:21506@dev.lookmedbook.ru");
        $this->waitForPageToLoad();
        $this->assertEquals("Lookmedbook — поиск врача, запись на прием, информация о заболеваниях, медицинский справочник", $this->getTitle());

    }















}