<?php
use Goutte\Client;
class CrawlShell extends AppShell {
	public $uses = ['School', 'EnrollmentStudent', 'AuthorizedCourse', 'TmpSchool'];
	public function main(){
		$this->getInfo();
	}

	public function getInfo(){

		
		$table_id = 1;
		
		// for ($lng = 1; $lng <=1 ; $lng++){
			for ($id = 501; $id <=600; $id++) {
				sleep(2);
				$insert_data = [];
				$client = new Client();
				// try{
					// if ($id % 25 == 0) {
					// 	$client = new Client();
					// }
					echo $id;
					$crawler = $client->request('GET', 'http://www.nisshinkyo.org/search/college.php?id='.$id);
					// $crawler = $client->request('GET', 'http://www.nisshinkyo.org/search/college.php?id='.$id
					// 	);
				// }catch(Exception $ex){
				// 	error_log(__METHOD__.' Exception was encountered: '.get_class($ex).' 1');
				// }
				if (empty(trim($crawler->filter('title')->text())) && empty(trim($crawler->filter('div p.bsp10')->text()))) {
					continue;
				}
				$school_info = [];
				$school_detail = [];
				// echo $client->getInternalResponse()->getStatus();
				// echo $crawler->html();exit;

				$school_info[0] = $crawler->filter('table.tableStyle04')->each(function($node, $i){

					if ($i === 0) {
						$child = $node->filter('.tableStyle04 table td');
					} else if($i === 1) {
						$child = $node->filter('td');
					} else if(in_array($i,[2,3,4,5])) {
						$child = $node->filter('tr');
					} 
					if (isset($child)) {
						return	$school_detail = $child->each(function($node, $i){
							return $node->text();
						});
					}

				});

				$school_info[1] = $crawler->filter('table.tableStyle03')->each(function($node, $i){
						$child = $node->filter('tr');
					if (isset($child)) {
						return	$school_detail = $child->each(function($node, $i){
							return $node->text();
						});
					}

				});


				$school_info[2] = $crawler->filter('p')->each(function($node, $i){
					return $node->text();
				});

				// $reg = '/^\d{4}年\d{1,2}月/';
				// $str_preg = "";
				// $str_date = "";
				// if (isset($school_info[2][2]) || isset($school_info[2][2])) {
				// 	if ($lng == 2) {
				// 		$str_preg = $school_info[2][3];
				// 	} else{
				// 		$str_preg = $school_info[2][2];
				// 	}
				// 	$str_date = (preg_replace('/[^0-9]/', '', $str_preg));
				// 	$str_date = wordwrap($str_date, 4, "/", true);
				// }

				// $school_detail_info = [];
				// $info = [];
				// $cnt = 0;

				// 進学先
				// $res = explode(PHP_EOL, $school_info[0][4][1]);
				// $univ_cnt =  0;
				// $univ_all = 0;
				// foreach ($res as $r) {
				// 	$univ_cnt += trim($r);
				// }

				// 進学先■卒業者数：
				// $res = preg_split('/[\s|\x{3000}]+/u',$school_info[2][6]);
				// $cnt = count($res);
				// if ($cnt >= 2) {
				// 	$univ_all = mb_ereg_replace('[^0-9]', '', $res[$cnt-1]);
				// }else {
				// 	$univ_all = mb_ereg_replace('[^0-9]', '', $res[0]);
				// }


				// 先生
				// $res = preg_split('/[\s|\x{3000}]+/u',trim($school_info[0][0][21]));
				// $teacher_num = trim($res[0]);


				// 収容定員
				// $student_num = mb_ereg_replace('[^0-9]', '', trim($school_info[0][0][25]));

				// // 学生宿舎 ()
				if (preg_match("/無/i", $school_info[0][0][29])) {
					$accommodations = 0;
				} else {
					$accommodations = 1;
				}
				// 日本語能力試験
// 				$res_1 = explode(PHP_EOL, $school_info[1][0][1]);
// 				$res_2 = explode(PHP_EOL, $school_info[1][0][2]);
// 				$jlpt_all = trim($res_1[6]);
// 				$jlpt_cnt = trim($res_2[6]);
				$insert_data[] =[
					'TmpSchool' => [
						'ns_school_id' => $id,
						'accommodations' => $accommodations,
					],
				];
$this->TmpSchool->saveAll($insert_data);
				// $this->TmpSchool->begin();
				// if ($this->TmpSchool->saveAll($insert_data)) {
				// 	$this->TmpSchool->commit();
				// } else {
				// 	$this->TmpSchool->rollback();
				// }


				continue;
				// if (isset($school_info[0][0])) {

				// 	$insert_data = $school_info[0][0];

				// 	$data = [];
				// 	$data['School']['id'] = $table_id;
				// 	$data['School']['lng'] = $lng;
				// 	$data['School']['school_id'] = $id;
				// 	$data['School']['school_name'] = !empty(trim($crawler->filter('title')->text())) ? trim($crawler->filter('title')->text()) : trim($crawler->filter('div p.bsp10')->text());
				// 	$data['School']['address'] = isset($insert_data[0])?trim($insert_data[0]):'';
				// 	$data['School']['tel'] = isset($insert_data[2])?trim($insert_data[2]):'';
				// 	$data['School']['station'] = isset($insert_data[3])?trim($insert_data[3]):'';
				// 	$data['School']['fax'] = isset($insert_data[5])?trim($insert_data[5]):'';

				// 	$data['School']['url'] = isset($insert_data[7])?trim($insert_data[7]):'';
				// 	$data['School']['email'] = isset($insert_data[9])?trim($insert_data[9]):'';
				// 	if (isset($school_info[0][5])){
				// 		$features = $school_info[0][5];
				// 		$data['School']['school_feature_1'] = isset($features[0])?trim($features[0]):'';
				// 		$data['School']['school_feature_2'] = isset($features[1])?trim($features[1]):'';
				// 		$data['School']['school_feature_3'] = isset($features[2])?trim($features[2]):'';
				// 	}
				// 	if ($lng == 1) {
				// 		$data['School']['installation_name'] = isset($insert_data[11])?trim($insert_data[11]):'';//設置者名
				// 		$data['School']['starting_date'] = isset($insert_data[13])?trim($insert_data[13]):'';//日本語教育開始年月日
				// 		$data['School']['establishment_type'] = isset($insert_data[15]) ? trim($insert_data[15]) : " ";//設置者種別
				// 		$data['School']['validity_term'] = isset($insert_data[17]) ? trim($insert_data[17]) : " ";//認定期間
				// 		$data['School']['representative_name'] = isset($insert_data[19])?trim($insert_data[19]):'';
				// 		$data['School']['teacher_num'] = isset($insert_data[21])?trim($insert_data[21]):'';
				// 		$data['School']['principal_name'] = isset($insert_data[23])?trim($insert_data[23]):'';
				// 		$data['School']['student_capacity'] = isset($insert_data[25])?trim($insert_data[25]):'';
				// 		$data['School']['education_law'] = isset($insert_data[27])?trim($insert_data[27]):'';
				// 		$data['School']['student_accommodation'] = isset($insert_data[29])?trim($insert_data[29]):'';
				// 		$data['School']['admission_qualification'] = isset($insert_data[31])?trim($insert_data[31]):'';
				// 		$data['School']['admission_selection'] = isset($insert_data[33])?trim($insert_data[33]):'';

				// 	} elseif ($lng == 2) {
				// 		$data['School']['establishment_type'] = isset($insert_data[11]) ? trim($insert_data[11]) : " ";//設置者種別
				// 		$data['School']['representative_name'] = isset($insert_data[13])?trim($insert_data[13]):'';
				// 		$data['School']['principal_name'] = isset($insert_data[15])?trim($insert_data[15]):'';
				// 		$data['School']['starting_date'] = isset($insert_data[17])?trim($insert_data[17]):'';//日本語教育開始年月日
				// 		$data['School']['validity_term'] = isset($insert_data[19]) ? trim($insert_data[19]) : " ";//認定期間
				// 		$data['School']['teacher_num'] = isset($insert_data[21])?trim($insert_data[21]):'';
				// 		$data['School']['student_capacity'] = isset($insert_data[23])?trim($insert_data[23]):'';
				// 		$data['School']['student_accommodation'] = isset($insert_data[25])?trim($insert_data[25]):'';
				// 		$data['School']['education_law'] = isset($insert_data[27])?trim($insert_data[27]):'';
				// 		$data['School']['admission_qualification'] = isset($insert_data[29])?trim($insert_data[29]):'';
				// 		$data['School']['admission_selection'] = isset($insert_data[31])?trim($insert_data[31]):'';
				// 	} else {
				// 		$data['School']['starting_date'] = isset($insert_data[13])?trim($insert_data[13]):'';//日本語教育開始年月日
				// 		$data['School']['establishment_type'] = isset($insert_data[15]) ? trim($insert_data[15]) : " ";//設置者種別
				// 		$data['School']['validity_term'] = isset($insert_data[17]) ? trim($insert_data[17]) : " ";//認定期間
				// 		$data['School']['representative_name'] = isset($insert_data[19])?trim($insert_data[19]):'';//代表者名
				// 		$data['School']['teacher_num'] = isset($insert_data[21])?trim($insert_data[21]):'';//教員数
				// 		$data['School']['principal_name'] = isset($insert_data[23])?trim($insert_data[23]):'';//校長名
				// 		$data['School']['student_capacity'] = isset($insert_data[25])?trim($insert_data[25]):'';//収容定員
				// 		$data['School']['education_law'] = isset($insert_data[27])?trim($insert_data[27]):'';
				// 		$data['School']['student_accommodation'] = isset($insert_data[29])?trim($insert_data[29]):'';
				// 		$data['School']['admission_qualification'] = isset($insert_data[31])?trim($insert_data[31]):'';
				// 		$data['School']['admission_selection'] = isset($insert_data[33])?trim($insert_data[33]):'';
				// 	}
				// 	try{
				// 		$this->School->save($data);
				// 		$table_id +=1;
				// 	}catch(Exception $ex){
				// 		error_log(__METHOD__.' Exception was encountered: '.get_class($ex). $id);
				// 	}

				// }
				// continue;
				// $school_detail_info['info'] = array_values($info);

				// 認定コースに在籍している学生数
				// 
				// if (isset($school_info[0][1])) {
				// 	$datas = [];
				// 	foreach ($school_info[0][1] as $k => $r) {
				// 		$data = explode(PHP_EOL, $r);
				// 		if (isset($data[0]) && isset($data[1])) {
				// 			$school_detail_info['school_detail_info'][$data[0]] = $data[1];
				// 			$datas[] = [
				// 				'EnrollmentStudent' => [
				// 					'lng' => $lng,
				// 					'school_id' => $id,
				// 					'as_of' => $str_date,
				// 					'country' => mb_convert_kana($data[0], 'KVC'),
				// 					'student_cnt' => $data[1],
				// 				]
				// 			];
				// 		}
				// 	}
				// 	if (count($datas) > 0) {
				// 		try{
				// 			$this->EnrollmentStudent->saveAll($datas);
				// 		}catch(Exception $e){
				// 			error_log(__METHOD__.' Exception was encountered: '.get_class($ex).' db');
				// 		}
				// 	}
				// 	// var_dump($school_detail_info['school_detail_info']);exit;
				// }
				// continue;


				// 設置コース情報
				$ks = [];
				if (isset($school_info[0][2])) {
					$datas = [];
					foreach ($school_info[0][2] as $k=>$r) {

						if ($k <= 1) {
							continue;
						}

						$data = explode(PHP_EOL, $r);

						// var_dump($data);exit;
						if (!empty(trim($data[0]))) {
							// $dk = $data[0];
							// unset($data[0]);
							// $school_detail_info['installation_course'][$dk] = $data;
							$datas[] = [
								'AuthorizedCourse' => [
									'lng' => $lng,
									'school_id' => $id,
									'name' => isset($data[0]) ? trim($data[0]) : '',
									'purpose' => isset($data[1]) ? trim($data[1]) : '',
									'length' => isset($data[2]) ? trim($data[2]) : '',
									'hours' => isset($data[3]) ? trim($data[3]) : '',
									'weeks' => isset($data[4]) ? trim($data[4]) : '',
									'month' => isset($data[5]) ? trim($data[5]) : '',
									'selection' => isset($data[6]) ? trim($data[6]) : '',
									'admission' => isset($data[7]) ? trim($data[7]) : '',
									'tuition' => isset($data[8]) ? trim($data[8]) : '',
									'others' => isset($data[9]) ? trim($data[9]) : '',
									'total' => isset($data[10]) ? trim($data[10]) : '',
								]
							];
						}
					}
					var_dump($datas);exit;
				// 	if (count($datas) > 0) {
				// 		try{
				// 			$this->AuthorizedCourse->saveAll($datas);
							
				// 		}catch(Exception $e){
				// 			error_log(__METHOD__.' Exception was encountered: '.get_class($e).' db');
				// 		}
				// 	}
				}


				// 日本留学試験(EJU) 受験状況
				// if (isset($school_info[0][3][4]) && isset($school_info[0][3][4])) {
				// 	$data_6 = explode(PHP_EOL, $school_info[0][3][4]);
				// 	$data_12 = explode(PHP_EOL, $school_info[0][3][4]);

				// 	array_splice($data_6, 6, 7);
				// 	array_splice($data_12, 0, 6);
				// 	array_splice($data_12, 6, 1);
				// 	$school_detail_info['euj_data'][0] = $data_6;
				// 	$school_detail_info['euj_data'][1] = $data_12;
				// }



				// 進学先■卒業者数：
				// if (isset($school_info[0][4][1])) {
				// 	$school_detail_info['up_data'][0] = explode(PHP_EOL, $school_info[0][4][1]);
				// }

				// if (isset($school_info[0][4][2])) {
				// 	$school_detail_info['up_data'][1] = explode(PHP_EOL, $school_info[0][4][2]);
				// }

				// 日本語教育の特色
				// $school_detail_info['school_feature'] = $school_info[0][5];

				// 日本語能力試験 受験状況
				// $title = [];
				// if (isset($school_info[1][0])) {
				// 	foreach ($school_info[1][0] as $k=>$r) {
				// 		if ($k == 0) {
				// 			$title = explode(PHP_EOL, $r);
				// 			array_splice($title, 0, 1);
				// 			continue;
				// 		}
				// 		$data = explode(PHP_EOL, $r);
				// 		$tmp_k = $data[0];
				// 		array_splice($data, 0, 1);
				// 		foreach ($data as $key=>$var) {
				// 			$school_detail_info['ja_ability_test'][$tmp_k][$title[$key]] = $var;
				// 		}
				// 	}
				// }
			}

			// $this->TmpSchool->begin();
			// if ($this->TmpSchool->saveAll($insert_data)) {
			// 	$this->TmpSchool->commit();
			// } else {
			// 	$this->TmpSchool->rollback();
			// }
		// }
	}
}