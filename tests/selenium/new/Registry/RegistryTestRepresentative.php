<?php
class RegistryTestFreelancer extends BaseSeleniumTest
{
    public function test_REGISTRY_17_doctor_info_saving ()
    {
        $this->representativeLogin();
        $this->open("/registry/doctor/information?id=211");
        for ($i=0; $i<=3; $i++){
            $this->type("css=input[name=\"form[last_name]\"]", "Test$i");
            $this->type("css=input[name=\"form[first_name]\"]", "Test$i");
            $this->type("css=input[name=\"form[second_name]\"]", "Test$i");
            $this->type("css=textarea[name=\"form[about]\"]", "Test$i");
            $this->clickAndWait("css=input[name=\"save\"]");
            $this->open("/registry/example/showDoctorPage?id=211");
            for ($second = 0; ; $second++) {
                if ($second >= 10) $this->fail("timeout");
                try {
                    if ("Test$i Test$i Test$i" == $this->getText("css=p.name > a")) break;
                } catch (Exception $e) {}
                sleep(1);
            }
            $this->assertEquals("Врач гинеколог, Test$i Test$i Test$i", $this->getTitle());
        }
    }

    public function test_REGISTRY_18_specialties_saving ()
    {
        $this->representativeLogin();
        $this->open("/registry/doctor/schedule_create?&id=211&clinic_id=1&specialty_id=14");
        $specialty_presence = $this->isTextPresent("акушер-гинеколог");
        for ($i=0; $i<=1; $i++) {
            if ($specialty_presence == 1) break;
            else
                $this->open("/registry/doctor/specialties?id=211");
            $this->select("css=select[name=\"specialty_id\"]", "label=акушер-гинеколог");
            $i=2;
        }
        $this->click("css=input[name=\"publish\"]");
    }

    public function test_REGISTRY_22_delete_photo ()
    {
        $this->representativeLogin();
        $this->open("/registry/doctor/photos?id=205");
        $this->click("css=span.delete-photo-button");
        $this->assertElementNotPresent("css=#image");
        $this->click("name=publish");
        sleep(6);
    }

    public function test_REGISTRY_23_1_cancel_button ()
    {
        $this->representativeLogin();
        $this->waitForPageToLoad("30000");
        $this->open("/registry/doctor/information?id=205");
        $last_name = $this->getText("name=form[last_name]");
        $first_name = $this->getText("name=form[first_name]");
        $second_name = $this->getText("name=form[second_name]");
        $about = $this->getText("name=form[about]");

        $this->type("name=form[last_name]", "Test");
        $this->type("name=form[first_name]", "Test");
        $this->type("name=form[second_name]", "Test");
        $this->type("name=form[about]","Test info");
        $this->click("name=cancel");

        $this->waitForPageToLoad();

        $this->assertEquals($last_name, $this->getText("name=form[last_name]"));
        $this->assertEquals($first_name, $this->getText("name=form[first_name]"));
        $this->assertEquals($second_name, $this->getText("name=form[second_name]"));
        $this->assertEquals($about, $this->getText("name=form[about]"));
    }

    public function test_REGISTRY_23_2_cancel_button ()
    {
        $this->representativeLogin();
        $this->open("/registry/doctor/specialties?id=205");
        $this->click("//input[@value='Добавить специализацию']");
        $this->select("xpath=(//select[@name='specialty_id'])[3]", "label=врач ультразвуковой диагностики");
        $this->type("xpath=(//input[@name='visit_price'])[3]", "1000");
        $this->type("xpath=(//input[@name='visit_price'])[4]", "2000");
        $specialty = $this->getText("name=specialty_id");
        $this->type("name=visit_price", "100000");
        $this->type("xpath=(//input[@name='visit_price'])[2]", "100000");
        $first = $this->getText("name=visit_price");
        $second = $this->getText("xpath=(//input[@name='visit_price'])[2]");
        $this->click("name=cancel");

        $this->waitForPageToLoad();

        $this->assertEquals($specialty, $this->getText("name=specialty_id"));
        $this->assertEquals($first, $this->getText("name=visit_price"));
        $this->assertEquals($second, $this->getText("xpath=(//input[@name='visit_price'])[2]"));
        $this->assertElementNotPresent("xpath=(//input[@name='visit_price'])[3]");
        $this->assertElementNotPresent("xpath=(//input[@name='visit_price'])[4]");
    }

