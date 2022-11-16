<?php
class ClinicPageTest extends BaseSeleniumTest
{
    public function testPage()
    {
        $this->login();

        $this->addToBookmark(); // кнопка "добавить в закладки"
        $this->checkBackToSearchButton(); // кнопка "назад к результатам поиска"
        $this->checkMapTab(); // наличие карты
        $this->viewMoreDoctors(); // показать еще 10 врачей
    }

    protected function addToBookmark()
    {
        $clinic_manager = new ClinicManager();
        $clinics = $clinic_manager->getList();
        $this->clinic = $clinics[rand(0, count($clinics) - 1)];

        $my_clinic_manager = new MyClinicManager();
        $entry = $my_clinic_manager->getOneByClinicIdAndAccountId($this->clinic->getId(), $this->test_account->getId());
        if ($entry)
            $my_clinic_manager->delete($entry);

        $this->open('/clinic/get?id='.$this->clinic->getId());

        $this->click("css=.btn-bookmark-big");
        $this->waitForText("css=.btn-bookmark-big .txt-added", 'В закладках');
        $test_entry = $my_clinic_manager->getOneByClinicIdAndAccountId($this->clinic->getId(), $this->test_account->getId());
        $this->assertTrue((bool)$test_entry);

        $this->click("css=.btn-bookmark-big");

        $this->waitForText("css=.btn-bookmark-big .txt", "Добавить в закладки");
        $test_entry = $my_clinic_manager->getOneByClinicIdAndAccountId($this->clinic->getId(), $this->test_account->getId());
        $this->assertNull($test_entry);
    }

    protected function checkBackToSearchButton()
    {
        $this->assertFalse($this->isElementPresent("css=.back-to-search-link"));
    }

    protected function checkMapTab()
    {
        $this->click("css=#ui-id-2");
        $this->assertTrue($this->isElementPresent("css=.ymaps-map"));
    }

    protected function viewMoreDoctors()
    {
        if ($this->isVisible("css=#view_more_doctors")==true)
        {
            $first_count = $this->getXpathCount("//div[contains(@class, 'doctor-big-card')]");
            $this->click("css=.view-more");
            sleep(5);
            $second_count = $this->getXpathCount("//div[contains(@class, 'doctor-big-card')]");
            $this->assertGreaterThan($first_count, $second_count);
        }
    }
}