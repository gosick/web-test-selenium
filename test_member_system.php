<?php

require_once('lib/__init__.php');

class TestMemberSystem {
	
	public function testLogin($host, $url, $account, $password)
	{
		$capabilities = DesiredCapabilities::firefox();
		$driver = RemoteWebDriver::create($host, $capabilities, 5000);

		$driver->get($url);
		

		$driver->manage()->deleteAllCookies();
		$driver->manage()->addCookie(array(
		  'name' => 'cookie_name',
		  'value' => 'cookie_value',
		));
		$cookies = $driver->manage()->getCookies();
		print_r($cookies);

		$link1 = $driver->findElement(WebDriverBy::id('account'))->sendKeys($account);
		$link2 = $driver->findElement(WebDriverBy::id('password'))->sendKeys($password);
		$link3 = $driver->findElement(WebDriverBy::classname('button-gold'));
		$link3->click();
		// print the title of the current page
		echo "The title is " . $driver->getTitle() . "'\n";

		// print the title of the current page
		echo "The current URI is " . $driver->getCurrentURL() . "'\n";

		$cookies = $driver->manage()->getCookies();
		print_r($cookies);
		// close the Firefox
		$driver->quit();
	}

}

?>

<?php
	$host = 'http://localhost:4444/wd/hub';
	$url = 'http://dev.muzik-online.com/tw/member/login';
	$account = 'f56112000@gmail.com';
	$password = 'ss07290420';
	$test = new TestMemberSystem;
	$test->testLogin($host, $url, $account, $password);

?>