<?php
App::uses('BaseAuthenticate', 'Controller/Component/Auth');
App::uses('SaeTOAuthV2', 'xiaosier/libweibo');

class WeiboAuthenticate extends BaseAuthenticate {

	public function authenticate(CakeRequest $request, CakeResponse $response) {

		if (session_status() !== PHP_SESSION_ACTIVE) {
			CakeSession::start();
			var_dump($_SESSION["access_token"]);exit;
		}

		if(!isset($_SESSION["access_token"])){
		     if(!isset($_REQUEST['code'])){
		        //Get unauthorized request token
		        $oAuth=new SaeTOAuthV2( Configure::read('weibo.app_id') , Configure::read('weibo.app_secret') );
		        //Get request token
		        $aurl = $oAuth->getAuthorizeURL('http://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]); 
		        header("Location:$aurl");
		    }else{
		        $keys = array();
		        $keys['code'] = $_REQUEST['code'];
		        $keys['redirect_uri'] = $auth_page;
		    }
		    $o = new SaeTOAuthV2( Configure::read('weibo.app_id') , Configure::read('weibo.app_secret') );
		    $access_token = $o->getAccessToken('code',$keys) ;
		    $_SESSION["access_token"]=$access_token;
		}

		$wb = new SaeTOAuthV2( Configure::read('weibo.app_id') , Configure::read('weibo.app_secret') );
		$code_url = $wb->getAuthorizeURL( Configure::read('weibo.app_cb_url') );

		$action = $request->params['action'];
		if ($action === 'weibo_login') {

			if ($code_url !== null) {
				$response->header('Location', $code_url);
				$response->send();
				exit();
			}
		} elseif ($action === 'callback') {
			try {
				$accessToken = $helper->getAccessToken();
			} catch(FacebookRequestException $ex) {
				// When Facebook returns an error
				error_log('Graph returned an error: ' . $ex->getMessage());
				$accessToken = null;
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $ex) {
				// When validation fails or other local issues
				error_log('Facebook SDK returned an error: ' . $ex->getMessage());
				$accessToken = null;
				exit;
			}
			$this->_fetch($fb, $accessToken, $request, $response);
		}
	}
}