    public function test_REGISTRY_24_add_clinic_to_doctor ()
    {
        $this->representativeLogin();
        $this->open("/registry/doctor/clinics?id=205");
        $this->select("name=clinic_id", "label=«120 на 80»");
        $this->click("css=input[name=\"publish\"]");
        $this->isTextPresent("Данные опубликованы на сайте");
        $this->waitForPageToLoad();
        $this->open("/doctor/shilovlb");
        $this->waitForPageToLoad();
        $this->isTextPresent("Клиника 120 на 80");
    }

    public function test_REGISTRY_25_clinic_add ()
    {
        $this->representativeLogin();
        $this->open("/registry/doctor/clinics?id=205&clinic_id=1");
        $this->click("css=input.add-clinic");
        $this->select("css=select[name=\"clinic_id\"]", "label=«120 на 80»");
        $this->select("xpath=(//select[@name='clinic_id'])[3]", "label=Test");
        $this->click("//input[@name='save']");
        $this->waitForTextPresent("Данные успешно сохранены");
        $this->open("/doctor/shilovlb");
        $this->click("css=li.loc-2 > span");
        $this->isTextPresent("Test");
    }

    public function test_REGISTRY_26_auto_save ()
    {
        $this->representativeLogin();
        $this->open("/registry/doctor/information?id=205");
        $this->type("name=form[last_name]", "Test1");
        $this->type("name=form[first_name]", "Test2");
        $this->type("name=form[second_name]", "Test3");
        $this->type("name=form[about]","Test info");
        $this->click("//a[contains(text(),'Специализации и цены')]");
        $this->waitForPageToLoad(3000);
        $this->open("/registry/doctor/information?id=205");
        $this->waitForPageToLoad();
        try {
            $this->assertEquals("Test1", $this->getValue("css=input[name=\"form[last_name]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }

        try {
            $this->assertEquals("Test2", $this->getValue("css=input[name=\"form[first_name]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }

        try {
            $this->assertEquals("Test3", $this->getValue("css=input[name=\"form[second_name]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }

        try {
            $this->assertEquals("Test info", $this->getValue("css=textarea[name=\"form[about]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }

        $this->type("name=form[last_name]", "Shilov");
        $this->type("name=form[first_name]", "Leonid");
        $this->type("name=form[second_name]", "Borisych");
        $this->type("name=form[about]","Bio");
        $this->click("//input[@name='save']");
        $this->waitForPageToLoad();
    }

    public function test_REGISTRY_29_many_specialties ()
    {
        $this->representativeLogin();
        $this->open("/registry/doctor/specialties?id=205");
        $this->click("//input[@value='Добавить специализацию']");
        $this->select("xpath=(//select[@name='specialty_id'])[3]", "label=невролог");
        $this->click("name=publish");
        $this->waitForTextPresent("Данные опубликованы на сайте");
        $this->open("/doctor/shilovlb");
        $this->isTextPresent("невролог", "гинеколог");
    }

    public function test_REGISTRY_30_price_publication ()
    {
        $this->representativeLogin();
        $this->open("/registry/doctor/specialties?id=205");
        $this->select("name=specialty_id", "label=гинеколог");
        $this->type("name=visit_price", "1200");
        $this->type("xpath=(//input[@name='visit_price'])[2]", "1200");
        $this->click("name=publish");
        $this->waitForTextPresent("Данные опубликованы на сайте");
        $this->open("/doctor/terapevt?&purpose_of_visit_id=284&doctor_name=Шилов");
        $this->waitForText("css=div.price-inf > p", "1200");
    }

    public function test_REGISTRY_31_html_formatting ()
    {
        $this->representativeLogin();
        $this->open("/registry/doctor/information?id=205");
        $this->type("name=form[about]", "<p>TestInfo</p>");
        $this->click("name=save");
        $this->waitForTextPresent("Данные успешно сохранены");
        $this->open("/doctor/shilovlb/");
        $this->isElementPresent("css=div.about-cont > p");
    }

    public function test_REGISTRY_32_preview_button()
    {
        $this->representativeLogin();
        $this->open("/registry/doctor/information?id=221");
        $this->click("//input[@value='Предпросмотр страницы']");
        $this->waitForPageToLoad();
        $this->assertEquals("Терапевт Волкова Наталия Викторовна", $this->getText("css=p.name > a"));
    }

    public function test_REGISTRY_33_clinic_edits_saving ()
    {
        $this->representativeLogin();
        $this->open("/registry/clinic/information?clinic_id=2");
        $this->waitForPageToLoad();
        $this->type("name=form[name]", "Test");
        $this->type("name=form[full_name]", "Test");
        $this->type("name=form[longitude]", "53.9999");
        $this->type("name=form[latitude]", "37.9999");
        $this->click("css=input[name=\"save\"]");
        $this->waitForTextPresent("Данные успешно сохранены");
        $this->open("/registry/example/showClinicPage?id=2");
        $this->waitForPageToLoad();
        $this->assertEquals('Test', $this->getText("//html/body/div/div/div[1]/div/div[1]/div[1]/h1"));
    }

    public function test_REGISTRY_35_metro_station_selection ()
    {
        $this->representativeLogin();
        $this->open("/registry/clinic/information?clinic_id=2");
        $this->select("css=div#metro-station-select-container.row-record-data > select", "label = Таганская");
        $this->click("name=publish");
        $this->waitForTextPresent("Данные опубликованы на сайте");
    }

    public function test_REGISTRY_36_37_clinic_publication()
    {
        $this->representativeLogin();
        $this->open("/registry/clinic/information?clinic_id=3");
        $this->click("//div[@id='information-form']/div/div/div/div[2]/div");
        $this->click("css=input[name=\"publish\"]");
        $this->isTextPresent("Данные опубликованы на сайте");
        $this->waitForPageToLoad();
        $this->open("/clinic");
        $this->type("css=input[name=\"clinic_name\"]", "милюта");
        $this->click("css=input.btn-1.btn-clinic");
        $this->waitForPageToLoad();
        $this->assertEquals("«Милюта»", $this->getText("css=span.post"));
        $this->open("/registry/clinic/information?clinic_id=3");
        $this->click("//div[@id='information-form']/div/div/div/div[2]/div");
        $this->click("css=input[name=\"publish\"]");
        $this->isTextPresent("Данные опубликованы на сайте");
        $this->waitForPageToLoad();
        $this->open("/clinic");
        $this->type("css=input[name=\"clinic_name\"]", "милюта");
        $this->click("css=input.btn-1.btn-clinic");
        $this->assertEquals("«Милюта»", !$this->getText("css=span.post"));
    }

    public function test_REGISTRY_40_1_cancel_button ()
    {
        $this->representativeLogin();
        $this->waitForPageToLoad("30000");
        $this->open("/registry/clinic/information?clinic_id=1");
        $name = $this->getText("name=form[name]");
        $full_name = $this->getText("name=form[full_name]");
        $city = $this->select("name=form[city_id]", "label=Москва");
        $longitude = $this->getText("name=form[longitude]", "37.6502");
        $latitude = $this->getText("name=form[latitude]", "55.7378");
        $this->select("name=form[city_id]", "label=Можайск");
        $this->type("name=form[name]", "Test");
        $this->type("name=form[full_name]", "Test");
        $this->type("name=form[longitude]", "37.5555");
        $this->type("name=form[latitude]", "57.5555");
        $this->click("name=cancel");
        $this->waitForPageToLoad();
        $this->assertEquals($name, $this->getText("name=form[name]"));
        $this->assertEquals($full_name, $this->getText("name=form[full_name]"));
        $this->assertEquals($longitude, $this->getText("name=form[longitude]"));
        $this->assertEquals($latitude, $this->getText("name=form[latitude]"));
        $this->assertEquals($city, $this->select("name=form[city_id]", "label=Москва"));
    }

    public function test_REGISTRY_40_2_cancel_button ()
    {
        $this->representativeLogin();
        $this->open("/registry/clinic/license?clinic_id=3");
        $license_number = $this->getText("css=div.row-record-data input");
        $license_starts = $this->getText("id=license_issue_date");
        $license_ends = $this->getText("id=license_validity_date");
        $this->type("css=div.row-record-data input", "12121221");
        $this->type("id=license_issue_date", "12-12-2012");
        $this->type("id=license_validity_date", "12-12-1984");
        $this->click("css=input[name=\"cancel\"]");
        $this->waitForPageToLoad("30000");
        $this->assertEquals($license_number, $this->getText("css=div.row-record-data input"));
        $this->assertEquals($license_starts, $this->getText("id=license_issue_date"));
        $this->assertEquals($license_ends, $this->getText("id=license_validity_date"));

    }

    public function test_REGISTRY_41_3_cancel_button ()
    {
        $this->representativeLogin();
        $this->open("/registry/clinic/description?clinic_id=3");
        $this->waitForPageToLoad();
        $description = $this->getText("html.CSS1Compat body.cke_show_borders");
        $this->type("css=html.CSS1Compat", "Test Description");
        $this->click("name=cancel");
        $this->assertEquals($description, $this->getText("id=description-form"));
    }

    public function test_REGISTRY_40_4_cancel_button ()
    {
        $this->representativeLogin();
        $this->open("/registry/clinic/requisites?clinic_id=3");
        $bank_name = $this->getText("name=form[name_of_bank]");
        $legal_address = $this->getText("name=form[legal_address]");
        $this->type("name=form[name_of_bank]", "fgfdgddgd");
        $this->type("name=form[legal_address]", "test address #2");
        $this->clickAndWait("name=cancel");
        $this->waitForPageToLoad(30000);
        $this->assertEquals($bank_name, $this->getText("name=form[name_of_bank]"));
        $this->assertEquals($legal_address, $this->getText("name=form[legal_address]"));
    }

    public function test_REGISTRY_41_license_saving ()
    {
        $this->representativeLogin();
        $this->open("/registry/clinic/license?clinic_id=2");
        $this->type("css=.row-record-data > input:nth-child(1)", "1234567890");
        $this->click("css=#license_issue_date_picker");
        $this->type("css=#license_issue_date", "13-11-2013");
        $this->type("css=#license_validity_date", "14-11-2013");
        $this->click("name=publish");
        $this->waitForTextPresent("Данные опубликованы на сайте");
    }

    public function test_REGISTRY_44_clinic_specialties ()
    {
        $this->representativeLogin();
        $this->click("//div[@id='clinic-license-form']/div[3]/div/div/div/label/div/div");
        $this->click("//div[@id='clinic-license-form']/div[3]/div/div/div[5]/label/div/div/span");
        $this->click("//div[@id='clinic-license-form']/div[3]/div/div/div[9]/label/div/div/span");
        $this->click("name=publish");
        $this->waitForTextPresent("Данные опубликованы на сайте");
        $this->waitForPageToLoad();
        $this->click("//a[contains(text(),'Услуги')]");
        $this->assertEquals("Акушерство", $this->getText("css=p.first-level-specialty-name"));
        $this->assertEquals("Гирудотерапия", $this->getText("//div[@id='services-form']/div/div[2]/div[3]/p"));
        $this->assertEquals("Восстановительная медицина", $this->getText("//div[@id='services-form']/div/div[2]/div[2]/p"));

    }

    public function test_REGISTRY_55()
    {
        $this->representativeLogin();
        $this->open("/registry/clinic/service?clinic_id=1153");
        $this->click("//div[@id='service-form']/div/div/ul/div/div[3]/li/div");
        $this->click("//div[@id='service-form']/div/div/ul/div[2]/div[3]/li/div");
        $this->click("//div[@id='service-form']/div/div/ul/div[3]/div[3]/li/div");
        $this->clickAndWait("css=input[name=\"publish\"]");
        $this->open("/clinic/alkoklinik");
        $this->assertEquals("Бесплатный Wi-Fi", $this->getText("css=div.info-col.col-comfort > ul > li"));
        $this->assertEquals("Комфортная уборная", $this->getText("//div[3]/ul/li[2]"));
        $this->assertEquals("Одноразовые простыни", $this->getText("//li[3]"));
        $this->open("/registry/clinic/service?clinic_id=1153");
        $this->click("//div[@id='service-form']/div/div/ul/div/div[3]/li/div");
        $this->click("//div[@id='service-form']/div/div/ul/div[2]/div[3]/li/div");
        $this->click("//div[@id='service-form']/div/div/ul/div[3]/div[3]/li/div");
        $this->clickAndWait("css=input[name=\"publish\"]");
    }

    public function test_REGISTRY_56_additional_services ()
    {
        $this->representativeLogin();
        $this->open("/registry/clinic/service?clinic_id=2");
        $this->waitForPageToLoad();
        $this->type("css=#new_feature", "CoffeeScript");
        $this->click("css=img.plus");
        $this->click("name=publish");
        $this->isTextPresent("Данные опубликованы на сайте");
        $this->waitForPageToLoad();
        $this->click("link=Выйти");
        $this->AdminLogin();
        $this->open("/admin/suggested_features");
        $this->waitForPageToLoad();
        $this->isTextPresent("CoffeeScript");
    }

    /* public function test_REGISTRY_57 ()
    {
        $this->representativeLogin();
        $this->open("/registry/clinic/services?clinic_id=1153");
        $this->assertEquals("Нарколог", $this->getText("//div[@id='services-form']/div/div[2]/div/div/div/div/div/div/div"));
        $this->clickAndWait("css=input[name=\"publish\"]");
        $this->open("/clinic");

        $this->select("css=a.chzn-single.chzn-single-with-drop > span", "id=specialties_to_search_clinic_chzn_o_37");
        $this->type("css=input[name=\"clinic_name\"]", "алкоклиник");
        $this->click("css=input.btn-1.btn-clinic");
        $this->assertEquals("«Алкоклиник»", $this->getText("css=span.post"));
    }*/

    public function test_REGISTRY_58_doctors_of_clinic ()
    {
        $this->representativeLogin();
        $this->open("/registry/clinic/doctors?clinic_id=2");
        $this->type("css=input.fio-field", "кузнецов");
        $this->click("css=input.find-doctor");
        $this->click("link=Кузнецов Владислав Владимирович");
    }

    public function test_REGISTRY_59_doctors_of_clinic_2 ()
    {
        $this->representativeLogin();
        $this->open("/registry/clinic/doctors?clinic_id=2");
        $this->type("css=input.fio-field", "Бутенко");
        $this->select("css=select.specialty-pick", "label=остеопат");
        $this->click("link=Бутенко Людмила Сергеевна");
    }

    public function test_REGISTRY_60_doctor_adding ()
    {
        $this->representativeLogin();
        $this->open("/registry/clinic/doctors?clinic_id=1");
        $this->click("link=Добавить врача");
        $this->waitForPageToLoad();
        $this->type("name=form[last_name]",     "One");
        $this->type("name=form[first_name]",    "Two");
        $this->type("name=form[second_name]",   "Three");
        $this->type("name=form[rate]",          "4");
        $this->type("name=form[about]",         "Test");
        $this->click("name=save");
        $this->isTextPresent("Запись успешно добавлена");
    }
}
