<?php
use Goutte\Client;
class WebSiteCrawShell extends AppShell {
	public function main(){

	}

	public function getInfo(){
		$client = new Client();
		$url = "http://www.kodaikyo.org/?page_id=718";
		$file_path = "export.csv";

		try{

			$crawler = $client->request('GET', $url
				, ['curl' => ['CURLOPT_TIMEOUT' => 500]]);
		}catch(Exception $ex){
			error_log(__METHOD__.' Exception was encountered: '.get_class($ex).' 1');
		}

		$infos = $crawler->filter('tbody tr')->each(function($node, $i){
			return $node->text();
		});

		$urls = $crawler->filter('tbody tr td a')->extract('href');
		$main_urls = [];
		foreach ($urls as $k=>$v) {
			if ($k%2 == 0) {
				$main_urls[] = $v;
			}
		}


		// $info = $crawler->filter('table a')->each(function($node, $i){
		// 	return $node->text();
		// });

		// $urls = $crawler->filter('table a')->extract('href');

		// if (touch($file_path)) {
		$file = fopen("export_k.csv", "w");
		if ($file) {
			foreach ($infos as $k => $v) {
				$array = explode("\n", $v); // とりあえず行に分割
				$array = array_map('trim', $array); // 各行にtrim()をかける
				$array = array_filter($array, 'strlen'); // 文字数が0の行を取り除く
				$array = array_values($array); // これはキーを連番に振りなおしてるだけ

				$export_arr = [
					$array[0],
					$array[1],
					$array[2],
					$array[3],
					$main_urls[$k]
				];
				var_dump(fputcsv($file, $export_arr));
			}
			// foreach ($info as $k=>$v) {
			// 	$export_arr = [
			// 		$v,
			// 		$urls[$k],
			// 	];
			// var_dump(fputcsv($file, $export_arr));
			// }
		}
		fclose($file);

		// $file = new SplFileObject( $file_path, "w" );

		// }
	    // ダウンロード用
	    // header("Pragma: public"); // required
	    // header("Expires: 0");
	    // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    // header("Cache-Control: private",false); // required for certain browsers 
	    // header("Content-Type: application/force-download");
	    // header("Content-Disposition: attachment; filename=\\"".basename($file_path)."\\";" );
	    // header("Content-Transfer-Encoding: binary");
	    // readfile("$file_path");


		// if (empty(trim($crawler->filter('title')->text())) && empty(trim($crawler->filter('div p.bsp10')->text()))) {
		// 	continue;
		// }
		// $url = $crawler->filter('#website a')->extract('href');
		// $school_info = $crawler->filter('#info dd')->each(function($node, $i){
		// 	return $node->text();
		// });
		// $datas = [];
		// $school_names = array_unique($crawler->filter('a')->extract('href'));
		// foreach ($school_names as $k => $r) {
		// 	$datas['JlecSchool'][$k] = [
		// 		'info' => $r,
		// 	];
		// }
		// if (count($datas) > 0) {
		// 	try{
		// 		$this->JlecSchool->saveAll($datas['JlecSchool']);

		// 	}catch(Exception $e){
		// 		error_log(__METHOD__.' Exception was encountered: '.get_class($e).' db');
		// 	}
		// }
	}
}