<?php

require_once('lib/__init__.php');

class MuzikOnlineTests extends PHPUnit_Framework_TestCase {

    //putenv("webdriver.chrome.driver=./chromedriver");
  //$driver = ChromeDriver::start();
	protected $host, $driver, $capabilities, $setTimeout, $refresh, $cookies;
	protected $account, $accountpath, $password, $passwordpath, $passwordConfirm, $btnSend, $keyword, $searchBtn;
	protected $periodicalTag, $memberFlag, $songListAmount, $songRepeat, $collectionAmount, $otherSongListAmount;
	protected $mySelfFlag, $playerFlag;
	protected $total;
	protected $url = 'http://dev.muzik-online.com/tw';
	protected $playerMyList, $playerMyCollection, $playertemporaryList;
	protected $playerMyCollectionContent, $playerMyListContent;
    protected $songListTitle, $songListDescription;

  	const CONNECT_TIMEOUT_MS = 500;


	private function elementSetUp() {

		$this->host = 'http://localhost:4444/wd/hub';
        $this->capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => 'chrome', \WebDriverCapabilityType::BROWSER_NAME => 'firefox');
        //$caps = DesiredCapabilities::chrome();
        //$caps->setCapability(ChromeOptions::CAPABILITY, $options);
        //$driver = RemoteWebDriver::create($url, $caps);
        $this->account = '';
        $this->password = '';
        $this->songListTitle = '';
        $this->songListDescription = '';
		$this->accountpath = 'account';
		$this->passwordpath = 'password';
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
		$this->playerMyList = 0;
		$this->playerMyCollection = 0;
		$this->playertemporaryList = 0;
	}

	public function pageRefresh() { $this->refresh->refresh(); }

	public function wait($second) { $this->setTimeout->implicitlyWait($second); }

/*------------------fill and check song list title description---------------------*/

    public function fillSongListTitleAndDescription($title, $description) {

        $this->songListTitle = $title;
        $this->songListDescription = $description;
    }

    public function checkSongList($inputTitle, $inputDescription, $index) {

        $getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div'));
        $this->driver->getMouse()->mouseMove($getSonglistDiv->getCoordinates());
        $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div/div/a[3]'))->click();
        $title = $this->driver->findElement(WebDriverBy::id('playlist_title'))->getText();
        $description = $this->driver->findElement(WebDriverBy::id('summary'))->getText();
        if((strcmp($title, $inputTitle) !== 0) && (strcmp($description, $inputDescription) !== 0)){
            return false;
        }
        else {
            return true;
        }
        
    }

/*-------------------------account and password generator--------------------------*/
    public function memberAccountGenerate() {

        $length = 10;
        $account = '';

        for($index = 1; $index <= $length; $index++) {
            $choose = rand(1, 3);
            if($choose == 1) {
                $result = chr(rand(97, 122));
            }
            if($choose == 2) {
                $result = chr(rand(65, 90));
            }
            if($choose == 3) {
                $result = rand(0, 9);
            }
            $account .= $result;
        }
        $account .= "@test.com";
        return $account;
        
    }

    public function memberPasswordGenerate() {

        $length = 10;
        $password = '';
        $word = 'abcdefghijkmnpqrstuvwxyz!@#$%^&*()-=ABCDEFGHIJKLMNPQRSTUVWXYZ<>;{}[]23456789';
        $len = strlen($word);

        for ($index = 0; $index < $length; $index++) {
            $password .= $word[rand() % $len];
        }

        return $password;
    }

