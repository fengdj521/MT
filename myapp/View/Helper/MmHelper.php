<?php
App::uses('AppHelper', 'View/Helper');
App::uses('Folder', 'Utility');

class MmHelper extends AppHelper {
	public $helpers = ['Html'];

	public function script_for_cdn($path, $compress = true) {
		$url = $this->create_url_of_cdn($path, $compress);
		return $this->Html->script($url);
	}

	public function css_for_cdn($path, $compress = true) {
		$url = $this->create_url_of_cdn($path, $compress);
		return $this->Html->css($url);
	}

	public function create_url_of_cdn($path, $compress) {
		if ($compress) {
			$path = (Configure::read('develop_env') !== 'dev') ? '/dist' . $path : '/css' . $path;
		}
		// クエリパラメタ付加
		$file_path = substr(WWW_ROOT,0,-1) .$path;
		if (@filemtime($file_path)) {
			$path .= '?' . filemtime($file_path);
		}

		// return (Conf::r('develop_env') !== 'dev') ? 'https://' . Configure::read('cdn_domain') . $path : $path;
		return $path;
	}
}
