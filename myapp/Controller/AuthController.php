<?php
class AuthController extends AppController {

	public function beforeFilter () {
		$this->Auth->allow();
		parent::beforeFilter();
	}
	public function weibo_login(){
		$result = $this->Auth->login();
		$data = $this->Auth->user();
	}

	public function callback($service = []) {
		$this->AppCommon->setFacebookAuth();
		$this->autoRender = false;
		$user = $this->Auth->identify($this->request, $this->response);
	}
}