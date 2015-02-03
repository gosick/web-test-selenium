<?php 

require_once('lib/__init__.php');

class muzikOnlineTest extends URLChecker{

	protected $host, $driver, $capabilities, $setTimeout, $refresh, $cookies;
	protected $account, $password, $passwordConfirm, $btnSend, $keyword, $searchBtn;
	protected $periodicalTag, $memberFlag;

	public function __construct($url)
	{
		$this->elementSetUp();
		$this->driver = RemoteWebDriver::create($this->host, $this->capabilities, 5000);
		$this->refresh = $this->driver->navigate();
		$this->driver->get($url);
		$this->setTimeout = $this->driver->manage()->timeouts();
	}

	public function pageRefresh()
	{
		$this->refresh->refresh();
	}

	public function elementSetUp()
	{
		$this->host = 'http://localhost:4444/wd/hub';
		$this->capabilities = DesiredCapabilities::firefox();

		$this->account = 'account';
		$this->password = 'password';
		$this->passwordConfirm = 'password_confirm';
		$this->btnSend = 'button-gold';
		$this->periodicalTag = '';
		$this->keyword = 'keyword';
		$this->searchBtn = 'span.icon.icon-header-search';
		$this->memberFlag = false;
	}

	public function player()
	{

	}

	public function menuTest($i, $select, $string, $account, $password)
	{
		switch ($select) {
			case 'allMusic':
				$total = '//div[@class="container"]/nav/ul/li[1]/ul/li';
				$totalLength = count($this->driver->findElements(WebDriverBy::xpath($total)));
				if($i > $totalLength || $i <= 0)
				{
					echo "element does not exist\n";
				}
				else
				{
					$this->allmusicMenuTest($i);
				}
				break;
			case 'article':
				$total = '//div[@class="container"]/nav/ul/li[2]/ul/li';
				$totalLength = count($this->driver->findElements(WebDriverBy::xpath($total)));
				if($i > $totalLength || $i <= 0)
				{
					echo "element does not exist\n";
				}
				else
				{
					$this->articleMenuTest($i);
				}
				break;
			case 'concert':
				if($i > 0 || $i < 0)
				{
					echo "element does not exist\n";
				}
				else
				{
					$this->concertTest();
				}
				break;
			case 'periodical':
				$total = '//div[@class="container"]/nav/ul/li[4]/ul/li';
				$totalLength = count($this->driver->findElements(WebDriverBy::xpath($total)));
				if($i > $totalLength || $i <= 0)
				{
					echo "element does not exist\n";
				}
				else
				{
					$this->periodicalTest($i);
				}
				break;
			case 'member':
				$total = '//div[@class="container"]/nav/ul/li[5]/ul/li';
				$totalLength = count($this->driver->findElements(WebDriverBy::xpath($total)));
				if($i > $totalLength || $i <= 0)
				{
					echo "element does not exist\n";
				}
				else
				{
					$this->memberTest($i);
				}
				break;
			case 'search':
				if($i == 0)
				{
					$this->search($string);
				}
				break;
			case 'login':
				if($i == 0)
				{
					$url = '//div[@class="container"]/div/div[1]/a[1]';
					$this->driver->findElement(WebDriverBy::xpath($url))->click();
					$this->pageRefresh();
					$this->loginTest($account, $password);
				}
				break;
			case 'register':
				if($i == 0)
				{
					$url = '//div[@class="container"]/div/div[1]/a[2]';
					$this->driver->findElement(WebDriverBy::xpath($url))->click();
					$this->pageRefresh();
					$this->registerTest($account, $password);
				}
				break;
			case 'homepage':
				if($i == 0)
				{
					$url = '//div[@class="container"]/a';
					$this->driver->findElement(WebDriverBy::xpath($url))->click();
					$this->pageRefresh();
				}
				break;
			case 'memberProfile':
				if($this->checkLogin())
				{
					$this->driver->findElement(WebDriverBy::classname('name'))->click();
					if($this->memberProfile())
						$this->memberSongList('add');
				}
				break;
			case 'loginchecked':
				break;
			default:
				break;
		}
	}

	public function memberProfile()
	{
		$this->memberFlag = true;
		//enter my song list
		
		return $this->memberFlag;
	}

