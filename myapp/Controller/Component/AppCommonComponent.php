<?php
class AppCommonComponent extends Component {
/**
 * initialize
 * 
 * @access	public
 * @return	void
 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}

	public function setWeiboAuth() {
		$this->controller->Auth->authenticate = [
			'Weibo',
		];
	}
}