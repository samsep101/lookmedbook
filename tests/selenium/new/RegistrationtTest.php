<?php
class RegistrationTest extends BaseSeleniumTest
{
	public function test_REGISTRATION_3_auth_popup()
	{
		$this->open('/account/logout');
		$this->open('/');
		$this->click("link=Войти");
		$this->waitForElementPresent('css=#registration-popup-link');
		$this->click('css=#registration-popup-link');
		sleep(2);

		$this->fillRegistrationPopup();
	}


	public function test_REGISTRATION_5_7_9_11_18_22_header_buttons()
	{
		$pages = array(
            '/example',
            '/help',
            '/about',
            '/doctor',
            '/clinic',
            '/disease',
            '/disease/zheltaya-lihoradka/male',
            '/analysis',
            '/shop/catalog',
            '/shop/basket',
		);

		foreach($pages as $page)
		{
			$this->open($page);
            $this->click("link=Зарегистрироваться");
            sleep(2);
            $this->type("css=input[name=\"email\"]", $this->getLogin());
            $this->type("css=input[name=\"password\"]", "2280104");
            $this->type("id=repeat_registration_password", "2280104");
            $this->clickAndWait("css=input.btn-1.submit_registration");
            $this->click("link=Выйти");
		}
	}

	public function test_REGISTRATION_6_8_10_12_19_23_header_buttons_auth_popup()
	{
		$pages = array(
            '/example',
            '/help',
            '/about',
            '/doctor',
            '/clinic',
            '/disease',
            '/disease/zheltaya-lihoradka/male',
            '/analysis',
            '/shop/catalog',
            '/shop/basket',
		);

		foreach($pages as $page)
		{
            $this->open($page);
            $this->waitForPageToLoad("30000");
            $this->click("link=Войти");
            sleep(2);
            $this->click("id=registration-popup-link");
            sleep(2);
            $this->type("css=input[name=\"email\"]", $this->getLogin());
            $this->type("css=input[name=\"password\"]", "2280104");
            $this->type("id=repeat_registration_password", "2280104");
            $this->click("css=input.btn-1.submit_registration");
            $this->waitForPageToLoad("30000");
            $this->click("link=Выйти");
		}
	}
    public function testRegistrationNewPopUp()//регистрация на странице заболеваний в блоке "О LookMedBook"
    {
        $this->open("/disease/zheltaya-lihoradka/male");
        $this->click("css=ul.list > a.btn-reg");
        $this->waitForPageToLoad("30000");
        $this->typeKeys("css=input[name=\"landing_registration_email\"]", $this->getLogin());
        $this->click("css=#submit_landing_registration");
        $this->waitForPageToLoad("30000");
        $this->click("link=Выйти");
    }
	private function fillRegistrationPopup()
	{
		$login = $this->getLogin();
		$password = $this->getPassword();

		$this->type('css=#registration-popup input[name="email"]', $login.' ');
		$this->type('css=#registration-popup input[name="password"]', $password);
		$this->type('css=#repeat_registration_password', $password);
		$this->clickAndWait("css=input.btn-1.submit_registration");
		$this->waitForText('css=.header-userprofile', $login);
        $this->click("link=Выйти");
	}

	private function fillRegistrationLandingPopup()
	{
		$login = $this->getLogin();

		$this->type('css=#landing-popup-registration input[name="landing_registration_email"]', $login.' ');
		$this->clickAndWait("css=#landing-popup-registration #submit_landing_registration");

		$this->waitForText('css=.header-userprofile', $login);
        $this->click("link=Выйти");
	}


	private function getLogin()
	{
		return 'tester'.time().'@test.ru';
	}

	private function getPassword()
	{
		return 123456;
	}
}