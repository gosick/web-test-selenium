<?php

require_once('lib/__init__.php');

class MuzikOnlineTests extends PHPUnit_Framework_TestCase {

	protected $host, $driver, $capabilities, $setTimeout, $refresh, $cookies;
	protected $account, $password, $passwordConfirm, $btnSend, $keyword, $searchBtn;
	protected $periodicalTag, $memberFlag, $songListAmount, $songRepeat, $collectionAmount, $otherSongListAmount;
	protected $mySelfFlag;
	protected $playerFlag;
	protected $url = 'http://dev.muzik-online.com/tw';

	private function elementSetUp() {

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
		$this->songRepeat = 0;
		$this->songListAmount = 0;
		$this->collectionAmount = 0;
		$this->otherSongListAmount = 0;
		$this->mySelfFlag = false;
		$this->playerFlag = 'null';
	}

	public function pageRefresh() { $this->refresh->refresh(); }

	public function wait($second) { $this->setTimeout->implicitlyWait($second); }

	public function setUp()
    {
    	$this->elementSetUp();
        $this->driver = RemoteWebDriver::create($this->host, $this->capabilities);
        $this->refresh = $this->driver->navigate();
		$this->driver->get($this->url);
		$this->setTimeout = $this->driver->manage()->timeouts();
    }

