<?php

class SearchByGoogleShell extends AppShell {

	public function main () {
		$this->getInfo();
	}

	public function getInfo () {
		$api_key='AIzaSyBW8E8SHdQWjavYpegJnXG7FCoopTDvfFo';
		$cx = '002935746458299813414:qx4cqmrftn0';
		$keyword = 'サンタバーバラ';
		$cse_url = "https://www.googleapis.com/customsearch/v1?key={$api_key}&cx={$cx}&q={$keyword}";
		$search_result = file_get_contents($cse_url, true);
		$search_result = json_decode($search_result);
		$result_first = $search_result->items[0];
		var_dump($result_first);exit;
	}
}