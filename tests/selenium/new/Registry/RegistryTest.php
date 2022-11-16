<?php

class RegistryTest extends BaseSeleniumTest
{
  /*   public function test_REGISTRY_13_doctor_search()
    {
        $this->mainManagerLogin();

        $this->click("xpath=(//input[@type='text'])[3]");
        $this->type("xpath=(//input[@type='text'])[3]", "Распопина");
        $this->click("xpath=(//input[@type=''])[3]");
        $this->waitForPageToLoad("30000");
    }

    public function test_REGISTRY_14_regions_search()
    {
        $this->mainManagerLogin();
        $this->click("css=input.txt");
        $this->type("css=input.txt", "32 плюс");
        $this->click("css=input.btn-search");
        $this->waitForPageToLoad("30000");
    }

    public function test_REGISTRY_15_clinics_search()
    {
        $this->mainManagerLogin();
        $this->click("xpath=(//input[@type='text'])[2]");
        $this->type("xpath=(//input[@type='text'])[2]", "120 на 80");
        $this->click("css=input.btn-search");
        $this->waitForPageToLoad("30000");
        $this->isElementPresent("link=Клиника \"120 на 80\"");
    }

    public function test_REGISTRY_16_new_clinic ()
    {
        $this->mainManagerLogin();
        $this->click("css=input.manage-add");
        $this->waitForPageToLoad();
        $this->type("name=clinic_name", "Clinic #3");
        $this->type("name=address", "Test Address 11111");
        $this->type("name=longitude", "37.6505");
        $this->type("name=latitude", "55.7878");
        $this->click("name=save");
        $this->isTextPresent("Клиника успешно добавлена");
    }

    public function test_REGISTRY_18_doctor_edit()
    {
        $this->mainManagerLogin();
        $this->open("/registry/doctor/information?id=205");
        $this->type("name=form[last_name]", "Test");
        $this->type("name=form[first_name]", "Test");
        $this->type("name=form[second_name]", "Test");
        $this->type("name=form[about]","Test info");
        $this->click("name=publish");
    }*/