	public function memberSongList($select)
	{
		$url = '//div[@class="primary-content js-controller-content"]/div[1]/a[1]';
		$this->driver->findElement(WebDriverBy::xpath($url))->click();
		$this->pageRefresh();
		$this->wait(0.5);

		switch ($select) {
			case 'add':
				//--add new song list--//

				//click add song list button
				$this->driver->findElement(WebDriverBy::cssSelector('div.btnAdd.add'))->click();
				
				//fill the song list title
				$this->wait(0.5);
				$this->driver->findElement(WebDriverBy::id('playlist_title'))->sendKeys('test');

				//fill the song list description and then send
				$this->driver->findElement(WebDriverBy::id('summary'))->sendKeys('testlist');
				$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();

				break;

			case 'edit':
				//--edit a song list--//

				//find the third song list outer div
				$getSonglistDiv = $this->driver->findElements(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[3]/div'));

				//write javascript to set the overflow not to hide
				$js = "arguments[0].style.height='auto'; arguments[0].style.overflow='scroll';";
				$this->driver->executeScript($js, $getSonglistDiv);

				//then the inner elements will be visible
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[3]/div/div/a[3]'))->click();
			
			
				//--fill the playlist_title and description with some texts and then submit--//

				$this->driver->findElement(WebDriverBy::id('playlist_title'))->clear()->sendKeys('ya');
				//clear title and then fill the title

				$songListAddFilldescription = $this->driver->findElement(WebDriverBy::id('summary'))->clear()->sendKeys('oK!');
				//clear description and then fill the description

				$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();

				break;

			case 'copy':
				//--copy a song list--//

				//find the first song list outer div
				$getSonglistDiv = $this->driver->findElements(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[1]/div'));

				//write javascript to set the overflow not to hide
				$js = "arguments[0].style.height='auto'; arguments[0].style.overflow='scroll';";
				$this->driver->executeScript($js, $getSonglistDiv);

				//then the inner elements will be visible
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[1]/div/div/a[2]'))->click();

				//--fill the playlist_title and description with some texts and then submit--//

				$this->driver->findElement(WebDriverBy::id('playlist_title'))->clear()->sendKeys('ya');
				//clear title and then fill the title

				$this->driver->findElement(WebDriverBy::id('summary'))->clear()->sendKeys('Ok!');
				//clear description and then fill the description

				$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click() ;
				
				break;

			case 'play':
				//play the songs of the song list

				//find the first song list outer div
				$getSonglistDiv = $this->driver->findElements(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[1]/div'));

				//write javascript to set the overflow not to hide
				$js = "arguments[0].style.height='auto'; arguments[0].style.overflow='scroll';";
				$this->driver->executeScript($js, $getSonglistDiv);

				//then the inner elements will be visible
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[1]/div/div/a[5]'))->click();

				break;

			case 'enter':
				//enter the song list

				$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[1]/a'));
				$getSonglistDiv->click();

				$this->select('add to list', 'temporary');


				break;

			case 'del':
				//delete the song list

				//click delete song list button
				$this->driver->findElement(WebDriverBy::cssSelector('div.btnDelete.remove'))->click();
				
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[3]/div/a'))->click();

				$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();

			default:
				# code...
				break;
		}
	}

	public function checkLogin()
	{
		$flag = false;
		$array = $this->cookies;
		$size = count($array);
		for($i = 0; $i < $size; $i++)
		{
			if($array[$i]['name'] == 'member_i18n')
				$flag = true;
		}
		
		return $flag;
	}

	public function search($string)
	{
		$this->wait(1);
		$this->driver->findElement(WebDriverBy::name($this->keyword))->sendKeys($string);
		$this->driver->findElement(WebDriverBy::cssSelector($this->searchBtn))->click();
	}

	public function wait($time)
	{
		$this->setTimeout->implicitlyWait($time);
	}
	

	public function indexTest()
	{

	}
//-----------------Dj Member Theme Music Album------------------------//
	public function allmusicMenuTest($j)
	{
		$allMusic = '//div[@class="container"]/nav/ul/li[1]';
		$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($allMusic))->getCoordinates());
		$url = '//div[@class="container"]/nav/ul/li[1]/ul/li['.$j.']/a';
		$this->driver->findElement(WebDriverBy::xpath($url))->click();
		$this->pageRefresh();
	}

//-----------------Focus Commodify Expert-------------------------//

	public function articleMenuTest($j)
	{
		$article = '//div[@class="container"]/nav/ul/li[2]';
		$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($article))->getCoordinates());
		$url = '//div[@class="container"]/nav/ul/li[2]/ul/li['.$j.']/a';
		$this->driver->findElement(WebDriverBy::xpath($url))->click();
		$this->pageRefresh();
	}


