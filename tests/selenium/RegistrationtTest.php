<?php
class RegistrationTest extends BaseSeleniumTest
{
	public function test_REGISTRATION_1_main_page()
	{
		$this->logout();
		$this->click('css=#registration-link');
		sleep(2);

		$this->fillRegistrationPopup();
	}

	public function test_REGISTRATION_2_main_page_how_we()
	{
		$this->logout();
		$this->click('css=#registration-link-2');
		sleep(2);

		$this->fillRegistrationPopup();
	}

	public function test_REGISTRATION_3_auth_popup()
	{
		$this->logout();
		$this->click('css=#authorization-link > button');
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
			'/disease/bartolinit/female',
		);

		foreach($pages as $page)
		{
			$this->logout();
			$this->open($page);

			$this->click('css=#authorization-block-on-disease-page .btn-reg');
			$this->waitForElementPresent('css=#landing-popup-registration input[name="landing_registration_email"]');
			$this->fillRegistrationLandingPopup();
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
			'/disease/bartolinit/female',
		);

		foreach($pages as $page)
		{
			$this->logout();
			$this->open($page);
			$this->click('css=#authorization-block-on-disease-page .btn-enter');
			$this->waitForElementPresent('css=#landing-login-popup #landing-registration-link');
			$this->click('css=#landing-login-popup #landing-registration-link');

			$this->waitForElementPresent('css=#landing-popup-registration input[name="landing_registration_email"]');
			$this->fillRegistrationLandingPopup();
		}
	}

	public function test_REGISTRATION_24_right_block_button()
	{
		$this->logout();
		$this->open('/disease/balanit/male');
		$this->click('css=.side-column .btn-reg');

		$this->fillRegistrationLandingPopup();
	}

	private function fillRegistrationPopup()
	{
		$login = $this->getLogin();
		$password = $this->getPassword();

		$this->waitForElementPresent('css=#registration-popup input[name="email"]');

		$this->type('css=#registration-popup input[name="email"]', $login.' ');
		$this->type('css=#registration-popup input[name="password"]', $password);
		$this->type('css=#repeat_registration_password', $password);
		$this->clickAndWait("css=input.btn-1.submit_registration");

		$this->waitForText('css=.header-userprofile', $login);
	}

	private function fillRegistrationLandingPopup()
	{
		$login = $this->getLogin();

		$this->waitForElementPresent('css=#landing-popup-registration input[name="landing_registration_email"]');

		$this->type('css=#landing-popup-registration input[name="landing_registration_email"]', $login.' ');
		$this->clickAndWait("css=#landing-popup-registration #submit_landing_registration");

		$this->waitForText('css=.header-userprofile', $login);
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