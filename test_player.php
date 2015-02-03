<?php

require_once('lib/__init__.php');

class TestPlayer {

	protected $cookies, $driver, $capabilities, $host, $setTimeout, $refresh;
	
	//--search--//
	protected $musicSearch, $composer, $composerWorks, $composerTrack, $composerAlbum;
	protected $keyword, $searchBtn;
	
	//--login--//
	protected $login, $account, $password, $loginSend;
	
	//--player--//
	protected $player, $pin, $temporaryList, $myList, $myCollection, $nowPlaying, $deleteSong;
	protected $footer, $footerLeft, $prev, $play, $pause, $next;
	protected $footerTrack, $progressBar, $share, $info, $download;
	protected $footerRight, $mute, $unmute, $volume, $repeat, $shuffle;
	protected $language, $chineseTra, $japanese, $english, $closePlayer;
	//--player--//


	public function __construct($url)
	{
		$this->host = 'http://localhost:4444/wd/hub';
		$this->capabilities = DesiredCapabilities::firefox();
		$this->driver = RemoteWebDriver::create($this->host, $this->capabilities, 5000);
		$this->driver->get($url);
		$this->setTimeout = $this->driver->manage()->timeouts();
		$this->elementSetUp();
	}

	
	public function elementSetUp()
	{
		// refresh page
		$this->refresh = $this->driver->navigate();
		// search
		$this->keyword = 'keyword';
		$this->searchBtn = 'span.icon.icon-header-search';
		$this->musicSearch = '//div[@class="search-category"]/ul/li[1]/a';
		$this->composer = '//div[@class="search-category"]/ul/li[2]/a';
		$this->composerWorks = '//div[@class="search-category"]/ul/li[3]/a';
		$this->composerTrack = '//div[@class="search-category"]/ul/li[4]/a';
		$this->composerAlbum = '//div[@class="search-category"]/ul/li[5]/a';
		// element of login
		$this->login = '//div[@class="container"]/div/div[1]/a[1]';
		$this->account = 'account';
		$this->password = 'password';
		$this->loginSend = 'button-gold';
		// get the element of the player
		$this->player = '//div[@class="player jp-player"]';
		$this->pin = '//div[@class="player jp-player over"]/div[1]/div[1]/a';
		$this->temporaryList = '//div[@class="player jp-player open"]/div[1]/div[2]/ul/li[1]/a';
		$this->myList = '//div[@class="player jp-player open"]/div[1]/div[2]/ul/li[2]/a';
		$this->myCollection = '//div[@class="player jp-player open"]/div[1]/div[2]/ul/li[3]/a';
		$this->nowPlaying = '//div[@class="player jp-player open"]/div[1]/div[2]/ul/li[4]/a';
		$this->deleteSong = '//div[@class="player jp-player open"]/div[1]/a';
		$this->footer = '//div[@class="player jp-player open"]/div[3]';
		$this->footerLeft = '//div[@class="player jp-player open"]/div[3]/div[1]';
		$this->prev = '//div[@class="player jp-player open"]/div[3]/div[1]/ul/li[1]/a';
		$this->play = '//div[@class="player jp-player open"]/div[3]/div[1]/ul/li[2]/a';
		$this->pause = '//div[@class="player jp-player open"]/div[3]/div[1]/ul/li[3]/a';
		$this->next = '//div[@class="player jp-player open"]/div[3]/div[1]/ul/li[4]/a';
		$this->footerTrack = '//div[@class="player jp-player open"]/div[3]/div[2]';
		$this->progressBar = '//div[@class="player jp-player open"]/div[3]/div[2]/div[2]/div[3]/div/div[2]';
		$this->share = '//div[@class="player jp-player open"]/div[3]/div[2]/div[3]/ul/li[1]/a';
		$this->info = '//div[@class="player jp-player open"]/div[3]/div[2]/div[3]/ul/li[2]/a';
		$this->download = '//div[@class="player jp-player open"]/div[3]/div[2]/div[3]/ul/li[3]/a';
		$this->footerRight = '//div[@class="player jp-player open"]/div[3]/div[3]';
		$this->mute = '//div[@class="player jp-player open"]/div[3]/div[3]/ul/li[1]/a';
		$this->unmute = '//div[@class="player jp-player open"]/div[3]/div[3]/ul/li[2]/a';
		$this->volume = '//div[@class="player jp-player open"]/div[3]/div[3]/ul/li[3]/div/div/div';
		$this->repeat = '//div[@class="player jp-player open"]/div[3]/div[3]/ul/li[4]/a';
		$this->shuffle = '//div[@class="player jp-player open"]/div[3]/div[3]/ul/li[5]/a';
		$this->language  = '//div[@class="player jp-player open"]/div[3]/div[3]/ul/li[6]/a';
		$this->chineseTra = '//div[@class="float js-float global"]/div[2]/a[1]';
		$this->japanese = '//div[@class="float js-float global"]/div[2]/a[2]';
		$this->english = '//div[@class="float js-float global"]/div[2]/a[3]';
		$this->closePlayer = '//div[@class="player jp-player open"]/div[3]/div[3]/ul/li[7]/a';
		
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

	public function login($account, $password)
	{
		$this->driver->findElement(WebDriverBy::xpath($this->login))->click();

		$this->driver->manage()->deleteAllCookies();
		$this->pageRefresh();
	
		$this->driver->findElement(WebDriverBy::id($this->account))->sendKeys($account);
		$this->driver->findElement(WebDriverBy::id($this->password))->sendKeys($password);
		$this->driver->findElement(WebDriverBy::classname($this->loginSend))->click();

		$this->cookies = $this->driver->manage()->getCookies();
		
	}

	public function pageRefresh()
	{
		$this->refresh->refresh();
	}

	public function wait($time)
	{
		$this->setTimeout->implicitlyWait($time);
	}
	public function search($string)
	{
		$this->wait(1);
		$this->driver->findElement(WebDriverBy::name($this->keyword))->sendKeys($string);
		$this->driver->findElement(WebDriverBy::cssSelector($this->searchBtn))->click();
	}
	public function searchMusic()
	{
		$this->driver->findElement(WebDriverBy::xpath($this->musicSearch))->click();
	}

	public function searchComposer()
	{
		$this->driver->findElement(WebDriverBy::xpath($this->composer))->click();
		$pageComposerList = '//div[@class="primary-content js-controller-content"]/section[1]/ul/li/a';
		$this->driver->findElement(WebDriverBy::xpath($pageComposerList))->click();
		$this->pageRefresh();

		$type = '//div[@class="primary-content js-controller-content"]/div/a';
		$this->driver->findElement(WebDriverBy::xpath($type))->click();

		$typeList = '//div[@class="primary-content js-controller-content"]/div/div/ul/li[2]/a[1]'; 
		$this->driver->findElement(WebDriverBy::xpath($typeList))->click();
	
		$this->pageRefresh();
		$work = '//div[@class="compo-work js-composer-work"]/ul/li/ul/li[1]/a';
		$this->driver->findElement(WebDriverBy::xpath($work))->click();
		
		$this->pageRefresh();


	}

	public function searchComposerWork()
	{
		$this->driver->findElement(WebDriverBy::xpath($this->composerWorks))->click();
	}

	public function searchComposerTrack()
	{
		$this->driver->findElement(WebDriverBy::xpath($this->composerTrack))->click();
	}

	public function searchComposerAlbum()
	{
		$this->driver->findElement(WebDriverBy::xpath($this->composerAlbum))->click();
	}

	public function player($select)
	{
		switch ($select) {

			case 'play':
				$this->driver->findElement(WebDriverBy::xpath($this->play))->click();
				break;
			case 'pause':
				$this->driver->findElement(WebDriverBy::xpath($this->pause))->click();
				break;
			case 'progressBar':
				$nowProgress = $this->driver->findElement(WebDriverBy::xpath($this->progressBar));
				$nowProgressX = $nowProgress->getLocation()->getX();
				$nowProgressY = $nowProgress->getLocation()->getY();
				$nowProgressWidth = $nowProgress->getSize()->getWidth();
				$nowProgressHeight = $nowProgress->getSize()->getHeight();
				$progressBar ='//div[@class="player jp-player open"]/div[3]/div[2]/div[2]/div[3]/div';
				$progressLength = $this->driver->findElement(WebDriverBy::xpath($progressBar))->getSize()->getWidth();
				$newPosition = $nowProgress->getLocation()->moveBy($progressLength / 2, $nowProgressHeight / 2);
				$this->driver->getMouse()->mouseMove($nowProgress->getCoordinates());

				//$this->driver->action()->click($nowProgress);
				//$this->driver->action()->moveToElement($nowProgress, $progressLength / 2, 0);
				//$this->driver->getMouse()->click();
				$iconPath = '//div[@class="player jp-player open"]/div[3]/div[2]/div[2]/div[3]/div/div[2]/span';
				$icon = $this->driver->findElement(WebDriverBy::xpath($iconPath));
				$this->driver->action()->moveByOffset(300, 1.5)->click();
				//$js = "arguments[0].style.width:'300px';";
				//$nowProgress1 = $this->driver->findElements(WebDriverBy::xpath($this->progressBar));
				//$this->driver->executeScript($js, $nowProgress1);
				//$this->driver->action()->dragAndDropBy($icon, 300, 0);
				
				//$this->driver->getMouse()->mouseMove($progress->getCoordinates());
				

				break;
			default:

				break;
		}
	}

	public function composerWorkSelect($select)
	{
		switch ($select) {

			case 'info':
				
				$info = $this->driver->findElement(WebDriverBy::xpath('//div[@class="table"]/div[2]/ul/li[1]/div[6]/a[2]'));
				$info->click();
				break;

			case 'download':

				$download = $this->driver->findElement(WebDriverBy::xpath('//div[@class="table"]/div[2]/ul/li[1]/div[6]/a[3]'));
				$download->click();
				break;

			case 'play':

				$play = $this->driver->findElement(WebDriverBy::xpath('//div[@class="table"]/div[2]/ul/li[1]/div[1]/a'));
				$play->click();
				break;
			
			case 'add to list':

				$menu = $this->driver->findElement(WebDriverBy::xpath('//div[@class="table"]/div[2]/ul/li[1]/div[6]/a[4]'));
				$menu->click();
				$this->listSelect($listSelect);
				break;

			case 'enter album':

				$album = $this->driver->findElement(WebDriverBy::xpath('//div[@class="table"]/div[2]/ul/li[3]/div[4]/a'));
				$album->click();

				break;

			default:
				# code...
				break;
		}
	}

}


?>

<?php
	$url = 'http://dev.muzik-online.com/tw';
	$test = new TestPlayer($url);
	//$test->login('f56112000@gmail.com', 'ss07290420');
	//$test->checkLogin();//need to add access token
	$test->pageRefresh();
	$test->search('貝');
	$test->pageRefresh();
	$test->searchComposer();
	$test->composerWorkSelect('play');
	$test->player('pause');
	//$test->player('progressBar');
	

	//$test->pageRefresh();

	//doesn't have 'pause', 'unmute', 'volume', 'chineseTra', 'japanese', 'english'
	//$js = "arguments[0].style.width='100%';";
	//$this->driver->executeScript($js, $this->volume);
	//all one cancel
	//$this->driver->getMouse()->mouseMove($this->player->getCoordinates());	

?>