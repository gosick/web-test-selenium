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

	public function testSearch($string)
	{
		$setTimeout = $this->driver->manage()->timeouts();
		if (!isset($this->cookies)) {
			echo "cookies is null";
		}
		else {
			$setTimeout->implicitlyWait(1);
			$search = $this->driver->findElement(WebDriverBy::name('keyword'));
			$search->sendKeys($string);
			$searchBtn = $this->driver->findElement(WebDriverBy::cssSelector('span.icon.icon-header-search'));
			$searchBtn->click();

		}
	}

	public function testMusicAndAlbum($selector)
	{	

		$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="container"]/nav/ul/li[1]'));
		
		//write javascript to set the overflow not to hide
		/*$js = "arguments[0].style.height='auto'; arguments[0].style.overflow='scroll';";
		$this->driver->executeScript($js, $getSonglistDiv);
		switch ($selector) {
			case 'music':
				
				$music = $this->driver->findElement(WebDriverBy::xpath('//div[@class="container"]/nav/ul/li[1]/ul/li[4]/a'));
				$music->click();
				break;

			case 'album':

				$album = $this->driver->findElement(WebDriverBy::xpath('//div[@class="container"]/nav/ul/li[1]/ul/li[5]/a'));
				$album->click();
				break;

			default:
				# code...
				break;
		}
		*/
	
	}

	public function listSelect($listSelect)
	{
		$setTimeout = $this->driver->manage()->timeouts();
		//three selection : new list, temporary, existed
		switch ($listSelect) {
			case 'new list':

				$newList = $this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/a[1]'));
				$newList->click();

				$setTimeout->implicitlyWait(0.5);
				$playlistTitle = $this->driver->findElement(WebDriverBy::id('playlist_title'));
				$playlistTitle->sendKeys('ya');
				$songListAdd = $this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'));
				$songListAdd->click();
				break;

			case 'temporary':

				$setTimeout->implicitlyWait(0.5);
				$temporary = $this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/a[2]'));
				$temporary->click();
				break;

			case 'existed':

				$setTimeout->implicitlyWait(0.5);
				$existed = $this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/div/div/div[2]/ul/li[3]/a'));
				$existed->click();
				break;

			default:
									
				break;

		}	
	}

	public function select($selectFunc, $listSelect)
	{

		$setTimeout = $this->driver->manage()->timeouts();
		$setTimeout->implicitlyWait(0.5);

		switch ($selectFunc) {

			case 'play all':
				//get the 'play all' element
				$playAll = $this->driver->findElement(WebDriverBy::cssSelector('a.playAll.noSelect.play-all'));
				$playAll->click();
				break;

			case 'play':
				//get the 'play' element			
				$play = $this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li[2]/div[4]/a[1]'));
				$play->click();			
				break;

			case 'del':
				//get the 'delete' element
				$del = $this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li[2]/div[4]/a[3]'));
				$del->click();			
				break;

			case 'add to list':
				//get the 'add to list' element
				$menu = $this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li[4]/div[4]/a[2]'));
				$menu->click();

				$this->listSelect($listSelect);

				break;
			case 'info':

				$info = $this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li[2]/div[4]/a[4]'));
				$info->click();
				break;

			case 'download':

				$download = $this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li[2]/div[4]/a[5]'));
				$download->click();
				break;

			default:
							
				break;
		}
	}

	public function testSearchComposerWorks($select, $listSelect)
	{
		$setTimeout = $this->driver->manage()->timeouts();
		$composer = $this->driver->findElement(WebDriverBy::xpath('//div[@class="primary-content js-controller-content"]/section[1]/ul/li/a'));
		$composer->click();
		$this->driver->navigate()->refresh();
		$type = $this->driver->findElement(WebDriverBy::xpath('//div[@class="primary-content js-controller-content"]/div/a'));
		$type->click();
		$typeList = $this->driver->findElement(WebDriverBy::xpath('//div[@class="primary-content js-controller-content"]/div/div/ul/li[2]/a[1]'));
		$typeList->click();
		$this->driver->navigate()->refresh();
		$work = $this->driver->findElement(WebDriverBy::xpath('//div[@class="compo-work js-composer-work"]/ul/li/ul/li[1]/a'));
		$work->click();
		$this->driver->navigate()->refresh();

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

	public function testMemberModSongList($select)
	{
		$setTimeout = $this->driver->manage()->timeouts();
		if (!isset($this->cookies)) {
			echo "cookies is null";
		}
		else {
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

					break;

				case 'copy':
					//--copy a song list--//

					$setTimeout->implicitlyWait(0.5);
					//find the first song list outer div
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

					break;

				case 'play':
					//play the songs of the song list

					$setTimeout->implicitlyWait(0.5);
					//find the first song list outer div
					$getSonglistDiv = $this->driver->findElements(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[1]/div'));

					//write javascript to set the overflow not to hide
					$js = "arguments[0].style.height='auto'; arguments[0].style.overflow='scroll';";
					$this->driver->executeScript($js, $getSonglistDiv);

					//then the inner elements will be visible
					$getSonglist = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[1]/div/div/a[5]'));
					$getSonglist->click();

					break;

				case 'enter':
					//enter the song list

					$setTimeout->implicitlyWait(0.5);
					$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[1]/a'));
					$getSonglistDiv->click();

					$this->select('add to list', 'temporary');


					break;

				case 'del':
					//delete the song list

					$setTimeout->implicitlyWait(0.5);
					//click delete song list button
					$songListDel = $this->driver->findElement(WebDriverBy::cssSelector('div.btnDelete.remove'));
					$songListDel->click();
					

					$getSonglist = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div[3]/div/a'));
					$getSonglist->click();

					$delBtn = $this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'));
					$delBtn->click();

				default:
					# code...
					break;
			}

		}
	}

}

?>

<?php
	$host = 'http://localhost:4444/wd/hub';
	//$url = 'https://www.muzik-online.com/tw/member/login';
	$url = 'http://dev.muzik-online.com/tw/member/login';
	$account = 'f56112000@gmail.com';
	$password = 'ss07290420';
	$test = new TestMemberSystem;
	$test->testLogin($host, $url, $account, $password);
	$test->testMemberModSongList('enter');
	//add
	$string = '貝多芬';
	//$test->testSearch($string);
	//$test->testSearchComposerWorks('play', null);
	//$test->testMusicAndAlbum('music');

?>