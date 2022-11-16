<?php
    class DoctorPageTest extends BaseSeleniumTest
    {
        protected $doctor;

        public function testPage()
        {
            $this->login();

            $this->getDoctorPage();

            //кнопка "Добавить в закладки"
            $this->addToBookmark();

            //кнопка "Записаться"
            //$this->showAppointmentPopup();

            //переключение вкладок с расписанием врача
            $this->viewDoctorWorkTimes();

        }

        private function getDoctorPage()
        {
            $doctor_manager = new DoctorManager();
            $doctors = $doctor_manager->getList();
            $this->doctor = $doctors[rand(0, count($doctors) - 1)];

            $this->open('/doctor/get?id=' . $this->doctor->getId());
        }

        protected function addToBookmark()
        {
            $my_doctor_manager = new MyDoctorManager();
            $entry = $my_doctor_manager->getOneByDoctorIdAndAccountId($this->doctor->getId(), $this->test_account->getId());
            if ($entry)
                $my_doctor_manager->delete($entry);

            // пробуем добавить в закладки
            $this->click("css=a.btn-bookmark");

            // проверяем наличие текста "В закладках" ($this->waitForText) и записи в базе
            $this->waitForText("css=span.txt", 'В закладках');
            $test_entry = $my_doctor_manager->getOneByDoctorIdAndAccountId($this->doctor->getId(), $this->test_account->getId());
            $this->assertTrue((bool)$test_entry);

            // пробуем убрать из закладок
            $this->click("css=span.txt.txt-added");

            // проверяем наличие текста и отсутствие записи в базе
            $this->waitForText("css=span.txt", "Добавить в закладки");
            $test_entry = $my_doctor_manager->getOneByDoctorIdAndAccountId($this->doctor->getId(), $this->test_account->getId());

            $this->assertNull($test_entry);
        }

        protected function showAppointmentPopup()
        {
            $this->click("css=#visit-order-button");
            $this->assertTrue($this->isVisible("css=#record-to-the-doctor-popup-" . $this->doctor->getId()));
            $this->click("css=a.fancybox-item.fancybox-close");
            $this->assertFalse($this->isVisible("css=#record-to-the-doctor-popup-" . $this->doctor->getId()));
        }

        protected function viewDoctorWorkTimes()
        {
            $doctor_to_clinic_manager = new DoctorToClinicManager();
            $doctors_clinics = $doctor_to_clinic_manager->getListByDoctorId($this->doctor->getId());

            if (count($doctors_clinics) > 2) {
                $this->assertFalse($this->isVisible("css=.section.section-2.visible.flo"));
                $this->click("css=li.loc-2. > span");
                $this->assertTrue($this->isVisible("css=.section.section-2.visible.flo"));

                $this->assertFalse($this->isVisible("css=.section.section-1.visible.flo"));
                $this->click("css=li.loc-1. > span");
                $this->assertTrue($this->isVisible("css=.section.section-1.visible.flo"));

                $this->assertFalse($this->isVisible("css=.section.section-2.visible.flo"));

            }
        }
    }