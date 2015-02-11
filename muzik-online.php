<?php 

require_once('lib/__init__.php');

class muzikOnlineTest extends URLChecker {

	protected $host, $driver, $capabilities, $setTimeout, $refresh, $cookies;
	protected $account, $password, $passwordConfirm, $btnSend, $keyword, $searchBtn;
	protected $periodicalTag, $memberFlag, $songListAmount, $songRepeat, $collectionAmount, $otherSongListAmount;
	protected $mySelfFlag;

	public function __construct($url) {

		$this->elementSetUp();

		$this->driver = RemoteWebDriver::create($this->host, $this->capabilities, 5000);
		$this->refresh = $this->driver->navigate();
		$this->driver->get($url);
		$this->setTimeout = $this->driver->manage()->timeouts();
	}

	public function pageRefresh() { $this->refresh->refresh(); }

	public function wait($second) { $this->setTimeout->implicitlyWait($second); }

	public function elementSetUp() {

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
	}

	public function checkLogin() {

		$flag = false;
		$array = $this->cookies;
		$size = count($array);
		for($i = 0; $i < $size; $i++)
		{
			if($array[$i]['name'] == 'member_i18n')
			{
				$flag = true;
			}
		}
		
		return $flag;
	}

	public function loginTest($account, $password) {

		$this->driver->findElement(WebDriverBy::id($this->account))->sendKeys($account);
		$this->driver->findElement(WebDriverBy::id($this->password))->sendKeys($password);
		$this->wait(1);
		$this->driver->findElement(WebDriverBy::classname($this->btnSend))->click();
		$this->cookies = $this->driver->manage()->getCookies();	
	}

	public function registerTest($account, $password) {
				
		$this->driver->findElement(WebDriverBy::id($this->account))->sendKeys($account);
		$this->driver->findElement(WebDriverBy::id($this->password))->sendKeys($password);
		$this->wait(1);
		$this->driver->findElement(WebDriverBy::id($this->passwordConfirm))->sendKeys($password);
		$this->driver->findElement(WebDriverBy::classname($this->btnSend))->click();

	}


	public function forgetPasswordTest($account, $password) {

		$this->driver->findElement(WebDriverBy::id($this->account))->sendKeys($account);
		$this->driver->findElement(WebDriverBy::id($this->password))->sendKeys($password);
		$this->driver->findElement(WebDriverBy::id($this->passwordConfirm))->sendKeys($password);
		$this->driver->findElement(WebDriverBy::classname($this->btnSend))->click();
	}

	public function search($string) {

		$this->wait(1);
		$this->driver->findElement(WebDriverBy::name($this->keyword))->sendKeys($string);
		$this->driver->findElement(WebDriverBy::cssSelector($this->searchBtn))->click();
	}

