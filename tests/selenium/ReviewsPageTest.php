<?php
    class ReviewsPageTest extends BaseSeleniumTest
    {
        public function testPage()
        {
            $this->login();

            $this->showPage();

            //кнопка "Оставить отзыв"
            $this->addReviews();

            //переключение Доктороыв/Клиник
            $this->doctorsAndClinicsButton();

        }

        private function showPage()
        {
            $this->open('/account/reviews');
        }

        protected function addReviews()
        {
            if ($this->isElementPresent('link=Оставить отзыв') == TRUE) {
                $this->click('link=Оставить отзыв');
                sleep(3);

                $this->assertTrue($this->isVisible('id=step1-submit'));
                $this->select('id=cabinet', 'label=1');
                $this->select('id=waiting_time', 'label=2');
                $this->select('id=relationship', 'label=3');
                $this->select('id=value_for_money', 'label=4');
                $this->select('id=diagnosis_is_clear', 'label=5');
                $this->select('id=service_at_the_reception', 'label=2');
                $this->click('id=step1-submit');

                sleep(3);
                $this->assertTrue($this->isVisible('id=step2-submit'));
                $this->assertTrue($this->isVisible('id=step2-skip'));
                $this->click('id=not-advice-doctor');
                $this->click('id=yes-advice-clinic');
                $this->click('id=step2-submit');
                sleep(3);

                $this->assertTrue($this->isVisible('id=step3-submit'));
                $this->assertTrue($this->isVisible('id=step3-skip'));
                $this->type('id=doctor_review', 'very good doctor!');
                $this->click('id=step3-submit');
                sleep(3);

                $this->assertTrue($this->isVisible('id=step4-submit'));
                $this->assertTrue($this->isVisible('id=step4-skip'));
                $this->type('id=clinic_review', 'very good clinic!');
                $this->click('id=step4-submit');
                sleep(3);

                $this->assertTrue($this->isVisible('id=step5-submit'));
                $this->type('id=private_review', ' I`m very happy!');
                $this->click('id=step5-submit');

            }
        }

        protected function doctorsAndClinicsButton()
        {
            if ($this->isElementPresent('css=li.doctors_switch > a') == TRUE && $this->isElementPresent('css=li.clinics_switch > a') == TRUE) {

                $this->click("css=li.doctors_switch > a");
                $this->assertTrue($this->isElementPresent("css=li.doctors_switch.active"));
                $this->assertFalse($this->isElementPresent("css=li.clinics_switch.active"));
                //кнопка "Показать еще докторов"
                $this->viewMoreDoctorsReviews();
                sleep(1);

                $this->click("css=li.clinics_switch > a");
                $this->assertTrue($this->isElementPresent("css=li.clinics_switch.active"));
                $this->assertFalse($this->isElementPresent("css=li.doctors_switch.active"));
                //кнопка "Показать еще клиник"
                $this->viewMoreClinicsReviews();
                sleep(1);

                $this->click("css=li.doctors_switch > a");
                $this->assertTrue($this->isElementPresent("css=li.doctors_switch.active"));
                $this->assertFalse($this->isElementPresent("css=li.clinics_switch.active"));
            }
        }

        protected function viewMoreDoctorsReviews()
        {
            if ($this->isVisible("css=#view_more_doctor_reviews") == TRUE) {
                $first_count = $this->getXpathCount("//div[contains(@class, 'doctors_reviews')]");
                $this->click("css=#view_more_doctor_reviews");
                sleep(3);
                $second_count = $this->getXpathCount("//div[contains(@class, 'doctors_reviews')]");
                $this->assertGreaterThanOrEqual($first_count, $second_count);
            }
        }

        protected function viewMoreClinicsReviews()
        {
            if ($this->isVisible("css=#view_more_clinic_reviews") == TRUE) {
                $first_count = $this->getXpathCount("//div[contains(@class, 'clinics_reviews')]");
                $this->click("css=#view_more_clinic_reviews");
                sleep(3);
                $second_count = $this->getXpathCount("//div[contains(@class, 'clinics_reviews')]");
                $this->assertGreaterThanOrEqual($first_count, $second_count);
            }
        }

    }