<?php
use Goutte\Client;
class JlecShell extends AppShell {
	public $uses = ['JlecSchool'];
	public function main(){
		$this->getInfo();
	}

	public function getInfo(){

		$client = new Client();
		$search_list_urls = Configure::read('jlec.search_list');

		foreach ($search_list_urls as $url) {

			try{
				// if ($id % 25 == 0) {
				// 	$client = new Client();
				// }
				$crawler = $client->request('GET', $url
					, ['curl' => ['CURLOPT_TIMEOUT' => 500]]);
			}catch(Exception $ex){
				error_log(__METHOD__.' Exception was encountered: '.get_class($ex).' 1');
			}


			// if (empty(trim($crawler->filter('title')->text())) && empty(trim($crawler->filter('div p.bsp10')->text()))) {
			// 	continue;
			// }
			// $url = $crawler->filter('#website a')->extract('href');
			// $school_info = $crawler->filter('#info dd')->each(function($node, $i){
			// 	return $node->text();
			// });
			$datas = [];
			$school_names = array_unique($crawler->filter('a')->extract('href'));
			foreach ($school_names as $k => $r) {
				$datas['JlecSchool'][$k] = [
					'info' => $r,
				];
			}
			
			if (count($datas) > 0) {
				try{
					$this->JlecSchool->saveAll($datas['JlecSchool']);

				}catch(Exception $e){
					error_log(__METHOD__.' Exception was encountered: '.get_class($e).' db');
				}
			}
		}
	}
}