    public function teardown()
    {
    	$this->driver->close();
    }
/*
    public function testMenuAllMusic() {

    	$total = $this->countMenuList();
    	$this->menu('allMusic', $total['allMusic'], 3);
    	sleep(1);
    	$this->menu('allMusic', $total['allMusic'], 6);
    }

    public function testMenuArticle() {

    	$total = $this->countMenuList();
    	$this->menu('article', $total['article'], 2);
    	sleep(1);
    	$this->menu('article', $total['article'], 4);
    }

    public function testMenuConcert() {

    	$total = $this->countMenuList();
    	$this->menu('concert', $total['concert'], 1);
    	sleep(1);
    	$this->menu('concert', $total['concert'], 0);
    }

    public function testMenuPeriodical() {
    	$total = $this->countMenuList();
    	$this->menu('periodical', $total['periodical'], 2);
    	sleep(1);
    	$this->menu('periodical', $total['periodical'], 4);
    }


    public function testMenuMemberCenter() {
    	$total = $this->countMenuList();
    	$this->menu('memberCenter', $total['memberCenter'], 1);
    	sleep(1);
    	$this->menu('memberCenter', $total['memberCenter'], 4);
    }


    public function testMenuLogin() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    }

    public function testMenuRegister() {
    	$total = $this->countMenuList();
    	$this->menu('register', $total['register'], 1);
    }

    public function testMenuHomepage() {
    	$total = $this->countMenuList();
    	$this->menu('homepage', $total['homepage'], 1);
    }
    public function testLogin() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com','gosick');

    }

    public function testMenuLogout() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->menu('logout', $total['logout'], 1);
    	sleep(2);

    }

    public function testMenuPayment() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('payment', $total['payment'], 1);
    	sleep(2);
    }

    public function testMenuMemberProfile() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    }

*/
/*
    public function testMenuMemberProfileSongListOperationAdd() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('songList');
    	sleep(2);
    	$this->memberSongListOperation('add', 0);
    }


    public function testMenuMemberProfileSongListOperationEdit() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('songList');
    	sleep(2);
    	$this->memberSongListOperation('edit', 8);
    }


    public function testMenuMemberProfileSongListOperationCopy() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('songList');
    	sleep(2);
    	$this->memberSongListOperation('copy', 4);
    }


    public function testMenuMemberProfileSongListOperationPlay() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('songList');
    	sleep(2);
    	$this->memberSongListOperation('play', 1);
    }

    public function testMenuMemberProfileSongListOperationDel() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('songList');
    	sleep(2);
    	$this->memberSongListOperation('del', 4);
    }

    public function testMenuMemberProfileSongListOperationEnter() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('songList');
    	sleep(2);
    	$this->memberSongListOperation('enter', 1);
    	
    }

	public function testMenuMemberProfileSongListOperationEnterPlayAll() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('songList');
    	sleep(2);
    	$this->memberSongListOperation('enter', 1);
    	sleep(3);
    	$this->memberSongListSongSelect('play all', 1);
    	
    }

    public function testMenuMemberProfileSongListOperationEnterPlay() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('songList');
    	sleep(2);
    	$this->memberSongListOperation('enter', 1);
    	sleep(3);
    	$this->memberSongListSongSelect('play', 3);
    	
    }

    public function testMenuMemberProfileSongListOperationEnterInfo() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('songList');
    	sleep(2);
    	$this->memberSongListOperation('enter', 1);
    	sleep(3);
    	$this->memberSongListSongSelect('info', 3);
    	
    }

    public function testMenuMemberProfileSongListOperationEnterDownload() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('songList');
    	sleep(2);
    	$this->memberSongListOperation('enter', 1);
    	sleep(3);
    	$this->memberSongListSongSelect('download', 3);

    }


    public function testMenuMemberProfileSongListOperationEnterAddToListNewList() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('songList');
    	sleep(2);
    	$this->memberSongListOperation('enter', 1);
    	sleep(3);
    	$this->memberSongListSongSelect('add to list', 3);
    	sleep(1);
    	$this->addToListSelect('new list', 3);
    	
    }
    public function testMenuMemberProfileSongListOperationEnterAddToListTemporaryList() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('songList');
    	sleep(2);
    	$this->memberSongListOperation('enter', 1);
    	sleep(3);
    	$this->memberSongListSongSelect('add to list', 3);
    	sleep(1);
    	$this->addToListSelect('temporary list', 3);
    	
    }

    public function testMenuMemberProfileSongListOperationEnterAddToListMyList() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('songList');
    	sleep(2);
    	$this->memberSongListOperation('enter', 1);
    	sleep(3);
    	$this->memberSongListSongSelect('add to list', 3);
    	sleep(1);
    	$this->addToListSelect('my list', 1);
    }

    public function testMenuMemberProfileSongListOperationEnterDel() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('songList');
    	sleep(2);
    	$this->memberSongListOperation('enter', 1);
    	sleep(3);
    	$this->memberSongListSongSelect('del', 0);
    }
*/
    public function testMenuMemberProfileMyCollectionOperationCollect() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('f56112000@gmail.com', 'ss07290420');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('collection');
    	sleep(2);
    	$this->memberCollection('collect', $index);
    	sleep(3);
    	$this->memberSongListSongSelect('del', 0);
    }

    public function testMenuMemberProfileMyCollectionOperationEnter() {

    }
	public function menu($select, $total, $index) {


		switch ($select) {

			case 'allMusic':

				$this->assertFalse($index > $total || $index <= 0, "all music element does not exist");
				$allMusic = '//div[@class="container"]/nav/ul/li[1]';
				$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($allMusic))->getCoordinates());
				$url = '//div[@class="container"]/nav/ul/li[1]/ul/li['.$index.']/a';
				$this->driver->findElement(WebDriverBy::xpath($url))->click();
				$this->pageRefresh();

				break;

			case 'article':

				$this->assertFalse($index > $total || $index <= 0, "article element does not exist");
				$article = '//div[@class="container"]/nav/ul/li[2]';
				$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($article))->getCoordinates());
				$url = '//div[@class="container"]/nav/ul/li[2]/ul/li['.$index.']/a';
				$this->driver->findElement(WebDriverBy::xpath($url))->click();
				$this->pageRefresh();
				

				break;

			case 'concert':

				$this->assertTrue($total, "concert element does not exist");
				$url = '//div[@class="container"]/nav/ul/li[3]/a';
				$this->driver->findElement(WebDriverBy::xpath($url))->click();
				$this->pageRefresh();

				break;

			case 'periodical':

				$this->assertFalse($index > $total || $index <= 0, "periodical element does not exist");
				$periodical = '//div[@class="container"]/nav/ul/li[4]';
				$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($periodical))->getCoordinates());
				$url = '//div[@class="container"]/nav/ul/li[4]/ul/li['.$index.']/a';
				$this->driver->findElement(WebDriverBy::xpath($url))->click();
				$this->pageRefresh();

				break;

			case 'memberCenter':

				$this->assertFalse($index > $total || $index <= 0, "member center element does not exist");
				$periodical = '//div[@class="container"]/nav/ul/li[5]';
				$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($periodical))->getCoordinates());
				$url = '//div[@class="container"]/nav/ul/li[5]/ul/li['.$index.']/a';
				$this->driver->findElement(WebDriverBy::xpath($url))->click();
				$this->pageRefresh();

				break;

			case 'login':

				$this->assertFalse($this->checkLogin(), "have logged in!");
				$this->driver->manage()->deleteAllCookies();
				$url = '//div[@class="container"]/div/div[1]/a[1]';
				$this->driver->findElement(WebDriverBy::xpath($url))->click();
				$this->wait(1);
				sleep(1);
				

				break;

			case 'register':

				$this->assertEquals(1, $total, "register element does not exist");
				$url = '//div[@class="container"]/div/div[1]/a[2]';
				$this->driver->findElement(WebDriverBy::xpath($url))->click();
				$this->wait(1);
				sleep(1);
				
				

				break;

			case 'homepage':

				$this->assertEquals(1, $total, "homepage element does not exist");
				$url = '//div[@class="container"]/a';
				$this->driver->findElement(WebDriverBy::xpath($url))->click();
				$this->pageRefresh();
				

				break;

			case 'memberProfile':

				$this->assertTrue($this->checkLogin(), "you should log in first");
				$this->driver->findElement(WebDriverBy::classname('name'))->click();
				$this->mySelfFlag = true;
				$this->pageRefresh();

				break;

			case 'logout':

				$this->assertTrue($this->checkLogin(), "you should log in first");
				$this->pageRefresh();
				$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::classname('name'))->getCoordinates());
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="info js-header-info"]/div/a[3]'))->click();

				break;

			case 'payment':

				$this->assertTrue($this->checkLogin(), "you should log in first");
				$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::classname('name'))->getCoordinates());
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="info js-header-info"]/div/a[2]'))->click();
				$this->pageRefresh();

				break;

			default:
				break;
		}
	}


	public function countMenuList() {

		$allMusic = count($this->driver->findElements(WebDriverBy::xpath('//div[@class="container"]/nav/ul/li[1]/ul/li')));
		$article = count($this->driver->findElements(WebDriverBy::xpath('//div[@class="container"]/nav/ul/li[2]/ul/li')));
		$periodical = count($this->driver->findElements(WebDriverBy::xpath('//div[@class="container"]/nav/ul/li[4]/ul/li')));
		$memberCenter = count($this->driver->findElements(WebDriverBy::xpath('//div[@class="container"]/nav/ul/li[5]/ul/li')));

		$array = array(

			'allMusic' => $allMusic,
			'article' => $article,
			'periodical' => $periodical,
			'memberCenter' => $memberCenter,
			'concert' => 1,
			'search' => 1,
			'login' => 1,
			'register' => 1,
			'homepage' => 1,
			'memberProfile' => 1,
			'logout' => 1,
			'payment' => 1,
		);

		return $array;
	}

	public function checkLogin() {

		$flag = false;
		$array = $this->cookies;
		$size = count($array);

		$this->playerFlag = 'true';

		for($index = 0; $index < $size; $index++) {
			if($array[$index]['name'] == 'member_i18n') {
				$flag = true;
			}
		}
		
		return $flag;
	}

	