	public function membercenterTest($index) {

		$periodical = '//div[@class="container"]/nav/ul/li[5]';
		$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($periodical))->getCoordinates());
		$url = '//div[@class="container"]/nav/ul/li[5]/ul/li['.$index.']/a';
		$this->driver->findElement(WebDriverBy::xpath($url))->click();
		$this->pageRefresh();
	}

	public function periodicalTest($index) {

		$periodical = '//div[@class="container"]/nav/ul/li[4]';
		$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($periodical))->getCoordinates());
		$url = '//div[@class="container"]/nav/ul/li[4]/ul/li['.$index.']/a';
		$this->driver->findElement(WebDriverBy::xpath($url))->click();
		$this->pageRefresh();
	}

	public function concertTest() {

		$url = '//div[@class="container"]/nav/ul/li[3]/a';
		$this->driver->findElement(WebDriverBy::xpath($url))->click();
		$this->pageRefresh();
	}

	public function articleMenuTest($index) {

		$article = '//div[@class="container"]/nav/ul/li[2]';
		$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($article))->getCoordinates());
		$url = '//div[@class="container"]/nav/ul/li[2]/ul/li['.$index.']/a';
		$this->driver->findElement(WebDriverBy::xpath($url))->click();
		$this->pageRefresh();
	}

	public function allmusicMenuTest($index) {

		$allMusic = '//div[@class="container"]/nav/ul/li[1]';
		$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath($allMusic))->getCoordinates());
		$url = '//div[@class="container"]/nav/ul/li[1]/ul/li['.$index.']/a';
		$this->driver->findElement(WebDriverBy::xpath($url))->click();
		$this->pageRefresh();
	}

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


	public function menu($select, $total, $index) {

		switch ($select) {

			case 'allMusic':

				if($index > $total || $index <= 0) {
					echo "element does not exist"."\n";
					echo "allMusic: ".$total." index: ".$index."\n";
				}
				else {
					$this->allmusicMenuTest($index);
				}

				break;

			case 'article':

				if($index > $total || $index <= 0) {
					echo "element does not exist"."\n";
					echo "article: ".$total." index: ".$index."\n";
				}
				else {
					$this->articleMenuTest($index);
				}

				break;

			case 'concert':

				$this->concertTest();

				break;

			case 'periodical':

				if($index > $total || $index <= 0) {
					echo "element does not exist"."\n";
					echo "periodical: ".$total." index: ".$index."\n";
				}
				else {
					$this->periodicalTest($index);
				}

				break;

			case 'memberCenter':

				if($index > $total || $index <= 0) {
					echo "element does not exist"."\n";
					echo "memberCenter: ".$total." index: ".$index."\n";
				}
				else {
					$this->membercenterTest($index);
				}

				break;

			case 'login':

				if(!$this->checkLogin()) {

					$this->driver->manage()->deleteAllCookies();
					$url = '//div[@class="container"]/div/div[1]/a[1]';
					$this->driver->findElement(WebDriverBy::xpath($url))->click();
					$this->pageRefresh();
				}

				break;

			case 'register':

				if($total) {

					$url = '//div[@class="container"]/div/div[1]/a[2]';
					$this->driver->findElement(WebDriverBy::xpath($url))->click();
					$this->pageRefresh();
				}

				break;

			case 'homepage':

				if($total) {

					$url = '//div[@class="container"]/a';
					$this->driver->findElement(WebDriverBy::xpath($url))->click();
					$this->pageRefresh();
				}

				break;

			case 'memberProfile':

				if($this->checkLogin()) {
					$this->driver->findElement(WebDriverBy::classname('name'))->click();
					$this->mySelfFlag = true;
				}
				else {
					echo "Please login!"."\n";
				}

				break;

			case 'logout':

				if($this->checkLogin()) {

					$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::classname('name'))->getCoordinates());
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="info js-header-info"]/div/a[3]'))->click();
				}
				else {
					echo "Please login!"."\n";	
				}

				break;

			case 'payment':

				if($this->checkLogin()) {

					$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::classname('name'))->getCoordinates());
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="info js-header-info"]/div/a[2]'))->click();
				}
				else {
					echo "Please login!"."\n";
				}

				break;

			default:
				break;
		}
	}

