<?php

require_once('lib/__init__.php');

class TestUrl extends URLChecker{

	public function getHttpRequest($host, $url)
	{
		$capabilities = DesiredCapabilities::firefox();
		$driver = RemoteWebDriver::create($host, $capabilities, 5000);

		$driver->get($url);

		$this->getUrlList($url);

		$driver->manage()->deleteAllCookies();
		$driver->manage()->addCookie(array(
		  'name' => 'cookie_name',
		  'value' => 'cookie_value',
		));
		$cookies = $driver->manage()->getCookies();
		print_r($cookies);

		// click the link 'About'
		/*$link = $driver->findElement(
		WebDriverBy::id('menu_about')
		);
		$link->click();*/

		// print the title of the current page
		echo "The title is " . $driver->getTitle() . "'\n";

		// print the title of the current page
		echo "The current URI is " . $driver->getCurrentURL() . "'\n";

		// Search 'php' in the search box
		/*$input = $driver->findElement(
		WebDriverBy::id('q')
		);
		$input->sendKeys('php')->submit();
		*/
		// wait at most 10 seconds until at least one result is shown
		/*$driver->wait(10)->until(
			WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
				WebDriverBy::className('gsc-result')
			)
		);*/


		// close the Firefox
		$driver->quit();
	}

	public function responseCode($timeout_in_ms, $url)
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

	public function getUrlList($url)
	{
		$homepage = file_get_contents($url);
		preg_match_all("/(http|https|ftp):\/\/[^<>[:space:]]+[[:alnum:]#?\/&=+%_]/", $homepage, $match);
		$list = $match[0];

		
		foreach ($list as $value)
		{
			$result = preg_replace("/\'\/|\"(;var)/", "", $value);
			$requestcode = $this->responseCode(5000, $result);
			echo $requestcode."\t";
			echo $result."\n";
		}


		preg_match_all("/(href=\")\/[^<>[:space:]]+[[:alnum:]#?\/&=+%_]/",$homepage, $match1);
		$list1 = $match1[0];

		foreach ($list1 as $value)
		{
			$result = preg_replace("/(href=\")/", "", $value);
			if(preg_match("/\/(tw)\//", $result)||preg_match("/\/(ysm)/", $result)||preg_match("/\/css/", $result))
			{
				$string = 'http://dev.muzik-online.com'.$result;
				$requestcode = $this->responseCode(5000, $string);
				echo $requestcode."\t";
				echo $string."\n\n";
			}
			else if(preg_match("/\/(concert)/", $result)||preg_match("/\/(article)/", $result)||preg_match("/\/(listen)/", $result)||preg_match("/\/(download)/", $result))
			{
				$string = 'http://dev.muzik-online.com/tw'.$result;
				$requestcode = $this->responseCode(5000, $string);
				echo $requestcode."\t";
				echo $string."\n\n";
			}
			else
			{
				$string = 'http:'.$result;
				$requestcode = $this->responseCode(5000, $string);
				echo $requestcode."\t";
				echo $string."\n\n";
			}
		}
	}
}

?>

<?php

	$host = 'http://localhost:4444/wd/hub';
	$url = 'http://dev.muzik-online.com/tw/'; 
	$test = new TestUrl;
	$test->getHttpRequest($host, $url);

?>
