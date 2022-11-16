<?php
class ProfileFamilyPageTest extends BaseSeleniumTest
{
    public function testPage()
    {
        $this->login();

        $this->addFamilyRelation();
        $this->confirmFamilyRelation();
        $this->declineFamilyRelation();
    }

    private function addFamilyRelation() // создание запроса на добавление родственника
    {
        $this->open("/account/about");
        $this->click("link=Семья");
        $this->waitForPageToLoad("3000");

        $this->click("name=first_name");
        $this->type("name=first_name", "Карвига");
        $this->click("name=last_name");
        $this->type("name=last_name", "Денис");
        $this->click("name=middle_name");
        $this->type("name=middle_name", "Валерьевич");
        $this->click("name=phone");
        $this->type("name=phone", "+75298885224");
        $this->click("name=email");
        $this->type("name=email", "d.karviga@gmail.com");
        $this->click("id=add-relation-button");
        sleep(1);
        $this->click("css=a.fancybox-item.fancybox-close");
    }

    private function confirmFamilyRelation() // подтверждение запроса на добавление родственника
    {
        $this->initRelationQuery();
        $this->open("/account/family");
        $this->waitForPageToLoad("3000");

        $this->click("//input[contains(@class, 'confirm-relation')]");
        sleep(2);
        $this->assertTrue($this->isElementPresent("css=.in-club"));
        $this->assertFalse($this->isElementPresent("css=.request-block"));

        $this->removeFamilyRelation();
    }

    private function declineFamilyRelation() // отклонение запроса на добавление родственника
    {
        $this->initRelationQuery();
        $this->open("/account/family");
        $this->waitForPageToLoad("3000");

        $this->click("//input[contains(@class, 'delete-relation-moderate')]");
        sleep(2);
        $this->assertFalse($this->isElementPresent("css=.in-club"));
        $this->assertFalse($this->isElementPresent("css=.request-block"));
    }

    private function removeFamilyRelation() // удаление родственника из клуба
    {
        $this->click("//input[contains(@class, 'delete-relation')]");
        sleep(2);
        $this->assertFalse($this->isElementPresent("css=.in-club"));
        $this->assertFalse($this->isElementPresent("css=.request-block"));
    }

    private function initRelationQuery() // создание тестового аккаунта с имитацией запроса на
    {                                    // добавление в список родственников
        $family_relation_moderate_manager = new FamilyRelationModerateManager();
        $account_manager = new AccountManager();
        $account_phone_manager = new AccountPhoneManager();

        $relative_account = $account_manager->getOneByEmail('tester@gmail.com');
        if (!$relative_account)
        {
            $relative_account = new AccountModel();
            $relative_account->disableValidation();
            $relative_account->first_name = 'Tester';
            $relative_account->last_name = 'First';
            $relative_account->middle_name = 'Testering';
            $relative_account->email = 'tester@gmail.com';
            $relative_account->save();
        }

        $test_phone = $account_phone_manager->getConfirmedOneByAccountId($relative_account->getId());

        if (!$test_phone)
        {

            $test_phone = new AccountPhoneModel();
            $test_phone->disableValidation();
            $test_phone->phone = '+711122233414';
            $test_phone->account_id = $relative_account->getId();
            $test_phone->is_confirmed = 1;
            $test_phone->save();
        }
        $family_relation_moderate = $family_relation_moderate_manager->checkExistsByAccountIdAndToAccountIdAndIsConfirmed($relative_account->getId(), $this->test_account->getId());

        if (!$family_relation_moderate)
        {
            $family_relation_moderate = new FamilyRelationModerateModel();
            $family_relation_moderate->account_id = $relative_account->getId();
            $family_relation_moderate->family_relation_status_id = 4;
            $family_relation_moderate->to_account_id = $this->test_account->getId();
            $family_relation_moderate->save();
        }
    }
}