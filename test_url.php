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

		// print the title of the current page
		echo "The title is " . $driver->getTitle() . "'\n";

		// print the title of the current page
		echo "The current URI is " . $driver->getCurrentURL() . "'\n";

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
		//get homepage contents
		$homepage = file_get_contents($url);
		
		//use regex parse http:// or https:// or ftp:// url
		preg_match_all("/(http|https|ftp):\/\/[^<>[:space:]]+[[:alnum:]#?\/&=+%_]/", $homepage, $match);
		$list = $match[0];

		
		foreach ($list as $value)
		{
			//replace '/ and ";var to null string  
			$result = preg_replace("/\'\/|\"(;var)/", "", $value);
			// use result as url to pass 
			$requestcode = $this->responseCode(5000, $result);
			echo $requestcode."\t";
			echo $result."\n\n";
		}

		//use regex parse href="" url
		preg_match_all("/(href=\")\/[^<>[:space:]]+[[:alnum:]#?\/&=+%_]/",$homepage, $match1);
		$list1 = $match1[0];

		foreach ($list1 as $value)
		{
			//replace href=" to null string
			$result = preg_replace("/(href=\")/", "", $value);
			if(preg_match("/\/(tw)\//", $result)||preg_match("/\/(ysm)/", $result)||preg_match("/\/css/", $result))
			{
				// combine http://dev.muzik-online.com with \tw\ , \ysm , \css
				$string = 'http://dev.muzik-online.com'.$result;
				// use result string as url to pass
				$requestcode = $this->responseCode(5000, $string);
				echo $requestcode."\t";
				echo $string."\n\n";
			}
			else if(preg_match("/\/(concert)/", $result)||preg_match("/\/(article)/", $result)||preg_match("/\/(listen)/", $result)||preg_match("/\/(download)/", $result))
			{
				//combine http://dev.muzik-online.com/tw with \concert , \article , \listen , \download
				$string = 'http://dev.muzik-online.com/tw'.$result;
				// use result string as url to pass
				$requestcode = $this->responseCode(5000, $string);
				echo $requestcode."\t";
				echo $string."\n\n";
			}
			else
			{
				//other website 
				$string = 'http:'.$result;
				$requestcode = $this->responseCode(5000, $string);
				echo $requestcode."\t";
				echo $string."\n\n";
			}
		}

	}

	public function testElement($host, $url)
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

		

		$link = $driver->findElement(WebDriverBy::name('keyword'));

		$link->sendKeys('貝多芬');

		// print the title of the current page
		echo "The title is " . $driver->getTitle() . "'\n";

		echo "The current URI is " . $driver->getCurrentURL() . "'\n";


		// close the Firefox
		//$driver->quit();
	}
}

?>

<?php

	$host = 'http://localhost:4444/wd/hub';
	$url = 'http://dev.muzik-online.com/tw/';
 	//$url = 'https://www.muzik-online.com/tw/';
	//$url = 'https://github.com/gosick/web-test-selenium';

	$test = new TestUrl;
	//$test->getHttpRequest($host, $url);
	$test->testElement($host, $url);
?>