/*--------------------------------------player-------------------------------------*/
    
    public function playerOpen() {

        sleep(3);
        $this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::cssSelector('div.player.jp-player'))->getCoordinates());
        $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player over"]/div[1]/div[1]/a'))->click();
    }

    public function playerCheckAlbum() {

        if($this->driver->findElement(WebDriverBy::cssSelector('a.close.jp-delete'))->isDisplayed() == 1) {
            sleep(1);
            $this->driver->findElement(WebDriverBy::cssSelector('a.close.jp-delete'))->click();
        }
    }

    public function playerClose() {

        $this->driver->findElement(WebDriverBy::cssSelector('a.open.icon.jp-open'))->click();
    }

    public function playerheaderSelect($select) {

        $headerElememts = $this->driver->findElements(WebDriverBy::cssSelector('a.item'));
        switch ($select) {

            case 'temporaryList':

                if($this->checkLogin()) {
                    $this->playerFlag = 'temporaryList';
                }
                $this->playertemporaryList = count($this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li')));
                $headerElememts[0]->click();
                break;

            case 'myList':

                if($this->checkLogin()) {
                    $this->playerFlag = 'myList';
                    $this->playerMyList = $this->countPlayerMyListLeftColumnContent();
                }
                $headerElememts[1]->click();
                break;

            case 'myCollection':

                if($this->checkLogin()) {
                    $this->playerFlag = 'myCollection';
                    $this->playerMyCollection = $this->countPlayerMyCollectionLeftColumnContent();
                }
                $headerElememts[2]->click();
                break;

            case 'now playing':

                $headerElememts[3]->click();
                break;

            default:

                break;
        }
    }

    public function playerfooterSelect($select) {

        $footerLeftElements = $this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[3]/div[1]/ul/li'));

        switch ($select) {

            case 'prev':

                $footerLeftElements[0]->click();
                break;

            case 'play':

                $footerLeftElements[1]->click();
                break;

            case 'pause':

                $footerLeftElements[2]->click();
                break;

            case 'next':

                $footerLeftElements[3]->click();
                break;

            case 'info':
                
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[3]/div[2]/div[3]/ul/li[2]/a'))->click();
                break;

            case 'download':

                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[3]/div[2]/div[3]/ul/li[3]/a'))->click();
                break;

            case 'mute':

                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[3]/div[3]/ul/li[1]/a'))->click();
                break;

            case 'unmute':

                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[3]/div[3]/ul/li[2]/a'))->click();
                break;

            case 'repeat':

                $this->songRepeat += 1;
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[3]/div[3]/ul/li[4]/a'))->click();
                if($this->songRepeat >= 3) {
                    $this->songRepeat = 0;
                }
                break;

            case 'shuffle':

                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[3]/div[3]/ul/li[5]/a'))->click();
                break;

            case 'language':

                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[3]/div[3]/ul/li[6]/a'))->click();
                break;

            case 'unpin':

                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[1]/div[1]/a'))->click();
                break;

            default:
                
                break;
        }
    }

    public function chooseLanguage($select) {

        switch ($select) {

            case 'chinese':

                $this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float global"]/div[2]/a[1]'))->click();
                break;
            
            case 'english':

                $this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float global"]/div[2]/a[2]'))->click();
                break;

            case 'japanese':

                $this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float global"]/div[2]/a[3]'))->click();
                break;

            default:
                
                break;
        }
    }
    
    public function countPlayerMyListLeftColumnContent() { return count($this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li'))) - 1; }

    public function countPlayerMyCollectionLeftColumnContent() { return count($this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li'))); }

    public function playerLeftContentMyCollectionSongListSelect($parentIndex, $index) {

        $item = $this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$parentIndex.']/ul/li['.$index.']'));
        $js = "arguments[0].scrollIntoView(true);";
        $this->driver->executeScript($js, $item);
        $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$parentIndex.']/ul/li['.$index.']/div/a'))->click();
        $songAmount = count($this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li')));
    }

    public function playerLeftContentSelect($select, $index) {

        $this->assertNotEquals($this->playerFlag, 'null', "user does not log in , player flag is null");
        $this->assertNotEquals($this->playerFlag, 'temporaryList', "temporary list does not have left content");

        if($this->playerFlag == 'myList') {
            $this->assertFalse($index > 0 && $this->playerMyList >= $index, "index exceeds the amount");
        }

        else if($this->playerFlag == 'myCollection') {
            $this->assertFalse($index > 0 && $this->playerMyCollection >= $index, "index exceeds the amount");
        }

        switch ($select) {

            case 'choose':

                if($this->playerFlag == 'myList' || $this->playerFlag == 'myCollection') {
                    $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']/div[1]/a'))->click();
                }
                break;

            case 'del':

                if($this->playerFlag == 'myCollection') {

                    $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']/a'))->click();
                    sleep(1);
                    $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']/div[2]/a'))->click();
                }
                else if($this->playerFlag == 'myList') {

                    $this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']'))->getCoordinates());
                    $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']/div[2]/a[2]'))->click();
                    sleep(1);
                    $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']/div[3]/a'))->click();
                }
                
                break;

            case 'edit':

                $this->assertEquals('myList', $this->playerFlag, "player flag isn't myList");       
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']/div[2]/a[1]'))->click();
                sleep(1);
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']/form/input'))->clear()->sendKeys($this->songListTitle);
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']/form/button'))->click();
                break;

            case 'new list':

                
                $this->assertEquals('myList', $this->playerFlag, "player flag isn't myList");       
                $myList = count($this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li')));
                $item = $this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$myList.']'));
                $js = "arguments[0].scrollIntoView(true);";
                $this->driver->executeScript($js, $item);
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$myList.']/div/a'))->click();
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$myList.']/li/form/input'))->clear()->sendKeys($this->songListTitle);
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$myList.']/li/form/button'))->click();
                
                break;

            default:
                
                break;
        }
    }

    public function playerSongContentfunc($select, $index) {

        if($this->playerFlag == 'temporaryList') {
            $this->assertFalse($index > 0 && $this->playertemporaryList >= $index, "index exceeds the amount");
        }
        else if($this->playerFlag == 'myList') {
            $this->assertFalse($index > 0 && $this->playerMyListContent >= $index, "index exceeds the amount");
        }
        else if($this->playerFlag == 'myCollection') {
            $this->assertFalse($index > 0 && $this->playerMyCollectionContent >= $index, "index exceeds the amount");
        }

        switch ($select) {
            case 'play':
                
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li['.$index.']/div[1]/a'))->click();
                break;

            case 'choose':

                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li['.$index.']/div[2]/a'))->click();
                break;

            case 'info':

                $this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li['.$index.']/div[3]/div'))->getCoordinates());
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li['.$index.']/div[3]/div/div/a[2]'))->click();
                break;

            case 'download':

                $this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li['.$index.']/div[3]/div'))->getCoordinates());
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li['.$index.']/div[3]/div/div/a[3]'))->click();
                break;

            case 'add to list':

                $this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li['.$index.']/div[3]/div'))->getCoordinates());
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li['.$index.']/div[3]/div/div/a[4]'))->click();
                break;

            case 'del':

                if($this->playerFlag == 'myCollection') {
                    break;
                }
                else {
                    $this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li['.$index.']/div[3]/div'))->getCoordinates());
                    $this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li['.$index.']/div[3]/div/div/a[5]'))->click();
                }
                break;

            default:
                
                break;
        }
    }

