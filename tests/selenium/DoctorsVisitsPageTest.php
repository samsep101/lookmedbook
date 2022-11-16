<?php
class DoctorsVisitsPageTest extends BaseSeleniumTest
{
    public function testPage()
    {
        $this->login();

        $this->changeVisitTime();
        $this->leaveReview();

    }

    private function changeVisitTime()
    {
        $this->click("link=Профиль");
        $this->waitForPageToLoad("30000");
        $this->click("link=Запись к врачу");
        $this->waitForPageToLoad("30000");
        if ($this->isElementPresent("css=.btn-4"))
        {
            $this->assertTrue($this->isElementPresent("css=.record-cart"));
            $visit_id = $this->getAttribute('css=.btn-4@data-visit_id');
            $current_visit = ModelManagerFactory::getByName('visit')->getOneById($visit_id);
            $this->click("css=.btn-4");
            $this->waitForText("css=.record-to-the-doctor-popup h1", "Запись на прием");

            if ($this->isElementPresent("css=li.active")) {
                $this->click("css=.time .active");
                $this->click("css=input.btn-1.resume-btn");
                sleep(1);

                $this->assertFalse($this->isVisible("css=.time-scroll"));
                $this->assertTrue($this->isVisible("css=.form-block"));
                $this->click("link=Я принимаю условия");
                $this->click("css=input.btn-1.send-button");
                sleep(3);

                $this->waitForText("css=.cab-cont h2", "Предстоящие");
                ModelManagerFactory::getByName('visit')->clearRegister();
                $changed_visit = ModelManagerFactory::getByName('visit')->getOneById($visit_id);
                var_dump($current_visit->schedule_id);
                var_dump($changed_visit->schedule_id);
                $this->assertNotEquals($current_visit->schedule_id, $changed_visit->schedule_id);

                $this->assertFalse($this->isElementPresent("css=.fancybox-overlay"));
            }
        }
    }

    private function leaveReview()
    {
        $this->click("link=Профиль");
        $this->waitForPageToLoad("30000");
        $this->click("link=Запись к врачу");
        $this->waitForPageToLoad("30000");
        $this->click("link=Прошедшие");
        $this->waitForPageToLoad("30000");
        if ($this->isElementPresent("css=.review-link"))
        {
            $this->assertTrue($this->isElementPresent("css=.visit-remind-block"));
            $visit_href = $this->getAttribute('css=.review-link@href');
            $visit_id = str_replace('#add-review-popup-','',$visit_href);
            $this->click("css=.review-link");
            $this->waitForText("css=.rev-popup h4", "Оцените врача");

            $this->assertTrue($this->isElementPresent("css=.rating-section"));
            $this->click("css=#step1-submit");
            $this->click("css=#step2-skip");
            $this->click("css=#step3-skip");
            $this->click("css=#step4-skip");
            $this->click("css=.fancybox-inner .btn-4 input");
            sleep(4);

            $this->assertFalse($this->isElementPresent("css=.#add-review-popup-".$visit_id));
            $this->assertNotEquals(ModelManagerFactory::getByName('visit_rating')->getOneByVisitIdAndAccountId($visit_id, $this->test_account->getId()),null);
        }
    }

}