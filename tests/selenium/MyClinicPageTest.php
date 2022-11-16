<?php
    class MyClinicPageTest extends BaseSeleniumTest
    {
        protected $doctor;

        public function testPage()
        {
            $this->login();

            $this->showPage();

            $this->viewMoreVisitedClinicks();

            $this->viewMoreBookmarksClinics();
        }

        private function showPage()
        {
            $this->open('/account/my_clinic');
        }

        protected function viewMoreVisitedClinicks()
        {
            if ($this->isVisible("css=#view_more_visited_clinics")==true)
            {
                $first_count = $this->getXpathCount("//div[contains(@class, 'doctor-big-card')]");
                $this->click("css=#view_more_visited_clinics");
                sleep(5);
                $second_count = $this->getXpathCount("//div[contains(@class, 'doctor-big-card')]");
                $this->assertGreaterThanOrEqual($first_count, $second_count);
            }
        }

        protected function viewMoreBookmarksClinics()
        {
            if ($this->isVisible("css=#view_more_my_clinics")==true)
            {
                $first_count = $this->getXpathCount("//div[contains(@class, 'doctor-big-card')]");
                $this->click("css=#view_more_my_clinics");
                sleep(5);
                $second_count = $this->getXpathCount("//div[contains(@class, 'doctor-big-card')]");
                $this->assertGreaterThanOrEqual($first_count, $second_count);
            }
        }
    }