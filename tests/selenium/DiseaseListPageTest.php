<?php
class DiseaseListPageTest extends BaseSeleniumTest
{
    public function testPage()
    {
        $this->login();

        //$this->moreDiseaseButton(); // кнопка "еще заболевания" (не работает, т.к. видимость тегов li
                                    //осуществляется по свойству overflow тега ul)

        $this->trySearch();
        $this->tryFailSearch();
    }

    private function trySearch()
    {
        $this->open("/disease/search");
        $this->click("css=form > input[name=\"query\"]");
        $this->type("css=form > input[name=\"query\"]", "беременность");
        $this->click("css=input.btn-1.illness-search-submit");
        $this->waitForPageToLoad("3000");
        $this->assertTrue($this->isElementPresent("css=.ilness-result"));
        if ($this->isElementPresent("link=Подробнее"))
        {
            $this->click("link=Подробнее");
            $this->waitForPageToLoad("3000");
            $this->assertTrue($this->isElementPresent("css=.illness-description"));
        }
    }

    private function tryFailSearch()
    {
        $this->open("/disease/search");
        $this->click("css=form > input[name=\"query\"]");
        $this->type("css=form > input[name=\"query\"]", "sdgdvfbvsdf");
        $this->click("css=input.btn-1.illness-search-submit");
        $this->waitForPageToLoad("3000");
        $this->assertTrue($this->isElementPresent("css=.illness-catalog-error"));
    }

    public function moreDiseaseButton()
    {
        $this->open("/disease/search");
        $first_count = $this->getXpathCount("//html/body/div/div/div/div[2]/div[3]/div[1]/ul/li");
        $this->click("//html/body/div/div/div/div[2]/div[3]/div/a");
        $second_count = $this->getXpathCount("//html/body/div/div/div/div[2]/div[3]/div[1]/ul/li");
        $this->assertGreaterThan($first_count, $second_count);
    }
}