<?php
use libweibo\SaeTOAuthV2;
class TopController extends AppController{
	public $uses = ['Health'];

	public function index(){

		$Redis = ClassRegistry::init('PhpRedis');
		$Redis->zincrby(date('Ymd') . ':' . 'test' . ':pv', 1, '1');
		
		$this->set('datas', $this->Health->find('all'));
	}

	public function getrssfeed(){
		try {
			// $rss = $this->Rss->read('http://blog.livedoor.jp/spyder_5615/index.rdf');
			// $rss = $this->Rss->read('http://www.umikujira.asia/feeds/posts/default?alt=rss');
			// $rss = $this->Rss->read('http://mobile.163.com/special/001144R8/xinjisudi_copy.xml');
			// $rss = $this->Rss->read('http://cook.2chmtm.net/feed/');
			// $rss = $this->Rss->read('http://oryouri.2chblog.jp/archives/cat_160759.xml');
			$rss = $this->Rss->read('http://oryouri.2chblog.jp/index.rdf');
			
		}catch(InternalErrorException $e) {
			$rss = "nothing";
		}
		// var_dump($rss);exit;
		
		$this->set('rss', $rss);
	}
}