<?php
class ProfileNotificationsPageTest extends BaseSeleniumTest
{
    public function testPage()
    {
        $this->login();

        $this->changeNotifications();
    }

    private function changeNotifications()
    {
        $this->open("/account/about");
        $this->click("link=Настройки");
        $this->waitForPageToLoad("3000");

        $notifications_manager = new NotificationSettingsManager();
        if ($notifications_manager->getOneByAccountId($this->test_account->getId())) {
            if ($this->isVisible("//div[contains(@class, 'phone-settings-checkboxes')]")==false){
                $this->click("//div[contains(@class, 'phone-settings-checkbox-main')]");
            }
            else
            {
                $this->click("css=div.chekBox.phone-settings-checkbox > span");
                $this->click("//div[2]/div[2]/span");
                $this->click("//div[3]/span");
            }

            $this->click("css=div.chekBox.mail-settings-checkbox > span");
            $this->click("//div[2]/div[2]/div/div/div[2]/span");
            $this->click("//div[2]/div/div/div[3]/span");

            $this->click("id=save-options-button");

            $this->assertTrue($this->isVisible("css=#success-popup"));
            $this->click("css=a.fancybox-item.fancybox-close");
            $this->assertFalse($this->isVisible("css=#success-popup"));
        }
    }
}