<?php
use Goutte\Client;
class YjlsShell extends AppShell {
	public $uses = ['School', 'EnrollmentStudent', 'AuthorizedCourse', 'YjlsSchool'];
	public function main(){
		$this->getInfo();
	}

	public function getInfo(){

		$client = new Client();

		for ($id = 1; $id <=460; $id++) {
			try{
				if ($id % 25 == 0) {
					$client = new Client();
				}
				$crawler = $client->request('GET', 'http://www.jpnschools.com/detail.php?id='.$id
					, ['curl' => ['CURLOPT_TIMEOUT' => 500]]);
			}catch(Exception $ex){
				error_log(__METHOD__.' Exception was encountered: '.get_class($ex).' 1');
			}
			if (empty(trim($crawler->filter('title')->text())) && empty(trim($crawler->filter('div p.bsp10')->text()))) {
				continue;
			}
			$url = $crawler->filter('#website a')->extract('href');
			$school_info = $crawler->filter('#info dd')->each(function($node, $i){
				return $node->text();
			});

			$school_name = $crawler->filter('h1')->text();
			$datas = [];
			$datas[] = [
				'YjlsSchool' => [
					'id' => $id,
					'school_name' => isset($school_name) ?  $school_name : '',
					'prefecture' => isset($school_info[0]) ? $school_info[0] : '',
					'address' => isset($school_info[1]) ? $school_info[1] : '',
					'establishment' => isset($school_info[2]) ? $school_info[2] : '',
					'capacity' => isset($school_info[3]) ? $school_info[3] : '',
					'domitory' => isset($school_info[4]) ? $school_info[4] : '',
					'url' => isset($url[0]) ? $url[0] : '' ,
					'tel' => isset($school_info[5]) ? $school_info[5] : '',
					'fax' => isset($school_info[6]) ? $school_info[6] : '',
					'email' => preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $school_info[7]) ? $school_info[7] : ''
				]
			];
			var_dump($id.' : '.$school_name);
			if (count($datas) > 0) {
				try{
					$this->YjlsSchool->saveAll($datas);
				}catch(Exception $e){
					error_log(__METHOD__.' Exception was encountered: '.get_class($e).' db');
				}
			}
		}
	}
}