//----------------------memberProfile settings-------------//
	public function countMemberSongList() { return count($this->driver->findElements(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div'))); }

	public function countMemberSong() { return count($this->driver->findElements(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li'))) - 1; }

	public function memberData() {
		$this->memberFlag = true;
		return $this->memberFlag;
	}

	public function memberSelect($select) {

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

	public function memberSongList($select, $index) {

		sleep(3);
		$this->songListAmount = $this->countMemberSongList();

		while($this->songListAmount == 0) {

			$url = 'link.my.listenBtn.active';
			$this->driver->findElement(WebDriverBy::cssSelector($url))->click();
			$this->wait(0.5);
			$this->songListAmount = $this->countMemberSongList();
		}

		switch ($select) {

			case 'add':

				//--add new song list--//
				if($this->mySelfFlag == true) {

					$number = $this->songListAmount + 1;
					$this->driver->findElement(WebDriverBy::cssSelector('div.btnAdd.add'))->click();
					$this->wait(0.5);
					$this->driver->findElement(WebDriverBy::id('playlist_title'))->sendKeys('new_'.$number);
					$this->driver->findElement(WebDriverBy::id('summary'))->sendKeys('testlist');
					$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();
					$this->songListAmount = $this->countMemberSongList();
				}

				break;

			case 'edit':

				//--edit a song list--//
				if($index > 0 && $index <= $this->songListAmount && $this->songListAmount > 0 && $this->mySelfFlag == true) {

					$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div'));
					$this->driver->getMouse()->mouseMove($getSonglistDiv->getCoordinates());
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div/div/a[3]'))->click();
					$this->driver->findElement(WebDriverBy::id('playlist_title'))->clear()->sendKeys('ha');
					$this->driver->findElement(WebDriverBy::id('summary'))->clear()->sendKeys('haha');
					$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();
				}
				else {
					echo "SongListAmount: ".$this->songListAmount." index: ".$index."\n";
				}

				break;

			case 'copy':

				//--copy a song list--//
				if($index > 0 && $index <= $this->songListAmount && $this->songListAmount > 0) {

					$number = $this->songListAmount + 1;
					$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div'));
					$this->driver->getMouse()->mouseMove($getSonglistDiv->getCoordinates());
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div/div/a[2]'))->click();
					$this->driver->findElement(WebDriverBy::id('playlist_title'))->clear()->sendKeys('new_'.$number);
					$this->driver->findElement(WebDriverBy::id('summary'))->clear()->sendKeys('Ok!');
					$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click() ;
					$this->songListAmount = $this->countMemberSongList();
				}
				else {
					echo "SongListAmount: ".$this->songListAmount." index: ".$index."\n";
				}

				break;

			case 'play':

				//play the songs of the song list
				if($index > 0 && $index <= $this->songListAmount && $this->songListAmount > 0) {

					$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div'));
					$this->driver->getMouse()->mouseMove($getSonglistDiv->getCoordinates());
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div/div/a[5]'))->click();
				}
				else {
					echo "SongListAmount: ".$this->songListAmount." index: ".$index."\n";
				}

				break;

			case 'enter':

				//enter the song list
				if($index > 0 && $index <= $this->songListAmount && $this->songListAmount > 0) {

					$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/a'))->click();
				}
				else {
					echo "SongListAmount: ".$this->songListAmount." index: ".$index."\n";
				}

				break;

			case 'del':

				//delete the song list
				if($index > 0 && $index <= $this->songListAmount && $this->songListAmount > 0 && $this->mySelfFlag == true) {

					$this->driver->findElement(WebDriverBy::cssSelector('div.btnDelete.remove'))->click();
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$index.']/div/a'))->click();
					$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();
					$this->songListAmount = $this->countMemberSongList();
				}
				else {
					echo "SongListAmount: ".$this->songListAmount." index: ".$index."\n";
				}

				break;

			default:
				
				break;
		}
	}

	public function songSelect($selectFunc, $index) {

		$number = $index + 1;
		//for first li is head title, second li is the first song
		$this->wait(0.5);
		$amount = $this->countMemberSong();
		while(($amount + 1) == 0) {
			$this->pageRefresh();
			$amount = $this->countMemberSong();
		}

		switch ($selectFunc) {

			case 'play all':

				$this->driver->findElement(WebDriverBy::cssSelector('a.playAll.noSelect.play-all'))->click();
				break;

			case 'play':

				if($number > 1 && $index <= $amount && $amount > 0) {
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[1]'))->click();			
				}	
				else {
					echo "play song: ".$amount." index: ".$index."\n";
				}
				
				break;

			case 'del':

				if($number > 1 && $index <= $amount && $amount > 0 && $this->mySelfFlag == true) {
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[3]'))->click();		
				}
				else {
					echo "del song: ".$amount." index: ".$index."\n";
				}
				
				break;

			case 'add to list':

				if($number > 1 && $index <= $amount && $amount > 0) {
					//$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[2]'))->getLocationOnScreenOnceScrolledIntoView();
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[2]'))->click();
				}
				else {
					echo "add to list song: ".$amount." index: ".$index."\n";
				}

				break;

			case 'info':

				if($number > 1 && $index <= $amount && $amount > 0) {
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[4]'))->click();
				}
				else {
					echo "info song: ".$amount." index: ".$index."\n";
				}
				
				break;

			case 'download':

				if($number > 1 && $index <= $amount && $amount > 0) {
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[5]'))->click();
				}
				else {
					echo "download song: ".$amount." index: ".$index."\n";
				}

				break;

			default:
							
				break;
		}
	}


	public function listSelect($listSelect, $index)
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

			case 'temporary':

				$this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/a[2]'))->click();

				break;

			case 'existed':

				if($index > 0 && $index <= $this->songListAmount && $this->songListAmount > 0) {	
					$this->wait(3);
					//$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/div/div/div[2]/ul'))->getCoordinates());
					//$item = $this->driver->findElements(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/div/div/div[2]/ul/li['.$i.']'));
					//$js = "arguments[0].scrollIntoView(true);";
					//$this->driver->executeScript($js, $item);
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/div/div/div[2]/ul/li['.$i.']/a'))->click();
				}
				
				break;

			default:
									
				break;

		}	
	}

	public function countMemberCollection() { return count($this->driver->findElements(WebDriverBy::xpath('//div[@class="listen-list listen-subscribe"]/ul/li'))); }

	public function memberCollection($select, $index) {

		switch ($select) {

			case 'collect'://collect or cancel collect

				if($index != 0 && $index <= $this->collectionAmount && $this->collectionAmount > 0) {
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="listen-list listen-subscribe"]/ul/li['.$index.']/a[3]'))->click();
				}
				else {
					echo "collection: ".$this->collectionAmount." index: ".$index."\n";
				}

				break;

			case 'enter':

				if($index != 0 && $index <= $this->collectionAmount && $this->collectionAmount > 0) {
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="listen-list listen-subscribe"]/ul/li['.$index.']/a[1]'))->click();
					$this->mySelfFlag = false;
				}
				else {
					echo "collection: ".$this->collectionAmount." index: ".$index."\n";
				}

				break;

			default:
				
				break;
		}
	}


	public function countOtherMemberSongList() { return count($this->driver->findElements(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div'))); }

//----------------------memberProfile settings-------------//
//----------------------player-----------------------------//
	public function playerfooterSelect($select) {

		switch ($select) {

			case 'open':

				$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player"]'))->getCoordinates());
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player over"]/div[1]/div[1]/a'))->click();

				break;

			case 'play':

				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[3]/div[1]/ul/li[2]/a'))->click();

				break;

			case 'pause':

				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[3]/div[1]/ul/li[3]/a'))->click();

				break;

			case 'next':

				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[3]/div[1]/ul/li[4]/a'))->click();

				break;

			case 'prev':

				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[3]/div[1]/ul/li[1]/a'))->click();

				break;

			case 'info':
				//$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[4]/a'))->click();
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

			case 'close':

				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[3]/div[3]/ul/li[7]/a'))->click();

				break;

			case 'unpin':

				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[1]/div[1]/a'))->click();

				break;

			default:
				
				break;
		}
	}

	public function playerheaderSelect($select) {

		switch ($select) {

			case 'now playing':

				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[1]/div[2]/ul/li[4]/a'))->click();
				sleep(8);
				break;

			case 'temporaryList':

				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[1]/div[2]/ul/li[1]/a'))->click();

				break;

			case 'myList':

				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[1]/div[2]/ul/li[2]/a'))->click();

				break;

			case 'myCollection':

				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[1]/div[2]/ul/li[3]/a'))->click();

				break;

			default:

				break;
		}
	}
//--------------------------------------------------//
	public function playercontent($select, $index) {

		switch ($select) {
			case 'temporaryList':
				
				break;
			
			case 'myList':

				sleep(8);
				$collectionList = count($this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li')));

				break;

			case 'myCollection':

				sleep(8);
				$collectionList = count($this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li')));
				
				if($index > 0 && $collectionList >= $index)
				{
					/*
					$item = $this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']'));
					$js = "arguments[0].scrollIntoView(true);";
					$this->driver->executeScript($js, $item);
					*/
					$this->myCollectionSelect('choose', $index);
				}

				break;

			default:
				
				break;
		}
	}

	public function myCollectionSelect($select, $index) {
		
		$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']/div[1]/a'))->click();
		sleep(8);
		$songListNumber = count($this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']/ul/li')));
		switch ($select) {

			case 'choose':

				$item = $this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']/ul/li[2]'));//ul/li[$k]
				$js = "arguments[0].scrollIntoView(true);";
				$this->driver->executeScript($js, $item);
		 		$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']/ul/li[2]/div/a'))->click();//ul/li[$k]
		 		$songAmount = count($this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li')));

		 		$indextem = $this->driver->findElements(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li[2]'));//2
				$js = "arguments[0].scrollIntoView(true);";
				$this->driver->executeScript($js, $item);
		 		$this->myCollectionSongFunc('info', 2);
		 		sleep(4);
		 	
				break;

			case 'del':

				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']/a'))->click();
				sleep(1);
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[1]/div[2]/ul/li['.$index.']/div[2]/a'))->click();
				break;
			
			default:
				
				break;
		}
	}

	public function myCollectionSongFunc($select, $index){

		sleep(5);
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

				$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li['.$index.']/div[3]/div'))->getCoordinates());
				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[2]/div[2]/div[2]/div[2]/ul/li['.$index.']/div[3]/div/div/a[5]'))->click();
				break;

			default:
				
				break;
		}
	}

	public function chooseLanguage($select)
	{
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

	
	//-------------haven't been completed------------------------//
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

	$menuList = $test->countMenuList();

	sleep(2);
	$test->menu('login', $menuList['login'], 0);
	sleep(2);
	$test->loginTest('f56112000@gmail.com', 'ss07290420');
	sleep(2);
	$test->menu('memberProfile', $menuList['memberProfile'], 0);
	sleep(2);

	/*
	sleep(2);
	$test->memberSelect('collection');
	sleep(2);
	$test->memberCollection('enter', 4);
	sleep(2);
	$test->memberSelect('collection');
	sleep(2);
	$test->memberCollection('collect', 1);
	sleep(2);
	$test->memberCollection('collect', 1);
	sleep(2);
	$test->memberCollection('enter', 1);
	sleep(2);
	$test->memberSelect('songList');
	sleep(2);
	$test->memberSongList('copy', 1);
	sleep(2);
	$test->memberSongList('enter', 1);
	sleep(2);
	$test->songSelect('add to list', 2);
	sleep(2);
	$test->listSelect('temporary', 0);
	*/
	sleep(2);
	$test->playerfooterSelect('open');
	sleep(2);
	$test->playerheaderSelect('myCollection');
	sleep(2);
	$test->playercontent('myCollection', 2);
	sleep(10);
	$test->myCollectionSongFunc('play', 3);
	sleep(2);
	$test->playerfooterSelect('pause');
	sleep(2);
	$test->myCollectionSongFunc('info', 3);
	sleep(2);
	$test->myCollectionSongFunc('play', 3);
	sleep(2);
	$test->myCollectionSongFunc('download', 3);
	sleep(2);
	$test->myCollectionSongFunc('add to list', 3);
	sleep(2);
	$test->listSelect('temporary', 1);
	sleep(2);
	$test->playerheaderSelect('temporaryList');
	sleep(2);
	$test->playerheaderSelect('now playing');
	sleep(10);
	$test->myCollectionSongFunc('add to list', 3);
	sleep(2);
	$test->listSelect('new list', 1);
	sleep(2);
	$test->playerheaderSelect('myList');
	sleep(2);
	$test->menu('logout', $menuList['logout'], 0);

?>