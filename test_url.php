<?php

require_once('lib/__init__.php');

class TestUrl extends URLChecker{

	public function getHttpRequest($host, $url)
	{
		$capabilities = DesiredCapabilities::firefox();
		$driver = RemoteWebDriver::create($host, $capabilities, 5000);

		$driver->get($url);

		//$code = $this->getHTTPResponseCode(5000, 'http://docs.seleniumhq.org/');

		$driver->manage()->deleteAllCookies();
		$driver->manage()->addCookie(array(
		  'name' => 'cookie_name',
		  'value' => 'cookie_value',
		  'http_request' => '$code'
		));
		$cookies = $driver->manage()->getCookies();
		print_r($cookies);

		// click the link 'About'
		$link = $driver->findElement(
		WebDriverBy::id('menu_about')
		);
		$link->click();

		// print the title of the current page
		echo "The title is " . $driver->getTitle() . "'\n";

		// print the title of the current page
		echo "The current URI is " . $driver->getCurrentURL() . "'\n";

		// Search 'php' in the search box
		$input = $driver->findElement(
		WebDriverBy::id('q')
		);
		$input->sendKeys('php')->submit();

		// wait at most 10 seconds until at least one result is shown
		$driver->wait(10)->until(
			WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
				WebDriverBy::className('gsc-result')
			)
		);


		// close the Firefox
		$driver->quit();
	}

	public function ttt($timeout_in_ms, $url)
	{
		$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, self::CONNECT_TIMEOUT_MS);
    	// There is a PHP bug in some versions which didn't define the constant.
    	curl_setopt(
     	 	$ch,
     	 	156, // CURLOPT_CONNECTTIMEOUT_MS
      		self::CONNECT_TIMEOUT_MS
    	);
    	$code = null;
	    try {
	      curl_exec($ch);
	      $info = curl_getinfo($ch);
	      $code = $info['http_code'];
	    } catch (Exception $e) {
	    }
	    curl_close($ch);
	    return $code;
	}
}

?>

<?php

	$host = 'http://localhost:4444/wd/hub';
	$url = 'https://www.muzik-online.com/tw/article/focus/63b1a9c4-b077-7711-81f6-f23af65f44c5'; 
	$test = new TestUrl;
	//$test->getHttpRequest($host, $url);
	$code = $test->ttt(5000, $url);
	echo $code;
?>