/*--------------------------------------menu and member-------------------------------------*/
    public function menu($select, $total, $index) {


        switch ($select) {

            case 'allMusic':

                $this->assertFalse($index > $total || $index <= 0, "all music element does not exist");
                $allMusic = '//div[@class="container"]/nav/ul/li[1]';
                $this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($allMusic))->getCoordinates());
                sleep(1);
                $url = '//div[@class="container"]/nav/ul/li[1]/ul/li['.$index.']/a';
                $this->driver->findElement(WebDriverBy::xpath($url))->click();
                $this->pageRefresh();
                break;

            case 'article':

                $this->assertFalse($index > $total || $index <= 0, "article element does not exist");
                $article = '//div[@class="container"]/nav/ul/li[2]';
                $this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($article))->getCoordinates());
                sleep(1);
                $url = '//div[@class="container"]/nav/ul/li[2]/ul/li['.$index.']/a';
                $this->driver->findElement(WebDriverBy::xpath($url))->click();
                $this->pageRefresh();
                break;

            case 'concert':

                $this->assertEquals(1, $total, "concert element does not exist");
                $url = '//div[@class="container"]/nav/ul/li[3]/a';
                $this->driver->findElement(WebDriverBy::xpath($url))->click();
                $this->pageRefresh();
                break;

            case 'periodical':

                $this->assertFalse($index > $total || $index <= 0, "periodical element does not exist");
                $periodical = '//div[@class="container"]/nav/ul/li[4]';
                $this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($periodical))->getCoordinates());
                sleep(1);
                $url = '//div[@class="container"]/nav/ul/li[4]/ul/li['.$index.']/a';
                $this->driver->findElement(WebDriverBy::xpath($url))->click();
                $this->pageRefresh();
                break;

            case 'memberCenter':

                $this->assertFalse($index > $total || $index <= 0, "member center element does not exist");
                $periodical = '//div[@class="container"]/nav/ul/li[5]';
                $this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($periodical))->getCoordinates());
                sleep(1);
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

        $this->total = array(

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

                $url = '//div[@class="primary-content j
                $this->pageRefresh();s-controller-content"]/div[1]/a[2]';
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
            $url = '//div[@class="main"]/div[3]/div[2]/div[2]/div[1]/a[1]';
            sleep(1);
            $this->driver->findElement(WebDriverBy::xpath($url))->click();
            $this->wait(0.5);
            $this->songListAmount = $this->countMemberSongList();
        }

        switch ($select) {

            case 'new list':

                //--add new song list--//
                $this->assertTrue($this->mySelfFlag);
                $number = $this->songListAmount + 1;
                $this->driver->findElement(WebDriverBy::cssSelector('div.btnAdd.add'))->click();
                $this->wait(0.5);
                $this->driver->findElement(WebDriverBy::id('playlist_title'))->sendKeys($this->songListTitle);
                $this->driver->findElement(WebDriverBy::id('summary'))->sendKeys($this->songListDescription);
                $this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();
                $this->songListAmount = $this->countMemberSongList();
                break;

            case 'edit':

                //edit a song list
                $this->assertNotEquals(0, $this->songListAmount, "song list is null, you cannot edit");
                $this->assertTrue($index > 0 && $index <= $this->songListAmount && $this->songListAmount > 0 && $this->mySelfFlag == true, "index is less than 0 or myself is false");
                $getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div'));
                $this->driver->getMouse()->mouseMove($getSonglistDiv->getCoordinates());
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div/div/a[3]'))->click();
                $this->driver->findElement(WebDriverBy::id('playlist_title'))->clear()->sendKeys($this->songListTitle);
                $this->driver->findElement(WebDriverBy::id('summary'))->clear()->sendKeys($this->songListDescription);
                $this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();
                break;

            case 'copy':

                //copy a song list
                $this->assertNotEquals(0, $this->songListAmount, "song list is null, you cannot copy");
                $this->assertTrue($index > 0 && $index <= $this->songListAmount && $this->songListAmount > 0, "index is less than 0");
                $number = $this->songListAmount + 1;
                $getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div'));
                $this->driver->getMouse()->mouseMove($getSonglistDiv->getCoordinates());
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div/div/a[2]'))->click();
                $this->driver->findElement(WebDriverBy::id('playlist_title'))->clear()->sendKeys($this->songListTitle);
                $this->driver->findElement(WebDriverBy::id('summary'))->clear()->sendKeys($this->songListDescription);
                $this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click() ;
                $this->songListAmount = $this->countMemberSongList();
                break;

            case 'play':

                //play the songs of the song list
                $this->playerFlag = 'temporaryList';
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

/*------------------music adding to new list, temporary list or existed list-----------------*/

    public function countFloatMenu(){ return count($this->driver->findElements(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/div/div/div[2]/ul/li'))); }

    public function addToListSelect($listSelect, $index) {
        //three selection : new list, temporary, existed
        switch ($listSelect) {

            case 'new list':

                $number = $this->songListAmount + 1;
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/a[1]'))->click();
                $this->wait(0.5);
                $this->driver->findElement(WebDriverBy::id('playlist_title'))->sendKeys($this->songListTitle);
                $this->driver->findElement(WebDriverBy::id('summary'))->sendKeys($this->songListDescription);
                $this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();
                break;

            case 'temporary list':

                $this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/a[2]'))->click();
                break;

            case 'my list':
                
                $this->assertTrue($index > 0 && $index <= $this->countFloatMenu() && $this->countFloatMenu() > 0,  "have no song lists or index exceeds the amount");
                
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

                $this->assertTrue($index != 0 && $index <= $this->collectionAmount && $this->collectionAmount > 0,  "have no collection or index exceeds the amount");
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="listen-list listen-subscribe"]/ul/li['.$index.']/a[1]'))->click();
                $this->mySelfFlag = false;
                break;

            default:
                
                break;
        }
    }

/*----------------------------------member login-----------------------------------------*/
    public function login($account, $password) {

        $this->driver->findElement(WebDriverBy::id($this->accountpath))->sendKeys($account);
        $this->driver->findElement(WebDriverBy::id($this->passwordpath))->sendKeys($password);
        $this->wait(1);
        $this->driver->findElement(WebDriverBy::classname($this->btnSend))->click();
        $this->cookies = $this->driver->manage()->getCookies();
    }

/*----------------------------------register account-------------------------------------*/
    public function register($account, $password) {
                
        $this->driver->findElement(WebDriverBy::id($this->accountpath))->sendKeys($account);
        $this->driver->findElement(WebDriverBy::id($this->passwordpath))->sendKeys($password);
        $this->wait(1);
        $this->driver->findElement(WebDriverBy::id($this->passwordConfirm))->sendKeys($password);
        $this->driver->findElement(WebDriverBy::classname($this->btnSend))->click();
        $this->cookies = $this->driver->manage()->getCookies();

    }

/*----------------------------------forget password--------------------------------------*/
    public function forgetPassword($account, $password) {

        $this->driver->findElement(WebDriverBy::id($this->accountpath))->sendKeys($account);
        $this->driver->findElement(WebDriverBy::id($this->passwordpath))->sendKeys($password);
        $this->driver->findElement(WebDriverBy::id($this->passwordConfirm))->sendKeys($password);
        $this->driver->findElement(WebDriverBy::classname($this->btnSend))->click();
    }

/*------------------------------------menu search function-------------------------------*/
    public function search($string) {

        $this->wait(1);
        $this->driver->findElement(WebDriverBy::name($this->keyword))->sendKeys($string);
        $this->driver->findElement(WebDriverBy::cssSelector($this->searchBtn))->click();
    }

/*---------------------------responseCode and get url list------------------------*/
    public function responseCode($timeout_in_ms, $url) {

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
        }
        catch (Exception $e) {}

        curl_close($ch);
        return $code;
    }

    public function getUrlList($url) {

        //get homepage contents
        $homepage = file_get_contents($url);
        mb_convert_encoding($homepage, 'UTF-8');
        //use regex parse http:// or https:// or ftp:// url
        mb_regex_encoding('UTF-8');
        
        preg_match_all("/(http|https|ftp):\/\/[^<>[:space:]]+[[:alnum:]#?\/&=+%_]/", $homepage, $match);
        $list = $match[0];

        $recordList = Array();

        
        foreach ($list as $value) {

            //replace '/ and ";var to null string  
            $result = preg_replace("/\'\/|\"(;var)/", "", $value);
            $result = utf8_encode($result);
            // use result as url to pass 
            $requestcode = strval($this->responseCode(10000, $result));

            if((strpos($requestcode, "4", 0) === 0) ||(strpos($requestcode, "5", 0) === 0)) {
                $temp = array("responseCode" => $requestcode, "url" => $result);
                array_push($recordList, $temp);
            }
            
        }
        
        //use regex parse href="" url
        preg_match_all("/(href=\")\/[^<>[:space:]]+[[:alnum:]#?\/&=+%_]/", $homepage, $match1);
        $list1 = $match1[0];

        foreach ($list1 as $value) {

            //replace href=" to null string
            $result = preg_replace("/(href=\")/", "", $value);
            if(preg_match("/\/(tw)\//", $result)||preg_match("/\/(ysm)/", $result)||preg_match("/\/css/", $result)) {
                // combine http://dev.muzik-online.com with \tw\ , \ysm , \css
                $string = utf8_encode('http://dev.muzik-online.com'.$result);
                // use result string as url to pass
                $requestcode = strval($this->responseCode(10000, $string));

                if((strpos($requestcode, "4", 0) === 0) ||(strpos($requestcode, "5", 0) === 0)) {
                    $temp = array("responseCode" => $requestcode, "url" => $string);
                    array_push($recordList, $temp);
                }

              
            }
            else if(preg_match("/\/(concert)/", $result)||preg_match("/\/(article)/", $result)||preg_match("/\/(listen)/", $result)||preg_match("/\/(download)/", $result)||preg_match("/\/(cashflow)/", $result)||preg_match("/\/(periodical)/", $result)) {
                //combine http://dev.muzik-online.com/tw with \concert , \article , \listen , \download, \cashflow, \periodical
                $string = utf8_encode('http://dev.muzik-online.com/tw'.$result);
                // use result string as url to pass
                $requestcode = strval($this->responseCode(10000, $string));

                if((strpos($requestcode, "4", 0) === 0) ||(strpos($requestcode, "5", 0) === 0)) {
                    $temp = array("responseCode" => $requestcode, "url" => $string);
                    array_push($recordList, $temp);
                }
             
            }
            else {
                //other website 
                $string = utf8_encode('http:'.$result);
                $requestcode = strval($this->responseCode(10000, $string));

                if((strpos($requestcode, "4") === 0) ||(strpos($requestcode, "5") === 0)) {
                    $temp = array("responseCode" => $requestcode, "url" => $string);
                    array_push($recordList, $temp);
                }
            }
        }

        return $recordList;
    }

/*---------------------------responseCode and get url list------------------------*/
	protected function setUp() {

    	$this->elementSetUp();
        $this->driver = RemoteWebDriver::create($this->host, $this->capabilities, 30000);
        $this->refresh = $this->driver->navigate();
		$this->driver->get($this->url);
		$this->setTimeout = $this->driver->manage()->timeouts();
    }

    protected function teardown() {

    	$this->driver->close();
    }

    public function ads() {
        $adDiv = $this->driver->findElement(WebDriverBy::cssSelector('div.float-upgrade.js-float-upgrade.visible'));
        $adElement = $this->driver->findElement(WebDriverBy::xpath('//div[@class="float-upgrade js-float-upgrade visible"]/div/iframe'));
        if($adDiv->isDisplayed()) {
            if($adElement->isDisplayed()) {
               
                $this->driver->switchTo()->frame($adElement);
                $this->driver->findElement(WebDriverBy::xpath('//div[@class="how-do-you-turn-this-on"]/ul/li[1]/div/div/div[1]/a'))->click();
                sleep(2);
                //$this->driver->switchTo()->defaultContent();
            }
            else {
                break;
            }
        }
        else {
            break;
        }
        
    }
    
/*-------------------------- test response code ------------------------------

    public function testMemberCenterServiceResponseCode() {
        $total = $this->countMenuList();
        $this->menu('memberCenter', $total['memberCenter'], 1);
        $url = $this->driver->getCurrentURL();
        $result = $this->getUrlList($url);
        $this->assertNull($result, "responseCode contains: 4xx, 5xx");
    }

    public function testMemberCenterCashflowResponseCode() {
        $total = $this->countMenuList();
        $this->menu('memberCenter', $total['memberCenter'], 2);
        $url = $this->driver->getCurrentURL();
        $result = $this->getUrlList($url);
        $this->assertNull($result, "responseCode contains: 4xx, 5xx");
    }

    public function testPeriodicalMuzikResponseCode() {
        $total = $this->countMenuList();
        $this->menu('periodical', $total['periodical'], 1);
        $url = $this->driver->getCurrentURL();
        $result = $this->getUrlList($url);
        $this->assertNull($result, "responseCode contains: 4xx, 5xx");
    }

    public function testPeriodicalAllMusicResponseCode() {
        $total = $this->countMenuList();
        $this->menu('periodical', $total['periodical'], 2);
        $url = $this->driver->getCurrentURL();
        $result = $this->getUrlList($url);
        $this->assertNull($result, "responseCode contains: 4xx, 5xx");
    }

    public function testConcertResponseCode() {
        $total = $this->countMenuList();
        $this->menu('concert', $total['concert'], 1);
        $url = $this->driver->getCurrentURL();
        $result = $this->getUrlList($url);
        $this->assertNull($result, "responseCode contains: 4xx, 5xx");
    }

    public function testArticleFocusResponseCode() {
        $total = $this->countMenuList();
        $this->menu('article', $total['article'], 1);
        $url = $this->driver->getCurrentURL();
        $result = $this->getUrlList($url);
        $this->assertNull($result, "responseCode contains: 4xx, 5xx");
    }

    public function testArticleCommodifyResponseCode() {
        $total = $this->countMenuList();
        $this->menu('article', $total['article'], 2);
        $url = $this->driver->getCurrentURL();
        $result = $this->getUrlList($url);
        $this->assertNull($result, "responseCode contains: 4xx, 5xx");
    }

    public function testArticleExpertResponseCode() {
        $total = $this->countMenuList();
        $this->menu('article', $total['article'], 3);
        $url = $this->driver->getCurrentURL();
        $result = $this->getUrlList($url);
        $this->assertNull($result, "responseCode contains: 4xx, 5xx");  
    }

    public function testListenDjResponseCode() {
        $total = $this->countMenuList();
        $this->wait(2);
        $this->menu('allMusic', $total['allMusic'], 1);
        $url = $this->driver->getCurrentURL();
        $result = $this->getUrlList($url);
        $this->assertNull($result, "responseCode contains: 4xx, 5xx");  
    }

    public function testListenMemberResponseCode() {
        $total = $this->countMenuList();
        $this->wait(2);
        $this->menu('allMusic', $total['allMusic'], 2);
        $url = $this->driver->getCurrentURL();
        $result = $this->getUrlList($url);
        $this->assertNull($result, "responseCode contains: 4xx, 5xx");  
    }

    public function testListenThemeResponseCode() {
        $total = $this->countMenuList();
        $this->wait(2);
        $this->menu('allMusic', $total['allMusic'], 3);
        $url = $this->driver->getCurrentURL();
        $result = $this->getUrlList($url);
        $this->assertNull($result, "responseCode contains: 4xx, 5xx");  
    }

    public function testListenMusicTwResponseCode() {
        $total = $this->countMenuList();
        $this->wait(2);
        $this->menu('allMusic', $total['allMusic'], 4);
        $url = $this->driver->getCurrentURL();
        $result = $this->getUrlList($url);
        $this->assertNull($result, "responseCode contains: 4xx, 5xx");
    }


    public function testListenAlbumResponseCode() {
        $total = $this->countMenuList();
        $this->wait(2);
        $this->menu('allMusic', $total['allMusic'], 5);
        $url = $this->driver->getCurrentURL();
        $result = $this->getUrlList($url);
        $this->assertNull($result, "responseCode contains: 4xx, 5xx");
    }

    public function testHomepageResponseCode() {
        $total = $this->countMenuList();
        $this->wait(2);
        $this->menu('homepage', $total['homepage'], 1);
        $url = $this->driver->getCurrentURL();
        $result = $this->getUrlList($url);
        $this->assertNull($result, "responseCode contains: 4xx, 5xx");
    }

-------------------------- test response code ------------------------------*/
    
  
/*-----------------------test member profile---------------------------
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
    	$this->memberSongListOperation('new list', 0);
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

    public function testMenuMemberProfileMyCollectionOperationCollect() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('collection');
    	sleep(2);
    	$this->memberCollection('collect', 1);
    	sleep(3);
    }

    public function testMenuMemberProfileMyCollectionOperationOtherMemberEnterSongListsDel() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('collection');
    	sleep(2);
    	$this->memberCollection('enter', 1);
    	sleep(3);
    	$this->memberSongListOperation('del', 1);
    }

    public function testMenuMemberProfileMyCollectionOperationOtherMemberEnterSongListsPlay() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('collection');
    	sleep(2);
    	$this->memberCollection('enter', 1);
    	sleep(3);
    	$this->memberSongListOperation('play', 1);
    }

    public function testMenuMemberProfileMyCollectionOperationOtherMemberEnterSongListsAdd() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('collection');
    	sleep(2);
    	$this->memberCollection('enter', 1);
    	sleep(3);
    	$this->memberSongListOperation('new list', 1);
    }

    public function testMenuMemberProfileMyCollectionOperationOtherMemberEnterSongListsCopy() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('collection');
    	sleep(2);
    	$this->memberCollection('enter', 1);
    	sleep(3);
    	$this->memberSongListOperation('copy', 1);
    }

    public function testMenuMemberProfileMyCollectionOperationOtherMemberEnterSongListsEdit() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('collection');
    	sleep(2);
    	$this->memberCollection('enter', 1);
    	sleep(3);
    	$this->memberSongListOperation('edit', 1);
    }

    public function testMenuMemberProfileMyCollectionOperationOtherMemberEnterSongListsEnter() {
    	$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
    	$this->login('gosick@test.com', 'gosick');
    	sleep(2);
    	$this->pageRefresh();
    	sleep(1);
    	$this->menu('memberProfile', $total['memberProfile'], 1);
    	sleep(2);
    	$this->memberProfileSelect('collection');
    	sleep(2);
    	$this->memberCollection('enter', 1);
    	sleep(3);
    	$this->memberSongListOperation('enter', 1);
    }

-----------------------test member profile----------------------------------*/
/*------------------------ test player ---------------------------------------
	
	public function testPlayerMyListLeftContentChooseSongChoose() {

		$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
		$this->login('gosick@test.com', 'gosick');
		sleep(3);
		$this->playerOpen();
		sleep(2);
		$this->playerheaderSelect('myList');
		sleep(8);
		$this->playerLeftContentSelect('choose', 1);
		sleep(2);
		$this->playerClose();
	}

	public function testPlayerMyListLeftContentChooseSongPlay() {

		$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
		$this->login('gosick@test.com', 'gosick');
		sleep(3);
		$this->playerOpen();
		sleep(2);
		$this->playerheaderSelect('myList');
		sleep(8);
		$this->playerLeftContentSelect('choose', 1);
		sleep(8);
		$this->playerSongContentfunc('play', 1);
		sleep(2);
		$this->playerClose();
	}

	public function testPlayerMyListLeftContentChooseSongInformation() {

		$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
		$this->login('gosick@test.com', 'gosick');
		sleep(3);
		$this->playerOpen();
		sleep(2);
		$this->playerheaderSelect('myList');
		sleep(8);
		$this->playerLeftContentSelect('choose', 1);
		sleep(8);
		$this->playerSongContentfunc('info', 1);
		sleep(2);
		$this->playerClose();
	}

	public function testPlayerMyListLeftContentChooseSongDownload() {

		$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
		$this->login('gosick@test.com', 'gosick');
		sleep(3);
		$this->playerOpen();
		sleep(2);
		$this->playerheaderSelect('myList');
		sleep(8);
		$this->playerLeftContentSelect('choose', 1);
		sleep(8);
		$this->playerSongContentfunc('download', 1);
		sleep(2);
		$this->playerClose();
	}

	public function testPlayerMyListLeftContentChooseSongAddToListTemporaryList() {

		$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
		$this->login('gosick@test.com', 'gosick');
		sleep(3);
		$this->playerOpen();
		sleep(2);
		$this->playerheaderSelect('myList');
		sleep(8);
		$this->playerLeftContentSelect('choose', 1);
		sleep(8);
		$this->playerSongContentfunc('add to list', 1);
		sleep(1);
		$this->addToListSelect('temporary list', 1);
		sleep(1);
		$this->playerheaderSelect('temporaryList');
		sleep(2);
		$this->playerClose();
	}


	public function testPlayerMyListLeftContentChooseSongAddToListMyList() {

		$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
		$this->login('gosick@test.com', 'gosick');
		sleep(3);
		$this->playerOpen();
		sleep(2);
		$this->playerheaderSelect('myList');
		sleep(8);
		$this->playerLeftContentSelect('choose', 2);
		sleep(8);
		$this->playerSongContentfunc('add to list', 1);
		sleep(1);
		$this->addToListSelect('my list', 1);
		sleep(1);
		$this->playerheaderSelect('myList');
		sleep(2);
		$this->playerClose();
	}

	public function testPlayerMyListLeftContentChooseSongAddToListMyList() {

		$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
		$this->login('gosick@test.com', 'gosick');
		sleep(3);
		$this->playerOpen();
		sleep(2);
		$this->playerheaderSelect('myList');
		sleep(8);
		$this->playerLeftContentSelect('choose', 2);
		sleep(8);
		$this->playerSongContentfunc('add to list', 1);
		sleep(1);
		$this->addToListSelect('new list', 1);
		sleep(2);
		$this->playerClose();
	}
	
	public function testPlayerMyListLeftContentChooseSongDel() {

		$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
		$this->login('gosick@test.com', 'gosick');
		sleep(3);
		$this->playerOpen();
		sleep(2);
		$this->playerheaderSelect('myList');
		sleep(8);
		$this->playerLeftContentSelect('choose', 1);
		sleep(8);
		$this->playerSongContentfunc('del', 1);
		sleep(2);
		$this->playerClose();
	}


	public function testPlayerMyListLeftContentDel() {

		$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
		$this->login('gosick@test.com', 'gosick');
		sleep(3);
		$this->playerOpen();
		sleep(2);
		$this->playerheaderSelect('myList');
		sleep(8);
		$this->playerLeftContentSelect('Del', 2);
		sleep(8);
		$this->playerClose();
	}

	public function testPlayerMyListLeftContentEdit() {

		$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
		$this->login('gosick@test.com', 'gosick');
		sleep(3);
		$this->playerOpen();
		sleep(2);
		$this->playerheaderSelect('myList');
		sleep(8);
		$this->playerLeftContentSelect('Edit', 2);
		sleep(8);
		$this->playerClose();
	}

	public function testPlayerMyListLeftContentNewList() {

		$total = $this->countMenuList();
    	$this->menu('login', $total['login'], 1);
    	sleep(2);
		$this->login('gosick@test.com', 'gosick');
		sleep(3);
		$this->playerOpen();
		sleep(2);
		$this->playerheaderSelect('myList');
		sleep(8);
		$this->playerLeftContentSelect('new list', 1);
		sleep(8);
		$this->playerClose();
	}

    public function testPlayerMyCollectionLeftContentChooseSongList() {

        $total = $this->countMenuList();
        $this->menu('login', $total['login'], 1);
        sleep(2);
        $this->login('gosick@test.com', 'gosick');
        sleep(3);
        $this->playerOpen();
        sleep(2);
        $this->playerheaderSelect('myCollection');
        sleep(8);
        $this->playerLeftContentSelect('choose', 2);//choose the first collection
        sleep(8);
        $this->playerLeftContentMyCollectionSongListSelect(2, 2);//in the first collection and choose the second song list
        $this->playerClose();   
    }

    public function testPlayerMyCollectionLeftContentDel() {

        $total = $this->countMenuList();
        $this->menu('login', $total['login'], 1);
        sleep(2);
        $this->login('gosick@test.com', 'gosick');
        sleep(3);
        $this->playerOpen();
        sleep(2);
        $this->playerheaderSelect('myCollection');
        sleep(8);
        $this->playerLeftContentSelect('del', 2);//delete the first collection
        sleep(8);
        $this->playerClose();
    }

    public function testPlayerifAlbumSongs() {
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
        $this->memberSongListOperation('play', 15);
        sleep(2);
        $this->playerCheckAlbum();
        sleep(2);
        $this->playerfooterSelect('pause');
    }
------------------------ test player ---------------------------------------*/
    

   /* public function testCheckSongListNewListAndEdit() {
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
        $this->fillSongListTitleAndDescription('aaa', 'bbb');
        $this->memberSongListOperation('new list', 1);
        sleep(2);
        $this->assertTrue($this->checkSongList('aaa', 'bbb', $this->songListAmount), "song list operation error , check if the title or the description is different");
        sleep(2);
        $this->fillSongListTitleAndDescription('ccc', 'ddd');
        $this->memberSongListOperation('edit', 1);
        sleep(2);
        $this->assertTrue($this->checkSongList('ccc', 'ddd', 1), "song list operation error , check if the title or the description is different");
        sleep(3);

    }*/



}   


?>