//------------------------------------------------------
	public function countMemberSongList() { return count($this->driver->findElements(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div'))); }

	public function countMemberSong() { return count($this->driver->findElements(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li'))) - 1; }

	public function memberData() {
		$this->memberFlag = true;
		return $this->memberFlag;
	}

	public function memberProfileSelect($select) {

		switch ($select) {

			case 'collect':

				if($this->mySelfFlag == false) {
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="primary-content js-controller-content"]/section/div[2]/a'))->click();
				}

				break;

			case 'songList':

				$this->pageRefresh();
				$url = '//div[@class="primary-content js-controller-content"]/div[1]/a[1]';
				$this->driver->findElement(WebDriverBy::xpath($url))->click();
				$this->pageRefresh();
				sleep(2);
				$this->songListAmount = $this->countMemberSongList();
				
				break;
				
			case 'collection':

				$this->pageRefresh();
				$url = '//div[@class="primary-content js-controller-content"]/div[1]/a[2]';
				$this->driver->findElement(WebDriverBy::xpath($url))->click();
				$this->pageRefresh();
				sleep(2);

				$this->collectionAmount = $this->countMemberCollection();

				break;
				
			case 'profile':

				$this->pageRefresh();
				$url = '//div[@class="primary-content js-controller-content"]/div[1]/a[3]';
				$this->driver->findElement(WebDriverBy::xpath($url))->click();
				$this->wait(0.5);

				break;
			
			default:
				
				break;
		}
	}
/*---------------------------------member song list operation--------------------------------*/
	public function memberSongListOperation($select, $index) {

		sleep(3);
		$this->songListAmount = $this->countMemberSongList();

		if($this->songListAmount == 0) {
			$url = 'link.my.listenBtn.active';
			$this->driver->findElement(WebDriverBy::cssSelector($url))->click();
			$this->wait(0.5);
			$this->songListAmount = $this->countMemberSongList();
		}

		switch ($select) {

			case 'add':

				//--add new song list--//
				$this->assertTrue($this->mySelfFlag);

				$number = $this->songListAmount + 1;
				$this->driver->findElement(WebDriverBy::cssSelector('div.btnAdd.add'))->click();
				$this->wait(0.5);
				$this->driver->findElement(WebDriverBy::id('playlist_title'))->sendKeys('new_'.$number);
				$this->driver->findElement(WebDriverBy::id('summary'))->sendKeys('testlist');
				$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();
				$this->songListAmount = $this->countMemberSongList();

				break;

			case 'edit':

				//--edit a song list--//
				$this->assertNotEquals(0, $this->songListAmount, "song list is null, you cannot edit");
				$this->assertTrue($index > 0 && $index <= $this->songListAmount && $this->songListAmount > 0 && $this->mySelfFlag == true, "index is less than 0 or myself is false");
				$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div'));
				$this->driver->getMouse()->mouseMove($getSonglistDiv->getCoordinates());
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div/div/a[3]'))->click();
				$this->driver->findElement(WebDriverBy::id('playlist_title'))->clear()->sendKeys('ha');
				$this->driver->findElement(WebDriverBy::id('summary'))->clear()->sendKeys('haha');
				$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();

				break;

			case 'copy':

				//--copy a song list--//
				$this->assertNotEquals(0, $this->songListAmount, "song list is null, you cannot copy");
				$this->assertTrue($index > 0 && $index <= $this->songListAmount && $this->songListAmount > 0, "index is less than 0");
				$number = $this->songListAmount + 1;
				$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div'));
				$this->driver->getMouse()->mouseMove($getSonglistDiv->getCoordinates());
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div/div/a[2]'))->click();
				$this->driver->findElement(WebDriverBy::id('playlist_title'))->clear()->sendKeys('new_'.$number);
				$this->driver->findElement(WebDriverBy::id('summary'))->clear()->sendKeys('Ok!');
				$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click() ;
				$this->songListAmount = $this->countMemberSongList();

				break;

			case 'play':

				//play the songs of the song list
				$this->assertNotEquals(0, $this->songListAmount, "song list is null, you cannot play");
				$this->assertTrue($index > 0 && $index <= $this->songListAmount && $this->songListAmount > 0, "index is less than 0");
				$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div'));
				$this->driver->getMouse()->mouseMove($getSonglistDiv->getCoordinates());
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div/div/a[5]'))->click();

				break;

			case 'enter':

				//enter the song list
				$this->assertNotEquals(0, $this->songListAmount, "song list is null, you cannot enter");
				$this->assertTrue($index > 0 && $index <= $this->songListAmount && $this->songListAmount > 0, "index is less than 0");
				$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/a'))->click();

				break;

			case 'del':

				//delete the song list
				$this->assertNotEquals(0, $this->songListAmount, "song list is null, you cannot delete");
				$this->assertTrue($index > 0 && $index <= $this->songListAmount && $this->songListAmount > 0 && $this->mySelfFlag == true, "index is less than 0 or myself is false");
				$this->driver->findElement(WebDriverBy::cssSelector('div.btnDelete.remove'))->click();
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div/a'))->click();
				$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();
				$this->songListAmount = $this->countMemberSongList();

				break;

			default:
				
				break;
		}
	}
/*---------------------------------member song list operation--------------------------------*/

/*--------------------song selection in member song list-------------------------------------*/
	public function memberSongListSongSelect($selectFunc, $index) {

		$number = $index + 1;

		$this->pageRefresh();
		//for first li is head title, second li is the first song
		$amount = $this->countMemberSong();
		if($amount == -1) {
			$this->pageRefresh();
			$this->wait(0.5);
			$amount = $this->countMemberSong();
		}

		switch ($selectFunc) {

			case 'play all':

				$this->assertNotEquals(-1, $amount, "have no songs");
				$this->driver->findElement(WebDriverBy::cssSelector('a.playAll.noSelect.play-all'))->click();
				break;

			case 'play':

				$this->assertTrue($number > 1 && $index <= $amount && $amount > 0, "have no songs or index exceeds the amount");
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[1]'))->click();			
				
				break;

			case 'del':

				$this->assertTrue($number > 1 && $index <= $amount && $amount > 0 && $this->mySelfFlag == true ,"have no songs or index exceeds the amount or myself is false");
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[3]'))->click();
				
				break;

			case 'add to list':

				$this->assertTrue($number > 1 && $index <= $amount && $amount > 0, "have no songs or index exceeds the amount");
				//$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[2]'))->getLocationOnScreenOnceScrolledIntoView();
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[2]'))->click();

				break;

			case 'info':

				$this->assertTrue($number > 1 && $index <= $amount && $amount > 0, "have no songs or index exceeds the amount");
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[4]'))->click();
				
				break;

			case 'download':

				$this->assertTrue($number > 1 && $index <= $amount && $amount > 0, "have no songs or index exceeds the amount");
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[5]'))->click();

				break;

			default:
							
				break;
		}
	}
/*--------------------song selection in member song list-------------------------------------*/	

/*------------------music adding to new list, temporary list or existed list-----------------*/
	public function addToListSelect($listSelect, $index)
	{
		//three selection : new list, temporary, existed
		switch ($listSelect) {

			case 'new list':

				$number = $this->songListAmount + 1;
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/a[1]'))->click();
				$this->wait(0.5);
				$this->driver->findElement(WebDriverBy::id('playlist_title'))->sendKeys('new_'.$number);
				$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();

				break;

			case 'temporary list':

				$this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/a[2]'))->click();

				break;

			case 'my list':

				$this->assertTrue($index > 0 && $index <= $this->songListAmount && $this->songListAmount > 0,  "have no song lists or index exceeds the amount");
				
				$this->wait(3);
					//$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/div/div/div[2]/ul'))->getCoordinates());
					//$item = $this->driver->findElements(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/div/div/div[2]/ul/li['.$i.']'));
					//$js = "arguments[0].scrollIntoView(true);";
					//$this->driver->executeScript($js, $item);
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/div/div/div[2]/ul/li['.$index.']/a'))->click();
				
				break;

			default:
									
				break;

		}	
	}
/*------------------music adding to new list, temporary list or existed list-----------------*/
	public function countMemberCollection() { return count($this->driver->findElements(WebDriverBy::xpath('//div[@class="listen-list listen-subscribe"]/ul/li'))); }
/*-------------------------------member collection page select---------------------------*/
	public function memberCollection($select, $index) {

		switch ($select) {

			case 'collect'://collect or cancel collect

				$this->assertTrue($index != 0 && $index <= $this->collectionAmount && $this->collectionAmount > 0,  "have no collection or index exceeds the amount");
				
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="listen-list listen-subscribe"]/ul/li['.$index.']/a[3]'))->click();
				

				break;

			case 'enter':

				$this->assertTrue($index != 0 && $index <= $this->collectionAmount && $this->collectionAmount > 0,  "have no collection or index exceeds the amount"););
				
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="listen-list listen-subscribe"]/ul/li['.$index.']/a[1]'))->click();
				$this->mySelfFlag = false;
				

				break;

			default:
				
				break;
		}
	}
/*-------------------------------member collection page select---------------------------*/

/*----------------------------------member login-----------------------------------------*/
	public function login($account, $password) {
		$this->driver->findElement(WebDriverBy::id($this->account))->sendKeys($account);
		$this->driver->findElement(WebDriverBy::id($this->password))->sendKeys($password);
		$this->wait(1);
		$this->driver->findElement(WebDriverBy::classname($this->btnSend))->click();
		$this->cookies = $this->driver->manage()->getCookies();
	}
/*----------------------------------member login-----------------------------------------*/

/*----------------------------------register account-------------------------------------*/
    public function register($account, $password) {
				
		$this->driver->findElement(WebDriverBy::id($this->account))->sendKeys($account);
		$this->driver->findElement(WebDriverBy::id($this->password))->sendKeys($password);
		$this->wait(1);
		$this->driver->findElement(WebDriverBy::id($this->passwordConfirm))->sendKeys($password);
		$this->driver->findElement(WebDriverBy::classname($this->btnSend))->click();
		$this->cookies = $this->driver->manage()->getCookies();

	}
/*----------------------------------register account-------------------------------------*/

/*----------------------------------forget password--------------------------------------*/
	public function forgetPassword($account, $password) {

		$this->driver->findElement(WebDriverBy::id($this->account))->sendKeys($account);
		$this->driver->findElement(WebDriverBy::id($this->password))->sendKeys($password);
		$this->driver->findElement(WebDriverBy::id($this->passwordConfirm))->sendKeys($password);
		$this->driver->findElement(WebDriverBy::classname($this->btnSend))->click();
	}
/*----------------------------------forget password--------------------------------------*/

/*------------------------------------menu search function-------------------------------*/
	public function search($string) {

		$this->wait(1);
		$this->driver->findElement(WebDriverBy::name($this->keyword))->sendKeys($string);
		$this->driver->findElement(WebDriverBy::cssSelector($this->searchBtn))->click();
	}
/*------------------------------------menu search function-------------------------------*/
}


?>