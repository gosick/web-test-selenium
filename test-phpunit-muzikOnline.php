<?php 

require_once('phpunit-muzikOnline.php');

class Muzik extends WebTest
{
	protected function setUp() {
		parent::elementSetUp();
        $this->setBrowserUrl('http://dev.muzik-online.com/tw');
	}

	public function test11() {

		$this->url('http://dev.muzik-online.com/tw');
        sleep(1);
        parent::countMenuList();
        sleep(1);
        parent::menu('login', $this->total['login'], 1);
        sleep(1);
        parent::login('gosick@test.com', 'gosick');
        sleep(1);
        parent::refresh();
        sleep(2);
        //parent::ads();
        sleep(2);
        parent::menu('memberProfile', $this->total['memberProfile'], 1);
        sleep(2);
        parent::memberProfileSelect('collection');
        sleep(2);
        parent::memberProfileSelect('songList');
        sleep(4);
        parent::fillSongListTitleAndDescription('test3232', '22ssdfest');
        parent::memberSongListOperation('new list', 1);
        sleep(2);
	}
}

?>