//-----------------concert-------------------------//
	
	public function concertTest()
	{
		$url = '//div[@class="container"]/nav/ul/li[3]/a';
		$this->driver->findElement(WebDriverBy::xpath($url))->click();
		$this->pageRefresh();
	}

//------------------------------------------//

	public function periodicalTest($j)
	{
		$periodical = '//div[@class="container"]/nav/ul/li[4]';
		$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($periodical))->getCoordinates());
		$url = '//div[@class="container"]/nav/ul/li[4]/ul/li['.$j.']/a';
		$this->driver->findElement(WebDriverBy::xpath($url))->click();
		$this->pageRefresh();

		/*if($this->periodicalTag == '')
		{
			$this->periodicalTag = 'muzik';
			$flag = 0;
			$this->periodicalMuzikTest($flag);
		}*/

	}
	//flag should only be 0 or 1.
	public function selectPeriodical($flag)
	{
		$url = '//div[@class="mag-type"]/ul/li['.$flag.']/a';
		$this->driver->findElement(WebDriverBy::xpath($url))->click();

		if($flag == 0)
		{
			$this->periodicalTag = 'muzik';
			$this->periodicalMuzikTest($flag);
		}

		if($flag == 1)
		{
			$this->periodicalTag = 'category';
			$this->periodicalCategory($flag);
		}

		
	}

	public function periodicalMuzikTest($flag)
	{
		/*------this is the menu dropdown for select the magazine--*/
		// open the menu
		$menu = '//div[@class="mag-year dropdown clearfix"]/div/a';
		$this->driver->findElement(WebDriverBy::xpath($menu))->click();
		$menuList = '//div[@class="list dropdown-menu"]/ul/li';
		$list = $this->driver->findElements(WebDriverBy::xpath($menuList));
		$length = count($list);
		$url = '//div[@class="list dropdown-menu"]/ul/li[3]/a';
		$this->driver->findElement(WebDriverBy::xpath($url))->click();
	}


	public function periodicalCategory($flag)
	{
		switch (1) {
			case 'special_column':
				# code...
				break;
			
			case 'people_profile':
				break;

			case 'elegant_living':
				break;

			case 'encyclopedia':
				break;

			case 'publication_comment':
				break;

			default:
				break;
		}
	}

//------------------------------------------//

	public function memberTest($j)
	{
		$periodical = '//div[@class="container"]/nav/ul/li[5]';
		$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($periodical))->getCoordinates());
		$url = '//div[@class="container"]/nav/ul/li[5]/ul/li['.$j.']/a';
		$this->driver->findElement(WebDriverBy::xpath($url))->click();
		$this->pageRefresh();
	}

//------------------------------------------//

	public function loginTest($account, $password)
	{
		$this->driver->findElement(WebDriverBy::id($this->account))->sendKeys($account);
		$this->driver->findElement(WebDriverBy::id($this->password))->sendKeys($password);
		$this->wait(1);
		$this->driver->findElement(WebDriverBy::classname($this->btnSend))->click();
		$this->pageRefresh();
		$this->cookies = $this->driver->manage()->getCookies();	
	}

//------------------------------------------//

	public function registerTest($account, $password)
	{
				
		$this->driver->findElement(WebDriverBy::id($this->account))->sendKeys($account);
		$this->driver->findElement(WebDriverBy::id($this->password))->sendKeys($password);
		$this->wait(1);
		$this->driver->findElement(WebDriverBy::id($this->passwordConfirm))->sendKeys($password);
		$this->driver->findElement(WebDriverBy::classname($this->btnSend))->click();
		$this->pageRefresh();

	}

//------------------------------------------//	

	public function forgetPasswordTest()
	{
		$this->driver->findElement(WebDriverBy::id($this->account))->sendKeys($account);
		$this->driver->findElement(WebDriverBy::id($this->password))->sendKeys($password);
		$this->driver->findElement(WebDriverBy::id($this->passwordConfirm))->sendKeys($password);
		$this->driver->findElement(WebDriverBy::classname($this->btnSend))->click();
		$this->pageRefresh();
	}

//------------------------------------------//

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

//----------------------------------------//

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

}


?>

<?php 

	$url = 'http://dev.muzik-online.com/tw/service';
	$test = new muzikOnlineTest($url);
	//$test->getUrlList($url);
	$test->menuTest(0, 'login', '', 'f56112000@gmail.com', 'ss07290420');
	$test->menuTest(0, 'memberProfile', '', '', '');
	//$test->periodicalTest();
	//$test->menuTest();
?>