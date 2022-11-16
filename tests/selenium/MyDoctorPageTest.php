<?php
    class MyDoctorPageTest extends BaseSeleniumTest
    {
        protected $doctor;

        public function testPage()
        {
            $this->login();

            $this->showPage();

            $this->viewMoreVisitedDoctors();

            $this->viewMoreBookmarksDoctors();
        }

        private function showPage()
        {
            $this->open('/account/my_doctor');
        }

        protected function viewMoreVisitedDoctors()
        {
            if ($this->isVisible("css=#more_visited_doctors")==true)
            {
                $first_count = $this->getXpathCount("//div[contains(@class, 'doctor-big-card')]");
                $this->click("css=#more_visited_doctors");
                sleep(5);
                $second_count = $this->getXpathCount("//div[contains(@class, 'doctor-big-card')]");
                $this->assertGreaterThanOrEqual($first_count, $second_count);
            }
        }

        protected function viewMoreBookmarksDoctors()
        {
            if ($this->isVisible("css=#more_my_doctors")==true)
            {
                $first_count = $this->getXpathCount("//div[contains(@class, 'doctor-big-card')]");
                $this->click("css=#more_my_doctors");
                sleep(5);
                $second_count = $this->getXpathCount("//div[contains(@class, 'doctor-big-card')]");
                $this->assertGreaterThanOrEqual($first_count, $second_count);
            }
        }

    }