    public function test_REGISTRY_19_doctor_specialties_add ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/doctor/specialties?id=205");
        $this->waitForPageToLoad();
        $this->click("//div[@id='doctor-specialties-form']/div[4]/input");
        $this->select("name=specialty_id", "label=гинеколог");
        sleep(2);
        $this->type("xpath=(//input[@name='visit_price'])[1]", "1000");
        $this->type("xpath=(//input[@name='visit_price'])[2]", "1000");
        $this->click("name=publish");
        $this->isTextPresent("*");
    }


    public function test_REGISTRY_22_delete_photo ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/doctor/photos?id=205");
        $this->click("css=span.delete-photo-button");
        !$this->isElementPresent("css=#image");
        $this->click("name=publish");
        sleep(6);
    }



    public function test_REGISTRY_24_1_cancel_button ()
    {
        $this->mainManagerLogin();
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

    public function test_REGISTRY_24_2_cancel_button ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/doctor/specialties?id=205");
        $this->click("//input[@value='Добавить специализацию']");
        $this->select("name=specialty_id", "label=врач ультразвуковой диагностики");
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
    }



    public function test_REGISTRY_25_add_clinic_to_doctor ()
    {
        $this->open("/admin/security/logout");
        $this->type("name=login", "account");
        $this->type("name=password", "denis");
        $this->click("id=signin");
        $this->open("/registry/doctor/clinics?id=205");
        $this->select("name=clinic_id", "label=120 на 80");
        $this->click("name=publish");
        $this->isTextPresent("Данные опубликованы на сайте");
        $this->waitForPageToLoad();
        $this->open("/doctor/shilovlb");
        $this->waitForPageToLoad();
        $this->isTextPresent("Клиника 120 на 80");
    }

    public function test_REGISTRY_26_clinic_add ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/doctor/clinics?id=205&clinic_id=1");
        $this->click("css=input.add-clinic");
        sleep(3);
        $this->select("//div[@id='doctor-clinics-form']/div[3]/div/div/div/select", "label=«Милюта»");
        $this->click("name=publish");
        $this->waitForTextPresent("Данные успешно сохранены");
        $this->open("/doctor/shilovlb");
        $this->click("css=li.loc-2");
        $this->isTextPresent("Милюта");
        $this->open("/registry/doctor/clinics?id=205&clinic_id=1");
        $this->waitForPageToLoad();
        $this->click("//div[@id='doctor-clinics-form']/div[2]/div/span/input");
        $this->click("name=publish");
        $this->waitForTextPresent("Данные успешно сохранены");
    }

    public function test_REGISTRY_27_auto_save ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/doctor/information?id=205");
        $this->type("name=form[last_name]", "Test2");
        $this->type("name=form[first_name]", "Test2");
        $this->type("name=form[second_name]", "Test2");
        $this->click("link=Специализации и цены");
        $this->waitForPageToLoad();
        $this->click("css=div.nav-2 > ul > li > a");
        $this->waitForPageToLoad();
        try {
            $this->assertEquals("Test2", $this->getValue("css=input[name=\"form[first_name]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        try {
            $this->assertEquals("Test2", $this->getValue("css=input[name=\"form[last_name]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        try {
            $this->assertEquals("Test2", $this->getValue("css=input[name=\"form[second_name]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
    }

    public function test_REGISTRY_30_many_specialties ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/doctor/specialties?id=205");
        $this->click("//input[@value='Добавить специализацию']");
        $this->select("xpath=(//select[@name='specialty_id'])[3]", "label=невролог");
        $this->click("name=publish");
        $this->waitForTextPresent("Данные опубликованы на сайте");
        $this->open("/doctor/shilovlb");
        $this->isTextPresent("невролог", "гинеколог");
    }

    public function test_REGISTRY_31_price_publication ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/doctor/specialties?id=205");
        $this->select("name=specialty_id", "label=гинеколог");
        $this->type("name=visit_price", "1200");
        $this->type("xpath=(//input[@name='visit_price'])[2]", "1200");
        $this->click("name=publish");
        $this->waitForTextPresent("Данные опубликованы на сайте");
        $this->open("/doctor/terapevt?&purpose_of_visit_id=284&doctor_name=Шилов");
        $this->waitForText("css=div.price-inf > p", "1200");
    }

    public function test_REGISTRY_32_html_formatting ()
    {
        $this->open("/admin/security/logout");
        $this->type("name=login", "account");
        $this->type("name=password", "denis");
        $this->click("id=signin");
        $this->waitForPageToLoad(40000);
        $this->open("/registry/doctor/information?id=205");
        $this->type("name=form[about]", "<p>TestInfo</p>");
        $this->click("name=publish");
        $this->waitForTextPresent("Данные опубликованы на сайте");
        $this->open("/doctor/shilovlb/");
        $this->isElementPresent("css=div.about-cont > p");
    }

    public function test_REGISTRY_33_clinic_edits_saving ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/information?clinic_id=10");
        $this->type("name=form[name]", "Test");
        $this->type("name=form[full_name]", "Test");
        $this->type("name=form[longitude]", "37.1111");
        $this->type("name=form[latitude]", "55.4444");
        $this->click("name=publish");
        $this->waitForTextPresent("Данные опубликованы на сайте");
        $this->open("/registry/example/showClinicPage?id=10");
        //$this->assertEquals('Test', $this->getText("//html/body/div/div/div[1]/div/div[1]/div[1]/h1"));
    }


    public function test_REGISTRY_37_non_publishing ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/information?clinic_id=3");
        $this->click("css=div.chekBox");
        $this->click("name=publish");
        $this->waitForTextPresent("Данные опубликованы на сайте");
        $this->open("/clinic");
        $this->type("name=clinic_name", "милюта");
        $this->click("css=input.btn-1.btn-clinic");
        $this->assertFalse($this->isTextPresent("Милюта"));
    }

    public function test_REGISTRY_41_1_cancel_button ()
    {
        $this->mainManagerLogin();

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
        $this->type("name=form[latitude]", "55.5555");

        $this->click("name=cancel");
        $this->waitForPageToLoad();

        $this->assertEquals($name, $this->getText("name=form[name]"));
        $this->assertEquals($full_name, $this->getText("name=form[full_name]"));
        $this->assertEquals($longitude, $this->getText("name=form[longitude]"));
        $this->assertEquals($latitude, $this->getText("name=form[latitude]"));
        $this->assertEquals($city, $this->select("name=form[city_id]", "label=Москва"));
    }

    public function test_REGISTRY_41_2_cancel_button ()
    {
        $this->mainManagerLogin();
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

    public function test_REGISTRY_41_4_cancel_button ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/requisites?clinic_id=3");
        //$this->type("name=form[name_of_bank]", "test name #1");
        //$this->type("name=form[legal_address]", "test address #1");
        $bank_name = $this->getText("name=form[name_of_bank]");
        $legal_address = $this->getText("name=form[legal_address]");
        $this->type("name=form[name_of_bank]", "fgfdgddgd");
        $this->type("name=form[legal_address]", "test address #2");

        $this->clickAndWait("name=cancel");
        //$this->waitForPageToLoad(30000);
        $this->assertEquals($bank_name, $this->getText("name=form[name_of_bank]"));
        $this->assertEquals($legal_address, $this->getText("name=form[legal_address]"));
    }

    public function test_REGISTRY_42_license_saving ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/license?clinic_id=2");
        $this->type("css=.row-record-data > input:nth-child(1)", " 77-01-004841");
        $this->click("css=#license_issue_date_picker");
        $this->type("css=#license_issue_date", "13-11-2013");
        $this->type("css=#license_validity_date", "14-11-2013");
        $this->click("name=publish");
        $this->waitForTextPresent("Данные опубликованы на сайте");
    }

    public function test_REGISTRY_57_additional_services ()
    {
        $this->mainManagerLogin();
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

    public function test_REGISTRY_59_doctors_of_clinic ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/doctors?clinic_id=2");
        $this->type("css=input.fio-field", "кузнецов");
        $this->click("css=input.find-doctor");
        $this->click("link=Кузнецов Владислав Владимирович");
    }

    public function test_REGISTRY_60_doctors_of_clinic_2 ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/doctors?clinic_id=2");
        $this->type("css=input.fio-field", "Акст");
        $this->select("css=select.specialty-pick", "label=эндокринолог");
        $this->click("css=input.find-doctor");
        $this->click("link=Акст Людмила Вячеславовна");
    }

    public function test_REGISTRY_61_doctor_adding ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/doctors?clinic_id=2");
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

    public function test_REGISTRY_64_clinic_type_change ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/information?clinic_id=2");
        $this->click("//div[@id='information-form']/div/div/div[4]/div/ul/li[3]/div");
        $this->click("name=publish");
        $this->isTextPresent("Данные опубликованы на сайте");
        $this->open("/clinic");
        $this->waitForPageToLoad();
        $this->click("//div[@id='clinic-search-form']/div/div/div/div[2]/div[3]/ul/li[2]/div/span");
        $this->type("name=clinic_name", "Остеон");
        $this->click("css=input.btn-1.btn-clinic");
        $this->isTextPresent("Клиника неврологии и остеопатии /Остеон/");
    }

    public function test_REGISTRY_65_REGIONS_SEARCH()
    {
        $this->mainManagerLogin();
        $this->type("//input[@type='text']", "Семейная клиника Династия");
        $this->click("//input[@type='']");
        $this->waitForPageToLoad("30000");
        for ($second = 0; ; $second++) {
            if ($second >= 60) $this->fail("timeout");
            try {
                if ("Семейная клиника Династия" == $this->getText("link=Семейная клиника Династия")) break;
            } catch (Exception $e) {}
            sleep(1);
        }
    }

    public function test_REGISTRY_66_FILTERING_BY_ROLE ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/manage/regions?&registry_user_id=");
        $this->waitForPageToLoad();
        $this->click("name=freelancer-pick");
        $this->select("name=freelancer-pick", "label=test3");
        $this->waitForPageToLoad();
        $this->assertTrue($this->isElementPresent("link=Все - 64"));
    }

    public function test_REGISTRY_67_DELETE_REGION ()
    {
        $this->mainManagerLogin();
        $num = rand(1, 1500);
        $this->open("/registry/clinic/information?clinic_id=$num");
        $this->waitForPageToLoad();
        $name = $this->getText("css=span");
        $this->click("name=delete_clinic");
        $this->click("id=ask_section_save_butoon");
        $this->isTextPresent("Клиника удалена");
        $this->open("/registry/manage");
        $this->type("//input[@data-id='clinic']", $name);
        $this->click("//input[@type='']");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("По вашему запросу клиник не найдено", $this->getText("css=p.no-results"));
    }

    public function test_REGISTRY_68 ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/manage/regions?&status_id=2&page=1");
        $this->waitForPageToLoad();
        $count = $this->getXpathCount("/html/body/div[3]/div[2]/div/div/div/div[3]/ul/li");
        $selected = rand(1, $count);
        $this->click("/html/body/div[3]/div[2]/div/div/div/div[3]/ul/li[".$selected."]/a");
        $presence = $this->isElementPresent("css=div.chekBox.act > span");
        if ($presence == 1) $this->fail("Необработанная клиника не может быть опубликованной");
            else {
                $this->click("css=div.chekBox.act > span");
                $this->clickAndWait("css=input[name=\"publish\"]");
                $title = $this->getTitle();
                $this->open("/registry/manage");
                $this->type("css=input.txt", $title);
            };
    }

    public function test_REGISTRY_71_status_changing ()
    {
        $this->mainManagerLogin();
        $this->open("/registry/manage/regions?status_id=3");
        $this->waitForPageToLoad();
        $count = $this->getXpathCount("/html/body/div[3]/div[2]/div/div/div/div[3]/ul/li/a");
        $selected = rand(1, $count);
        $this->click("/html/body/div[3]/div[2]/div/div/div/div[3]/ul/li[".$selected."]/a");
        $presence = $this->isElementPresent("css=div.chekBox.act > span");
        if ($presence == 1) $this->fail("Необработанная клиника не может быть опубликованной");
        else {
            $this->click("css=div.chekBox.act > span");
            $this->clickAndWait("css=input[name=\"save\"]");
            $title = $this->getText("css=span");
            $this->open("/registry/manage");
            $this->type("css=input.txt", $title);
            try {
                $this->assertTrue($this->isElementPresent("css=li > span.region-img.region-problem"));
            } catch (PHPUnit_Framework_AssertionFailedError $e) {
                array_push($this->verificationErrors, $e->toString());
            }
        };
    }

    /* public function test_REGISTRY_72_status_changing_unable_to_find ()
 {
     $this->mainManagerLogin();
     $this->open("/registry/manage/regions?status_id=1");
     $this->waitForPageToLoad();
     $count = $this->getXpathCount("/html/body/div[3]/div[2]/div/div/div/div[3]/ul/li/a");
     $selected = rand(1, $count);
     $this->click("/html/body/div[3]/div[2]/div/div/div/div[".$selected."]/ul/li/a");
     $this->click("css=div.chekBox > span");
     $this->clickAndWait("css=input[name=\"publish\"]");
     $title = $this->getText("css=span");
     $this->open("/clinic");

 }*/

    public function test_REGISTRY_73_HTML_formatting()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/information?clinic_id=329");
        $this->open("/registry/manage/region_check");
        $this->waitForPageToLoad(40000);
        try {
            $this->assertTrue($this->isElementPresent("//img[contains(@src,'http://cs310920.vk.me/v310920513/63d1/GiRi05LyUjw.jpg')]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }

    }

    public function test_REGISTRY_74_clinic_WEB_site()
    {
        $this->mainManagerLogin();
        $this->open("/registry/manage/region_check");
        sleep(10);
        $this->click("css=a.btn-appoint");
        $this->waitForPageToLoad();
        $this->type("css=input[name=\"form[site]\"]", "http://www.ugmk-clinic.ru");
        $this->clickAndWait("css=input[name=\"publish\"]");
        $this->open("/registry/manage/region_check");
        $this->click("//a[contains(text(),'Перейти на сайт клиники')]");
        $this->assertEquals("Европейский Екатеринбургский Медицинский Центр (ЕМЦ) \"УГМК-Здоровье\" | Поликлиники Екатеринбурга | Частная семейная медицинская клиника", $this->getTitle());
    }

    public function test_REGISTRY_76_specialties_region_check()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/license?clinic_id=329");
        $this->click("//div[@id='clinic-license-form']/div[3]/div/div/div[2]/label/div/div");
        $this->click("//div[@id='clinic-license-form']/div[3]/div/div/div[6]/label/div/div");
        $this->click("//div[@id='clinic-license-form']/div[3]/div/div/div[10]/label/div/div");
        $this->click("css=input[name=\"publish\"]");
        $this->isTextPresent("Данные опубликованы на сайте");
        $this->open("/registry/manage/region_check");
        sleep(5);
        $this->assertEquals("аллергология-иммунология\n гастроэнтерология\n гомеопатия", $this->getTable("css=table.region-clinic.1.2"));
    }


    public function test_REGISTRY_77_users_filtering ()
    {
        $this->mainManagerLogin();
        $this->open("/manage/user");
        $this->type("name=login", "one");
        $this->select("name=role_id", "label=Аккаунт-менеджер");
        $this->select("name=clinic_id", "label=«120 на 80»");
        $this->click("//input[@value='Применить']");
        $this->waitForPageToLoad("3000");
        $this->isTextPresent("one");
        $this->isTextPresent("Аккаунт-менеджер");
    }

    public function test_REGISTRY_78_users_login_filtering ()
    {
        $this->mainManagerLogin();
        $this->open("/manage/user");
        $this->type("name=login", "one");
        $this->click("css=input[type=\"submit\"]");
        $this->waitForPageToLoad("12000");
        $this->isTextPresent("one");
    }

    public function test_REGISTRY_79_USERS_ROLE_FILTERING ()
    {
        $this->mainManagerLogin();
        $this->open("/manage/user");
        $this->select("name=role_id", "label=Аккаунт-менеджер");
        $this->click("css=input[type=\"submit\"]");
        $this->waitForPageToLoad();
        $this->open("/manage/user?login=&role_id=4&clinic_id=0");
        for ($second = 0; ; $second++) {
            if ($second >= 60) $this->fail("timeout");
            try {
                if ("Аккаунт-менеджер" == $this->getText("//td[2]")) break;
            } catch (Exception $e) {}
            sleep(1);
        }
    }

    public function test_REGISTRY_80_USERS_CLINIC_FILTERING ()
    {
        $this->mainManagerLogin();
        $this->open("/manage/user");
        $this->waitForPageToLoad();
        $this->select("name=clinic_id", "label=«120 на 80»");
        $this->click("css=input[type=\"submit\"]");
        $this->isElementPresent("link='Редактировать'");
    }

    public function test_REGISTRY_81_NO_PARAMETERS_FILTERING ()
    {
        $this->mainManagerLogin();
        $this->open("/manage/user");
        $this->click("css=input[type=\"submit\"]");
        $this->waitForPageToLoad();
        $this->waitForTextPresent("Представитель клиники");
        $this->waitForTextPresent("Аккаунт-менеджер");
        $this->waitForTextPresent("Менеджер-фрилансер");
    }

    public function test_REGISTRY_82_NEW_USER_ADDING ()
    {
        $this->mainManagerLogin();
        $this->open("/manage/user");
        $this->click("css=input.btn-appoint.block-button");
        $this->waitForPageToLoad();
        $this->type("name=login", "Test Login");
        $this->type("id=password", "Testpassword");
        $this->type("name=password2", "Testpassword");
        $this->select("name=role_id", "label=Представитель клиники");
        $this->select("name=clinic_id", "label=Ваше здоровье");
        $this->click("name=save");
        $this->isTextPresent("Пользователь успешно добавлен");
    }

    public function test_REGISTRY_83_FIELDS_VALIDATING_ALERT ()
    {
        $this->mainManagerLogin();
        $this->open("/manage/user/create");
        $this->waitForPageToLoad();
        $this->click("name=save");
        sleep(1);
        $this->assertEquals("Поле обязательно для заполнения", $this->getText("css=label.error"));
    }

    public function test_REGISTRY_84_FIELDS_VALIDATING_ALERT_2 ()
    {
        $this->mainManagerLogin();
        $this->open("/manage/user/create");

        $this->type("id=password", "1234");
        $this->click("name=save");
        for ($second = 0; ; $second++) {
            if ($second >= 60) $this->fail("timeout");
            try {
                if ("Длина пароля должна быть не менее 5 символов" == $this->getText("//span[2]/label")) break;
            } catch (Exception $e) {}
            sleep(1);
        }
    }

    public function test_REGISTRY_85_FIELDS_VALIDATING_ALERT_3 ()
    {
        $this->mainManagerLogin();
        $this->open("/manage/user/create");
        $this->type("id=password", "123456");
        $this->click("name=save");
        for ($second = 0; ; $second++) {
            if ($second >= 60) $this->fail("timeout");
            try {
                if ("Поле обязательно для заполнения" == $this->getText("//span[1]/label")) break;
            } catch (Exception $e) {}
            sleep(1);
        }
    }

    public function test_REGISTRY_86 ()
    {
        $this->mainManagerLogin();
        $this->open("/manage/user/create");
        $this->type("id=password", "123456");
        $this->type("name=password2", "1234567");
        $this->click("name=save");
        for ($second = 0; ; $second++) {
            if ($second >= 60) $this->fail("timeout");
            try {
                if ("Пароль не совпадает" == $this->getText("//span[2]/label")) break;
            } catch (Exception $e) {}
            sleep(1);
        }
    }

    public function test_REGISTRY_87_ADD_EXISTING_USER ()
    {
        $this->mainManagerLogin();
        $this->open("/manage/user/create");
        $this->waitForPageToLoad();
        $this->type("name=login", "test");
        $this->type("id=password", "testtest");
        $this->type("name=password2", "testtest");
        $this->select("css=select[name=\"clinic_id\"]", "label=Test");
        $this->select("css=select[name=\"role_id\"]", "label=Аккаунт-менеджер");
        $this->click("css=input[name=\"save\"]");
        $this->assertAlert("Пользователь уже существует");
    }

    public function test_REGISTRY_86_FIELDS_VALIDATING_ALERT_4 ()
    {
        $this->mainManagerLogin();
        $this->open("/manage/user/create");
        $this->type("id=password", "123456");
        $this->type("name=password2", "1234567");
        $this->click("name=save");
        for ($second = 0; ; $second++) {
            if ($second >= 60) $this->fail("timeout");
            try {
                if ("Пароль не совпадает" == $this->getText("//span[2]/label")) break;
            } catch (Exception $e) {}
            sleep(1);
        }
    }

    public function test_REGISTRY_88_USER_EDITING ()
    {
        $this->mainManagerLogin();
        $this->open("/manage/user");
        $this->click("xpath=(//a[contains(text(),'редактировать')])[2]");
        $this->waitForPageToLoad();
        $this->type("name=login", "example@example.com");
        $this->type("css=#password", "123456");
        $this->type("css=input[name=\"password2\"]", "123456");
        $this->select("css=select[name=\"role_id\"]", "label=Аккаунт-менеджер");
        $this->click("css=input[name=\"save\"]");
            for ($second = 0; ; $second++) {
                if ($second >= 60) $this->fail("timeout");
                try {
                    if ("Данные успешно сохранены" == $this->getText("css=div.fancybox-inner > div")) break;
                } catch (Exception $e) {}
                sleep(1);
            }
        $this->open("/manage/user");
        $this->type("css=input[name=\"login\"]", "example@example.com");
        $this->click("css=input[type=\"submit\"]");
        $this->waitForPageToLoad();
        $this->assertEquals("example@example.com", $this->getText("css=td"));

    }

    public function test_REGISTRY_89_CLINIC_DELETE ()
    {
        $this->mainManagerLogin();
        $this->open("/manage/user");
        $this->click("xpath=(//a[contains(text(),'редактировать')])[31]");
        $this->waitForPageToLoad();
            if ($this->isTextPresent("Клиника \"120 на 80\"")){
                return;
                }
            else {
                $this->select("css=select[name=\"clinic_id\"]", "label=«120 на 80»");
                $this->click("css=div.block.new_clinic_block > input[type=\"submit\"]");
                $this->waitForPageToLoad();
                }
        $this->click("css=a.delete-clinic");
        $this->click("css=#ask_section_save_butoon");
        $this->refresh();
        $this->waitForPageToLoad();
        $this->assertTextNotPresent("Клиника 120 на 80");
    }
}

