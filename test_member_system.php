<?php

require_once('lib/__init__.php');

class TestMemberSystem {
	protected $cookies;
	protected $driver;
	protected $capabilities;
	public function testLogin($host, $url, $account, $password)
	{
		$this->capabilities = DesiredCapabilities::firefox();
		$this->driver = RemoteWebDriver::create($host, $this->capabilities, 5000);

		$this->driver->get($url);
		
		$setTimeout = $this->driver->manage()->timeouts();
		$this->driver->manage()->deleteAllCookies();
		$this->driver->manage()->addCookie(array(
		  'name' => 'cookie_name',
		  'value' => 'cookie_value',
		));
		$this->cookies = $this->driver->manage()->getCookies();
		//$setTimeout->implicitlyWait(1);

		$link1 = $this->driver->findElement(WebDriverBy::id('account'))->sendKeys($account);
		$link2 = $this->driver->findElement(WebDriverBy::id('password'))->sendKeys($password);
		$link3 = $this->driver->findElement(WebDriverBy::classname('button-gold'));
		$link3->click();

		// print the title of the current page
		echo "The title is " . $this->driver->getTitle() . "'\n";

		// print the title of the current page
		echo "The current URI is " . $this->driver->getCurrentURL() . "'\n";
		$setTimeout->implicitlyWait(1);
		$this->cookies = $this->driver->manage()->getCookies();
		print_r($this->cookies);
		$this->driver->navigate()->refresh();
		// close the Firefox
		//$driver->quit();
	}

	public function testMemberModSongList($select)
	{
		$setTimeout = $this->driver->manage()->timeouts();
		if (!isset($this->cookies))
		{
			echo "cookies is null";
		}
		else
		{
			//go to member profile
			$setTimeout->implicitlyWait(1);
			$memberProfileLink = $this->driver->findElement(WebDriverBy::classname('name'));
			$memberProfileLink->click();

			//go to song list page
			$setTimeout->implicitlyWait(0.5);
			$songList = $this->driver->findElement(WebDriverBy::cssSelector('a.link.my.listenBtn.active'));
			$songList->click();

			switch ($select) {
				case 'add':
					//--add new song list--//
					
					//click add song list button
					$setTimeout->implicitlyWait(0.5);
					//click add song list button
					$songListAdd = $this->driver->findElement(WebDriverBy::cssSelector('div.btnAdd.add'));
					$songListAdd->click();
					
					//fill the song list title
					$setTimeout->implicitlyWait(0.5);
					$songListAddFilltitle = $this->driver->findElement(WebDriverBy::id('playlist_title'));
					$songListAddFilltitle->sendKeys('test');

					//fill the song list description and then send
					$songListAddFilldescription = $this->driver->findElement(WebDriverBy::id('summary'));
					$songListAddFilldescription->sendKeys('testlist');
					$songListAddNew = $this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'));
					$songListAddNew->click();

					break;

				case 'edit':
					//--edit a song list--//

					$setTimeout->implicitlyWait(0.5);
					//find the third song list outer div
					$getSonglistDiv = $this->driver->findElements(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[3]/div'));

					//write javascript to set the overflow not to hide
					$js = "arguments[0].style.height='auto'; arguments[0].style.overflow='scroll';";
					$this->driver->executeScript($js, $getSonglistDiv);

					//then the inner elements will be visible
					$getSonglist = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[3]/div/div/a[3]'));
					$getSonglist->click();
				
					//--fill the playlist_title and description with some texts and then submit--//

					$songListAddFilltitle = $this->driver->findElement(WebDriverBy::id('playlist_title'));
					//clear title
					$songListAddFilltitle->clear();
					//fill the title
					$songListAddFilltitle->sendKeys('ya');

					$songListAddFilldescription = $this->driver->findElement(WebDriverBy::id('summary'));
					//clear description
					$songListAddFilldescription->clear();
					//fill the description
					$songListAddFilldescription->sendKeys('ok!');

					$songListAddNew = $this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'));
					$setTimeout->implicitlyWait(0.5);
					$songListAddNew->click();

				case 'copy':
					//--copy a song list--//

					$setTimeout->implicitlyWait(0.5);
					//find the third song list outer div
					$getSonglistDiv = $this->driver->findElements(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[1]/div'));

					//write javascript to set the overflow not to hide
					$js = "arguments[0].style.height='auto'; arguments[0].style.overflow='scroll';";
					$this->driver->executeScript($js, $getSonglistDiv);

					//then the inner elements will be visible
					$getSonglist = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[1]/div/div/a[2]'));
					$getSonglist->click();

					//--fill the playlist_title and description with some texts and then submit--//

					$songListAddFilltitle = $this->driver->findElement(WebDriverBy::id('playlist_title'));
					//clear title
					$songListAddFilltitle->clear();
					//fill the title
					$songListAddFilltitle->sendKeys('ya');

					$songListAddFilldescription = $this->driver->findElement(WebDriverBy::id('summary'));
					//clear description
					$songListAddFilldescription->clear();
					//fill the description
					$songListAddFilldescription->sendKeys('ok!');

					$songListAddNew = $this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'));
					$setTimeout->implicitlyWait(0.5);
					$songListAddNew->click();


				default:
					# code...
					break;
			}
			
			//$this->driver->get('')
			//$link = $this->driver->findElement(WebDriverBy::classname('div.btnAdd.add'))->click();
		}
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
	$test->testMemberModSongList('copy');//add

?>