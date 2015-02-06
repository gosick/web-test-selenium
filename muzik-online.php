<?php 

require_once('lib/__init__.php');

class muzikOnlineTest extends URLChecker{

	protected $host, $driver, $capabilities, $setTimeout, $refresh, $cookies;
	protected $account, $password, $passwordConfirm, $btnSend, $keyword, $searchBtn;
	protected $periodicalTag, $memberFlag, $songListAmount, $songRepeat, $collectionAmount;

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
		$this->songRepeat = 0;
		$this->songListAmount = 0;
		$this->collectionAmount = 0;
	}

	public function player($select)
	{
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

				if($this->songRepeat >= 3)
				{
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

	public function playerHeaderSelect($select)
	{
		switch ($select) {

			case 'now playing':

				$this->driver->findElement(WebDriverBy::xpath('//div[@class="player jp-player open"]/div[1]/div[2]/ul/li[4]/a'))->click();
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
					$this->driver->manage()->deleteAllCookies();
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
				}
				break;
			case 'logout':
				if($this->checkLogin())
				{
					$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::classname('name'))->getCoordinates());
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="info js-header-info"]/div/a[3]'))->click();
				}
				break;
			case 'payment':
				if($this->checkLogin())
				{
					$this->driver->getMouse()->mouseMove($this->driver->findElement(WebDriverBy::classname('name'))->getCoordinates());
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="info js-header-info"]/div/a[2]'))->click();
				}
				break;
			default:
				break;
		}
	}

	public function countMemberSongList()
	{
		$elements = $this->driver->findElements(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div'));
		return count($elements);
	}

	public function countMemberSong()
	{
		$elements = $this->driver->findElements(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li'));
		return count($elements) - 1;
	}

	public function memberData()
	{
		$this->memberFlag = true;
		//enter my song list
		
		return $this->memberFlag;
	}

	public function memberSelect($select)
	{
		switch ($select) {
			case 'songList':
				$this->pageRefresh();
				$url = '//div[@class="primary-content js-controller-content"]/div[1]/a[1]';
				$this->driver->findElement(WebDriverBy::xpath($url))->click();
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
				# code...
				break;
		}
	}

	public function countMemberCollection()
	{
		$amount = $this->driver->findElements(WebDriverBy::xpath('//div[@class="listen-list listen-subscribe"]/ul/li'));
		
		return count($amount);
	}

	public function memberCollection($select, $i)
	{
		switch ($select) {

			case 'cancel'://cancel collect

				if($i != 0 && $i <= $this->collectionAmount && $this->collectionAmount > 0)
				{
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="listen-list listen-subscribe"]/ul/li['.$i.']/a[3]'))->click();
				}
				break;

			case 'enter':

				if($i != 0 && $i <= $this->collectionAmount && $this->collectionAmount > 0)
				{
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="listen-list listen-subscribe"]/ul/li['.$i.']/a[1]'))->click();
				}
				break;

			default:
				
				break;
		}
	}

	public function otherMember()
	{
		//collect
		//cancel collect
		//profile
		//collection
		//song list
			//copy
			//play
			//enter
				//play all
				//play
				//info
				//download
				//add to list
					//add to new list
					//add to temporary list
					//add to existed list
	}
	
	public function memberSongList($select, $i)
	{
		sleep(3);
		$this->songListAmount = $this->countMemberSongList();
		while($this->songListAmount == 0)
		{
			$url = 'link.my.listenBtn.active';
			$this->driver->findElement(WebDriverBy::cssSelector($url))->click();
			$this->wait(0.5);
			$$this->songListAmount = $this->countMemberSongList();
		}
		switch ($select) {
			case 'add':
				//--add new song list--//
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

				if($i > 0 && $i <= $this->songListAmount && $this->songListAmount > 0)
				{
					$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$i.']/div'));
					$this->driver->getMouse()->mouseMove($getSonglistDiv->getCoordinates());
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$i.']/div/div/a[3]'))->click();
					$this->driver->findElement(WebDriverBy::id('playlist_title'))->clear()->sendKeys('ha');
					$this->driver->findElement(WebDriverBy::id('summary'))->clear()->sendKeys('haha');
					$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();
				}

				break;

			case 'copy':
				//--copy a song list--//

				if($i > 0 && $i <= $this->songListAmount && $this->songListAmount > 0)
				{
					$number = $this->songListAmount + 1;
					$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$i.']/div'));
					$this->driver->getMouse()->mouseMove($getSonglistDiv->getCoordinates());
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$i.']/div/div/a[2]'))->click();
					$this->driver->findElement(WebDriverBy::id('playlist_title'))->clear()->sendKeys('new_'.$number);
					$this->driver->findElement(WebDriverBy::id('summary'))->clear()->sendKeys('Ok!');
					$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click() ;
					$this->songListAmount = $this->countMemberSongList();
				}
					
				break;

			case 'play':
				//play the songs of the song list

				if($i > 0 && $i <= $this->songListAmount && $this->songListAmount > 0)
				{
					$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$i.']/div'));
					$this->driver->getMouse()->mouseMove($getSonglistDiv->getCoordinates());
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$i.']/div/div/a[5]'))->click();
				}
				
				break;

			case 'enter':
				//enter the song list
				if($i > 0 && $i <= $this->songListAmount && $this->songListAmount > 0)
				{
					$getSonglistDiv = $this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$i.']/a'))->click();
				}

				break;

			case 'del':
				//delete the song list
				if($i > 0 && $i <= $this->songListAmount && $this->songListAmount > 0)
				{
					$this->driver->findElement(WebDriverBy::cssSelector('div.btnDelete.remove'))->click();
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="listenContentInner clearfix"]/div['.$i.']/div/a'))->click();
					$this->driver->findElement(WebDriverBy::cssSelector('button.button.button-gold'))->click();
					$this->songListAmount = $this->countMemberSongList();
				}
				
			default:
				
				break;
		}
	}

	public function songSelect($selectFunc, $i)
	{
		$number = $i + 1;
		$this->wait(0.5);
		$amount = $this->countMemberSong();
		switch ($selectFunc) {

			case 'play all':
				//get the 'play all' element
				$this->driver->findElement(WebDriverBy::cssSelector('a.playAll.noSelect.play-all'))->click();
				break;

			case 'play':
				//get the 'play' element
				if($number > 0 && $number <= ($amount + 1) && $amount > 0)
				{
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[1]'))->click();			
				}	
				
				break;

			case 'del':
				//get the 'delete' element
				if($number > 0 && $number <= ($amount + 1) && $amount > 0)
				{
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[3]'))->click();		
				}
				
				break;

			case 'add to list':
				//get the 'add to list' element
				if($number > 0 && $number <= ($amount + 1) && $amount > 0)
				{
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[2]'))->click();
				}

				break;

			case 'info':

				if($number > 0 && $number <= ($amount + 1) && $amount > 0)
				{
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[4]'))->click();
				}
				
				break;

			case 'download':

				if($number > 0 && $number <= ($amount + 1) && $amount > 0)
				{
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="compoTable"]/ul/li['.$number.']/div[4]/a[5]'))->click();
				}

				break;

			default:
							
				break;
		}
	}


	public function listSelect($listSelect, $i)
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

				if($i > 0 && $i <= $this->songListAmount && $this->songListAmount > 0)
				{
					//$js = "arguments[0].scrollIntoView(true);";
					//$scrollbar = $this->driver->findElements(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/div'));
					//$this->driver->executeScript($js, $scrollbar);
					//sleep(3);
					//scrollbar
					$this->driver->findElement(WebDriverBy::xpath('//div[@class="float js-float menu"]/div/div/div/div[2]/ul/li['.$i.']/a'))->click();
				}
				
				break;

			default:
									
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

	}

//------------------------------------------//	

	public function forgetPasswordTest()
	{
		$this->driver->findElement(WebDriverBy::id($this->account))->sendKeys($account);
		$this->driver->findElement(WebDriverBy::id($this->password))->sendKeys($password);
		$this->driver->findElement(WebDriverBy::id($this->passwordConfirm))->sendKeys($password);
		$this->driver->findElement(WebDriverBy::classname($this->btnSend))->click();
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
	
	//30s message exception
	
	//$test->menuTest(0, 'search', '貝多芬','','','');
	//$test->menuTest(0, 'memberProfile', '', '','');
	//memberProfile is needed to be modify.
	//memberSonglist
	//select function should be added.

	//$test->periodicalTest();
	//$test->menuTest();
?>